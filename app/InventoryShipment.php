<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryShipment extends Model
{
    protected $fillable = [
        'inventory_id',
        'shipment_id',
        'quantity',
        'total',
        'discount'
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventory::class,'id');
    }

    public function traspaso()
    {
        return $this->belongsTo(Shipment::class,'id');
    }
}
