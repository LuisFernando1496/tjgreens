<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Address;
class BranchOffice extends Model
{
    protected $fillable = [ 'name','address_id','status'];


    public function address(){
        return $this->belongsTo(Address::class);
    }
    public function add($data){
        return $this->create($data);
    }
    public function edit($data){
        return $this->fill($data)->save();
    }
    public function changeStatus($status)
    {
      return $this->fill(["status"=>$status])->save();
    }
    public function url(){
        return $this->id ? 'sucursales.update' : 'sucursales.store';
    }
 
    public function method(){
        return $this->id ? 'PUT' : 'POST';
    }
}
