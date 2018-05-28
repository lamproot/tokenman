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
class Manage extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('GroupActivity');
        $this->thememodel = model('ActivityTheme');
        $this->codemodel = model('Codes');
        $this->type = [0 => '请选择活动类型', 1 => 'Code 邀请活动', 2 => '拟稿人活动'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->type = [0 => 'Choose Type', 1 => 'Code Activity', 2 => 'Article Activity'];
        }

        //激活类型 0-TokenMan 激活 1-群激活 2-TokenMan和群激活
        $this->activate_type = [0 => 'TokenMan 激活', 1 => '群激活', 2 => 'TokenMan和群激活'];
        if (isset($_COOKIE['think_var']) && $_COOKIE['think_var'] == 'en') {
            $this->activate_type = [0 => 'TokenMan', 1 => 'Group', 2 => 'TokenMan And Group'];
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
     * 发布活动
     */
    public function publish()
    {

        //查询
        $activityList = $this->model
                ->where('is_del', '=', 0)
                ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                ->select();
        if ($activityList) {
            foreach ($activityList as $key => $value) {
                if (isset($value['type']) && isset($this->type[$value['type']])) {
                    unset($this->type[$value['type']]);
                }
            }
        }

        $this->view->assign('groupList', build_select('row[type]', $this->type, 1, ['class' => 'form-control selectpicker']));

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                // if (isset($params['type']) && intval($params['type']) === 0) {
                //     return $this->error(__('请选择活动类型'));
                // }
                //查询是否已添加过相同活动
                $params['type'] = $params['type'] ? $params['type'] : 1;
                $total = $this->model
                        ->where('is_del', '=', 0)
                        ->where('type', '=', $params['type'])
                        ->where('chat_bot_id', '=', $_SESSION['think']['admin']['chat_bot_id'])
                        ->count();

                if ($total > 0) {
                    $this->error(__('The same activity has already existed'));
                }

                $params['chat_bot_id'] = $_SESSION['think']['admin']['chat_bot_id'];
                $params['message'] = "";
                $params['logo'] = "";
                $params['join_button_text'] = "";
                $params['join_button_url'] = "";
                $params['bottom_text'] = "";
                $params['rule'] = "";
                $params['is_del'] = 0;
                $create = $this->model->create($params);
                $url = $this->request->get('url');
                if ($create) {
                    //$this->redirect("添加成功", '/admin/activity/manage/edit/ids/'.$create['id']);
                    $this->redirect('/admin/activity/manage/edit/ids/'.$create['id'], [], 302, ['referer' => $url]);
                }else{
                    $this->error("Create Error");
                }
            }
            $this->error("Params No");
        }
        return $this->view->fetch();
    }



    /**
     * 添加
     * @internal
     */
    public function add()
    {
        $this->view->assign('groupList', build_select('row[type]', $this->type, 1, ['class' => 'form-control selectpicker']));

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['chat_bot_id'] = $_SESSION['think']['admin']['chat_bot_id'];
                $params['is_del'] = 0;
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
                if ($params['started_at']) {
                    $params['started_at'] = strtotime($params['started_at']);
                }
                if ($params['stoped_at']) {
                    $params['stoped_at'] = strtotime($params['stoped_at']);
                }
                $params['status'] = 0;
                $row->save($params);
                $this->success();
            }
            $this->error();
        }

        //获取活动主题数据
        $themeList = $this->thememodel
                ->field('id,name')
                ->where('is_del', '=', 0)
                ->select();
        foreach ($themeList as $key => $value) {
            $activityThemeList[$value['id']] = $value['name'];
        }


        $this->view->assign('activateTypeList', build_select('row[activate_type]', $this->activate_type, 1, ['class' => 'form-control selectpicker']));
        $this->view->assign('activityThemeList', build_select('row[theme_id]', $activityThemeList, 1, ['class' => 'form-control selectpicker']));

        //Code活动
        $activityUrl = "";
        if ($row['type'] == 1) {
            $botCode = $this->codemodel->get(['activity_id' => $ids, 'from_id' => 1, 'status' => 3]);
            $activityUrl = "http://m.name-technology.fun/Index/code/".$botCode['code'];
        }

        if ($row['type'] == 2) {
            $activityUrl = "http://m.name-technology.fun/Index/activity/".$row['id'];
        }
        $row['baseurl'] =
        //{$botCode['code']}
        $this->view->assign('activityUrl', $activityUrl);

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
