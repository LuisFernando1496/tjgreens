<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductInSale extends Model
{
    protected $fillable = [ 'product_id','sale_id','sale_price','discount','quantity','subtotal','total','total_cost'];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
