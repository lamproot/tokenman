<?php

namespace app\admin\controller\group;

use app\admin\model\ChatCommand;
use app\admin\model\ChatBot;
use app\admin\model\GroupUser;
use app\admin\model\IllegaLog;
use app\admin\model\NewsTotal;
use app\common\controller\Backend;

/**
 *
 *
 * @icon fa fa-users
 * @remark 管理员可以查看自己所拥有的权限的管理员日志
 */
class User extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ChatGroup');
        $this->adminmodel = model('Admin');
        $this->chatbotmodel = model('ChatBot');
        $this->group_usermodel = model('GroupUser');
        $this->illega_logmodel = model('IllegaLog');
        $this->news_totalmodel = model('NewsTotal');


        $this->type = [0 => '', 1 => '入群', 2 => '退群', -1 => '拉黑'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->type = [0 => '', 1 => 'Join', 2 => 'Out', -1 => 'Blank'];
        }
    }

    /**
     * 查看
     */
    public function manage($ids)
    {
        //$ids = $_GET['ids'];
        //获取是否添加机器人
        $row = $this->model->get(['id' => $ids]);
        //echo json_encode($row);exit;
        // if (!$row)
        //     $this->error(__('No Results were found'));

        //$this->view->assign("row", $row->toArray());
        // if (!$row['chat_id']) {
        //     $this->error(__('chat_id No Results were found'));
        // }
        $chat_id = $row['chat_id'];
        $this->view->assign("chat_id", $chat_id);
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->group_usermodel
                    ->where($where)
                    ->where('chat_id', '=', $chat_id)
                    ->order($sort, $order)
                    ->count();

            $list = $this->group_usermodel
                    ->where($where)
                    ->where('chat_id', '=', $chat_id)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->view->assign("row", $row->toArray());
        $this->view->assign("ids", $ids);
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
            if ($this->request->isPost())
            {

                $idList = explode(",", $ids);
                foreach ($idList as $key => $value) {
                    $row = $this->group_usermodel->get(['id' => $value]);

                    if ($row && $row['chat_bot_id']) {
                        $chatBot = $this->chatbotmodel->get(['id' => $_SESSION['think']['admin']['chat_bot_id']]);

                        if (!$chatBot)
                            return $this->error("数据获取失败");

                        if ($chatBot && !empty($chatBot['token'])) {
                            $_SESSION['think']['token'] = $chatBot['token'];
                        }else{
                            return $this->error("Token不存在");
                        }
                        //进行踢出群操作调用接口
                        //获取机器人Token
                        $result = $this->kickChatMember($row['chat_id'], $row['from_id']);
                        if ($result) {
                            $params['type'] = -1;
                            $row->save($params);
                        }else{
                            $result[] = $row['from_id'];
                            //$this->error($result);
                        }
                    }

                }
            }
            $this->success();
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
