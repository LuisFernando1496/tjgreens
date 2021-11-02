<?php

use Illuminate\Database\Seeder;
use App\CashClosing;

class CashClosingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CashClosing::create([
            "initial_cash" => 10.0, "end_cash" => 100.0, "branch_office_id" => 1, "user_id" => 2, 'box_id' => 1
        ]);
        CashClosing::create([
            "initial_cash" => 10.0, "end_cash" => 100.0, "branch_office_id" => 2, "user_id" => 3, 'box_id' => 2
        ]);
    }
}
