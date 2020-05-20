<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBankCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid')->comment('会员uid');
            $table->string('card_no')->comment('卡号');
            $table->string('real_name')->comment('卡号账户名');
            $table->string('bank_name')->comment('开户行名称');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_bank_cards` comment '提现账户'");
        DB::statement("ALTER TABLE `pay_bank_cards` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_cards');
    }
}
