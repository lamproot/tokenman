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
class Completeinfo extends Frontend
{

    protected $layout = 'common';
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
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
     *
     */
    public function mail()
    {
        $email = new Email;
        $content = '<p>
    &nbsp;p.p1 {margin: 0.0px 0.0px 0.0px 0.0px; text-align: justify; font: 10.5px Helvetica; -webkit-text-stroke: #000000} p.p2 {margin: 0.0px 0.0px 0.0px 0.0px; text-align: justify; text-indent: 21.0px; font: 10.5px Helvetica; -webkit-text-stroke: #000000} p.p3 {margin: 0.0px 0.0px 0.0px 0.0px; text-align: justify; font: 10.5px Helvetica; -webkit-text-stroke: #000000; min-height: 12.0px} p.p4 {margin: 0.0px 0.0px 0.0px 0.0px; text-align: justify; text-indent: 21.0px; font: 10.5px Helvetica; -webkit-text-stroke: #000000; min-height: 12.0px} p.p5 {margin: 0.0px 0.0px 0.0px 0.0px; text-align: justify; text-indent: 21.0px; font: 10.5px Helvetica; -webkit-text-stroke: #000000; background-color: #fffb01} span.s1 {font-kerning: none} span.s2 {text-decoration: underline ; font-kerning: none; background-color: #fffb01} span.s3 {text-decoration: underline ; font-kerning: none}
</p>
<p>
    <span><strong>YOUR TokenMan Account CONFIRMATION</strong></span>
</p>
<p>
    <span>Thank you for signing up with TokenMan account. You&#39;re one step closer to completing the setup. For security purposes, please click </span><span><strong>here</strong></span><span> to confirm your account.Your TokenMan account is:</span><span>67052934@qq.com</span>
</p>
<p>
    <span>If the link above doesn&#39;t work, please copy and paste the URL below in a new browser window to complete the setup.</span>
</p>
<p>
    <span>http://www.iamtokenman.com/login/pw/goAccountVerfy?</span>
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
    <span>Need to SET or RESET your password?Just click </span><span><strong>here</strong></span><span>, or copy and paste the URL below in a web browser window.</span>
</p>
<p>
    <span>http://passport.iamtokenman.com/wauthen/ResetPassword2Check?lang=en_HK&amp;username=6705293@qq.com&amp;vc=TfLJ5i0hLB</span>
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
    <span>感谢您激活TokenMan账户。您离完成设置又近了一步。为安全起见，请点击</span><span><strong>此处</strong></span><span>激活您的账户。TokenMan账号：</span><span>67052934 @qq.com</span>
</p>
<p>
    <span>如链接无效，请在新的浏览器窗口中复制粘贴以下页面地址完成账户激活：</span>
</p>
<p>
    <span>http://www.iamtokenman.com/login/pw/goAccountVerfy?</span>
</p>
<p>
    <br/>
</p>
<p>
    <span>TokenMan账户团队</span>
</p>
<p>
    <br/>
</p>
<p>
    <span>需要设置或重置您的密码？只需点击</span><span><strong>此处</strong></span><span>，或者在浏览器窗口中复制粘贴并访问以下页面地址：</span>
</p>
<p>
    <span>http://passport.iamtokenman.com/wauthen/ResetPassword2Check?lang=en_HK&amp;username=6705293@qq.com&amp;vc = TfLJ5i0hLB</span>
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
        ->to("304151978@qq.com")
        ->subject(__("This is a test mail"))
        ->message('<div style="min-height:550px; padding: 100px 55px 200px;">' . __('This is a test mail content') . '</div>')
        ->send();
        // $result = $email
        //         ->to("304151978@qq.com")
        //         ->subject("YOUR TokenMan Account CONFIRMATION 您的TokenMan账户已激活")
        //         ->message('<div style="min-height:550px; padding: 100px 55px 200px;">' .$content . '</div>')
        //         ->send();
        //
        //         echo json_encode($result);exit;
        return $this->view->fetch();
    }
}