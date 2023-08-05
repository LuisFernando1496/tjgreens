<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Category::create(["name"=>"Ropa","status"=>true]);
        Category::create(["name"=>"Zapatos","status"=>true]);
        Category::create(["name"=>"Comida","status"=>true]);
        Category::create(["name"=>"Frutas","status"=>true]);
    }
}
