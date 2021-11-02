<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\BranchOffice;

class Box extends Model
{
        protected $guarded = [];

        public function add($data){
            return $this->create($data);
        }
        public function edit($data){
            return $this->fill($data)->save();
        }
        public function BranchOffice(){
            return $this->belongsTo(BranchOffice::class);
        }
}
