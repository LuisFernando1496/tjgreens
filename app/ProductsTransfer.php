<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsTransfer extends Model
{
    protected $fillable = ['transfer_id','product_id','quantity'];

    public function transfer(){
        return $this->belongsTo(Transfer::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
