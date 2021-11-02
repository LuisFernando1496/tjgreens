<?php

use Illuminate\Database\Seeder;
use App\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::create(["name"=>"Barcel","status"=>true]);
        Brand::create(["name"=>"Bimbo","status"=>true]);
        Brand::create(["name"=>"Sabritas","status"=>true]);
        Brand::create(["name"=>"Precisimo","status"=>true]);
    }
}
