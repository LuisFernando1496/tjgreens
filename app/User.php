<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Rols;
use App\Address;
use App\BranchOffice;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','rol_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function add($data){
        return $this->create($data);
    }
    public function edit($data){
        return $this->fill($data)->save();
    }
    public function rol(){
        return $this->belongsTo(Rol::class);
    }
    public function branchOffice(){
        return $this->belongsTo(BranchOffice::class);
    }
    public function changeStatus($data){
        return $this->fill(["status"=>$data])->save();
    }
    public function address(){
        return $this->belongsTo(Address::class);
    }
    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }
}
