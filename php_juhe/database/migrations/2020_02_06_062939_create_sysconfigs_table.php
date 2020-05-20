<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSysconfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sysconfigs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('min_price')->default(0)->comment('最小提现金额');
            $table->boolean('fl_type')->default(0)->comment('费率类型 默认0单笔，1百分例');
            $table->decimal('tx_fl')->default(0)->comment('费率，根据费率类型');
        });

        DB::statement("ALTER TABLE `pay_sysconfigs` comment '系统配置'");
        DB::statement("ALTER TABLE `pay_sysconfigs` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sysconfigs');
    }
}
