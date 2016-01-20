<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable as UserAuth;

class Sale extends Controller
{
	protected $pageLimit = 10;

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('auth.userOwner');
    }

    public function index(UserAuth $userAuth, $uid)
    {
        $seller = \App\Models\User::find($uid) ?: $this->notFoundJson();

    	$query = $seller->sells()
	        ->with([
	        	'product' => function($q) {$q->select('id', 'title');}, 
	        	'seller' => function($q) {$q->select('id', 'username');}, 
	        	'buyer' => function($q) {$q->select('id', 'username');}, 
	        ]);

        $count = $query->count();

        $items = $query->forPage(request('page', 1), request('limit', $this->pageLimit))->get();

        if($userAuth) {
            $products = collect();
            foreach($items as $item){
                $products[] = $item->product;
            }
            \App\Models\Product::setFavourites($products, $userAuth);
        }

        return [
            "count" => $count,
            "limit" => request('limit', $this->pageLimit),
            "page" => request()->input('page', 1),
            "items" => $items,
        ];
    }

    public function show(UserAuth $user, $uid, $sid)
    {
        return $user->sells()
	        ->with([
	        	'product' => function($q) {$q->select('id', 'title');}, 
	        	'seller' => function($q) {$q->select('id', 'username');}, 
	        	'buyer' => function($q) {$q->select('id', 'username');}, 
	        ])
	        ->find($sid) ?: $this->notFoundJson();
    }
}