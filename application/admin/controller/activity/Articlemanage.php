<?php

namespace app\admin\controller\activity;

use app\admin\model\ChatCommand;
use app\common\controller\Backend;

/**
 * 拟稿人活动管理
 *
 * @icon fa fa-users
 * @remark 管理员可以查看自己所拥有的权限的管理员日志
 */
class Articlemanage extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Drafters');
        $this->activitymodel = model('GroupActivity');
    }

    /**
     * 查看
     */
    public function index()
    {
        $activity_id = $_GET['activity_id'] ? intval($_GET['activity_id']) : 0;

        $row = $this->activitymodel->get(['id' => $activity_id]);
        if (!$row)
            $this->error(__('No Results were found'));

        if ($row && intval($row['chat_bot_id']) != intval($_SESSION['think']['admin']['chat_bot_id'])) {
            $this->error(__('无权限查看，请联系管理员'));
        }

        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->where('activity_id', '=', $activity_id)
                    //->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->where('activity_id', '=', $activity_id)
                    //->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * 邀请用户列表
     */
    public function user()
    {
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            //echo json_encode($where);exit;
            $total = $this->model
                    ->where($where)
                    ->where('status', '=', 3)
                    //->where('parent_code', '=', $parent_code)
                    //->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->where('status', '=', 3)
                    //->where('parent_code', '=', $parent_code)
                    //->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
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
