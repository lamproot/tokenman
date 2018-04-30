<?php

namespace app\admin\controller\general;

use app\admin\model\Admin;
use app\admin\model\AuthGroupAccess;
use app\admin\model\ChatBot;
use app\common\controller\Backend;
use fast\Random;
use think\Session;

/**
 * License 认证
 *
 * @icon fa fa-user
 */
class License extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Admin');
    }

    /**
     * 查看
     */
    public function index()
    {
        // if ($this->request->isAjax())
        // {
        //     $total = $model
        //             ->where($where)
        //             ->where('admin_id', $this->auth->id)
        //             ->order($sort, $order)
        //             ->count();
        //
        //     $list = $model
        //             ->where($where)
        //             ->where('admin_id', $this->auth->id)
        //             ->order($sort, $order)
        //             ->limit($offset, $limit)
        //             ->select();
        //
        //     $result = array("total" => $total, "rows" => $list);
        //
        //     return json($result);
        // }
        $row = $this->model->get(['id' => $_SESSION['think']['admin']['id']]);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 更新个人信息
     */
    public function update()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $params = array_filter(array_intersect_key($params, array_flip(array('license', 'account'))));
            unset($v);

            if ($params)
            {
                $admin = Admin::get($_SESSION['think']['admin']['id']);
                $admin->save($params);
                //因为个人资料面板读取的Session显示，修改自己资料后同时更新Session
                //Session::set("admin", $admin->toArray());
                $this->success();
            }
            $this->error();
        }
        return;
    }

}
