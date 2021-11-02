<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [ 'name','status'];

     
    public function url(){
        return $this->id ? 'marcas.update' : 'marcas.store';
    }
 
    public function method(){
        return $this->id ? 'PUT' : 'POST';
    }
}
