<?php

namespace app\index\controller;

use app\common\controller\Frontend;

class contactus extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = 'default';

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        //echo json_encode($se);exit;
        return $this->view->fetch();
    }

}
