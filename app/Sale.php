<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [ 'payment_type','status','status_credit','cart_subtotal','cart_total','turned','ingress','folio_branch_office','shopping_cart_id','branch_office_id','user_id','total_cost','amount_discount','discount','client_id'];
    
    public function url(){
        return $this->id ? 'ventas.update' : 'ventas.store';
    }
 
    public function method(){
        return $this->id ? 'PUT' : 'POST';
    }

    public function changeStatus($data){
        return $this->fill(["status"=>$data])->save();
    }

    public function productsInSale(){
        return $this->hasMany(ProductInSale::class);
    }

    public function shoppingCart(){
        return $this->belongsTo(ShoppingCart::class);
    }

    public function branchOffice(){
        return $this->belongsTo(BranchOffice::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
