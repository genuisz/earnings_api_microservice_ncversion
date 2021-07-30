<?php

namespace Database\Factories;

use App\Models\OrderTransact;
use App\Models\OrderTransactProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderTransactProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderTransactProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $feedback = null;
        $expected_ETD = null;
        $confirmDeliveryDate = null;
        $product = Product::factory()->create();
        $orderTransact = OrderTransact::factory()->create();
        $uniqueID = quickRandom(10);
        $orderQuantity = $this->faker->numberBetween(1,$product->quantity_of_log_inventory_limit);
        $orderProductStatus  =$this->faker->randomElement(1,2,3,4,5);
        $tracking = $this->faker->ean13();
        $weight = $product->log_weight * $orderQuantity;
        $logisticCost  = OrderTransactProduct::where('logistic_id',$orderTransact->logistic_id)->where('from_port_id',$product->from_port_id)->where('to_port_id',$orderTransact->to_port_id)->select(['courier_charger_unit'])->get()->toArray()['courier_charger_unit'] * $weight;
        if($orderProductStatus==4 || $orderProductStatus==5){
            $expected_ETD = $this->faker->dateTime('+30 days'); 
        }
        if($orderProductStatus==5){
            $confirmDeliveryDate = $this->faker->dateTime('+60 days');
        }
        $dueat = $this->faker->dateTime('+200 days');
        $downpayment  = $product->price * $product->downpayment_ratio * $orderQuantity;
        $deposit  = $product->price * $product->deposit_ratio * $orderQuantity;
        $subTotal  = $product->price * $orderQuantity;
        $total  = $subTotal +$logisticCost;
        if($orderProductStatus==5){
            $feedback = $this->faker->text(200);
        }
        




        return [
            //
            ''
        ];
    }
}
