<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable as UserAuth;

class Buy extends Controller
{
    protected $pageLimit = 10;

    public function __construct()
    {
        $this->middleware('auth', ['only' => ['store', 'destroy']]);
        $this->middleware('auth.userOwner', ['only' => ['store', 'destroy']]);
    }

    public function index(UserAuth $user, $uid)
    {
    	$query = \App\Models\Sale::query()
	        ->with([
	        	'product' => function($q) {$q->select('id', 'title');}, 
	        	'seller' => function($q) {$q->select('id', 'username');},
	        ])
	        ->where('buyer_id' , '=', $uid);

        $count = $query->count();

        $items = $query->forPage(request('page', 1), request('limit', $this->pageLimit))->get();

        //if($user) \App\Models\Product::setFavourites($items->keyBy('product.id'), $user);

        return [
            "count" => $count,
            "limit" => request('limit', $this->pageLimit),
            "page" => request()->input('page', 1),
            "items" => $items,
        ];
    }

    public function store($uid)
    {
        $this->validateJson(request()->all(), $rules = [
            'pid' => 'required|integer|exists:products,id,deleted_at,NULL,is_active,1',
            'quantity' => 'required|integer',
        ]);

    	$product = \App\Models\Product::find(request('pid'));

    	if($uid == $product->seller_id)
    		$this->errorValidateJson(['pid' => 'seller of a product can\'t buy his products']);

    	if(!$product->canSell($quantity = (int)request('quantity')))
    		$this->errorValidateJson(['quantity' => 'the productc can\'t sell the quantity or is disabled']);

    	$sale = \App\Models\Sale::create([
    		'buyer_id' => $uid,
    		'seller_id' => $product->seller_id,
    		'product_id' => $product->id,
    		'quantity' => $quantity,
    		'price' => $product->price,
    		'total' => $quantity * $product->price,
    	]);

    	$product->stock_available -= $quantity;
    	$product->update();

    	$sale->product = $product;
    	$sale->load('seller');

    	return $sale;
    }

    public function show($uid, $pid)
    {
     	return \App\Models\Sale::query()
	        ->with([
	        	'product' => function($q) {$q->select('id', 'title');}, 
	        	'seller' => function($q) {$q->select('id', 'username');},
	        ])
	        ->where('buyer_id' , '=', $uid)
    		->find($pid);       
    }
}