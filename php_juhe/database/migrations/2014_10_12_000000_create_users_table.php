<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username',20)->unique()->comment('用户名');
            $table->string('remark')->nullable()->comment('用户备注');
            $table->integer('pid')->nullable()->comment('代理id');
            $table->integer('uid')->unique()->comment('用户id');
            $table->decimal('balance',11,2)->default(0)->comment('余额');
            $table->decimal('djmoney',11,2)->default(0)->comment('冻结金额');
            $table->boolean('group_type')->default(0)->comment('用户组标识：0商户,1代理');
            $table->string('password')->comment('登录密码');
            $table->string('save_code')->comment('安全码');
            $table->string('google_key')->nullable()->comment('谷歌密钥');
            $table->string('api_key')->comment('商户密钥');
            $table->string('last_ip')->nullable()->comment('最后一次登录ip');
            $table->boolean('status')->default(0)->comment('登录状态：0禁用，1启用');
            $table->boolean('is_jd')->default(0)->comment('接单状态：0禁用，1启用');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_users` comment '会员表'");
        DB::statement("ALTER TABLE `pay_users` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
