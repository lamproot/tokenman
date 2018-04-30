<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

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

        //totaluser
        //todayuser
        //groupuser
        //activateuser
        //notactivateuser

        $this->view->assign([
            'totaluser'        => rand(35200,37200),
            'todayuser'       => rand(34200,37200),
            'groupuser'       => rand(34200,37200),
            'activateuser'       => rand(34200,37200),
            'notactivateuser'       => rand(34200,37200),
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
