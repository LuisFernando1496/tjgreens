<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendProduct extends Model
{
    protected $table = "send_products";
    protected $fillable = [
        'product_id',
        'transfer_id',
        'quantity',
        'subtotal',
        'sale_price',
        'total',
      //  'cost',
       // 'total_cost',
        'discount'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

   
    public function trasnfer()
    {
        return $this->belongsTo(Transfer::class,'transfer_id');
    }

}
