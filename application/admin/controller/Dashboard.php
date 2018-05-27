<?php

namespace app\admin\controller;
use app\admin\model\Codes;
use app\common\controller\Backend;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Codes');
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
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist = $createlist = [];
        for ($i = 0; $i < 7; $i++)
        {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
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
            'uploadmode'       => $uploadmode
        ]);

        return $this->view->fetch();
    }

}
