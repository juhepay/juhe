<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateApistylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apistyles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('api_name')->comment('接口名称');
            $table->string('api_mark')->unique()->comment('接口标识');
            $table->boolean('is_polling')->default(0)->comment('0关闭轮询,1启用轮询');
            $table->text('polling_ids')->nullable()->comment('序列化权重和接口账户id');
            $table->boolean('status')->default(0)->comment('状态: 0关闭，1开启');
        });
        DB::statement("ALTER TABLE `pay_sysconfigs` comment '系统配置'");
        DB::statement("ALTER TABLE `pay_sysconfigs` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apistyles');
    }
}
