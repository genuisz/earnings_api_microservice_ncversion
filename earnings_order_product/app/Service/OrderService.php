<?php 
namespace App\Service;

use App\Event\OrderCreatedEvent;
use App\Exceptions\CreateOrUpdateCartException;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\InputDataErrorException;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Jobs\test;
use App\Listeners\SendOrderConfrimationEmail;
use App\Mailable\SendOrderToCustomerMailable;
use App\Models\OrderTransact;
use App\Models\OrderTransactProduct;
use App\Models\Product;
use App\Repository\Interfaces\CartItemRepositoryInterface;
use App\Repository\Interfaces\CartRepositoryInterface;
use App\Repository\Interfaces\OrderTransactProductRepositoryInterface;
use App\Repository\Interfaces\OrderTransactRepositoryInterface;
use App\Repository\Interfaces\ProductRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Traits\OrderProductTransformable;
use App\Traits\OrderTransformable;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use JsonException;
use Symfony\Component\HttpFoundation\Exception\JsonException as ExceptionJsonException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use App\Models\CartItem;
use Exception;
use App\Http\Requests\UpdateCartItemRequest;
use App\Exceptions\CartItemEmptyErrorException;
use App\Http\Requests\GetCartRequest;
use App\Http\Requests\GetOrderProductDetailsByUserRequest;
use App\Http\Requests\GetOrderProductSnippetRequest;
use App\Http\Requests\GetOrderRelatedProductRequest;
use App\Exceptions\PermissionNotEnoughException;
use App\Http\Requests\ProceedOrderHandleRequest;
use Illuminate\Support\Arr;

class OrderService {
    use OrderProductTransformable;
    protected $orderTransactRepository;
    protected $productRepository;
    protected $cartItemRepo;
    protected $cartRepo;
    protected $orderProductRepo;

    public function __construct(OrderTransactRepositoryInterface $orderTransactRepository,ProductRepositoryInterface $productRepository,UserRepositoryInterface $userRepository,CartItemRepositoryInterface $cartItemRepo,CartRepositoryInterface $cartRepo,OrderTransactProductRepositoryInterface $orderProductRepo)
    {
        $this->orderTransactRepository = $orderTransactRepository;
        $this->productRepository = $productRepository;
        $this->cartItemRepo = $cartItemRepo;
        $this->cartRepo = $cartRepo;
        $this->orderProductRepo = $orderProductRepo;
    }


    public function proceedOrderHandleCartJson($cartJson,$logisticId,$toPortId,$productColumn){
        $total_fee['downpayment_total'] =0;
        $total_fee['deposit_total']=0;
        $total_fee['logistic_cost']=0;
        $total_fee['sub_total']=0;
        $total_fee['total'] =0;
                
        $productIdList = collect($cartJson)->map(function ($item) {
            return $item['product_id'];
        });

        $productList = $this->productRepository->getById($productIdList, null);
        $detailUnitPriceOfEachProductList = $productList->load([
            'fromLogisticPort.logisticPortTo'=>function ($q) use($toPortId,$logisticId) {
                $q->where('id',$toPortId)->wherePivot('logistic_id',$logisticId);

            }
        ])->toArray();
        $productKeyArray = $productList->map(function(Product $product)use ($productColumn){
            return $product->only($productColumn);
        });

        $productKeyArray = array_column($productKeyArray->toArray(),null,'id'); 
        
        try{
            foreach ($cartJson as $index =>$item) {
                //$unitLogisticCost = (float)$this->orderTransactRepository->model->toLogisticPort->logisticPortFrom()->find($product['from_port_id'])->logisticFrom()->wherePivot('logistic_id',$item['logistic_id'])->wherePivot('to_port_id',$item['to_port_id'])->first()->toArray()['pivot']['courier_charges_unit'];
                    $unitLogisticCost = (float)$detailUnitPriceOfEachProductList[$index]['from_logistic_port']['logistic_port_to'][0]['pivot']['courier_charges_unit'];
                    //$total_fee = $this->orderTransactRepository->calculateOrderFee($item,$productList->toArray()[$index] , $total_fee,$unitLogisticCost);  
                    
                    $orderFeeArray = $this->orderTransactRepository->calculateOrderEachProductFee($item['quantity_of_log'],$productList->toArray()[$index],$unitLogisticCost);
                    $total_fee = $this->orderTransactRepository->calculateOrderFee($total_fee,$orderFeeArray);
                    $productKeyArray[$item['product_id']]['order_product']['downpayment'] = number_format($orderFeeArray['downpayment_total'],2);
                    $productKeyArray[$item['product_id']]['order_product']['deposit'] = number_format($orderFeeArray['deposit_total'],2);
                    $productKeyArray[$item['product_id']]['order_product']['logistic_cost'] = number_format($orderFeeArray['logistic_cost'],2);
                    $productKeyArray[$item['product_id']]['order_product']['sub_total'] = number_format($orderFeeArray['sub_total'],2);
                    $productKeyArray[$item['product_id']]['order_product']['total'] = number_format($orderFeeArray['total'],2);
                    $productKeyArray[$item['product_id']]['order_product']['quantity_of_log'] = $item['quantity_of_log'];
                    //dump($total_fee);
            }
        }

        catch(ErrorException $e){
            throw new InputDataErrorException($e->getTraceAsString());
        }

        return [
            'status'=>'Y',
            'product'=>array_column($productKeyArray,null,null),
            'gurantee_fee'=>number_format($total_fee['downpayment_total'],2)
        ];


    }

