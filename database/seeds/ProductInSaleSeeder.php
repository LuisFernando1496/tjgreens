<?php

use App\ProductInSale;
use Illuminate\Database\Seeder;

class ProductInSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductInSale::create([
            'product_id' => 2,
            'discount' => 0,
            'sale_id' => 1,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0,
        ]);
        ProductInSale::create([
            'product_id' => 3,
            'discount' => 0,
            'sale_id' => 1,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0,
        ]);
        ProductInSale::create([
            'product_id' => 4,
            'discount' => 0,
            'sale_id' => 1,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0,
        ]);
        ProductInSale::create([
            'product_id' => 2,
            'discount' => 0,
            'sale_id' => 2,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0,
        ]);
        ProductInSale::create([
            'product_id' => 2,
            'discount' => 0,
            'sale_id' => 2,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0,
        ]);
        ProductInSale::create([
            'product_id' => 4,
            'discount' => 0,
            'sale_id' => 2,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0,
        ]);
        ProductInSale::create([
            'product_id' => 2,
            'discount' => 0,
            'sale_id' => 3,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0,
        ]);
        ProductInSale::create([
            'product_id' => 3,
            'discount' => 0,
            'sale_id' => 3,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0
        ]);
        ProductInSale::create([
            'product_id' => 4,
            'discount' => 0,
            'sale_id' => 3,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0
        ]);

        ProductInSale::create([
            'product_id' => 2,
            'discount' => 0,
            'sale_id' => 4,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0
        ]);
        ProductInSale::create([
            'product_id' => 3,
            'discount' => 0,
            'sale_id' => 4,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0
        ]);
        ProductInSale::create([
            'product_id' => 4,
            'discount' => 0,
            'sale_id' => 4,
            'sale_price' => 15.0,
            'quantity' => 2,
            'subtotal' => 30.0,
            'total_cost'=>20.0,
            'total' => 30.0
        ]);
    }
}
