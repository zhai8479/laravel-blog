<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// orm:  object relation map  对象关系映射
class Blog extends Model
{
    // 设置表名
    protected $table = 'blogs';

    // 设置主键字段
    protected $primaryKey = 'id';

    // 设置时间戳自动维护, 通过模型来添加和修改数据库时， created_at 和 updated_at 都会对应的变动
    // 只对模型有效
    public $timestamps = true;

    // 设置填充(插入)的黑白名单, 只对模型有效
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
