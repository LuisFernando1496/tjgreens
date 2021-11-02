<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [ 'name','status'];

     
    public function url(){
        return $this->id ? 'categorias.update' : 'categorias.store';
    }
 
    public function method(){
        return $this->id ? 'PUT' : 'POST';
    }
}
