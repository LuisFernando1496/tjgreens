<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartShopping extends Model
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
        return $this->belongsTo(Inventory::class,'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'id');
    }
}
