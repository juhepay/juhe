<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateOrderAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_no',64)->comment('订单号');
            $table->integer('level')->comment('当前级别');
            $table->integer('agent')->comment('代理id');
            $table->decimal('money',10,2)->comment('分润金额');
            $table->decimal('rate',10,2)->comment('费率');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_order_agents` comment '代理订单分润'");
        DB::statement("ALTER TABLE `pay_order_agents` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_agents');
    }
}
