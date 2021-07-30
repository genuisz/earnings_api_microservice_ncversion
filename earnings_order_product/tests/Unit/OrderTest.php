<?php

namespace Tests\Unit;

use App\Exceptions\ProductNumberNotEnoughException;
use App\Models\Logistic;
use App\Models\LogisticPort;
use App\Models\OrderStatus;
use App\Models\OrderTransact;
use App\Models\Product;
use App\Models\User;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use App\Repository\NonLocalRepository\UserRepository;
use App\Repository\OrderTransactRepository;
use App\Repository\ProductRepository;
use App\Service\OrderService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
class OrderTest extends TestCase
{
    use WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_create_order(){
        $orderId = quickRandom(10);
        $userId = '1';
        $logistic = Logistic::factory()->create();
        $toPort =  LogisticPort::factory()->create();
        $orderStatus = $this->faker->randomElement(array(1,2,3,4,5));
        $invoiceAt = $this->faker->dateTime('now');
        $data = [
            'order_transact_id'=>$orderId,
            'users_id'=> $userId,
            'logistic_id'=>$logistic->id,
            'to_port_id'=>$toPort->id,
            'order_status_id'=>$orderStatus,
            'invoice_at'=>$invoiceAt,
            'delivery_address'=>$this->faker->address,
            
        ];
        $orderRepo  =new OrderTransactRepository();
        $created = $orderRepo->createOrder($data);
        $this->assertEquals($data['order_transact_id'],$created->id);
        $this->assertEquals($data['users_id'],$created->users_id);
        $this->assertEquals($data['logistic_id'],$created->logistic_id);
        $this->assertEquals($data['to_port_id'],$created->to_port_id);
        $this->assertEquals($data['order_status_id'],$created->order_status_id);
        $this->assertEquals($data['invoice_at'],$created->invoice_at);
        $this->assertEquals($data['delivery_address'],$created->delivery_address);
    }

