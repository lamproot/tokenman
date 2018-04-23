<?php

namespace app\admin\controller\keyword;

use app\admin\model\ChatCommand;
use app\common\controller\Backend;

/**
 * 关键词回复管理
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
        $this->type = [1 => '普通消息类型', 3 => '图文回复类型', 4 => '文件类型'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->type = [1 => 'Common message type', 3 => 'Graph and text reply type', 4 => 'File reply type'];
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
        unset($this->type[2]);
        $this->view->assign('groupList', build_select('row[type]', $this->type, 1, ['class' => 'form-control selectpicker']));

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['chat_bot_id'] = $_SESSION['think']['admin']['chat_bot_id'];
                $params['is_del'] = 0;
                $params['url'] = (isset($params['url']) && !empty($params['url'])) ? $params['url'][0] : "";
                $params['content'] = (isset($params['content']) && !empty($params['content'])) ? $params['content'][0] : "";
                $this->model->create($params);
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
