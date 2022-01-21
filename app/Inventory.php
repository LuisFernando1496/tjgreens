<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'bar_code',
        'name',
        'cost',
        'price',
        'stock',
        'brand_id',
        'category_id',
        'warehouse_id'
    ];

    public function marca()
    {
        return $this->belongsTo(Brand::class,'id');
    }

    public function categoria()
    {
        return $this->belongsTo(Category::class,'id');
    }

    public function almacen()
    {
        return $this->belongsTo(Warehouse::class,'id');
    }

    public function carritos()
    {
        return $this->hasMany(Cart::class,'id','inventory_id');
    }
}
