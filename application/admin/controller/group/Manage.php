<?php

namespace app\admin\controller\group;

use app\admin\model\ChatCommand;
use app\admin\model\ChatBot;
use app\admin\model\GroupUser;
use app\admin\model\IllegaLog;
use app\admin\model\NewsTotal;
use app\admin\model\GroupBotConfig;
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
    protected $noNeedLogin = ['botconfig'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ChatGroup');
        $this->adminmodel = model('Admin');
        $this->chatbotmodel = model('ChatBot');
        $this->group_usermodel = model('GroupUser');
        $this->illega_logmodel = model('IllegaLog');
        $this->news_totalmodel = model('NewsTotal');
        $this->group_bot_configmodel = model('GroupBotConfig');


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
        //获取今日新增数据 group_user  1 入群 2 退群 3 拉黑
        $group_data['todayuser'] = $this->group_usermodel
        ->where('chat_id', '=', $row['chat_id'])
        ->where('type', '=', 1)
        ->where('created_at', '>=', strtotime(date("Y-m-d", time())))
        ->count();

        $group_data['totaluser'] = $this->group_usermodel
        ->where('chat_id', '=', $row['chat_id'])
        ->where('type', '=', 1)
        ->count();

        $group_data['extuser'] = $this->group_usermodel
        ->where('chat_id', '=', $row['chat_id'])
        ->where('type', '=', 2)
        ->count();

        $group_data['blankuser'] = $this->illega_logmodel
        ->where('chat_id', '=', $row['chat_id'])
        ->group('from_id')
        ->count();

        //拉黑消息数据 illega_log
        $group_data['blanknews'] = $this->illega_logmodel
        ->where('chat_id', '=', $row['chat_id'])
        ->count();

        //消息总数统计 news_total group_id chat_bot_id total
        $newstotal = $this->news_totalmodel
        ->get(['chat_id' => $row['chat_id']]);
        $group_data['newstotal'] = $newstotal ? $newstotal : ["total" => 0];

        $this->view->assign("group_data", $group_data);
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
            $chatInfo = $this->getChat($row['chat_id']);
        }

        if (!isset($chatInfo['description'])) {
            $chatInfo['description'] = "";
        }

        if (!isset($chatInfo['title'])) {
            $chatInfo['title'] = "";
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


    /**
     * 编辑
     */
    public function botconfig()
    {
        if ($this->request->isPost())
        {
            $params = $_POST;
            //echo json_encode($params);exit;
            if ($params)
            {
                $rule = isset($params['rule']) ? $params['rule'] : "";
                $value = isset($params['value']) ? (int)$params['value'] : "";
                $data = isset($params['data']) ? $params['data'] : "";
                $chat_bot_id = isset($params['chat_bot_id']) ? (int)$params['chat_bot_id'] : "";
                $chat_id = isset($params['chat_id']) ? (int)$params['chat_id'] : "";

                // $row = $this->group_bot_configmodel
                //        ->where('chat_id', '=', $chat_id)
                //        ->select();
                $row = $this->group_bot_configmodel->get(['chat_id' => $chat_id,'chat_bot_id' => $chat_bot_id, 'rule' => $rule]);

                if (!$row){
                    $addparams['rule'] = $rule;
                    $addparams['value'] = $value;
                    $addparams['data'] = $data;
                    $addparams['chat_bot_id'] = $chat_bot_id;
                    $addparams['chat_id'] = $chat_id;
                    $result = $this->group_bot_configmodel->create($addparams);

                }else{
                    $saveparams['rule'] = $rule;
                    $saveparams['value'] = $value;
                    $saveparams['data'] = $data;
                    $result = $row->save($saveparams);

                }
            }
            return json(["code" => 0, "msg" => "成功", "data" => $result]);
        }
        //echo json_encode($row);exit;

        // $chatBot = $this->chatbotmodel->get($row['chat_bot_id']);
        // if ($chatBot) {
        //     $_SESSION['think']['token'] = $chatBot['token'];
        //     $chatInfo = $this->getChat($row['chat_id']);
        // }
        //
        // if (!isset($chatInfo['description'])) {
        //     $chatInfo['description'] = "";
        // }
        //
        // if (!isset($chatInfo['title'])) {
        //     $chatInfo['title'] = "";
        // }
        // //$getFile = $this->getFile($chatInfo['photo']['small_file_id']);
        //
        // if ($this->request->isPost())
        // {
        //     $params = $this->request->post("row/a");
        //     if ($params)
        //     {
        //         $this->setChatTitle($chatBot['chat_id'], $params['title']);
        //         $this->setChatDescription($chatBot['chat_id'], $params['description']);
        //         $row->save($params);
        //         $this->success();
        //     }
        //     $this->error();
        // }
        // $this->view->assign("chatInfo", $chatInfo);
        // $this->view->assign("row", $row);
        //return $this->view->fetch();
    }

}
