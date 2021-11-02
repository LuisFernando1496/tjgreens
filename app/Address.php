<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [ 'street', 'ext_number', 'int_number','suburb', 'postal_code','city', 'state', 'country' ];
    // protected $guarded = [];

    public function add($data){
        return $this->create($data);
    }
    public function edit($data){
        return $this->fill($data)->save();
    }
}
