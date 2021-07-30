<?php
namespace App\Repository;

use App\Exceptions\ProductNumberNotEnoughException;
use App\Exceptions\UpdateDataErrorException;
use Illuminate\Support\Collection;
use App\Models\Product;
use App\Repository\Interfaces\OrderTransactRepositoryInterface;
use App\Models\OrderTransact;
use App\Models\User;
use App\Models\Logistic;
use App\Models\OrderStatus;
use App\Models\OrderTransactProduct;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Repository\NonLocalRepository\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
class OrderTransactRepository extends AbstractRepository implements OrderTransactRepositoryInterface{
    public function model(){
        return  'App\\Models\\OrderTransact';
     }


     public function getOrderTransact($users,$logisticColumn,$orderStatusColumn,$toPortColumn,$orderTransactColumn, $orderBy= 'id', $sortBy = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15){
        $q= $this->model::
            withHas('logistic',function($q) use ($logisticColumn){
            $q->select($logisticColumn);
        })->withHas('orderStatus',function($q) use ($orderStatusColumn){
            $q->select($orderStatusColumn);
        })->withHas('toLogisticPort',function($q) use ($toPortColumn){
            $q->select($toPortColumn);
        })
        ->orderBy($orderBy, $sortBy)->limit($limit)->offset($offset)->select($orderTransactColumn);
        if($users!=null){
            $q = $q->where('users_id',$users);
        }
        return $q;
     }

     public function calculateOrderFee($total_fee,$orderFeeArray){
        $total_fee['downpayment_total'] = $total_fee['downpayment_total']+$orderFeeArray['downpayment_total'];
        $total_fee['deposit_total'] = $total_fee['deposit_total'] +$orderFeeArray['deposit_total'];
        $total_fee['sub_total'] = $total_fee['sub_total'] + $orderFeeArray['sub_total'];
        $total_fee['logistic_cost']=  $total_fee['logistic_cost'] + $orderFeeArray['logistic_cost'] ;
        $total_fee['total'] = $total_fee['total']+$orderFeeArray['total'];

        return $total_fee;
     }

     public function calculateOrderEachProductFee($itemQuantityLog,$product,$unitLogisticCost){

         
        //$unitLogisticCost = (float)$this->model->toLogisticPort->logisticPortFrom()->find($product['from_port_id'])->logisticFrom()->wherePivot('logistic_id',$item['logistic_id'])->wherePivot('to_port_id',$item['to_port_id'])->first()->toArray()['pivot']['courier_charges_unit'];
        $logisticCost = $itemQuantityLog * $product['log_weight'] * $unitLogisticCost;
        // dump($product['log_weight']);
        // dump($logisticCost);
        $downPayment = $product['downpayment_ratio']/100* $product['price'] * $itemQuantityLog;
        $deposit = $product['deposit_ratio']/100* $product['price'] * $itemQuantityLog;
        $sub_total = $product['price']* $itemQuantityLog;
        $fee['downpayment_total'] =$downPayment;
        $fee['deposit_total'] = $deposit;
        $fee['sub_total'] = $sub_total ;
        $fee['logistic_cost']=  $logisticCost ;
        $fee['total'] = $sub_total + $logisticCost;

        return $fee; 

     }

     public function loadOrderTransactWithProductDetailsByOrder($order,$logisticColumn,$orderStatusColumn,$productColumn,$quantityUnitColumn,$orderProductStatusColumn,$orderProductColumn,$orderTransactColumn,$fromPortColumn){
        return $order->load($this->orderDetailsArrayProducer($logisticColumn,$orderStatusColumn,$productColumn,$quantityUnitColumn,$orderProductStatusColumn,$orderProductColumn,$orderTransactColumn,$fromPortColumn));
     }

