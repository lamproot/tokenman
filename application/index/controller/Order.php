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
class Order extends Frontend
{
    protected $layout = 'common';
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Order');
    }

    /**
     *
     */
    public function index()
    {
        $user = $this->auth->getUser();
        if ($this->request->isPost())
        {
            $user->project_name = $this->request->request('project_name');
            $user->name_token = $this->request->request('name_token');
            $user->robot = $this->request->request('robot');
            $user->telegram = $this->request->request('telegram');
            $user->wechat = $this->request->request('wechat');
            $user->save();
            $this->redirect('completeinfo/mail');
        }
        $this->assign("row", $user);
        $this->view->assign('title', __('Completeinfo'));
        return $this->view->fetch();
    }

    /**
     * 确认下单
     *
     */
    public function confirm()
    {
        if ($this->request->isPost())
        {
            $params['product_id'] = $this->request->request('product_id') ? $this->request->request('product_id') : 0;
            $params['wallet'] = $this->request->request('wallet') ? $this->request->request('wallet') : 0;
            $params['uid'] = $_COOKIE['uid'] ? $_COOKIE['uid'] : 0;
            $params['order_code'] = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);;
            $create = $this->model->create($params);

            echo json_encode(array("code" => 0, "msg" => "下单成功"));
            fastcgi_finish_request();
            //进行下单
            $user = $this->auth->getUser();
            //邮件发送
            //echo json_encode($user['email']);exit;
            $this->sendOrderCinfirmMail($user['email'], $user['nickname'], $params['order_code']);
        }

    }

    public function sendOrderCinfirmMail($mail, $nickname, $order_code)
    {
        $email = new Email;
        $text['account'] = $mail;
        $text['nickname'] = $nickname;
        $text['order_code'] = $order_code;
        $content = '
<p>
    <span class="s1"><strong>Your order has been submitted</strong></span>
</p>
<p>
    <span class="s1">Hello '.$text['nickname'].',</span>
</p>
<p>
    <span class="s1">We are pleased to confirm your order has been received by our system. The next step is to complete your payment within </span><span class="s2">2 hours</span><span class="s1">.</span>
</p>
<p>
    <span class="s1">Your order reference number is: </span><span class="s2">'.$text['order_code'].'</span><span class="s1">.<span class="Apple-converted-space">&nbsp;</span></span>
</p>
<p>
    <span class="s1">You can go to </span><span class="s3"><a href="http://iamtokenman.com/index/user/index.html">My Account</a></span><span class="s1"> to check the status of your order.</span>
</p>
<p>
    <span class="s1"></span><br/>
</p>
<p>
    <span class="s1"><strong>我们已收到您的订单</strong></span>
</p>
<p>
    <span class="s1">尊敬的'.$text['nickname'].'，</span>
</p>
<p>
    <span class="s1">我们已收到您的订单。请在</span><span class="s2">2小时</span><span class="s1">内完成付款。</span>
</p>
<p>
    <span class="s1">您的订单编号:</span><span class="s2">'.$text['order_code'].'</span><span class="s1">。</span>
</p>
<p>
    <span class="s1">您可随时访问</span><span class="s3"><a href="http://iamtokenman.com/index/user/index.html">我的账户</a></span><span class="s1">查看订单状态。</span>
</p>
<p>
    <br/>
</p>
        ';

        $email = new Email;
        $result = $email
        ->to($mail)
        ->subject(__("Your order has been submitted"))
        ->message($content)
        ->send();
    }

}
