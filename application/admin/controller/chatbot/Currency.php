<?php

namespace app\admin\controller\chatbot;

use app\admin\model\BotCurrency;
use app\common\controller\Backend;

/**
 * 实时币价管理 币价管理与设置
 *
 * @icon fa fa-users
 * @remark
 */
class Currency extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('BotCurrency');
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
        unset($this->type[2]);
        $this->view->assign('groupList', build_select('row[type]', $this->type, 1, ['class' => 'form-control selectpicker']));

        $total = $this->model
                ->where('is_del', '=', 0)
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                ->count();
        // vip 1 条 svip 2
        // if (intval($_SESSION['think']['admin']['type'] == 1) && intval($total) >= 1) {
        //     $this->error("实时币价条数已用完 请联系管理员购买");
        // }
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
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['currency'] = strtolower($params['currency']);
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
