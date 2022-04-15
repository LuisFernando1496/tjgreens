<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'warehouse_id',
        'office_id',
        'subtotal',
        'discount',
        'total',
        'type',
        'status',
        'user_id',
        'by',
    ];

    public function almacen()
    {
        return $this->belongsTo(Warehouse::class,'id');
    }

    public function oficina()
    {
        return $this->belongsTo(BranchOffice::class,'office_id','id');
    }

    public function usuario()
    {
        return $this->hasMany(User::class,'id','user_id');
    }

    public function productos()
    {
        return $this->hasMany(InventoryShipment::class);
    }
}
