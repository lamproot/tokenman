<?php

namespace app\admin\controller\guide;

use app\admin\model\GuideModel;
use app\common\controller\Backend;

/**
 * 新手指导管理
 *
 * @icon fa fa-guide
 * @remark
 */
class Manage extends Backend
{


    protected $model = null;
    protected $childrenGroupIds = [];
    protected $childrenAdminIds = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('GuideModel');
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
          list($where, $sort, $order, $offset, $limit) = $this->buildparams();
          $where = "is_delete = 0";
          $total = $this->model
                  ->where($where)
                  ->order($sort, $order)
                  ->count();

          $list = $this->model
                  ->where($where)
                  ->order($sort, $order)
                  ->limit($offset, $limit)
                  ->select();
          foreach ($list as $key => $value) {
              $list[$key]['url'] = "./guide/guide/detail?id=".$value['id'];
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

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
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
                $params['is_delete'] = 1;
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
