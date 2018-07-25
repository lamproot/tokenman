<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Ems as Emslib;
use app\common\model\User;

/**
 * 邮箱验证码接口
 */
class Ems extends Api
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
        \think\Hook::add('ems_send', function($params) {
            $obj = \app\common\library\Email::instance();

            $content = '<p class="p1">
                <span class="s1"><strong>Your password has changed</strong></span>
            </p>
            <p class="p1">
                <span class="s1">Hello Nick,</span>
            </p>
            <p class="p2">
                <span class="s1">We wanted to let you know that your TokenMan account password had changed.</span>
            </p>
            <p class="p2">
                <span class="s1">Your verification code is ：'.$params->code.'</span>
            </p>
            <p class="p2">
                <span class="s1">Looking for other information please visit:</span>
            </p>
            <p class="p3">
                <span class="s2"><a href="http://iamtokenman.com/">http://iamtokenman.com/</a></span><span class="s3"><span class="Apple-converted-space">&nbsp;</span></span>
            </p>
            <p class="p2">
                <span class="s1">Please contact us for any supports:</span>
            </p>
            <p class="p4">
                <span class="s2">http://iamtokenman.com/index/contactus/index.html</span>
            </p>
            <p class="p2">
                <span class="s1">We will never ask for your password and suggest you do not share it with anyone.</span>
            </p>
            <p class="p5">
                <span class="s1"></span><br/>
            </p>
            <p class="p1">
                <span class="s1"><strong>验证您的TokenMan账户</strong></span>
            </p>
            <p class="p1">
                <span class="s1">尊敬的Nick，</span>
            </p>
            <p class="p2">
                <span class="s1">我们已收到您的密码找回请求。</span>
            </p>
            <p class="p2">
                <span class="s1">你的验证码是：'.$params->code.'</span>
            </p>
            <p class="p5">
                <span class="s1"></span><br/>
            </p>
            <p class="p2">
                <span class="s1">如需更多信息，请访问：</span>
            </p>
            <p class="p3">
                <span class="s2"><a href="http://iamtokenman.com/">http://iamtokenman.com/</a></span>
            </p>
            <p class="p2">
                <span class="s1">如需技术支持，请联络我们：</span>
            </p>
            <p class="p4">
                <span class="s2">http://iamtokenman.com/index/contactus/index.html</span>
            </p>
            <p class="p2">
                <span class="s1">我们永远不会询问您的密码，请不要与任何人分享您的密码。</span>
            </p>
            <p>
                <br/>
            </p>';
            $result = $obj
                    ->to($params->email)
                    ->subject('Your password has changed')
                    ->message($content)
                    ->send();
            return $result;
        });
    }

    /**
     * 发送验证码
     *
     * @param string    $email      邮箱
     * @param string    $event      事件名称
     */
    public function send()
    {
        $email = $this->request->request("email");
        $event = $this->request->request("event");
        $event = $event ? $event : 'register';

        $last = Emslib::get($email, $event);
        if ($last && time() - $last['createtime'] < 60)
        {
            $this->error(__('发送频繁'));
        }
        if ($event)
        {
            $userinfo = User::getByEmail($email);
            if ($event == 'register' && $userinfo)
            {
                //已被注册
                $this->error(__('已被注册'));
            }
            else if (in_array($event, ['changeemail']) && $userinfo)
            {
                //被占用
                $this->error(__('已被占用'));
            }
            else if (in_array($event, ['changepwd', 'resetpwd']) && !$userinfo)
            {
                //未注册
                $this->error(__('未注册'));
            }
        }
        $ret = Emslib::send($email, NULL, $event);
        if ($ret)
        {
            $this->success(__('发送成功'));
        }
        else
        {
            $this->error(__('发送失败'));
        }
    }

    /**
     * 检测验证码
     *
     * @param string    $email      邮箱
     * @param string    $event      事件名称
     * @param string    $captcha    验证码
     */
    public function check()
    {
        $email = $this->request->request("email");
        $event = $this->request->request("event");
        $event = $event ? $event : 'register';
        $captcha = $this->request->request("captcha");

        if ($event)
        {
            $userinfo = User::getByEmail($email);
            if ($event == 'register' && $userinfo)
            {
                //已被注册
                $this->error(__('已被注册'));
            }
            else if (in_array($event, ['changeemail']) && $userinfo)
            {
                //被占用
                $this->error(__('已被占用'));
            }
            else if (in_array($event, ['changepwd', 'resetpwd']) && !$userinfo)
            {
                //未注册
                $this->error(__('未注册'));
            }
        }
        $ret = Emslib::check($email, $captcha, $event);
        if ($ret)
        {
            $this->success(__('成功'));
        }
        else
        {
            $this->error(__('验证码不正确'));
        }
    }

}
