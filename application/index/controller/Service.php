<?php

namespace app\index\controller;

use app\common\controller\Frontend;

class Service extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
    }

    public function privacy()
    {
        return $this->view->fetch();
    }

    public function intellectual()
    {
        return $this->view->fetch();
    }

    public function tems()
    {
        return $this->view->fetch();
    }

}
