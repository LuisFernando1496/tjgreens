<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create([
            'name'=>'Admin',

        ]);
        Rol::create([
            'name'=>'Vendedor',

        ]);

        Rol::create([
            'name' => 'Gerente',
        ]);
    }
}
