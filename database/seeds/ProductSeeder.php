<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create(
            [
                "name" => "COCA", "stock" => 100, "cost" => 10.0,"brand_id"=>1,
                "price_1" => 15.0, "price_2" => 17.0, "price_3" => 17.0,
                "bar_code" => "bbb", "branch_office_id" => 1, "category_id" => 2
            ]
        );
        Product::create(
            [
                "name" => "HELADO", "stock" => 100, "cost" => 10.0,"brand_id"=>1,
                "price_1" => 15.0, "price_2" => 17.0, "price_3" => 17.0,
                "bar_code" => "ccc", "branch_office_id" => 1, "category_id" => 2
            ]
        );
        Product::create(
            [
                "name" => "BOLETO DE TRIANGULO", "stock" => 100, "cost" => 10.0,"brand_id"=>1,
                "price_1" => 15.0, "price_2" => 17.0, "price_3" => 17.0,
                "bar_code" => "ddd", "branch_office_id" => 1, "category_id" => 3
            ]
        );

        for ($i=0; $i < 50; $i++) { 
            Product::create(
                [
                    "name" => "GALLETA".$i, "stock" => 100, "cost" => 10.0,"brand_id"=>1,
                    "price_1" => 15.0, "price_2" => 17.0, "price_3" => 17.0,
                    "bar_code" => "a".$i, "branch_office_id" => 1, "category_id" => 3
                ]
            );
        }
    }
}
