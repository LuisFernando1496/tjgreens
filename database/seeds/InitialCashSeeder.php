<?php

use Illuminate\Database\Seeder;
use App\InitialCash;

class InitialCashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InitialCash::create([
            'amount' => 200.0
        ]);
    }
}
