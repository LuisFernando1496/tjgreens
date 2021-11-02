<?php

use Illuminate\Database\Seeder;
use App\Address;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::create([
            'street'=>'Av central',
            'suburb'=>'El Calvario',
            'ext_number'=>'1234',
            'int_number' =>'4321',
            'postal_code'=>'29000',
            'ext_number'=>'1324',
            'int_number'=>'1324',
            'city'=>'Tuxtla Gutiérrez',
            'state'=>'Chiapas',
            'country'=>'México'
        ]);
        Address::create([
            'street'=>'Calle higo quemado',
            'suburb'=>'Higo quemado',
            'ext_number'=>'1234',
            'int_number' =>'4321',
            'postal_code'=>'29049',
            'city'=>'Tuxtla Gutiérrez',
            'state'=>'Chiapas',
            'country'=>'México',
            'ext_number'=>'1324',
            'int_number'=>'1324',
        ]);
        Address::create([
            'street'=>'Central street',
            'suburb'=>'Washington d.c',
            'ext_number'=>'1234',
            'int_number' =>'4321',
            'postal_code'=>'29000',
            'city'=>'Tuxtla Gutiérrez',
            'state'=>'Chiapas',
            'country'=>'United States',
            'ext_number'=>'1324',
            'int_number'=>'1324',
        ]);
        Address::create([
            'street'=>'Cancun street',
            'suburb'=>'Cancun D.C',
            'ext_number'=>'1234',
            'int_number' =>'4321',
            'postal_code'=>'29009',
            'city'=>'Cancun',
            'state'=>'Quintanaroo',
            'country'=>'México',
            'ext_number'=>'1324',
            'int_number'=>'1324',
        ]);

    }
}
