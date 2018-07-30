<?php

namespace app\admin\controller;
use app\admin\model\Codes;
use app\common\controller\Backend;
use app\admin\model\GroupBotConfig;
/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    protected $model = null;
    protected $noNeedLogin = ['test'];
    protected $noNeedRight = ['index', 'logout'];
    public function _initialize()
    {
        parent::_initialize();

        $this->model = model('Codes');
        $this->groupActivityModel = model('GroupActivity');
        $this->chatGroupModel = model('chatGroup');
        $this->groupUserModel = model('GroupUser');
        $this->illegaLogModel = model('IllegaLog');
        $this->group_bot_configmodel = model('GroupBotConfig');

        // $this->status = [0 => '关闭', 1 => '打开'];
        // if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
        //     $this->status = [0 => 'Close', 1 => 'Open'];
        // }
    }
    /**
     * 查看
     */
    public function index()
    {
        $seventtime = \fast\Date::unixtime('day', -30);
        $paylist = $createlist = [];
        for ($i = 0; $i < 30; $i++)
        {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            //活动参与人数
            $paylist[$day] = $this->groupUserModel
            ->where('type', '=', 1)
            ->where('chat_bot_id', '=',  $_SESSION['think']['admin']['chat_bot_id'])
            ->where('created_at', '>=', strtotime($day))
            ->where('created_at', '<=', strtotime($day . " 23:59:59") + 1)
            ->count();

            //入群人数
            $createlist[$day] =  $this->model
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                ->where('created_at', '>=', strtotime($day))
                ->where('created_at', '<=', strtotime($day . " 23:59:59") + 1)
                ->count();


        }



        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';

        $chat_bot_id = $_SESSION['think']['admin']['chat_bot_id'];
        //totaluser 活动总人数
        $totaluser = $this->model
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                ->count();
        //activateuser 激活人数
        $activateuser = $this->model
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                ->where('status', '=', 3)
                ->count();
        //notactivateuser 未激活人数
        $notactivateuser = $this->model
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                ->where('status', '=', 1)
                ->count();
        //邀请人次数 parent_group
        $parent_group = $this->model
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                ->where('parent_code', '<>', '')
                ->where('from_id', '<>', '')
                ->count();
        //todayuser 今日活动总人数
        $todayuser = $this->model
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                ->where('created_at', '>', strtotime(date('Y-m-d', time())))
                ->count();
        //激活奖励 activate_rate
        $activate_rate = 0;
        //邀请奖励 invitation_rate
        $invitation_rate = 0;
        //奖励总数 total_rate
        $total_rate = 0;

        //获取活动参与总用户
        $groupActivityList =  $this->groupActivityModel
                ->where('is_del', '=', 0)
                ->where('type', '=', 1)
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                ->limit(0, 3)
                ->order("id", "desc")
                ->select();
        $activityData = [];
        foreach ($groupActivityList as $key => $value) {
            //echo json_encode($value['chat_bot_id']);exit;
            //已激活用户(入群)
            $activityData[$key]['totaluser'] = $this->model
                    ->where('chat_bot_id', '=', $value['chat_bot_id'])
                    ->where('activity_id', '=', $value['id'])
                    ->count();
            //activateuser 激活人数
            $activityData[$key]['activateuser'] = $this->model
                    ->where('chat_bot_id', '=', $value['chat_bot_id'])
                    ->where('activity_id', '=', $value['id'])
                    ->where('status', '=', 3)
                    ->count();
            //notactivateuser 未激活人数
            $activityData[$key]['notactivateuser'] = $this->model
                    ->where('chat_bot_id', '=', $value['chat_bot_id'])
                    ->where('activity_id', '=', $value['id'])
                    ->where('status', '=', 1)
                    ->count();
            //邀请人次数 parent_group
            $activityData[$key]['parent_group'] = $this->model
                    ->where('chat_bot_id', '=', $value['chat_bot_id'])
                    ->where('activity_id', '=', $value['id'])
                    ->where('parent_code', '<>', '')
                    ->where('from_id', '<>', '')
                    ->count();
            //todayuser 今日活动总人数
            $activityData[$key]['todayuser'] = $this->model
                    ->where('chat_bot_id', '=', $value['chat_bot_id'])
                    ->where('activity_id', '=', $value['id'])
                    ->where('created_at', '>', strtotime(date('Y-m-d', time())))
                    ->count();

            //激活奖励 activate_rate
            $activityData[$key]['activate_rate'] = (int)$value['group_rate'] * (int)$activityData[$key]['activateuser'];
            //邀请奖励 invitation_rate
            $activityData[$key]['invitation_rate'] = (int)$value['rate'] * (int)$activityData[$key]['parent_group'];
            //奖励总数 total_rate
            $activityData[$key]['total_rate'] = $activityData[$key]['activate_rate'] + $activityData[$key]['invitation_rate'];
            $activityData[$key]['title'] = $value['title'];
            $activityData[$key]['rate_unit'] = $value['rate_unit'];
            $bgcolor = ["bg-aqua-gradient", "bg-purple-gradient", "bg-green-gradient", "bg-blue", "bg-red-gradient"];
            shuffle($bgcolor);
            $activityData[$key]['bgcolor'] = $bgcolor[$key%5];
        }


        //获取群数据
        $chatList =  $this->chatGroupModel
                ->where('is_del', '=', 0)
                ->where('status', '=', 1)
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                //->limit(0, 3)
                ->order("id", "desc")
                ->select();
        $chatData = [];
        foreach ($chatList as $key => $value) {
            $chatData[$key]['title'] = $value['title'];
            // 今日新增用户
            $chatData[$key]['todayuser'] = $this->groupUserModel
            ->where('type', '=', 1)
            ->where('chat_id', '=', $value['chat_id'])
            ->where('created_at', '>', strtotime(date('Y-m-d', time())))
            ->count();

            // 群用户总人数
            $chatData[$key]['totaluser'] = $this->groupUserModel
            ->where('type', '=', 1)
            ->where('chat_id', '=', $value['chat_id'])
            ->count();
            // 退群用户数据
            $chatData[$key]['extuser'] = $this->groupUserModel
            ->where('type', '=', 2)
            ->where('chat_id', '=', $value['chat_id'])
            ->count();
            // 封禁用户数据
            // $chatData[$key]['blankuser'] = $this->illegaLogModel
            // ->where('chat_id', '=', $value['chat_id'])
            // ->count();

            // 屏蔽群用户消息数据
            $chatData[$key]['blanknews'] = $this->illegaLogModel
            ->where('chat_id', '=', $value['chat_id'])
            ->count();

            $chatData[$key]['blankuser']  = $this->illegaLogModel
            ->field('from_id,count(1) as count')
            ->where('chat_id', '=', $value['chat_id'])
            ->group('from_id')
            ->count();

            $bgcolor = ["bg-aqua-gradient", "bg-purple-gradient", "bg-green-gradient", "bg-blue", "bg-red-gradient"];
            shuffle($bgcolor);
            $chatData[$key]['bgcolor'] = $bgcolor[$key%5];
        }
        //echo json_encode($activityData);exit;

        // 3,222,200 THM
        // 消耗奖励
        // 3,222,200 THM
        // 入群奖励
        // 3,200 THM
        // 激活奖励

        $this->view->assign([
            'totaluser'        => $totaluser,
            'todayuser'       => $todayuser,
            'activateuser'       => $activateuser,
            'notactivateuser'       => $notactivateuser,
            'parent_group' => $parent_group,
            'activate_rate' => $activate_rate,
            'invitation_rate' => $invitation_rate,
            'total_rate' => $total_rate,
            'totalorder'       => rand(32200,37200),
            'totalorderamount' => rand(33200,37200),
            'todayuserlogin'   => rand(35200,37200),
            'todayusersignup'  => rand(35200,37200),
            'todayorder'       => rand(35200,37200),
            'unsettleorder'    => 132,
            'sevendnu'         => '80%',
            'sevendau'         => '32%',
            'paylist'          => $paylist,
            'createlist'       => $createlist,
            'uploadmode'       => $uploadmode,
            'activityData' => $activityData,
            'chatData' => $chatData
        ]);

        return $this->view->fetch();
    }

    /**
     * 查看
     */
    public function test()
    {
        //获取所有权限
        $chat_bot_id = $_GET['chat_bot_id'];
        $chat_id = $_GET['chat_id'];


        $row = $this->group_bot_configmodel
               ->where('chat_bot_id', '=', $chat_bot_id)
               ->where('chat_id', '=', $chat_id)
               ->select();

        $result = [];
        foreach ($row as $key => $value) {
            $result[$value['rule']] = $value['value'];
            if ($value['data']) {
                $result[$value['rule']] = explode(",", $value['data']);
            }

            if ($value['rule'] == 'clear_all_news_time') {
                $clear_all_news_time = (($value['updated_at'] + $value['value']) - time());
                $result["clear_all_news_time_count"] = $clear_all_news_time <= 0 ? 0 : $clear_all_news_time;
            }
        }
        //echo json_encode($result);exit;
        $this->assign("result", $result);
        return $this->view->fetch();
    }

}
