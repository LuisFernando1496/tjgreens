<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchPrice extends Model
{
    protected $table = 'branch_prices';
    protected $fillable = ['office_id','inventory_id','branch_cost'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class,'inventory_id','id');
    }

    public function office()
    {
        return $this->belongsTo(BranchOffice::class,'office_id','id');
    }
}
