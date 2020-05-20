<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateSyslogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syslogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('module')->nullable()->comment('操作模块');
            $table->text('content')->nullable()->comment('操作内容');
            $table->string('username')->nullable()->comment('用户名');
            $table->string('ip')->nullable()->comment('操作ip');
            $table->boolean('is_admin')->default(0)->comment('1管理员，0用户');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_syslogs` comment '系统日志'");
        DB::statement("ALTER TABLE `pay_syslogs` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syslogs');
    }
}
