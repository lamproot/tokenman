<?php

namespace app\admin\controller\chatbot;

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
        $this->model = model('ChatBot');
        $this->adminmodel = model('Admin');
        $this->is_shield = [0 => '关闭', 1 => '打开'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->is_shield = [0 => 'Close', 1 => 'Open'];
        }

        $this->is_currency = [0 => '关闭', 1 => '打开'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->is_currency = [0 => 'Close', 1 => 'Open'];
        }


    }

    /**
     * 查看
     */
    public function index()
    {
        //获取是否添加机器人
        $row = $this->adminmodel->get(['id' => $_SESSION['think']['admin']['id']]);
        //echo json_encode($row);exit;

        // if (!$row)
        //     $this->error(__('No Results were found'));

        $this->view->assign("row", $row->toArray());
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
                    ->field('id,chat_id,master_id,code_cmd,created_at,name,activity_id,remark,is_shield')
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
        $row = $this->adminmodel->get(['id' => $_SESSION['think']['admin']['id']]);
        if ($row && $row['chat_bot_id'] != 0) {
            $this->error(__('您已存在机器人'));
        }
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['chat_id'] = 0;
                $params['master_id'] = 0;
                $params['activity_id'] = 0;
                $create = $this->model->create($params);
                //更新账户管理机器人
                $_SESSION['think']['admin']['chat_bot_id'] = $botparams['chat_bot_id'] = $create['id'];
                $row->save($botparams);

                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }



    /**
     * 添加
     * @internal
     */
    public function create()
    {
        $row = $this->model->get(['admin_id' => $_SESSION['think']['admin']['id']]);

        if ($row) {
            // $this->error(__('您已存在机器人'));
            return json(array("code" => 200, "msg" => '您已存在机器人'));
        }

        if ($this->request->isPost())
        {
            $params = $_POST;
            if ($params)
            {

                $token = $_SESSION['think']['token'] = $params['token'];
                $params['token'] = $token;
                $params['admin_id'] = $_SESSION['think']['admin']['id'];
                $params['started_at'] = time();
                $params['stoped_at'] = time() + 365 * 24 * 60 * 60;

                $botInfo = $this->getMe();

                if (!$botInfo) {
                    return json(["code" => 0,"msg" => '创建失败Bot']);
                }

                //获取机器人信息
                $params['tokenman_id'] = ($botInfo && isset($botInfo['result'])) ? $botInfo['result']['id'] : "";
                $params['name'] = ($botInfo && isset($botInfo['result'])) ? $botInfo['result']['first_name'] : "";
                $params['tokenman_name'] = ($botInfo && isset($botInfo['result'])) ? $botInfo['result']['username'] : "";

                $create = $this->model->create($params);
                $url = "https://m.name-technology.fun/callback.php/Callback/run?bot_id=".$create['id']."&t=".time();

                $this->setWebhook($url);
                // $params['chat_id'] = 0;
                // $params['master_id'] = 0;
                // $params['activity_id'] = 0;
                // $create = $this->model->create($params);
                // //更新账户管理机器人
                // $_SESSION['think']['admin']['chat_bot_id'] = $botparams['chat_bot_id'] = $create['id'];
                //$row->save($botparams);

                //$this->success();

                // echo json_encode(array("code" => 200, "msg" => '创建成功'));exit;
                return json(["msg" => '创建成功']);
            }
            //$this->error();
        }

        return json(["create_bot" => true, "msg" => '创建机器人']);
        //return $this->view->fetch();
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

        $this->view->assign('currencyList', build_select('row[is_currency]', $this->is_currency, $row['is_currency'], ['class' => 'form-control selectpicker']));
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