    public function proceedOrderDataHandle(ProceedOrderHandleRequest $request){

        $cartJson = [];
        if (empty($request['order_transact_id'])) {
            if (empty($request['cart'])) {
                $cart = $this->cartRepo->findRecentCart(auth('jwt')->user()->id);
                $cartItems = $this->cartRepo->getCorrespondingCartItems($cart);
                
                foreach ($cartItems->toArray() as $item) {
                    $element = array('product_id'=>$item['product_id'],'quantity_of_log'=>$item['quantity_of_log']);
                    $cartJson[] = $element;
                }
            }
            else if(!empty($request['cart'])){
                try {
                    $cartJson = json_decode($request['cart'], true);
                } catch (ExceptionJsonException $e) {
                    throw new JsonException();
                }
            }


            //dd($cartJson);

            if (!empty($cartJson)) {
                $result = $this->proceedOrderHandleCartJson(
                    $cartJson,
                    $request['logistic_id'],
                    $request['to_port_id'],
                    ['id','name_'.$request->header('Accept-Language','en'),'quantity_of_log','quantity_reach_target_in_log','duedate','image_url']
                );

                return response()->json($result);



            }
        }
        else{      
            $product  = $this->productRepository->getById(explode(',',$request['product_id']),['id','name_'.$request->header('Accept-Language','en'),'image_url','duedate','price','quantity_per_log','quantity_unit_id']); 
            $language = $request->header('Accept-Language','en');
            $orderTransactProductDetails = $this->productRepository->productLoadUnitOrderProductStatus(
                $product,
                $request['order_transact_id'],
                ['id','name_'.$language],
                ['*'],
                ['id','name_'.$language]
            );    
            $total_fee ['deposit'] = 0;
            $total_fee ['logistic_cost']=0;
            $total_fee ['total']=0;
            $total_fee['pre_total']=0;
            $total_fee['minus_deposit']=0;
            $total_fee['minus_logistic_cost']=0;
            $total_fee['minus_downpayment']=0;
            if($orderTransactProductDetails instanceof Collection)
            foreach($orderTransactProductDetails as $product){
                $data = $product->toArray()['order_product'][0];
                switch($data['order_product_status_id']){
                    case '2':
                        $total_fee['deposit'] += $data['deposit'];
                        $total_fee['logistic_cost'] += $data['logistic_cost'];
                        $total_fee['total'] += $data['deposit']+$data['logistic_cost'];
                        break;

                    case '3':
                        $total_fee['pre_total'] += $data['total'];
                        $total_fee['total'] += $data['total'] - $data['downpayment']-$data['logistic_cost']-$data['deposit'];
                        $total_fee['minus_deposit'] += $data['deposit'];
                        $total_fee['minus_logistic_cost']+= $data['logistic_cost'];
                        $total_fee['minus_downpayment']+= $data['downpayment'];                       
                }

            }
            $resultJson= [
                'status'=>'Y',
                'product'=>$orderTransactProductDetails->toArray()
            ];
            $resultJson = $this->determinePushJson($total_fee,$resultJson);
            return response()->json($resultJson);
            


        }
    }