    public function orderDetailsArrayProducer($logisticColumn,$orderStatusColumn,$productColumn,$quantityUnitColumn,$orderProductStatusColumn,$orderProductColumn,$orderTransactColumn,$fromPortColumn){
        $array = array();

        if($orderProductColumn!=null){
            $array=array_merge($array,['orderProduct'=>function($q) use ($orderProductColumn){
                $q->select($orderProductColumn);
            }]);
        }

        if($orderProductStatusColumn!=null){
            $array=array_merge($array,['orderProduct.orderStatus'=>function($q) use ($orderProductStatusColumn){
                $q->select($orderProductStatusColumn);
            }]);
        }
        if($productColumn!=null){
            $array=array_merge($array,['orderProduct.product'=>function($q) use ($productColumn){
                $q->select($productColumn);
            }]);
        }
        
        if($quantityUnitColumn!=null){
            $array=array_merge($array,['orderProduct.product.quantityUnit'=>function($q)use ($quantityUnitColumn){
                $q->select($quantityUnitColumn);
            }]);
        }
        if($fromPortColumn!=null){
            $array=array_merge($array,['orderProduct.product.fromLogisticPort'=>function($q) use ($fromPortColumn) {
                $q->select($fromPortColumn);

            }]);
        }
        if($orderStatusColumn!=null){
            $array=array_merge($array,['orderStatus'=>function($q) use ($orderStatusColumn){
                $q->select($orderStatusColumn);
            }]);
        }

        if($logisticColumn!=null){
            $array=array_merge($array,['logistic'=>function($q) use ($logisticColumn){
                $q->select($logisticColumn);
            }]);
        }
        return $array;

    }

 

     public function getOrderTransactWithProductDetails($users,$orders,$logisticColumn,$orderStatusColumn,$productColumn,$quantityUnitColumn,$orderProductStatusColumn,$orderProductColumn,$orderTransactColumn,$fromPortColumn, $orderBy= 'id', $sortBy = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15){
        $q =  $this->model::with($this->orderDetailsArrayProducer($logisticColumn,$orderStatusColumn,$productColumn,$quantityUnitColumn,$orderProductStatusColumn,$orderProductColumn,$orderTransactColumn,$fromPortColumn))->orderBy($orderBy, $sortBy)->limit($limit)->offset($offset)->select($orderTransactColumn);
        if($users!=null){
            $q = $q->where('users_id',$users);
        }
        if($orders!=null){
            $q = $q->where('id',$orders);
        }
        return $q;
     }


     public function getProductDetailByOrderId($productSelectColumn,$orderId,$userId=null){
         if($userId!=null){
            $order = $this->model->where('id',$orderId)->where('users_id',$userId)->first();
            
            return $order->product()->select($productSelectColumn)->get();
         }
         
        
     }


     public function transFormOrderTransact(Collection $list){

     }

 
     public function getOrderTransactById($id,$orderColumn){
         return $this->getOneById($id,$orderColumn);

     }
 
     public function getOrderTrsanctWithProductDetailsById($id){

     }
     public function listOrderTransactProduct():Collection
     {
         return $this->model->product->map(function (Product $product){
            // $product->unique_order_product_id = $product->order_product->unique_order_product_id;
            // $product->order_quantity_in_log = $product->order_product->order_quantity_in_log;
            // $product->order_product_status_id = $product->order_product->order_product_status_id;
            // $product->confirmed_delivery_date = $product->order_product->confirmed_delivery_date;
            // $product->expected_ETD = $product->order_product->expected_ETD;
            // $product->due_at = $product->order_product->due_at;
            // $product->downpayment = $product->order_product->downpayment;
            // $product->deposit = $product->order_product->deposit;
            // $product->total = $product->order_product->total;
            // $product->feedback = $product->order_product->feedback;
            return $product;
         });
     }



     public function getByLogistic(Logistic $logistic):OrderTransact{

     }
 
     public function getByOrderStatus(OrderStatus $orderStatus):OrderTransact{

     }


     public function getOrder( $id):OrderTransact{
         return $this->getOneById($id);
     }
     
 
     public function getUser():Collection{
        
     }
 
