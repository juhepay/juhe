<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateUserRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid')->comment('会员uid');
            $table->integer('apistyle_id')->comment('接口类型id');
            $table->decimal('rate',10,2)->nullable()->comment('费率');
            $table->integer('upaccount_id')->nullable()->comment('上游账户id');
            $table->boolean('status')->default(0)->comment('0禁用，1启用');
        });
        DB::statement("ALTER TABLE `pay_user_rates` comment '会员费率表'");
        DB::statement("ALTER TABLE `pay_user_rates` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_rates');
    }
}
