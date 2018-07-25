<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;
use app\common\library\Email;

/**
 * 会员中心
 */
class User extends Frontend
{

    protected $layout = 'common';
    protected $noNeedLogin = ['login', 'register', 'third'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
        $auth = $this->auth;

        $ucenter = get_addon_info('ucenter');
        if ($ucenter && $ucenter['state'])
        {
            include ADDON_PATH . 'ucenter' . DS . 'uc.php';
        }

        //监听注册登录注销的事件
        Hook::add('user_login_successed', function($user) use($auth) {
            $expire = input('post.keeplogin') ? 30 * 86400 : 0;
            Cookie::set('uid', $user->id, $expire);
            Cookie::set('token', $auth->getToken(), $expire);
        });
        Hook::add('user_register_successed', function($user) use($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });
        Hook::add('user_delete_successed', function($user) use($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        Hook::add('user_logout_successed', function($user) use($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });

        $this->ordermodel = model('Order');
    }

    /**
     * 会员中心
     */
    public function index()
    {
        $this->view->assign('title', __('User center'));
        return $this->view->fetch();
    }

    /**
     * 注册会员
     */
    public function register()
    {
        $url = $this->request->request('url', url('user/index'));
        if ($this->auth->id)
            $this->success(__('You\'ve logged in, do not login again'), $url);
        if ($this->request->isPost())
        {
            $username = $this->request->post('username');
            $password = $this->request->post('password');
            $email = $this->request->post('email');
            $mobile = $this->request->post('mobile', '');
            $captcha = $this->request->post('captcha');
            $token = $this->request->post('__token__');
            $rule = [
                'username'  => 'require|length:3,30',
                'password'  => 'require|length:6,30',
                'email'     => 'require|email',
                'mobile'    => 'regex:/^1\d{10}$/',
                'captcha'   => 'require|captcha',
                '__token__' => 'token',
            ];

            $msg = [
                'username.require' => 'Username can not be empty',
                'username.length'  => 'Username must be 3 to 30 characters',
                'password.require' => 'Password can not be empty',
                'password.length'  => 'Password must be 6 to 30 characters',
                'captcha.require'  => 'Captcha can not be empty',
                'captcha.captcha'  => 'Captcha is incorrect',
                'email'            => 'Email is incorrect',
                'mobile'           => 'Mobile is incorrect',
            ];
            $data = [
                'username'  => $username,
                'password'  => $password,
                'email'     => $email,
                'mobile'    => $mobile,
                'captcha'   => $captcha,
                '__token__' => $token,
            ];
            $validate = new Validate($rule, $msg);
            $result = $validate->check($data);
            if (!$result)
            {
                $this->error(__($validate->getError()));
            }
            if ($this->auth->register($username, $password, $email, $mobile))
            {
                $synchtml = '';
                ////////////////同步到Ucenter////////////////
                if (defined('UC_STATUS') && UC_STATUS)
                {
                    $uc = new \addons\ucenter\library\client\Client();
                    $synchtml = $uc->uc_user_synregister($this->auth->id, $password);
                }
                //发送注册成功邮件
                $this->sendRegisterMail($email);
                //注册成功之后跳转地址

                $this->success("", "/index/completeinfo/index");
                //$this->success(__('Sign up successful') . $synchtml, $url);
            }
            else
            {
                $this->error($this->auth->getError());
            }
        }
        Session::set('redirect_url', $url);
        $this->view->assign('title', __('Register'));
        return $this->view->fetch();
    }

    /**
     * 会员登录
     */
    public function login()
    {
        $url = $this->request->request('url', url('product/index'));
        if ($this->auth->id)
            $this->success(__('You\'ve logged in, do not login again'), $url);
        if ($this->request->isPost())
        {
            $account = $this->request->post('account');
            $password = $this->request->post('password');
            $keeplogin = (int) $this->request->post('keeplogin');
            $token = $this->request->post('__token__');
            $rule = [
                'account'   => 'require|length:3,50',
                'password'  => 'require|length:6,30',
                '__token__' => 'token',
            ];

            $msg = [
                'account.require'  => 'Account can not be empty',
                'account.length'   => 'Account must be 3 to 50 characters',
                'password.require' => 'Password can not be empty',
                'password.length'  => 'Password must be 6 to 30 characters',
            ];
            $data = [
                'account'   => $account,
                'password'  => $password,
                '__token__' => $token,
            ];
            $validate = new Validate($rule, $msg);
            $result = $validate->check($data);
            if (!$result)
            {
                $this->error(__($validate->getError()));
                return FALSE;
            }
            if ($this->auth->login($account, $password))
            {
                $synchtml = '';
                ////////////////同步到Ucenter////////////////
                if (defined('UC_STATUS') && UC_STATUS)
                {
                    $uc = new \addons\ucenter\library\client\Client();
                    $synchtml = $uc->uc_user_synlogin($this->auth->id);
                }
                $this->success(__('Logged in successful') . $synchtml, $url);
            }
            else
            {
                $this->error($this->auth->getError());
            }
        }
        $this->view->assign('title', __('Login'));
        return $this->view->fetch();
    }

    /**
     * 注销登录
     */
    function logout()
    {
        //注销本站
        $this->auth->logout();
        $synchtml = '';
        ////////////////同步到Ucenter////////////////
        if (defined('UC_STATUS') && UC_STATUS)
        {
            $uc = new \addons\ucenter\library\client\Client();
            $synchtml = $uc->uc_user_synlogout();
        }
        $this->success(__('Logout successful') . $synchtml, url('user/index'));
    }

    /**
     * 个人信息
     */
    public function profile()
    {
        $this->view->assign('title', __('Profile'));

        $row['project_name'] = "project_name";
        $row['name_token'] = "name_token";
        $row['robot'] = "robot";
        $row['telegram'] = "telegram";
        $row['wechat'] = "wechat";

        $this->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 修改密码
     */
    public function changepwd()
    {
        if ($this->request->isPost())
        {
            $oldpassword = $this->request->post("oldpassword");
            $newpassword = $this->request->post("newpassword");
            $renewpassword = $this->request->post("renewpassword");
            $token = $this->request->post('__token__');
            $rule = [
                'oldpassword'   => 'require|length:6,30',
                'newpassword'   => 'require|length:6,30',
                'renewpassword' => 'require|length:6,30|confirm:newpassword',
                '__token__'     => 'token',
            ];

            $msg = [
            ];
            $data = [
                'oldpassword'   => $oldpassword,
                'newpassword'   => $newpassword,
                'renewpassword' => $renewpassword,
                '__token__'     => $token,
            ];
            $field = [
                'oldpassword'   => __('Old password'),
                'newpassword'   => __('New password'),
                'renewpassword' => __('Renew password')
            ];
            $validate = new Validate($rule, $msg, $field);
            $result = $validate->check($data);
            if (!$result)
            {
                $this->error(__($validate->getError()));
                return FALSE;
            }

            $ret = $this->auth->changepwd($newpassword, $oldpassword);
            if ($ret)
            {
                $synchtml = '';
                ////////////////同步到Ucenter////////////////
                if (defined('UC_STATUS') && UC_STATUS)
                {
                    $uc = new \addons\ucenter\library\client\Client();
                    $synchtml = $uc->uc_user_synlogout();
                }

                //发送注册成功邮件
                //$this->sendRegisterMail();
                $this->success(__('Reset password successful') . $synchtml, url('user/login'));
            }
            else
            {
                $this->error($this->auth->getError());
            }
        }
        $this->view->assign('title', __('Change password'));
        return $this->view->fetch();
    }


    /**
     * 订单管理
     */
    public function orderindex()
    {
        $this->view->assign('title', __('Order index'));
        //获取用户订单数据
        $list = $this->ordermodel
                ->where('uid', '=', $_COOKIE['uid'])
                ->order(array("id" => "desc"))
                ->select();

        foreach ($list as $key => $value) {
            $product_id_text = "";
            if ($value['product_id'] == 1) {
                $product_id_text = "普通账户";
            }

            if ($value['product_id'] == 2) {
                $product_id_text = "高级账户";
            }

            $status_text = "";
            if ($value['status'] == 0) {
                $status_text = "等待付款";
            }

            if ($value['status'] == 1) {
                $status_text = "已付款";
            }

            if ($value['status'] == 2) {
                $status_text = "已完成";
            }

            if ($value['status'] == -1) {
                $status_text = "已取消";
            }

            $list[$key]['product_id_text'] = $product_id_text;
            $list[$key]['status_text'] = $status_text;
            $list[$key]['created_at'] = date("Y-m-d H:i:s", $list[$key]['created_at']);
        }
        $this->view->assign('list', $list);
        return $this->view->fetch();
    }


    public function sendRegisterMail($mail)
    {
        $email = new Email;
        $text['account'] = $mail;
        $content = '
        <p>
            <span><strong>YOUR TokenMan Account CONFIRMATION</strong></span>
        </p>
        <p>
            <span>Thank you for signing up with TokenMan account. You&#39;re one step closer to completing the setup. Your TokenMan account is:</span><span>'.$text['account'].'</span>
        </p>
        <p>
            <span>If the link above doesn&#39;t work, please copy and paste the URL below in a new browser window to complete the setup.</span>
        </p>

        <p>
            <br/>
        </p>
        <p>
            <span>TokenMan Account Team</span>
        </p>
        <p>
            <br/>
        </p>
        <p>
            <span>Need to SET or RESET your password?Just click </span><span><a href="http://www.iamtokenman.com/index/user/login.html">here</a></span><span>, or copy and paste the URL below in a web browser window.</span>
        </p>
        <p>
            <span>http://www.iamtokenman.com/index/user/login.html</span>
        </p>
        <p>
            <br/>
        </p>
        <p>
            <span>This email was sent automatically by the system, please do not reply to this email.Wish you enjoy it and thank you for using.</span>
        </p>
        <p>
            <br/>
        </p>
        <p>
            <span>Your Sincerely TokenMan group</span>
        </p>
        <p>
            <span>-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --</span>
        </p>
        <p>
            <span>Follow us now and get more information.</span>
        </p>
        <p>
            <span>Twitter:http://twitter.com/IamTokenMan</span>
        </p>
        <p>
            <span>Website:http://www.iamtokenman.com/</span>
        </p>
        <p>
            <br/>
        </p>
        <p>
            <span><strong>您的TokenMan账户已激活</strong></span>
        </p>
        <p>
            <span>感谢您激活TokenMan账户。您离完成设置又近了一步。TokenMan账号：</span><span>'.$text['account'].'</span>
        </p>
        <p>
            <span>TokenMan账户团队</span>
        </p>
        <p>
            <br/>
        </p>
        <p>
            <span>需要设置或重置您的密码？只需点击</span><span><a href="http://www.iamtokenman.com/index/user/login.html">此处</a></span><span>，或者在浏览器窗口中复制粘贴并访问以下页面地址：</span>
        </p>
        <p>
            <span>http://www.iamtokenman.com/index/user/login.html</span>
        </p>
        <p>
            <br/>
        </p>
        <p>
            <span>此邮件为系统自动发送，请勿直接回复。祝您生活愉快。</span>
        </p>
        <p>
            <br/>
        </p>
        <p>
            <span>TokenMan账户团队敬上</span>
        </p>
        <p>
            <span>-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --</span>
        </p>
        <p>
            <span>访问以下内容，获取更多资讯。</span>
        </p>
        <p>
            <span>Twitter:http://twitter.com/IamTokenMan</span>
        </p>
        <p>
            <span>网站:http://www.iamtokenman.com/</span>
        </p>
        <p>
            <br/>
        </p>';

        $email = new Email;
        $result = $email
        ->to($mail)
        ->subject(__("YOUR TokenMan Account CONFIRMATION"))
        ->message($content)
        ->send();
    }
}
