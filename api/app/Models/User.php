<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    protected $table = 'users';

    protected $fillable = ['username', 'first_name', 'last_name', 'email', 'phone', 'password'];

    protected $hidden = ['password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['deleted_at'];

    //relations

    public function favourites()
    {
    	return $this->belongsToMany('App\Models\Product', 'favourites', 'user_id', 'product_id');
    }

    public function wares()
    {
    	return $this->hasMany('App\Models\Product', 'seller_id', 'id');
    }

    public function buys()
    {
    	return $this->hasMany('App\Models\Sale', 'buyer_id', 'id');
    }

    public function sells()
    {
    	return $this->hasMany('App\Models\Sale', 'seller_id', 'id');
    }

    //mutators

    public function setFirstNameAttribute($name)
    {
        $this->attributes['first_name'] = ucwords($name);
    }

    public function setLastNameAttribute($name)
    {
        $this->attributes['last_name'] = ucwords($name);
    }

    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = \Hash::make($pass);
    }

}
