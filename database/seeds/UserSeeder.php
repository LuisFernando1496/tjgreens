<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
    
        /*
        User::create([
            'name' => 'Samuel',
            'last_name' => 'Aguilera Mendoza',
            'curp' => 'AUMS951102HCSGNM02',
            'rfc' => 'AUMS951102N17',
            'phone' => '9616490518',
            'email' => 'sam021195@hotmail.com',
            'password' => '14270995',
            'status' => 1,
            'address_id' => 1,
            'branch_office_id' => 1,
            'rol_id' => 1,
        ]);
        */
        User::create([
            'name' => 'Santiago',
            'last_name' => 'López',
            'curp' => \Str::random(10),
            'rfc'=> \Str::random(10),
            'phone' => '9612343086',
            'email' => 'admin@gmail.com',
            'password' => 'Pass1234',
            'status' => 1,
            'address_id'=>1,
            'branch_office_id'=> 1,
            'rol_id'=>1,
        ]);
        User::create([
            'name' => 'Santiago',
            'last_name' => 'López',
            'curp' => \Str::random(10),
            'rfc'=> \Str::random(10),
            'phone' => '9612343086',
            'email' => 'gerente@gmail.com',
            'password' => 'Pass1234',
            'status' => 1,
            'address_id'=>1,
            'branch_office_id'=> 1,
            'rol_id'=>3,
        ]);
        /*User::create([
            'name' => 'Juan',
            'last_name' => 'López',
            'curp' => \Str::random(10),
            'rfc'=> \Str::random(10),
            'phone' => '9612343086',
            'email' => 'empleado@gmail.com',
            'password' => 'Pass1234',
            'status' => 1,
            'user_id' => 1,
            'address_id'=>2,
            'branch_office_id'=> 1,
            'rol_id'=>3,
        ]);
        User::create([
            'name' => 'Ernesto',
            'last_name' => 'Guerra',
            'curp' => \Str::random(10),
            'rfc'=> \Str::random(10),
            'phone' => '9612343086',
            'email' => 'empleado2@gmail.com',
            'password' => 'Pass1234',
            'status' => 1,
            'address_id'=>3,
            'user_id' => 2,
            'branch_office_id'=> 2,
            'rol_id'=>2,
        ]);*/

    }
}