    public function determinePushJson($data,$array){
        foreach($data as $key=>$value){
            ($value!=0)? $array[$key]=$value: '' ;
        }
        return $array;
        
    }


    

    public function createOrder(CreateOrderRequest $request){
        $language = $request->header('Accept-Language','en');
        $orderId  =quickRandom(10);
        $userId = auth('jwt')->user()->id;
        $orderStatusID = 1;
        $invoiceAt = Carbon::now()->toDateTimeString();
        $requestData = $request->all();
        $requestData['order_transact_id'] = $orderId;
        $requestData['users_id'] = $userId;
        $requestData['order_status_id'] = $orderStatusID;
        $requestData['invoice_at']=$invoiceAt;


        if (empty($request['cart'])) {
            try{
                $cart = $this->cartRepo->findRecentCart(auth('jwt')->user()->id);
                $cartItems = $this->cartRepo->getCorrespondingCartItems($cart);
            }
            catch(DataNotFoundException $e){
                throw  new CartItemEmptyErrorException($e->getTraceAsString());
            }

            $cartJson = [];
            foreach ($cartItems->toArray() as $item) {
                $element = array('product_id'=>$item['product_id'],'quantity_of_log'=>$item['quantity_of_log']);
                $cartJson[] = $element;
            }
            if (!empty($cartJson)) {
                $order = $this->orderTransactRepository->createOrder($requestData, $cartJson);
                $res = $this->orderTransactRepository->handleCartJsonAddData($cartJson, $requestData);
                $result = $this->orderTransactRepository->proceedCalcuateOrderFee($res);
            } else {
                throw new CreateOrUpdateCartException("");
            }
        } else {
            $order= $this->orderTransactRepository->createOrder($requestData);
            $res = $this->orderTransactRepository->handleCartJsonAddData($request['cart'], $requestData);
            $result = $this->orderTransactRepository->proceedCalcuateOrderFee($res);
        }


        $carts = $this->cartRepo->findAllCart(auth('jwt')->user()->id);
        $this->cartItemRepo->removeCartItems($carts);
        $this->cartRepo->removeCarts(auth('jwt')->user()->id);

        /**
         * Proceed to Payment After Payment Sucess , Event should be fired
         */
        event(new OrderCreatedEvent($this->orderTransactRepository->loadOrderTransactWithProductDetailsByOrder(
            $order,
            ['id','name_'.$language],
            ['id','name_'.$language,'slug'],
            ['id','name_'.$language,'description','product_no','quantity_unit_id','quantity_per_log','price','image_url'],
            ['id','name_'.$language],
            ['id','name_'.$language,'slug'],
            ['product_id','order_transact_id','unique_order_product_id','order_quantity_in_log','order_product_status_id','weight','logistic_cost'],
            ['id','sub_total','logistic_cost','downpayment','deposit','total_price','logistic_id','from_port_id'],
            ['id','name_'.$language]
        )->toArray()));
        return $result;
    }


    public function updateOrderTransact($array,bool $massUpdate){
        if($massUpdate){
            $this->orderTransactRepository->updateOrderTransact($array,true);
        }
        else{
            DB::BeginTransaction();
            try{
                $this->orderTransactRepository->updateOrderTransact($array,false);
            
                $order = $this->orderTransactRepository->getOrder($array['id']);
                $this->reCalculateOrderAttributes($order);
            }
            catch(Exception $e){
                DB::rollBack();
                throw new InputDataErrorException($e->getTraceAsString());
            }
            DB::commit();

        }

    }

    public function updateOrderProduct($array,bool $massUpdate){
        if($massUpdate){
            $json = json_decode($array,true);
            $this->orderProductRepo->massUpdateDataOfOrderProduct($json);
            /**
             * mass Update for orderProduct can not reCalculate all attribute of each orderProduct
             * mass Update only allow for update the orderProduct Status/ ETA/ETD which doesnt require to 
             * calculate all the cost
             */

            /**
             * TODO  Send the Email for each order status updated 
             */

        }
        else{
            DB::BeginTransaction();
            try{
                $this->orderTransactRepository->updateOrderProduct($array);
                $this->reCalculateOrderAttributes($this->orderTransactRepository->getOrder($array['order_id']));
            }
            catch(Exception $e){
                DB::rollback();
                throw new InputDataErrorException($e->getTraceAsString());

            }
            DB::commit();

        }
    }



