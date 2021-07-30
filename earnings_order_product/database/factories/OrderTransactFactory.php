<?php

namespace Database\Factories;

use App\Models\Logistic;
use App\Models\LogisticPort;
use App\Models\LogisticPortLogistic;
use App\Models\OrderTransact;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderTransactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderTransact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $logistic = Logistic::factory()->create();
        $toPort = LogisticPort::factory()->create();
        return [
            //
            'id'=>quickRandom(10),
            'users_id'=>1,
            'sub_total'=>0,
            'logistic_cost'=>0,
            'downpayment'=>0,
            'deposit'=>0,
            'total_price'=>0,
            'logistic_id'=>$logistic->id,
            'to_port_id'=>$toPort->id,
            'order_status_id'=>$this->faker->randomElement([1,2,3,4,5]),
            'delivery_address'=>$this->faker->address,
            'invoice_at'=>$this->faker->dateTime('now')
        ];
    }

    // public function configure()
    // {
    //     $feedback = null;
    //     $expected_ETD = null;
    //     $confirmDeliveryDate = null;
    //     $product = Product::factory()->create();
    //     $orderTransact = OrderTransact::factory()->create();
    //     $uniqueID = quickRandom(10);
    //     $orderQuantity = $this->faker->random_int(1,$product->quantity_of_log_inventory_limit);
    //     $orderProductStatus  =$this->faker->randomElement(1,2,3,4,5);
    //     $tracking = $this->faker->ean13();
    //     $weight = $product->log_weight * $orderQuantity;
    //     $logisticCost  = LogisticPortLogistic::where('logistic_id',$orderTransact->logistic_id)->where('from_port_id',$product->from_port_id)->where('to_port_id',$orderTransact->to_port_id)->select(['courier_charger_unit'])->get()->toArray()['courier_charger_unit'] * $weight;
    //     if($orderProductStatus==4 || $orderProductStatus==5){
    //         $expected_ETD = $this->faker->dateTime('+30 days'); 
    //     }
    //     if($orderProductStatus==5){
    //         $confirmDeliveryDate = $this->faker->dateTime('+60 days');
    //     }
    //     $dueat = $this->faker->dateTime('+200 days');
    //     $downpayment  = $product->price * $product->downpayment_ratio * $orderQuantity;
    //     $deposit  = $product->price * $product->deposit_ratio * $orderQuantity;
    //     $subTotal  = $product->price * $orderQuantity;
    //     $total  = $subTotal +$logisticCost;
    //     if($orderProductStatus==5){
    //         $feedback = $this->faker->text(200);
    //     }

    //     $numOfProduct = $this->faker->random_int(1,10);


    //     $array = array();
    //     foreach($numOfProduct as $num){
    //         $product = Product::factory()->create();
    //         array_push($array,$product);
    //     }

    //     return $this->afterCreating(function(OrderTransact $order)use ($array,$orderQuantity,$feedback,$expected_ETD,$confirmDeliveryDate){
    //         foreach($array as $product){
    //             $order->product()->attach($product->id,[
    //                 'unique_order_product_id'=>quickRandom(10),
    //                 'order_quantity_in_log'=>$orderQuantity,
    //                 'order_product_status_id'=>
    //             ]);
    //         }
    //     });
    // }

}
