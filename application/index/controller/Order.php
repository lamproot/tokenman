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
        if ($this->request->isGet())
        {
            $params['product_id'] = $this->request->request('product_id') ? $this->request->request('product_id') : 0;
            $params['wallet'] = $this->request->request('wallet') ? $this->request->request('wallet') : 0;
            $params['uid'] = $_COOKIE['uid'] ? $_COOKIE['uid'] : 0;
            $params['order_code'] = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);;
            $create = $this->model->create($params);

            echo json_encode(array("code" => 0, "msg" => "下单成功"));

            //进行下单
            $user = $this->auth->getUser();
            //邮件发送
            //echo json_encode($user['email']);exit;
            $this->sendRegisterMail($user['email']);
        }

    }

    public function sendRegisterMail($mail)
    {
        $email = new Email;
        $text['account'] = $mail;
        $content = '
        <p>
            <span><strong>Order Confirm Email Test</strong></span>
        </p>
        ';

        $email = new Email;
        $result = $email
        ->to($mail)
        ->subject(__("Order Confirm Email Test"))
        ->message($content)
        ->send();
    }

}
