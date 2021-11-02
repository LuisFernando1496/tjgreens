<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [ 'name','last_name','email','phonenumber','address','authorized_credit','user_id','status'];

    public function changeStatus($data){
        return $this->fill(["status"=>$data])->save();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
