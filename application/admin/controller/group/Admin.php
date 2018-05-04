<?php

namespace app\admin\controller\group;

use app\admin\model\ChatCommand;
use app\admin\model\ChatBot;
use app\common\controller\Backend;

/**
 *
 *
 * @icon fa fa-users
 * @remark 管理员可以查看自己所拥有的权限的管理员日志
 */
class Admin extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ChatGroup');
        $this->chatbotmodel = model('ChatBot');
        $this->adminmodel = model('Admin');
        $this->whitemodel = model('White');
        $this->is_shield = [0 => '关闭', 1 => '打开'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->is_shield = [0 => 'Close', 1 => 'Open'];
        }
    }

    /**
     * 查看
     */
    public function index()
    {

        //$this->view->assign("row", $row->toArray());
        if ($this->request->isAjax())
        {
          //获取
          $chatBot = $this->chatbotmodel->get($_SESSION['think']['admin']['chat_bot_id']);
          if ($chatBot) {
              $_SESSION['think']['token'] = $chatBot['token'];
              $list = $this->getChatAdministrators($chatBot['chat_id']);

              //查询是否已添加至白名单
              if ($list) {
                  foreach ($list as $key => $value) {
                      $row = $this->whitemodel->get(['from_id' => $value['user']['id']]);
                      $list[$key]['whitestatus'] = $row ? true : false;
                  }
              }


              $result = array("total" => count($list), "rows" => $list);

              return json($result);
          }

        }
        return $this->view->fetch();
    }

    /**
     * 详情
     */
    public function detail($ids)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign("row", $row->toArray());
        return $this->view->fetch();
    }

    /**
     * 添加
     * @internal
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['admin_id'] = $_SESSION['think']['admin']['id'];

                $create = $this->model->create($params);
                //更新账户管理机器人
                // $_SESSION['think']['admin']['chat_bot_id'] = $botparams['chat_bot_id'] = $create['id'];
                // $row->save($botparams);

                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $row->save($params);
                $this->success();
            }
            $this->error();
        }


        $this->view->assign('shieldList', build_select('row[is_shield]', $this->is_shield, $row['is_shield'], ['class' => 'form-control selectpicker']));
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            $row = $this->model->get(['id' => $ids]);
            if (!$row)
                $this->error(__('No Results were found'));
            if ($this->request->isPost())
            {
                $params['is_del'] = 1;
                $row->save($params);
                $this->success();
            }
        }
        $this->error();
    }

    /**
     * 批量更新
     * @internal
     */
    public function multi($ids = "")
    {
        // 管理员禁止批量操作
        $this->error();
    }

    public function selectpage()
    {
        return parent::selectpage();
    }

}
