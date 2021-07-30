<?php

namespace Database\Factories;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Cart;
class CartItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = Product::factory()->create();
        $cart = Cart::factory()->create();
        return [
            //
            'product_id'=>$product->id,
            'quantity_of_log'=>$this->faker->numberBetween(1,$product->quantity_of_log_inventory_limit),
            'cart_id'=>$cart->id
        ];
    }
}
