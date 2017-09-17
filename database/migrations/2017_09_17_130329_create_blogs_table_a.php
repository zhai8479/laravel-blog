<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTableA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * title    标题     字符串      not null    255    varchar
         * content  内容      字符串     not null    无上限  text
         * ip       ip地址    字符串     not null    20      varchar
         * user_id  用户id    数字       not null    11       int
         */

        /**
         * 设置字段的时候，默认不能为空
         * string  -> varchar
         * comment -> 注释
         * unique  -> 设置字段的唯一性
         */
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title', 255)->comment('博客标题');
            $table->text('content')->comment('博客内容');
            $table->string('ip', 20)->comment('ip地址');
            $table->integer('user_id', false, true)->length(11)->comment('用户id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
