<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class CashClosing extends Model
{
    protected $guarded = [];

    public function add($data){
        return $this->create($data);
    }
    public function edit($data){
        return $this->fill($data)->save();
    }
    public function branch_office(){
        return $this->belongsTo(BranchOffice::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function box(){
        return $this->belongsTo(Box::class);
    }
    public function changeStatus($data){
        return $this->fill(["status"=>$data])->save();
    }
}
