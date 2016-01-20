<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    protected $categories = ['cars', 'books', 'clothing', 'computers', 
        'electronics', 'jewelery', 'accessories', 'movies', 
        'music', 'shoes',  'arts'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        Model::unguard();

        //truncate tables: 

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $tablesToReset  = [
            'users',
            'password_resets',
            'products',
            'sales',
            'favourites',
            'categories',
            'product_categories',
        ];
        foreach($tablesToReset as $table) {
            DB::table($table)->truncate();
        }
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // categories:

        sort($this->categories);
        
        foreach($this->categories as $category)
        {
            DB::table('categories')->insert([
                'name' => $category,
            ]);
        }


        // users:

        $users = factory(App\Models\User::class, 8)->create();


        // products:

        $products = collect();
        for($i = 1; $i < 40; $i++) {

            $seller = $users->random();

            $product = factory(App\Models\Product::class)->create([
                'seller_id' => $seller->id,
            ]);
            $products->push($product);

            // product categories:

            $total = count($this->categories);
            $middleTop = ceil( $total / 2);
        
            $product->categories()->sync([rand(1, $middleTop - 1), rand($middleTop, $total)]);
        }


        // sales:

        $sales = collect();
        for($i = 1; $i < count($products) * 2; $i++) {

            $product = $products->random();
            $buyer = collect($users)->forget($product->seller_id)->random(); 

            $sale = \App\Models\Sale::create([
                'buyer_id' => $buyer->id,
                'seller_id' => $product->seller_id,
                'product_id' => $product->id,
                'price' => $price = $product->price,
                'quantity' => $quantity = 1,        
                'total' => $price * $quantity,
                'created_at' => ($created_at = $faker->dateTimeBetween($product->created_at, 'now')),
                'updated_at' => $created_at,                    
            ]);
            $sales->push($sale);
        }


        // favourites:

        $favourites = collect();
        foreach($users as $user) {

            $favourites = [];
            $productsSelected = $products->random(rand(3, 5));
            foreach($productsSelected as $product){
                $favourites[$product->id] = 
                    ['created_at' => $faker->dateTimeBetween($product->created_at, 'now')];
            }

            $user->favourites()->sync($favourites);
        }


        Model::reguard();
    }
}
