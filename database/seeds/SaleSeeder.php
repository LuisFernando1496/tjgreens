<?php

use App\Sale;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sale::create([
            'payment_type'=>1,
            'discount'=>0,
            'cart_subtotal'=>90.0,
            'cart_total'=>90.0,
            'turned'=>10.0,
            'ingress'=>100.0,
            'cash_closing_id'=>1,
            'folio_branch_office'=>1,
            'cash_closing_id'=>1,
            'shopping_cart_id'=>1,
            'branch_office_id'=>1,
            'user_id'=>2,
            'total_cost'=>60.0,
            'created_at' => '2017-07-31 00:00:00',
        ]);
        Sale::create([
            'payment_type'=>0,
            'discount'=>0,
            'cart_subtotal'=>90.0,
            'cart_total'=>90.0,
            'turned'=>10.0,
            'ingress'=>100.0,
            'cash_closing_id'=>1,
            'folio_branch_office'=>21,
            'shopping_cart_id'=>1,
            'branch_office_id'=>2,
            'user_id'=>3,
            'total_cost'=>60.0,
            'created_at' => '2018-08-30 00:00:00',
        ]);
        Sale::create([
            'payment_type'=>0,
            'discount'=>0,
            'cart_subtotal'=>90.0,
            'cart_total'=>90.0,
            'turned'=>10.0,
            'ingress'=>100.0,
            'cash_closing_id'=>1,
            'folio_branch_office'=>2,
            'shopping_cart_id'=>3,
            'branch_office_id'=>2,
            'user_id'=>2,
            'total_cost'=>60.0,
            'created_at' => '2018-07-01 00:00:00',
        ]);
        Sale::create([
            'payment_type'=>0,
            'discount'=>0,
            'cart_subtotal'=>90.0,
            'cart_total'=>90.0,
            'turned'=>10.0,
            'ingress'=>100.0,
            'cash_closing_id'=>1,
            'folio_branch_office'=>2,
            'shopping_cart_id'=>4,
            'branch_office_id'=>2,
            'user_id'=>3,
            'total_cost'=>60.0,
        ]);
    }
}
