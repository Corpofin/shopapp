<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable as UserAuth;

class Ware extends Controller
{
    protected $pageLimit = 10;


    public function index(UserAuth $userAuth, $uid)
    {
        $seller = \App\Models\User::find($uid) ?: $this->notFoundJson();

        $query = $seller->wares()
            ->select(['id', 'title', 'subtitle', 'seller_id', 'price', 'stock_available', 'starts', 'ends', 'is_active', 'created_at', 'updated_at']);

        $count = $query->count();

        $items = $query
            ->forPage(request('page', 1), request('limit', $this->pageLimit))
            ->get();
        
        if($userAuth) {
            $products = collect();
            foreach($items as $item){
                $products[] = $item->product;
            }
            \App\Models\Product::setFavourites($products, $userAuth);
        }

        if($userAuth) \App\Models\Product::setFavourites($items, $userAuth);

        return [
            "count" => $count,
            "limit" => request('limit', $this->pageLimit),
            "page" => request()->input('page', 1),
            "items" => $items
        ];            
    }

    public function show(UserAuth $user, $uid, $pid)
    {
        $seller = \App\Models\User::find($uid) ?: $this->notFoundJson();

        return $seller->wares()->find($pid) ?: $this->notFoundJson();        
    }
}
