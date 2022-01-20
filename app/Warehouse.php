<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'office_id',
        'user_id',
        'status'
    ];

    public function oficina()
    {
        return $this->belongsTo(BranchOffice::class,'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'id','user_id');
    }

    public function inventarios()
    {
        return $this->hasMany(Inventory::class,'id','warehouse_id');
    }
}
