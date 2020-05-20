<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SysconfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sysconfigs')->insert([
            'min_price' => 100,
            'fl_type'   => 0,
            'tx_fl'     => 1
        ]);
    }
}
