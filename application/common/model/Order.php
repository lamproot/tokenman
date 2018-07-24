<?php

namespace app\common\model;

use think\Model;

class Order extends Model
{

    // 表名
    protected $name = 'orders';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    // 追加属性
    protected $append = [
    ];

}
