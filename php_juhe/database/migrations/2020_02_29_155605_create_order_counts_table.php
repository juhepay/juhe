<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_counts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('total_amount',11,2)->default(0)->comment('订单总额');
            $table->decimal('success_amount',11,2)->default(0)->comment('成功订单总额');
            $table->integer('total_count')->default(0)->comment('订单总笔数');
            $table->integer('success_count')->default(0)->comment('成功订单笔数');
            $table->decimal('success_fee',11,2)->default(0)->comment('成功订单总手续费');
            $table->decimal('success_agent_amount',11,2)->default(0)->comment('成功订单代理分润');
            $table->decimal('success_cost_amount',11,2)->default(0)->comment('成功订单成本');
            $table->integer('addtime')->comment('添加时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_counts');
    }
}
