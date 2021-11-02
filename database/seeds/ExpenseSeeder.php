<?php

use Illuminate\Database\Seeder;
use App\Expense;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Expense::create(["quantity"=>1,"description"=>"Luz","price"=>100.0,"user_id"=>1,"cash_closing_id"=>1,"branch_office_id"=>1]);
    }
}