     public function getLogistic():Collection{
         return $this->model->logistic()->get();

     }
     
     public function getOrderStatus():Collection{
        return $this->model->orderStatus()->get();
     }
 
     public function associateProduct(Product $product,int $quantityOfLog,$logisticCost){
         
         $uniqueProductId = quickRandom(10);
        try{
            $product->quantity_of_log = ($product->quantity_of_log-$quantityOfLog);
            $product->save();
        }
        catch(QueryException $e){
            throw new ProductNumberNotEnoughException();
        }

        $this->model->product()->attach($product->id,[
            'unique_order_product_id'=>$uniqueProductId,
            'order_quantity_in_log'=>$quantityOfLog,
            'order_product_status_id'=>'1',
            'due_at'=>$product->duedate,
            'downpayment'=>$product->downpayment_ratio/100 * $product->price*$quantityOfLog,
            'deposit'=>$product->deposit_ratio/100 * $product->price * $quantityOfLog,
            'sub_total'=>$product->price * $quantityOfLog,
            'weight'=>$product->log_weight * $quantityOfLog,
            'logistic_cost'=>$logisticCost,
            'total'=>$product->price* $quantityOfLog  + $logisticCost

            ]);
     }

     public function associateProductWithArray($associationArray){
         $this->model->product()->attach($associationArray);
        //  $pivotRepo =  new OrderTransactProductRepository();
        //  $pivotRepo->createDataWithLargeNumber($associationArray,false);
         
     }

     public function proceedBuildOrder(Collection $items){
        $total_fee['downpayment_total'] =0;
        $total_fee['deposit_total']=0;
        $total_fee['logistic_cost']=0;
        $total_fee['sub_total']=0;
        $total_fee['total'] =0;

        $productIdList = $items->map(function($item){

            return $item['product_id'];
        });

       
    
        $productRepo = new ProductRepository();
        $productList = $productRepo->getById($productIdList,null); 

        $detailUnitPriceOfEachProductList = $this->loadUnitLogisticCost($productList,$this->model['to_port_id'],$this->model['logistic_id']);



         $associationArray = array();
         
         $productReductionArray = array();
         foreach($items as $index =>$item){
            $product = $productList[$index];
            //$unitLogisticCost = (float)$this->model->toLogisticPort->logisticPortFrom()->find($product['from_port_id'])->logisticFrom()->wherePivot('logistic_id',$item['logistic_id'])->wherePivot('to_port_id',$item['to_port_id'])->first()->toArray()['pivot']['courier_charges_unit'];
            $unitLogisticCost = (float)$detailUnitPriceOfEachProductList[$index]['from_logistic_port']['logistic_port_to'][0]['pivot']['courier_charges_unit'];
            // dd($unitLogisticCost);
            $orderFeeArray = $this->calculateOrderEachProductFee($item['quantity_of_log'],$product,$unitLogisticCost);

            $total_fee = $this->calculateOrderFee($total_fee,$orderFeeArray);

            $uniqueProductId = quickRandom(10);

            $associationArray[$product->id] =[
                'unique_order_product_id'=>$uniqueProductId,
                'order_quantity_in_log'=>$item['quantity_of_log'],
                'order_product_status_id'=>'1',
                'due_at'=>$product->duedate,
                'downpayment'=>$orderFeeArray['downpayment_total'],
                'deposit'=>$orderFeeArray['deposit_total'],
                'sub_total'=>$orderFeeArray['sub_total'],
                'weight'=>$product->log_weight * $item['quantity_of_log'],
                'logistic_cost'=>$orderFeeArray['logistic_cost'],
                'total'=>$orderFeeArray['total']
            ];

            $reductionArrayElement = array('id'=>$product->id,'quantity_of_log'=>$product->quantity_of_log-$item['quantity_of_log']);
            array_push($productReductionArray,$reductionArrayElement);

            
            // $associationArray[]  =[
            //     'product_id' =>$product->id,
            //     'order_transact_id'=>$this->model->id,
            //     'unique_order_product_id'=>$uniqueProductId,
            //     'order_quantity_in_log'=>$item['quantity_of_log'],
            //     'order_product_status_id'=>'1',
            //     'due_at'=>$product->duedate,
            //     'downpayment'=>$product->downpayment_ratio/100 * $product->price*$item['quantity_of_log'],
            //     'deposit'=>$product->deposit_ratio/100 * $product->price * $item['quantity_of_log'],
            //     'sub_total'=>$product->price * $item['quantity_of_log'],
            //     'weight'=>$product->log_weight * $item['quantity_of_log'],
            //     'logistic_cost'=>$item['quantity_of_log'] * $product['log_weight'] * $unitLogisticCost,
            //     'total'=>$product->price* $item['quantity_of_log']  + $item['quantity_of_log'] * $product['log_weight'] * $unitLogisticCost
            // ];

 
         }

         DB::BeginTransaction();
         try{
            $productRepo = new ProductRepository();
            $productRepo->massUpdateProductQuantity($productReductionArray);
            $this->associateProductWithArray($associationArray);
         }
         catch(Exception $e){
             DB::rollBack();
             throw new ProductNumberNotEnoughException($e->getTraceAsString());
         }
         DB::commit();

         return $total_fee;
         
     }

