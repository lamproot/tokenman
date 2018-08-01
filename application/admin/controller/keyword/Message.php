<?php

namespace app\admin\controller\keyword;

use app\admin\model\ChatCommand;
use app\admin\model\ChatBot;
use app\common\controller\Backend;

/**
 *
 *
 * @icon fa fa-users
 * @remark 管理员可以查看自己所拥有的权限的管理员日志
 */
class Message extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ChatPush');
        $this->chatbot = model('ChatBot');
        $this->type = [1 => '普通消息类型', 3 => '图文回复类型', 4 => '文件类型'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->type = [1 => 'Common message type', 3 => 'Graph and text reply type', 4 => 'File reply type'];
        }

        $this->time = [0 => '实时发送', 1 => '定时发送'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->time = [0 => 'Real time transmission', 1 => 'Timing transmission'];
        }

        $this->pinList = [0 => '不置顶', 1 => '置顶'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->pinList = [0 => 'Not Pin', 1 => 'Pin'];
        }



    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->where('is_del', '=', 0)
                    ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->field('id,type,content,created_at,url,push_username,push_uid')
                    ->where($where)
                    ->where('is_del', '=', 0)
                      ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $type = $this->type;

            if ($list) {
                foreach ($list as $key => $value) {
                      $list[$key]['typename'] = isset($type[$value['type']]) ? $type[$value['type']] : "";
                }
            }
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

        $result['id'] = $row['id'];
        $result['created_at'] = date("Y-m-d H:i:s", $row['created_at']);
        $result['content'] = $row['content'];
        $result['type'] = $this->type[$row['type']];
        $result['url'] = $row['url'];
        $result['push_username'] = $row['push_username'];
        $result['push_uid'] = $row['push_uid'];

        $this->view->assign("row", $result);
        return $this->view->fetch();
    }

    /**
     * 添加
     * @internal
     */
    public function add()
    {

        //sendMessage
        //获取Chat Bot Token信息

        unset($this->type[2]);
        $this->view->assign('groupList', build_select('row[type]', $this->type, 1, ['class' => 'form-control selectpicker']));
        $this->view->assign('timeList', build_select('row[push_type]', $this->time, 0, ['class' => 'form-control']));
        $this->view->assign('pinList', build_select('row[is_pin]', $this->pinList, 0, ['class' => 'form-control']));

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['chat_bot_id'] = $_SESSION['think']['admin']['chat_bot_id'];
                $params['is_del'] = 0;
                $params['url'] = (isset($params['url']) && !empty($params['url'])) ? $params['url'][0] : "";
                $params['content'] = (isset($params['content']) && !empty($params['content'])) ? $params['content'][0] : "";
                $params['is_pin'] = (isset($params['is_pin']) && !empty($params['is_pin'])) ? $params['is_pin'] : 0;


                //定时推送功能
                if (isset($params['push_type']) && (int)$params['push_type'] == 1) {
                    if ($params['push_task'] == "" || !strtotime($params['push_task'])) {
                        return $this->error("task创建失败");
                    }
                    $params['push_task'] = strtotime($params['push_task']);
                    $this->model->create($params);
                    $this->success();
                    return;
                }

                $row = $this->chatbot->get(['id' => $_SESSION['think']['admin']['chat_bot_id']]);
                if (!$row)
                    return $this->error("数据获取失败");

                if ($row && !empty($row['token'])) {
                    $_SESSION['think']['token'] = $row['token'];
                }else{
                    return $this->error("Token不存在");
                }

                if ($params['type'] == 1) {
                    $result = $this->sendMessage($row['chat_id'], $params['content']);
                    if ($params['is_pin']) {
                        $this->pinChatMessage($row['chat_id'], $result);
                    }
                }

                if ($params['type'] == 3) {
                    $result = $this->sendPhoto($row['chat_id'], $params['url'], $params['content']);
                }

                if ($params['type'] == 4) {
                    $result = $this->sendDocument($row['chat_id'],$params['url'], $params['content']);
                }
                
                $this->model->create($params);
                //发送消息 更新status

                $this->success();
            }
            $this->error();
        }

        return $this->view->fetch();
    }

    public function selectpage()
    {
        return parent::selectpage();
    }

}
