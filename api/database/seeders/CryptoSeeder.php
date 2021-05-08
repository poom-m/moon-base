<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CryptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cryptos')->insert([
            'name' => 'MOON',
            'price' => 50,
            'balance' => 1000,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
