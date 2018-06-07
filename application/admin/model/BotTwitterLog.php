<?php

namespace app\admin\model;

use think\Model;

class BotTwitterLog extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = '';
    protected $table='bot_twitter_log';

}
