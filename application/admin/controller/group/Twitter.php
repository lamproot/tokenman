<?php

namespace app\admin\controller\chatbot;

use app\admin\model\ChatCommand;
use app\common\controller\Backend;

/**
 * Twitter转载管理 公共定时脚本 开启转载 转载记录
 *
 * @icon fa fa-users
 * @remark 管理员可以查看自己所拥有的权限的管理员日志
 */
class Twitter extends Backend
{

    protected $model = null;
    protected $noNeedLogin = 'script';
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('BotTwitter');
        $this->status = [0 => '关闭', 1 => '开启'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->status = [0 => 'Close', 1 => 'Open'];
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
                    ->where($where)
                    ->where('is_del', '=', 0)
                      ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
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
        $this->view->assign('statusList', build_select('row[status]', $this->status, 0, ['class' => 'form-control selectpicker']));

        $total = $this->model
                ->where('is_del', '=', 0)
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                ->count();
        // vip 15 条 svip 20条
        if (intval($_SESSION['think']['admin']['type'] == 1) && intval($total) >= 10) {
            $this->error("Twitter数已用完 请联系管理员购买");
        }
        //
        // if ($_SESSION['think']['admin']['type'] == 2 && $total >= 20) {
        //     $this->error("关键词条数已用完 请联系管理员购买");
        // }


        //判断条数是否够用


        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['chat_bot_id'] = $_SESSION['think']['admin']['chat_bot_id'];
                $params['is_del'] = 0;
                $params['opreate_uid'] = $_SESSION['think']['admin']['id'];
                $params['opreate_username'] = $_SESSION['think']['admin']['username'];

                $this->model->create($params);
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
        $this->view->assign('statusList', build_select('row[status]', $this->status, $row['status'], ['class' => 'form-control selectpicker']));

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
