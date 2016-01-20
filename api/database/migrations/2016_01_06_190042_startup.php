<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Startup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 20)->unique();
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->string('email')->unique();
            $table->string('phone', 30)->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seller_id')->unsigned();
            $table->string('title', 60);
            $table->string('subtitle', 120)->nullable();
            $table->text('description');
            $table->decimal('price', 7, 2)->unsigned();
            $table->smallInteger('stock_initial')->unsigned()->nullable();
            $table->smallInteger('stock_available')->unsigned()->nullable();
            $table->date('starts');
            $table->date('ends');
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('seller_id')->references('id')->on('users');
        });  

        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 40)->unique();
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buyer_id')->unsigned();
            $table->integer('seller_id')->unsigned();            
            $table->integer('product_id')->unsigned();
            $table->decimal('price', 7, 2)->unsigned();
            $table->integer('quantity')->unsigned()->nullable();
            $table->decimal('total')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('buyer_id')->references('id')->on('users');
            $table->foreign('seller_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::create('favourites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->timestamp('created_at');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Drop created tables

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $dropIfExists = [
            'users',
            'password_resets',
            'products',
            'sales',
            'favourites',
            'categories',
            'product_categories',
        ];
        array_walk($dropIfExists, ['Schema', 'dropIfExists']);
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
