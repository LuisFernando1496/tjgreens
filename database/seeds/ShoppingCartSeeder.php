<?php

use App\ShoppingCart;
use Illuminate\Database\Seeder;

class ShoppingCartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShoppingCart::create([]);
        ShoppingCart::create([]);
        ShoppingCart::create([]);
        ShoppingCart::create([]);
    }
}
