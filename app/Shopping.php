<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shopping extends Model
{
    protected $fillable = [
        'warehouse_id',
        'office_id',
        'subtotal',
        'discount',
        'total',
        'type',
        'status',
        'user_id'
    ];

    public function almacen()
    {
        return $this->belongsTo(Warehouse::class,'id');
    }

    public function oficina()
    {
        return $this->belongsTo(BranchOffice::class,'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'id');
    }

    public function productos()
    {
        return $this->hasMany(InventoryShopping::class,'id');
    }
}
