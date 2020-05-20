<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateUpstylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upstyles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('upstyle_name')->comment('名称');
            $table->string('upstyle_mark')->unique()->comment('标识');
            $table->text('params')->nullable()->comment('序列化参数');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_upstyles` comment '上游类型'");
        DB::statement("ALTER TABLE `pay_upstyles` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upstyles');
    }
}
