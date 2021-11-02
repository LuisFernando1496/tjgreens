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
    public function run()
    {
        $this->call(RolSeeder::class);

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
        /*
        $this->call(AddressSeeder::class);
        $this->call(BranchOfficesSeeder::class);*/
        $this->call(UserSeeder::class);/*
        $this->call(BrandSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(BoxSeeder::class);
        $this->call(CashClosingSeeder::class);
        $this->call(ShoppingCartSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CashClosingSeeder::class);
        $this->call(SaleSeeder::class);
        $this->call(ProductInSaleSeeder::class);
        $this->call(ExpenseSeeder::class);
        $this->call(InitialCashSeeder::class);*/
    }
}
