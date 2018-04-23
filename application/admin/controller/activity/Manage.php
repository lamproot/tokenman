<?php

namespace app\admin\controller\activity;

use app\admin\model\ChatCommand;
use app\common\controller\Backend;

/**
 * 关键词回复管理
 *
 * @icon fa fa-users
 * @remark 管理员可以查看自己所拥有的权限的管理员日志
 */
class Manage extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('GroupActivity');
        $this->type = [0 => 'Code 邀请活动', 1 => '拟稿人活动'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->type = [0 => 'Code Activity', 1 => 'Article Activity'];
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
        // vip 15 条 svip 20条
        if (intval($_SESSION['think']['admin']['type'] == 1) && intval($total) >= 15) {
            $this->error("关键词条数已用完 请联系管理员购买");
        }

        if ($_SESSION['think']['admin']['type'] == 2 && $total >= 20) {
            $this->error("关键词条数已用完 请联系管理员购买");
        }


        //判断条数是否够用


        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['chat_bot_id'] = $_SESSION['think']['admin']['chat_bot_id'];
                $params['is_del'] = 0;
                $params['url'] = (isset($params['url']) && !empty($params['url'])) ? $params['url'] : "";


                if ($params['type'] != 5) {
                    $params['content'] = $params['content'][0] ? $params['content'][0] : "";
                    $params['url'] = $params['url'][0] ? $params['url'][0] : "";
                }else{
                    $result = [];
                    foreach ($params['content'] as $key => $value) {
                        $result[$key]['note'] = $value;
                        $result[$key]['url'] = $params['url'][$key];
                    }
                    $params['content'] = json_encode($result);
                }
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
        //$this->view->assign('groupList', build_select('row[type]', $this->type, $row['type'], ['class' => 'form-control selectpicker']));
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                if ($params['type'] != 5) {
                    $params['content'] = $params['content'][0] ? $params['content'][0] : "";
                    $params['url'] = (isset($params['url'] ) && $params['url'][0]) ? $params['url'][0] : "";
                }else{
                    $result = [];
                    foreach ($params['content'] as $key => $value) {
                        $result[$key]['note'] = $value;
                        $result[$key]['url'] = $params['url'][$key];
                    }
                    $params['content'] = json_encode($result);
                }
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
