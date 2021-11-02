<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [ 'sale_id','deposit','leftover'];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }
}
