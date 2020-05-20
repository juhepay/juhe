<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('powers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('powers_name')->unique()->comment('权限名称');
            $table->string('powers_mark')->unique()->comment('权限标识');
            $table->integer('powers_sort')->comment('排序');
        });
        DB::statement("ALTER TABLE `pay_powers` comment '权限表'");
        DB::statement("ALTER TABLE `pay_powers` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('powers');
    }
}
