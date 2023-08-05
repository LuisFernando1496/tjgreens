<?php

namespace Database\Seeders;

use App\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

        Rol::create([
            'name' => 'Almacén'
        ]);
    }
}
