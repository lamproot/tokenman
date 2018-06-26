<?php

namespace app\admin\model;

use think\Model;

class GroupUser extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $table='group_user';

}
