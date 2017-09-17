<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBlogsTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 将表中的 title 长度由 255 修改为了 40
        Schema::table('blogs', function (Blueprint $table) {
            // add
            // alter

            // 对字段修改的操作，要加change
            $table->string('title', 40)->comment('博客标题')->change();

            // 添加字段操作
            $table->boolean('is_delete')->comment('是否删除')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('title', 255)->comment('博客标题')->change();
            $table->dropColumn('is_delete');
        });
    }
}
