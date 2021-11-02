<?php

use Illuminate\Database\Seeder;
use App\BranchOffice;
class BranchOfficesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BranchOffice::create([
            'name'=>'Oficina Cancún',
            'address_id'=>1,
        ]);
        BranchOffice::create([
            'name'=>'Oficina san cris',
            'address_id'=>2,
        ]);
        BranchOffice::create([
            'name'=>'Oficina nueva',
            'address_id'=>3,
        ]);
        BranchOffice::create([
            'name'=>'Oficina vieja',
            'address_id'=>4,
        ]);
    }
}
