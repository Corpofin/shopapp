<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $table = 'sales';

    protected $fillable = ['buyer_id', 'seller_id', 'product_id', 'price', 'quantity', 'total'];

	protected $dates = ['deleted_at'];


    public function buyer()
    {
    	return $this->belongsTo('App\Models\User', 'buyer_id', 'id');
    }

    public function seller()
    {
    	return $this->belongsTo('App\Models\User', 'seller_id', 'id');
    }

    public function product()
    {
    	return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
