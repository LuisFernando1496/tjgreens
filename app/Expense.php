<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = [];

    public function add($data){
        return $this->create($data);
    }
    public function edit($data){
        return $this->fill($data)->save();
    }
    public function cash_closing(){
        return $this->belongsTo(CashClosing::class);
    }
    public function branch_office(){
        return $this->belongsTo(BranchOffice::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function changeStatus($data){
        return $this->fill(["status"=>$data])->save();
    }
    public function del()
    {
        return $this->delete();
    }
    public function url(){
        return $this->id ? 'gastos.update' : 'gastos.store';
    }
 
    public function method(){
        return $this->id ? 'PUT' : 'POST';
    }
}
