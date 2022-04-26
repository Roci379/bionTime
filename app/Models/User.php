<?php

namespace App\Models;

/**
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


 * Class User
 * @package App\Models
 * @mixin Builder

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     *
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     *
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     *
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
} */



use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {

    use HasFactory, Notifiable;


    protected $table        = 'users';
    protected $primaryKey   = 'id';
    protected $fillable     = array("email", "first_name", "last_name", "profile_image");


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    'password',
    'remember_token',
    ];

    public function holidays(){
        return $this->hasMany(Holiday::class, 'user_id', 'id');
    }

    public function records(){
        return $this->hasMany(Record::class, 'user_id', 'id');
    }

    public function functionsOfUser(){
        return $this->hasMany(FunctionOfUser::class, 'user_id', 'id');
    }

    
}


