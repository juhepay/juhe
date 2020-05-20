<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid')->comment('会员uid');
            $table->integer('upaccount_id')->comment('上游账户id');
            $table->integer('apistyle_id')->comment('接口类型id');
            $table->char('order_no',64)->unique()->comment('订单号');
            $table->decimal('amount',10,2)->comment('订单金额');
            $table->decimal('user_amount',10,2)->comment('商户实收');
            $table->decimal('cost_amount',10,2)->comment('上游扣费');
            $table->decimal('agent_amount',10,2)->comment('代理收益');
            $table->decimal('fee',10,2)->comment('订单手续费');
            $table->string('api_style')->comment('接口类型');
            $table->string('client_sign')->nullable()->comment('客户端标识');
            $table->boolean('status')->default(0)->comment('状态 默认0 未支付，1已支付');
            $table->text('fj')->nullable()->comment('附加信息,json格式存储');
            $table->boolean('tz')->default(0)->comment('0未通知，1通知失败，2通知成功');
            $table->text('errorstr')->nullable()->comment('通知异常信息');
            $table->integer('paytime')->nullable()->comment('支付时间');
            $table->index('uid');
            $table->index('api_style');
            $table->index(['created_at']);
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_orders` comment '订单表'");
        DB::statement("ALTER TABLE `pay_orders` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
