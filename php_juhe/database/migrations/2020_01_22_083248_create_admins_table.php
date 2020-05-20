<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username',20)->unique()->comment('用户名');
            $table->string('nickname',20)->comment('昵称');
            $table->string('password')->comment('登录密码');
            $table->string('google_key')->nullable()->comment('谷歌密钥');
            $table->string('last_ip')->nullable()->comment('最后一次登录ip');
            $table->boolean('status')->default(0)->comment('登录状态：0禁用，1启用');
            $table->integer('role_id')->nullable()->comment('角色id');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_admins` comment '管理员表'");
        DB::statement("ALTER TABLE `pay_admins` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
