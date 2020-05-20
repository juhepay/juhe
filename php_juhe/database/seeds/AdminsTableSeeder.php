<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('admins')->insert([
            'username'  => 'admin',
            'nickname'  => '超级管理员',
            'password'  => bcrypt ('admin888'),
            'role_id'   => 1,
            'status'    => 1,
            'created_at'=> $now,
            'updated_at'=> $now
        ]);
    }
}