    public function reCalculateOrderAttributes($order){
        /**
         * update logistic fee after update the order transact attributes
         */
        $total_fee['downpayment_total'] =0;
        $total_fee['deposit_total']=0;
        $total_fee['logistic_cost']=0;
        $total_fee['sub_total']=0;
        $total_fee['total'] =0;

        $product = $this->orderTransactRepository->getProduct($order);
        $items = $this->orderTransactRepository->getOrderProduct($order);
        //dd($items);

        //dd($items);
        $detailUnitPriceOfEachProductList = $this->orderTransactRepository->loadUnitLogisticCost($product,$order->to_port_id,$order->logistic_id);
        
        try{
            foreach ($items as $index =>$item) {
                $productItem = $product[$index];
                //$unitLogisticCost = (float)$this->model->toLogisticPort->logisticPortFrom()->find($product['from_port_id'])->logisticFrom()->wherePivot('logistic_id',$item['logistic_id'])->wherePivot('to_port_id',$item['to_port_id'])->first()->toArray()['pivot']['courier_charges_unit'];
                $unitLogisticCost = (float)$detailUnitPriceOfEachProductList[$index]['from_logistic_port']['logistic_port_to'][0]['pivot']['courier_charges_unit'];

                $orderFeeArray = $this->orderTransactRepository->calculateOrderEachProductFee($item['order_quantity_in_log'], $productItem, $unitLogisticCost);
                
                $total_fee = $this->orderTransactRepository->calculateOrderFee($total_fee, $orderFeeArray);
            }
        }
        catch(Exception $e){
            throw new InputDataErrorException($e->getTraceAsString());
        }

        $order->downpayment = $total_fee['downpayment_total'];
        $order->deposit = $total_fee['deposit_total'];
        $order->total_price  = $total_fee['total'];
        $order->logistic_cost = $total_fee['logistic_cost'];
        $order->sub_total = $total_fee['sub_total'];
        $order->save();



    }

    // public function massUpdateOrderProductStatusOrETA(Request $request){
    //     if($request->has('order_product_json')){
    //         $json = json_decode($request['order_product_json'],true);
    //         if(jsonParaChecker($json,array("tracking_no",'product_id','order_transact_id','order_product_status_id','excepted_ETD','due_at','confirmed_delivery_date'),true)){
    //             //dd('pass json checker');
    //             $this->orderProductRepo->updateDataOfOrderProduct($json,true);
    //         }
    //     }
    // }

    // public function massUpdateOrderStatus(UpdateOrderRequest $request){
    //     if($request->has('order_json')){
    //         $json = json_decode($request['order_json'],true);

    //         if(jsonParaChecker($json,array('id','order_status_id'),false)){
    //             //dd('pass checker');
    //             $this->orderTransactRepository->updateOrderTransact($json,true);
    //         }
    //     }
    // }


    
    
    public function deleteOrder(Request $request){
        return $this->orderTransactRepository->deleteOrder($request->only('id'));
    }

    
        
    


    public function test(Request $request){
        //return $this->orderTransactRepository->getOrderTransactWithProductDetailsByUser("1");
        //return $this->orderTransactRepository->getOrderTransactByUser("1", ['id','name_en'],['id','name_en'])->get();
        //eturn $this->orderTransactRepository->getProductDetailsByUser(1,"0");
        //return $this->orderTransactRepository->getProductDetailByOrderId("0");
        $this->orderTransactRepository->updateOrderTransactStatusToNextStep('NUub4OQG7J');
    }

    public function indexOrderTransact(Request $request){
            $users = $request['user_id'];
  
        
        return $this->orderTransactRepository->getOrderTransact($users,
        ['id','name_'.$request->header('Accept-Language','en')],
        ['id','name_'.$request->header('Accept-Language','en')],
        ['id','name_'.$request->header('Accept-Language','en')],
        ['*'],
        $request['order'],$request['sort'],$request['startDate'],$request['endDate'],$request['offset'],$request['limit']
        )->get();
    }