    /** @test */
    public function it_can_handle_cart_json_add_data(){
        $orderId = quickRandom(10);
        $userId = '1';
        $logistic = Logistic::factory()->create();
        $toPort =  LogisticPort::factory()->create();
        $orderStatus = $this->faker->randomElement(array(1,2,3,4,5));
        $invoiceAt = $this->faker->dateTime('now');
        $data = [
            'order_transact_id'=>$orderId,
            'users_id'=> $userId,
            'logistic_id'=>$logistic->id,
            'to_port_id'=>$toPort->id,
            'order_status_id'=>$orderStatus,
            'invoice_at'=>$invoiceAt,
            'delivery_address'=>$this->faker->address,
            
        ];
        $numbOfProduct = $this->faker->numberBetween(1,20);
        $array = array();
        for($i =0; $i<$numbOfProduct;$i++ ){
            $product = Product::factory()->create();
            array_push($array,array('product_id'=>$product->id,'quantity_of_log'=>$this->faker->numberBetween(1,20)));

        }
        $jsonArray = json_encode($array);
        $orderRepo = new OrderTransactRepository();
        $result = $orderRepo->handleCartJsonAddData($jsonArray,$data);
        foreach($result as $element){
            $this->assertEquals($element['to_port_id'],$toPort->id);
            $this->assertEquals($element['logistic_id'],$logistic->id);
        }


    }
    /** @test */
    public function it_can_create_order_and_proceed_calculateOrderFee(){
        $orderId = quickRandom(10);
        $userId = '1';
        $logistic = Logistic::factory()->create();
        $toPort =  LogisticPort::factory()->create();
        $orderStatus = $this->faker->randomElement(array(1,2,3,4,5));
        $invoiceAt = $this->faker->dateTime('now');
        $data = [
            'order_transact_id'=>$orderId,
            'users_id'=> $userId,
            'logistic_id'=>$logistic->id,
            'to_port_id'=>$toPort->id,
            'order_status_id'=>$orderStatus,
            'invoice_at'=>$invoiceAt,
            'delivery_address'=>$this->faker->address,
            
        ];
        $orderRepo  =new OrderTransactRepository();
        $created = $orderRepo->createOrder($data);

        // $numbOfProduct = $this->faker->numberBetween(1,20);
        $numbOfProduct = 2;
        $array = array();
        $totalFee = array();
        $totalFee['downpayment'] =0;
        $totalFee['deposit']=0;
        $totalFee['logistic_cost']=0;
        $totalFee['sub_total']=0;
        for($i =0; $i<$numbOfProduct;$i++ ){
            $product = Product::factory()->create();
            $num = $this->faker->numberBetween(1,20);
            $logisticPivot =[
                'logistic_id'=>$logistic->id,
                'from_port_id'=>$product->from_port_id,
                'to_port_id'=>$toPort->id,
                'courier_charges_unit'=>$this->faker->randomFloat(2,10,1000)
            ];
            $unitLogistic =  DB::table('logistic_port_logistic')->insert(
                $logisticPivot
                );

            $totalFee['downpayment'] += $product->downpayment_ratio /100 *$num *$product->price;
            $totalFee['deposit']+=$product->deposit_ratio /100 * $num *$product->price;
            $totalFee['logistic_cost'] +=$logisticPivot['courier_charges_unit'] * $product->log_weight * $num;
            $totalFee['sub_total'] += $product->price *$num;

            array_push($array,array('product_id'=>$product->id,'quantity_of_log'=>$num));

        }

        $jsonArray = json_encode($array);
        $res = $orderRepo->handleCartJsonAddData($jsonArray,$data);
        $result = $orderRepo->proceedCalcuateOrderFee($res);


        $this->assertEquals(round($totalFee['downpayment'],2),round($result->downpayment,2));
        $this->assertEquals(round($totalFee['deposit'],2),round($result->deposit,2));
        $this->assertEquals(round($totalFee['logistic_cost'],2),round($result->logistic_cost,2));
        $this->assertEquals(round($totalFee['sub_total'],2),round($result->sub_total,2));
        $this->assertEquals(round($totalFee['sub_total']+$totalFee['logistic_cost'],2),round($result->total_price,2));

    }
    /** @test */
    public function it_can_assoicate_product_to_order_and_calculate_each_order_product_fee(){   
        $order = OrderTransact::factory()->create();

        $numbOfProduct = $this->faker->numberBetween(1,20);
        $array = array();
        for($i =0; $i<$numbOfProduct;$i++ ){
            $product = Product::factory()->create();
            $logisticPivot =[
                'logistic_id'=>$order->logistic_id,
                'from_port_id'=>$product->from_port_id,
                'to_port_id'=>$order->to_port_id,
                'courier_charges_unit'=>$this->faker->randomFloat(2,10,1000)
            ];
            $unitLogistic =  DB::table('logistic_port_logistic')->insert(
                $logisticPivot
                );
            
            $num = $this->faker->numberBetween(1,20);
            array_push($array,array(
                'product_id'=>$product->id,
                'quantity_of_log'=>$num,
                'logistic_id'=>$order->logistic_id,
                'to_port_id'=>$order->to_port_id,
                'downpayment'=>$product->downpayment_ratio*$product->price *$num,
                'deposit'=>$product->deposit_ratio*$product->price *$num,
                'logistic_cost'=>$unitLogistic * $num *$product->log_weight,
                'total'=>$product->price *$num + $unitLogistic * $num *$product->log_weight,
                'sub_total'=>$product->price *$num
            ));

        }


        $orderRepo = new OrderTransactRepository();
        $orderRepo->setModel($order);
        $result = $orderRepo->proceedCalcuateOrderFee($array);
        $createdOrderProduct = $result->orderProduct;
        $orderProduct = $orderRepo->getOrderProduct($order);

        foreach($createdOrderProduct as $index=> $cProduct){
            $this->assertEquals($orderProduct[$index],$cProduct);
        }


    }
    /** @test */
    public function it_can_mass_deduce_the_quantity_of_log(){
        $order = OrderTransact::factory()->create();

        $numbOfProduct = $this->faker->numberBetween(1,20);
        $array = array();
        for($i =0; $i<$numbOfProduct;$i++ ){
            $product = Product::factory()->create();
            $logisticPivot =[
                'logistic_id'=>$order->logistic_id,
                'from_port_id'=>$product->from_port_id,
                'to_port_id'=>$order->to_port_id,
                'courier_charges_unit'=>$this->faker->randomFloat(2,10,1000)
            ];
            $unitLogistic =  DB::table('logistic_port_logistic')->insert(
                $logisticPivot
                );
            
            $num = $this->faker->numberBetween(1,20);
            array_push($array,array(
                'product_id'=>$product->id,
                'quantity_of_log'=>$num,
                'logistic_id'=>$order->logistic_id,
                'to_port_id'=>$order->to_port_id,
                'downpayment'=>$product->downpayment_ratio*$product->price *$num,
                'deposit'=>$product->deposit_ratio*$product->price *$num,
                'logistic_cost'=>$unitLogistic * $num *$product->log_weight,
                'total'=>$product->price *$num + $unitLogistic * $num *$product->log_weight,
                'sub_total'=>$product->price *$num,
                'inventory_quantity_of_log'=>$product->quantity_of_log
            ));

        }


        $orderRepo = new OrderTransactRepository();
        $orderRepo->setModel($order);
        $orderTransactModel = $orderRepo->proceedCalcuateOrderFee($array);

        $products = $orderTransactModel->product;

        foreach($products as $index =>$product){
            $this->assertEquals($product->quantity_of_log,$array[$index]['inventory_quantity_of_log']-$array[$index]['quantity_of_log']);
        }
        

    }

