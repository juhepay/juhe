<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateApizjsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apizjs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('apistyle_id')->comment('接口类型id');
            $table->integer('upaccount_id')->comment('接口账户id');
            $table->integer('changetime')->default(0)->comment('当前切换开始时间，用户自动切换的记时');
            $table->boolean('ifchoose')->default(0)->comment('是否使用当前接口账户 默认0不是 1是 ，同一接口类型只能有一个是1');
            $table->decimal('costfl',6,2)->default(0)->comment('上游费率');
            $table->decimal('runfl',6,2)->default(0)->comment('运营费率');
            $table->integer('minje')->default(0)->comment('单笔最小金额');
            $table->integer('maxje')->default(0)->comment('单笔最大金额');
            $table->integer('todayje')->default(0)->comment('今日交易限额');
            $table->decimal('usedje',11,2)->default(0)->comment('已用限额');
            $table->boolean('status')->default(0)->comment('接口状态');
        });
        DB::statement("ALTER TABLE `pay_apizjs` comment '接口账户费率表'");
        DB::statement("ALTER TABLE `pay_apizjs` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apizjs');
    }
}