    public function getOrderTransactBase(Request $request){
        $user = auth('jwt')->user()->id;
        return $this->orderTransactRepository->getOrderTransact($user,
        ['id','name_'.$request->header('Accept-Language','en')],
        ['id','name_'.$request->header('Accept-Language','en')],
        ['id','name_'.$request->header('Accept-Language','en')],
        ['id','downpayment','deposit','sub_total','logistic_cost','total_price','to_port_id','order_status_id','invoice_at','logistic_id','delivery_address'],
        $request['order'],$request['sort'],$request['startDate'],$request['endDate'],$request['offset'],$request['limit']
        )->get();
    }

    public function getOrderTransactSnippet(GetOrderProductSnippetRequest $request){
        $user = auth('jwt')->user()->id;
        $language = $request->header('Accept-Language','en');
        return $this->orderTransactRepository->getOrderTransactWithProductDetails(
            $user,
            null,
            null,
            ['id','name_'.$language],
            ['id','name_'.$language,'image_url'],
            null,
            ['id','name_'.$language],
            ['product_id','order_transact_id','unique_order_product_id','order_quantity_in_log','order_product_status_id'],
            ['id','total_price','order_status_id'],
            null,
            $request['order'],
            $request['sort'],
            $request['startDate'],
            $request['endDate'],
            $request['offset'],
            $request['limit']
        )->get();
    }

    public function indexOrderTransactDetail(Request $request){

            $users = $request['user_id'];
            $orders = $request['order_transact_id'];
    
        $language = $request->header('Accept-Language','en');
        $orderDetails= $this->orderTransactRepository->getOrderTransactWithProductDetails(
        $users,
        $orders,
        ['id','name_'.$language],
        ['id','name_'.$language],
        ['*',DB::raw('CAST( (quantity_of_log_inventory_limit-quantity_of_log)/quantity_of_log_total *100 as DECIMAL(10,2)) as reach_rate')],
        ['id','name_'.$language],
        ['id','name_'.$language],
        ['*'],
        ['*'],
        ['id','name_'.$language]
        )->get();

        return $orderDetails;
    }

    public function getOrderTransactDetailsByUser(GetOrderProductDetailsByUserRequest $request){
        $user = auth('jwt')->user()->id;
        $language = $request->header('Accept-Language','en');
        $orderDetails= $this->orderTransactRepository->getOrderTransactWithProductDetails(
        $user,
        null,
        ['id','name_'.$language],
        ['id','name_'.$language],
        ['id','product_no',DB::raw('CAST((quantity_of_log_inventory_limit-quantity_of_log)/quantity_of_log_total *100 as DECIMAL(10,2)) as reach_rate'),'name_'.$language,'quantity_per_log','quantity_unit_id','image_url','from_port_id'],
        ['id','name_'.$language],
        ['id','name_'.$language],
        ['product_id','order_transact_id','unique_order_product_id','order_product_status_id','tracking_no','weight','logistic_cost','confirmed_delivery_date','expected_ETD','downpayment','due_at','deposit','total','feedback'],
        ['*'],
        ['id','name_'.$language],
        $request['order'],
        $request['sort'],
        $request['startDate'],
        $request['endDate'],
        $request['offset'],
        $request['limit']
        )->get();

        return $orderDetails;
        
    }

    

    public function getProductDetailWithOrder(GetOrderRelatedProductRequest $request){
        $user = auth('jwt')->user()->id;
        $products =  $this->orderTransactRepository->getProductDetailByOrderId(['id','name_'.$request->header('Accept-Language','en'),'quantity_of_log','quantity_reach_target_in_log','image_url'],$request['order_id'],$user);
        $products->transform(function(Product $product) use ($request){
            $product = $this->transformOrderWithProductDetails($product);
            return $product->only(array_keys($product->getAttributes()));
            
        });
        return $products;

    }

    public function updateCartItem(UpdateCartItemRequest $request){
    
        $userId = auth('jwt')->user()->id;
        $cart = $this->cartRepo->findRecentCart($userId);
        if($request->has('cart_list')){
            try{
                $json  = json_decode($request['cart_list'],true);
                if (jsonParaChecker($json, ['cart_item_id','quantity_of_log'], false)) {
                    return $this->cartItemRepo->updateCartItemInCart($cart, $json, true);
                }
                else{
                    throw new InputDataErrorException('');
                }
                //dd($json);
            }
            catch(JsonException $e){
                throw new InputDataErrorException($e);
            }
        }
        else{
            return $this->cartItemRepo->updateCartItemInCart($cart, $request->all(), false);
                
        }

        
        
        
    }

