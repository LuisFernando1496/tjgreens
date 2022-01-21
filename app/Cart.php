<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'inventory_id',
        'user_id',
        'quantity',
        'total',
        'discount',
        'status'
    ];

    public function inventario()
    {
        return $this->hasMany(Inventory::class,'id','inventory_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'id');
    }
}
