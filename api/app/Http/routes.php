		<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Task;
use Illuminate\Http\Request;

Route::group(['prefix' => env('API')], function () {

	Route::controller('mockup', 'Mockup');

	// auth

	Route::controller('auth', 'Auth\Auth');

	// user

	make_resource('user', 'User', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);

	make_resource('user.sale', 'User\Sale', ['only' => ['index' , 'show']]);

	make_resource('user.ware', 'User\Ware', ['only' => ['index', 'store', 'show', 'destroy']]);

	make_resource('user.buy', 'User\Buy', ['only' => ['index', 'store', 'show']]);

	make_resource('user.favourite', 'User\Favourite', ['only' => ['index', 'store', 'destroy']]);

	// product

	make_resource('product', 'Product', ['only' => ['index' , 'store', 'show', 'update', 'destroy']]);

	make_resource('product.category', 'Product\Category', ['only' => ['index', 'store', 'destroy']]);

});