    // public function updateCartItemQuantity(Request $request){
    //     $userId = auth('jwt')->user()->id;
    //     $cart = $this->cartRepo->findRecentCart($userId);
    //     return $this->cartItemRepo->updateCartItemInCart($cart,$request->only(['cart_item_id','quantity_of_log']),false);
    // }

    
    

    public function addItemToExistCartOrNewCart(Request $request){

        $userId = auth('jwt')->user()->id;

        $cart = $this->cartRepo->findRecentCart($userId);

        if(empty($cart)){
            $cart = $this->cartRepo->createCart(array('users_id'=>$userId));
        }
        $cartItems = $this->cartRepo->getCorrespondingCartItems($cart);

        if($request->has('cart_list')){
            try{
                $json  = json_decode($request['cart_list'],true);
            }
            catch(JsonException $e){
                throw new InputDataErrorException($e);
            }
            $list = collect($json)->map(function($item) use ($cart){
                $item['cart_id'] = $cart->id;
                return $item;
            });

        }
        else{
            $list = $request->all();
            $list['cart_id'] = $cart->id;
            $list = array($list);
  
        }

        $result = $this->cartItemRepo->comparingCartItemInCart($cartItems,$list);
        if($result==false) throw new CreateOrUpdateCartException("");

  



            return [
                'Y'
            ];
            //Comparing the cart item in order to add the quantity of item in cumulative
    }
    // Tips Model type can not use get() -> it retrieve all data of model
    // hasmany etc relation can get -> it only retrieve the select data
    // load use in the collection or selected model 
    // with use in has many relation  if use in model  will retrieve all data with get 
    //  relation == collection, relation() == has many etc relation
    // relation() ->get will retrieve selected data
    // first can use in collection or relation
    // select only works in builder / relation/model

    public function getRecentCartItems(GetCartRequest $request){
        $userId  = auth('jwt')->user()->id;
        $cart = $this->cartRepo->findRecentCart($userId);
        if(!is_null($cart)){
            $cartItems = $cart->cartItem()->orderBy($request['order'],$request['sort'])->limit($request['limit'])->offset($request['offset']);
            //dd($cartItems);
            if($request['full_cart']){
                $cartItems = $this->cartItemRepo->loadCartItemProductDetails(
                    $cartItems,
                    ['id','name_'.$request->header('Accept-Language','en'),'duedate','image_url','price','quantity_per_log',DB::raw('CAST( (quantity_of_log_inventory_limit-quantity_of_log)/quantity_of_log_total *100 as DECIMAL(10,2)) as reach_rate')],
                    ['id','product_id','quantity_of_log']
                );
            }
            else{
                $cartItems = $this->cartItemRepo->loadCartItemProductDetails($cartItems,
                ['id','name_'.$request->header('Accept-Language','en'),'price','duedate','image_url'],
                ['id','product_id','quantity_of_log']
            );
            }

        }
        else{ 
            $cartItems= [];
        }
        return response()->json([
            'status'=>'Y',
            'cart_items'=>$cartItems
        ]);
        
    }

    // public function getRecentCartItemsFull(GetCartRequest $request){
    //     $userId  = auth('jwt')->user()->id;
    //     $cart = $this->cartRepo->findRecentCart($userId);
    //     if(!is_null($cart)){
    //         $cartItems = $cart->cartItem();
    //         $cartItems = $this->cartItemRepo->loadCartItemProductDetails(
    //         $cartItems,
    //         ['id','name_'.$request->header('Accept-Language','en'),'duedate','image_url','price','quantity_per_log',DB::raw('CAST( (quantity_of_log_inventory_limit-quantity_of_log)/quantity_of_log_total *100 as DECIMAL(10,2)) as reach_rate')],
    //         ['id','product_id','quantity_of_log']
    //     );
    //     }
    //     else{ 
    //         $cartItems= [];
    //     }
    //     return response()->json([
    //         'status'=>'Y',
    //         'cart_items'=>$cartItems
    //     ]);
    // }

    public function rateFeedbackOrderProduct(Request $request){
        $userId = auth('jwt')->user()->id;
        $this->orderTransactRepository->rateOrderProduct($request->only(['order_transact_id','product_id']),$userId);

    }

    





    







    

   
}