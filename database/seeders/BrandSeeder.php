<?php

namespace Database\Seeders;

use App\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create(["name"=>"Barcel","status"=>true]);
        Brand::create(["name"=>"Bimbo","status"=>true]);
        Brand::create(["name"=>"Sabritas","status"=>true]);
        Brand::create(["name"=>"Precisimo","status"=>true]);
    }
}
