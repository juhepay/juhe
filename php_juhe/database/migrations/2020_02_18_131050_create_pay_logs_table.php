<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreatePayLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid')->comment('会员id');
            $table->string('order_no',64)->comment('订单号');
            $table->text('content')->comment('参数');
            $table->text('result')->comment('结果');
            $table->decimal('amount',10,2)->comment('订单金额');
            $table->string('pay_code',20)->comment('支付类型');
            $table->string('ip',20)->nullable()->comment('ip');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_pay_logs` comment '接口日志'");
        DB::statement("ALTER TABLE `pay_pay_logs` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_logs');
    }
}
