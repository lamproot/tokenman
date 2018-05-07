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
class Manage extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ChatGroup');
        $this->adminmodel = model('Admin');
        $this->chatbotmodel = model('ChatBot');
        $this->status = [0 => '未激活', 1 => '已激活'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->status = [0 => 'Not Activate', 1 => 'Is Activate'];
        }
    }

    /**
     * 查看
     */
    public function index()
    {
        //获取是否添加机器人
        //$row = $this->adminmodel->get(['id' => $_SESSION['think']['admin']['id']]);
        //echo json_encode($row);exit;

        // if (!$row)
        //     $this->error(__('No Results were found'));

        //$this->view->assign("row", $row->toArray());
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->where('is_del', '=', 0)
                    ->where('admin_id', '=', $_SESSION['think']['admin']['id'])
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->where('is_del', '=', 0)
                    ->where('admin_id', '=', $_SESSION['think']['admin']['id'])
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
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

        $total = $this->model
                ->where('is_del', '=', 0)
                ->where('admin_id', '=', $_SESSION['think']['admin']['id'])
                ->select();

        // vip 1 svip 1
        // if (intval($total) >= 1) {
        //     return $this->error("超出群创建上限 请联系管理员", '');
        // }

        //获取机器人信息
        $chatBot = $this->chatbotmodel
                    ->field("id,name")
                    ->where('is_del', '=', 0)
                    ->where('id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                    ->limit(0, 1)
                    ->select();

        if (!$chatBot) {
            $this->error("暂无机器人，请先创建机器人", 'chatbot/manage/index');
        }

        foreach ($chatBot as $key => $value) {
            $list[$value['id']] = $value['name'];
        }

        $this->view->assign('groupList', build_select('row[chat_bot_id]', $list, $chatBot[0]['id'], ['class' => 'form-control selectpicker']));


        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['admin_id'] = $_SESSION['think']['admin']['id'];

                $create = $this->model->create($params);

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

        $chatBot = $this->chatbotmodel->get($row['chat_bot_id']);
        if ($chatBot) {
            $_SESSION['think']['token'] = $chatBot['token'];
            $chatInfo = $this->getChat(520439801);
        }

        //$getFile = $this->getFile($chatInfo['photo']['small_file_id']);

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $this->setChatTitle($chatBot['chat_id'], $params['title']);
                $this->setChatDescription($chatBot['chat_id'], $params['description']);
                $row->save($params);
                $this->success();
            }
            $this->error();
        }
        $this->view->assign("chatInfo", $chatInfo);
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
