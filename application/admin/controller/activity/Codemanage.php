<?php

namespace app\admin\controller\activity;

use app\admin\model\ChatCommand;
use app\common\controller\Backend;

/**
 *
 *
 * @icon fa fa-users
 * @remark 管理员可以查看自己所拥有的权限的管理员日志
 */
class Codemanage extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Codes');
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
                //echo $this->model->getLastSql();exit;
            //获取邀请人数
            $codes = [];
            foreach ($list as $key => $value) {
                if ($value['code'] && !empty($value['code'])) {
                    $codes[] = $value['code'];
                }
                $list[$key]['invited'] = 0;
            }

            $parent_total = $this->model
                    ->field('parent_code,count(1) as count')
                    ->where('parent_code', 'in', $codes)
                    ->where('status', '=', 3)
                    ->where('activity_id', '=', $activity_id)
                    ->group('parent_code')
                    ->select();

            if ($parent_total) {
                foreach ($parent_total as $key => $value) {
                    foreach ($list as $lkey => $lvalue) {
                        if ($value['parent_code'] == $lvalue['code']) {
                            $list[$lkey]['invited'] = $value['count'];
                        }
                    }
                }
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        //获取活动相关数据
        //totaluser 活动总人数
        $activity_data['totaluser'] = $this->model
                ->where('activity_id', '=', $activity_id)
                ->count();
        //activateuser 激活人数
        $activity_data['activateuser'] = $this->model
                ->where('activity_id', '=', $activity_id)
                ->where('status', '=', 3)
                ->count();
        //notactivateuser 未激活人数
        $activity_data['notactivateuser'] = $this->model
                ->where('activity_id', '=', $activity_id)
                ->where('status', '=', 1)
                ->count();
        //邀请人次数 parent_group
        $activity_data['parent_group'] = $this->model
                ->where('activity_id', '=', $activity_id)
                ->where('parent_code', '<>', '')
                ->where('from_id', '<>', '')
                ->count();
        $activity_data['activate_rate'] = (int)$activity_data['activateuser'] * (int)$row['group_rate'];
        //邀请奖励 invitation_rate
        $activity_data['invitation_rate'] =  (int)$activity_data['parent_group'] * (int)$row['rate'];
        //奖励总数 total_rate
        $activity_data['total_rate'] = $activity_data['activate_rate'] + $activity_data['invitation_rate'];

        //todayuser 今日活动总人数
        // $todayuser = $this->model
        //         ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
        //         ->where('created_at', '>', strtotime(date('Y-m-d', time())))
        //         ->count();

        //消耗奖励 入群奖励 激活奖励

        $this->view->assign("activity_data", $activity_data);
        $this->view->assign("activity", $row->toArray());
        return $this->view->fetch();
    }


    /**
     * 邀请用户列表
     */
    public function user($ids)
    {
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            //echo json_encode($where);exit;
            //获取用户code 数据
            $row = $this->model->get(['id' => $ids]);
            if (!$row)
                $this->error(__('No Results were found'));

            $parent_code = $row['code'];

            $total = $this->model
                    ->where($where)
                    ->where('status', '=', 3)
                    ->where('parent_code', '=', $parent_code)
                    //->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->where('status', '=', 3)
                    ->where('parent_code', '=', $parent_code)
                    //->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            //echo $this->model->getLastSql();exit;
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
