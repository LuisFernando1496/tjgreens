<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [ 'name','stock','cost','expiration','iva','product_key','unit_product_key','lot','ieps','price_1','price_2','price_3','bar_code','branch_office_id','category_id','brand_id','status'];
     
    public function branch_office(){
        return $this->belongsTo(BranchOffice::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function url(){
        return $this->id ? 'productos.update' : 'productos.store';
    }
 
    public function method(){
        return $this->id ? 'PUT' : 'POST';
    }

    public function add($data){
        return $this->create($data);
    }
    public function edit($data){
        return $this->fill($data)->save();
    }
    public function changeStatus(){
        return $this->fill(["status"=>false])->save();
    }
    public function del()
    {
        return $this->delete();
    }
    public function image()
    {
        return $this->hasOne(Image::class);
    }
}
