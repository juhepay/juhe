<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateUpaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upaccounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('upaccount_name')->unique()->comment('账户名称');
            $table->string('upaccount_mark')->comment('标识,取值上游接口类型标识');
            $table->text('upaccount_params')->nullable()->comment('序列化配置参数');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_upaccounts` comment '上游账户'");
        DB::statement("ALTER TABLE `pay_upaccounts` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upaccounts');
    }
}