    /** @test */
    public function it_can_update_order_status_by_specific(){
        $order = OrderTransact::factory()->create();
        //$product = Product::factory()->create();
        $orderStatus = OrderStatus::factory()->create();
        $orderRepo = new OrderTransactRepository();
        $orderRepo->setModel($order);
        //$num = $this->faker->numberBetween(1,10);
        //$orderRepo->associateProduct($product,$num,1);
        $orderRepo->updateOrderTranactStatus($order->id,$orderStatus->id);
        $updatedOrder = $orderRepo->getOneById($order->id);
        $this->assertEquals($orderStatus->id,$updatedOrder->order_status_id);

    }
    /** @test */
    public function it_can_update_order_status_by_parent(){

        $parentOrderStatus = OrderStatus::factory()->create();
        $order = OrderTransact::factory()->create(['order_status_id'=>$parentOrderStatus->id]);
        $childOrderStatus = OrderStatus::factory()->create(['parent_id'=>$parentOrderStatus->id]);

        $orderRepo = new OrderTransactRepository();
        $orderRepo->setModel($order);
        $orderRepo->updateOrderTransactStatusToNextStep($order->id);

        $updateOrder = $orderRepo->getOneById($order->id);

        $this->assertEquals($childOrderStatus->id,$updateOrder->order_status_id);

    }

    /** @test */
    public function it_error_when_product_quantity_is_not_enough(){
        //dd(new Request(array('logistic_id'=>1,'to_port_id'=>1,'cart'=>1,'delivery_address'=>'test')));
        


        $this->expectException(ProductNumberNotEnoughException::class);
        $product = Product::factory()->create(['quantity_of_log'=>1]);
        $logistic = Logistic::factory()->create();
        $toPort = LogisticPort::factory()->create();
        $orderStatus = OrderStatus::factory()->create();

        $logisticPivot =[
            'logistic_id'=>$logistic->id,
            'from_port_id'=>$product->from_port_id,
            'to_port_id'=>$toPort->id,
            'courier_charges_unit'=>$this->faker->randomFloat(2,10,1000)
        ];
        $unitLogistic =  DB::table('logistic_port_logistic')->insert(
            $logisticPivot
            );
        $array = array(array('quantity_of_log'=>2,'product_id'=>$product->id));
        $json = json_encode($array);
        $orderRepo = new OrderTransactRepository();
        $requestdata = array('logistic_id'=>$logistic->id,'to_port_id'=>$toPort->id,'cart'=>$json,'delivery_address'=>'test','users_id'=>1,'order_status_id'=>$orderStatus->id,'invoice_at'=>Carbon::now()->toDateTimeString(),'order_transact_id'=>quickRandom(10));
        $orderRepo->createOrder($requestdata);
        $res = $orderRepo->handleCartJsonAddData($json,$requestdata);
        $result = $orderRepo->proceedCalcuateOrderFee($res);
        


    }



    public function it_can_create_an_order_with_single_product(){
        
        $orderId = quickRandom(10);
        $userId = '1';
        $logistic = Logistic::factory()->create();
        $toPort =  LogisticPort::factory()->create();
        $orderStatus = $this->faker->numberBetween(1,5);
        // $deliveryAddress=  $this->faker->text(100);
        $invoiceAt = $this->faker->dataTime('now');
        $product = Product::factory()->create();
        $numOfLog = $this->faker->numberBetween(1,$product->quantity_of_log_total);
        $logisticPivot =[
            'logistic_id'=>$logistic->id,
            'from_port_id'=>$product->from_port_id,
            'to_port_id'=>$toPort->id,
            'courier_charges_unit'=>$this->faker->randomFloat(2,10,1000)
        ];
        $unitLogistic =  DB::table('logistic_port_logistic')->insert(
            $logisticPivot
            );

        $cartJson = '[{"product_id"=>'.$product->id.',"quantity_of_log"=>'.$numOfLog.'}]';
        $downpayment = $product->downpayment_ratio * $numOfLog * $product->price;
        $deposit = $product->deposit_ratio * $numOfLog * $product->price;
        $logisticCost = $logisticPivot['courier_charger_unit'] * $numOfLog *
        $data = [
            'order_transact_id'=>$orderId,
            'users_id'=> $userId,
            'logistic_id'=>$logistic->id,
            'to_port_id'=>$toPort->id,
            'order_status_id'=>$orderStatus,
            'invoice_at'=>$invoiceAt,
        ];
        $orderTransactRepo = new OrderTransactRepository();
        $created = $orderTransactRepo->createOrder($data,$cartJson);

        $this->assertEquals($data['order_transact_id'],$created->id);
        $this->assertEquals($data['users_id'],$created->users_id);
        $this->assertEquals($data['logistic_id'],$created->logistic_id);
        $this->assertEquals($data['to_port_id'],$created->to_port_id);
        $this->assertEquals($data['order_status_id'],$created->order_status_id);
        $this->assertEquals($data['invoice_at'],$invoiceAt);
        $this->assertEquals($downpayment,$created->downpayment);
        $this->assertEquals($deposit,$created->deposit);

        
        


    }
}
