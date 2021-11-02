<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = ['status','provincial_branch_office_id','destination_branch_office_id','user_id','details'];

    public function branchOffice(){
        return $this->belongsTo(BranchOffice::class,'destination_branch_office_id');
    }
    public function origin()
    {
        return $this->belongsTo(BranchOffice::class,'provincial_branch_office_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function products(){
        return $this->hasMany(SendProduct::class);
    }
}