     public function updateOrderProduct($array){


            $order = $this->getOneById($array['order_id']);
            $filter = ['order_id','product_id'];
            $allow = array_filter($array,function($key) use ($filter){
                return !in_array($key,$filter);
            },ARRAY_FILTER_USE_KEY);
            
            $order->product()->updateExistingPivot($array['product_id'],$allow);
        
     }

     public function isValidAndRetry($id){
         try{
             $result = $this->getOneById($id);
             dd($result);
         }
         catch(QueryException $e){
            dd($e);
         }
     }
     public function proceedCalcuateOrderFee($result){
        try{
            $total_fee = $this->proceedBuildOrder(collect($result));
            
        }
        catch(ProductNumberNotEnoughException $e){
            // $this->model->product()->detach();
            // $this->model->forceDelete();
            throw new ProductNumberNotEnoughException($e->getTraceAsString());
        }
        $this->model->downpayment= $total_fee['downpayment_total'];
        $this->model->deposit = $total_fee['deposit_total'];
        $this->model->total_price = $total_fee['total'];  
        $this->model->sub_total = $total_fee['sub_total'];
        $this->model->logistic_cost = $total_fee['logistic_cost'];   
        $result = $this->model->save();
        if($result==true){
            return $this->model;
        }
     }

     public function handleCartJsonAddData($cartJson,$params){
        $result  = json_decode($cartJson,true);
        foreach($result as $index=> $item){
            $item['logistic_id'] = (int)$params['logistic_id'];
            $item['to_port_id']=(int)$params['to_port_id'];
            $result[$index] = $item;
        }
        return $result;
     }

     public function loadUnitLogisticCost($productList,$toPortId,$logisticId){

        $detailUnitPriceOfEachProductList = $productList->load([
            'fromLogisticPort.logisticPortTo'=>function($q) use ($toPortId,$logisticId){
                $q->where('id',(int)$toPortId)->wherePivot('logistic_id',$logisticId);

            }
        ])->toArray();
        return $detailUnitPriceOfEachProductList;
     }



