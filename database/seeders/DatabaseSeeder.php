<?php

use App\Address;
use App\BranchOffice;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
       

        Address::create([
            'street'=>'Av. Benito Juárez',
            'suburb'=>'Bienestar Social',
            'ext_number'=>'415',
            'int_number' =>'0',
            'postal_code'=>'29077',
            'ext_number'=>'415',
            'int_number'=>'0',
            'city'=>'Tuxtla Gutiérrez',
            'state'=>'Chiapas',
            'country'=>'México'
        ]);

        BranchOffice::create([
            'name'=>'Matriz',
            'address_id'=>1,
        ]); 
        $this->call([ 
       RolsSeeder::class,
        /*
        $this->call(AddressSeeder::class);
        $this->call(BranchOfficesSeeder::class);*/
        UserSeeder::class,
        BrandSeeder::class,
        CategorySeeder::class,/*
        BoxSeeder::class;
        CashClosingSeeder::class;
        ShoppingCartSeeder::class;*/
        ProductSeeder::class,/*
        CashClosingSeeder::class;
        SaleSeeder::class;
        ProductInSaleSeeder::class;
        ExpenseSeeder::class;
        InitialCashSeeder::class;*/
        ]);
    }
}
