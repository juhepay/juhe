<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('users')->insert([
            'username'  => 'test',
            'uid'       => date('Y') . 100,
            'password'  => bcrypt ('qq258369'),
            'save_code' => bcrypt ('qq258369'),
            'status'    => 1,
            'is_jd'     => 1,
            'api_key'   => md5(TimeMicroTime()),
            'created_at'=> $now,
            'updated_at'=> $now
        ]);
    }
}