     public function createOrder(array $params){
      // $json = '{"2":"2","1":"3"}';
      //$json = '[{"a":"2","b":"2"},{"a":"1","b":"1"}]';
    //   $id = quickRandom(10);
      //$json = '[{"product_id":"1","quantity_of_log":"2"},{"product_id":"2","quantity_of_log":"10"}]';
 
    //   $result  = json_decode($cartJson,true);
      $orderTransact = new OrderTransact($params);
      $this->setModel($orderTransact);
      $this->model->users_id  = $params['users_id'];
      $this->model->id = $params['order_transact_id'];
      $this->model->logistic_id = $params['logistic_id'];
      $this->model->to_port_id = $params['to_port_id'];
      $this->model->delivery_address = $params['delivery_address'];
      $this->model->order_status_id=$params['order_status_id'];
      $this->model->invoice_at = $params['invoice_at'];
      $result = $this->model->save();
      if($result==true){
          return $this->model;
      }
      return false;



    // try{
    //     $total_fee = $this->proceedBuildOrder(collect($result));
        
    // }
    // catch(ProductNumberNotEnoughException $e){
    //     // $this->model->product()->detach();
    //     // $this->model->forceDelete();
    //     throw new ProductNumberNotEnoughException($e->getTraceAsString());
    // }
    // $this->model->downpayment= $total_fee['downpayment_total'];
    // $this->model->deposit = $total_fee['deposit_total'];
    // $this->model->total_price = $total_fee['total'];  
    // $this->model->sub_total = $total_fee['sub_total'];
    // $this->model->logistic_cost = $total_fee['logistic_cost'];   
    // return $this->model->save();

    


     }
     public function updateOrderTransact($array,bool $massUpdate){
        //  $orderTransact = $this->getOneById($array['order_transact_id']);
        //  $this->setModel($orderTransact);
        if($massUpdate==true){
            try{
                $this->updateDataWithLargeNumber($array);
            }
             catch(Exception $e){
    
             }
        }
        else{
         $orderTransact = $this->getOneById($array['id']);
         $this->setModel($orderTransact);
         $this->updateData($array);
        }
         

     }
     public function updateOrderTransactProductWithSameAttribute($array,bool $massUpdate){
        if($massUpdate){
            $orderTransact = $this->getOneById($array['order_transact_id']);
            unset($array['order_transact_id']);
            $result = $orderTransact->orderProduct()->update($array);
        }
     }
     public function updateOrderTransactStatusToNextStep($orderTransactId){
         $orderTransact = $this->getOneById($orderTransactId);

         $nextStatus = $orderTransact->orderStatus->childOrderStatus->first();  // Must Keep One Parent Has only One Child

         $orderTransact->order_status_id = $nextStatus->id;
         $orderTransact->save();
         //dd($this->model->update(['order_status_id'=>$nextStatus->id]));
         //return $this->updateData(['order_status_id'=>$nextStatus->id]);
     }

     public function updateOrderTranactStatus($orderTransactId,$orderStatusId){
        $orderTransact = $this->getOneById($orderTransactId);
        $orderTransact->order_status_id = $orderStatusId;
        $orderTransact->save();
     }

     public function loadOrderProduct($orderTransact,$productColumn){
        return $orderTransact->load(['product'=>function($q)use ($productColumn){
            $q->select($productColumn);
        }]);
     }



     public function rateOrderProduct($array,$userId){
         $orderTransact = $this->model->where('users_id', $userId)->find($array['order_transact_id']);
         $orderProduct = $orderTransact->orderProduct->where('product_id', $array['product_id'])->first();
         $ratingArray = explode(',',$array['rating']);
         // $orderTransact = $this->getOneById($array['order_transact_id']);
         // $orderProduct = $this->loadOrderProduct($orderTransact,['*']);
         dd($orderProduct->toArray());
         $orderProductStatus = $orderProduct->orderStatus['slug'];
         dd($orderProduct);
         if ($orderProductStatus=='3' && is_null($orderProduct['col1_rating_id'])) {
         } else {
            $orderTransact->product()->attach($array['product_id'],[
                
            ]);
        // }
         }
     }

     public function feedbackOrderProduct($array,$userId){

     }

     public function getOrderProduct($order){
         return $this->getOneById($order->id)->orderProduct;

     }

     public function getProduct($order){
         return $this->getOneById($order->id)->product;
     }

     public function deleteOrder($array){

        //  $this->setModel($this->getOneById($array['id']));
        $this->model->destroy(array('zKVAr1h7Cq','Q4niQeGFda'));
         
         //return $this->deleteData();
     }
     

     

}