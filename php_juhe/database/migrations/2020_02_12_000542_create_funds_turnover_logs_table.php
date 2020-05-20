<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateFundsTurnoverLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funds_turnover_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('会员uid');
            $table->decimal('amount',10,2)->default(0)->comment('变动金额');
            $table->decimal('before_balance',10,2)->default(0)->comment('变动前余额');
            $table->decimal('after_balance',10,2)->default(0)->comment('变动后余额');
            $table->boolean('type')->default(1)->comment('操作类型，1充值，2提现');
            $table->string('content')->nullable()->comment('内容');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_funds_turnover_logs` comment '资金流水表'");
        DB::statement("ALTER TABLE `pay_funds_turnover_logs` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funds_turnover_logs');
    }
}
