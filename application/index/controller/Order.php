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
     * @param string    $email      邮箱
     * @param string    $event      事件名称
     * @param string    $captcha    验证码
     */
    public function confirm()
    {
        if ($this->request->isPost())
        {
            $user->product_id = $this->request->request('product_id');
            $user->product_id = $this->request->request('product_id');
            
            $user->wechat = $this->request->request('wechat');
            $user->save();
            $this->redirect('completeinfo/mail');
        }
        echo json_encode(array("code" => 0, "msg" => "下单成功"));exit;
    }

}
