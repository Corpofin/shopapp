<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['starts', 'ends', 'deleted_at'];

    protected $casts = [
        'price' => 'float',
        'stock_initial' => 'integer',
        'stock_available' => 'integer',
        'starts' => 'date_only', 
        'ends' => 'date_only',
        'is_active' => 'boolean',
    ];

    protected $appends = ['favourite'];

    protected $favourite;

    public function seller()
    {
    	return $this->belongsTo('App\Models\User', 'seller_id', 'id');
    }

    public function sells()
    {
    	return $this->hasMany('App\Models\Sale', 'product_id', 'id');
    }

    public function categories()
    {
    	return $this->belongsToMany('App\Models\Category', 'product_categories');
    }

    // mutators

    public function setFavouriteAttribute($value)
    {
        return $this->favourite = $value;
    }

    public function getFavouriteAttribute()
    {
        return $this->favourite;
    }  

    // methods:

    public function canSell($quantity = 1)
    {
        if(!$this->is_active) return false;
        if($this->deleted_at) return false;
        if((int)$quantity !== $quantity) return false;
        if($quantity <= 0) return false;
        if($quantity > $this->stock_available) return false;

        return true;
    }

    static function setFavourites(Collection $products, User $user)
    {
        $favourites = $user->favourites()
            ->whereIn('product_id' , $products->lists('id'))
            ->withPivot('created_at')
            ->get(['product_id as pid', 'favourites.created_at as added_fav']);

        foreach($favourites as $key => $fav){
            foreach($products as $item){
                if($item->id == $fav->pid) {
                    $item->favourite = $fav->added_fav;
                }
            }
        }
    }
}
