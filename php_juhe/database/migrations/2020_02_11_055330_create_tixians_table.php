<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateTixiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tixians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid')->comment('会员uid');
            $table->integer('upaccount_id')->nullable()->comment('上游账户id');
            $table->decimal('money',10,2)->comment('提现金额');
            $table->boolean('status')->default(0)->comment('状态 默认0申请提现 1已支付 2冻结 3取消');
            $table->string('card_no')->comment('卡号');
            $table->string('real_name')->comment('卡号账户名');
            $table->string('bank_name')->comment('开户行名称');
            $table->string('order_no')->unique()->comment('订单号');
            $table->decimal('fee')->default(0)->comment('手续费');
            $table->string('notify_url')->nullable()->comment('代付异步通知地址');
            $table->string('remark')->nullable()->comment('备注');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_tixians` comment '提现'");
        DB::statement("ALTER TABLE `pay_tixians` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tixians');
    }
}
