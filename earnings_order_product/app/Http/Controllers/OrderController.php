<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\GetCartRequest;
use App\Http\Requests\GetOrderProductDetailsByUserRequest;
use App\Http\Requests\GetOrderProductSnippetRequest;
use App\Http\Requests\GetOrderRelatedProductRequest;
use App\Http\Requests\GetOrderRequestByUser;
use App\Http\Requests\ProceedOrderHandleRequest;
use App\Http\Requests\UpdateOrderProductRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Service\OrderService;
use Laravel\Ui\Presets\React;
use App\Http\Requests\UpdateCartItemRequest;
use App\Exceptions\PermissionNotEnoughException;
use App\Http\Requests\indexOrderDetailsRequest;
use App\Http\Requests\indexOrderRequest;

class OrderController extends Controller
{
    //
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService  = $orderService;
    }

    public function createOrder(CreateOrderRequest $request){

        $this->orderService->createOrder($request);
    }

    public function test(Request $request){
        return $this->orderService->test($request);
    }

    public function getOrderBaseByUser(GetOrderRequestByUser $request){
        return $this->orderService->getOrderTransactBase($request);
    }

    public function getOrderProductDetailByUser(GetOrderProductDetailsByUserRequest $request){
        return $this->orderService->getOrderTransactDetailsByUser($request);
    }

    public function getOrderProductSnippet(GetOrderProductSnippetRequest $request){
        return $this->orderService->getOrderTransactSnippet($request);
    }

    public function getProductDetailsByOrder(GetOrderRelatedProductRequest $request){
        return $this->orderService->getProductDetailWithOrder($request);
    }

    public function addCartItem(AddToCartRequest $request){
        return $this->orderService->addItemToExistCartOrNewCart($request);
    }
    public function getRecentCartItems(GetCartRequest $request){
        return $this->orderService->getRecentCartItems($request);
    }

    // public function getRecentCartItemsFull(Request $request){
    //     return $this->orderService->getRecentCartItemsFull($request);
    // }

    // public function massUpdateCartItemQuantity(Request $request){
    //     return $this->orderService->massUpdateCartItemQuantity($request);
    // }
    // public function updateCartItemQuantity(Request $request){
    //     return $this->orderService->updateCartItemQuantity($request);
    // }

    public function updateCartItem(UpdateCartItemRequest $request){
        return $this->orderService->updateCartItem($request);
    }

    public function proceedCreateOrderHandle(ProceedOrderHandleRequest $request){
        return $this->orderService->proceedOrderDataHandle($request);
    }

    public function rateFeedbackOrderProduct(Request $request){
        return $this->orderService->rateFeedbackOrderProduct($request);
    }

    public function indexOrderTransact(indexOrderRequest $request){
        if($request->user()->isAllowPermission('read-user-order')){
            $order= $this->orderService->indexOrderTransact($request);
            return response()->json([
                'status'=>'Y',
                'order'=>$order
            ]);
        }
        else{
            throw new PermissionNotEnoughException("");
        }
        
    }

    public function indexOrderTransactDetail(indexOrderDetailsRequest $request){
        if(is_null($request['user']))
        if($request->user()->isAllowPermission('read-user-order')){
            $order= $this->orderService->indexOrderTransactDetail($request);
            return response()->json([
                'status'=>'Y',
                'order'=>$order
            ]);
        }
        else{
            throw new PermissionNotEnoughException("");
        }
        
    }

    public function deleteOrder(Request $request){
        $this->orderService->deleteOrder($request);

        return [
            'status'=>'Y'
        ] ;
    }
    

    public function updateOrder(UpdateOrderRequest $request){
        if(!$request->has('order_json')){
            $this->orderService->updateOrderTransact($request->all(),false);
        }
        else{
            
            $this->orderService->updateOrderTransact($request->only('order_json'),true);
        }
        

        return [
            'status'=>'Y'
        ];
    }

    public function updateOrderProduct(UpdateOrderProductRequest $request){
        if(!$request->has('order_product_json')){
            $this->orderService->updateOrderProduct($request->all(),false);

        }
        else{
            $this->orderService->updateOrderProduct($request['order_product_json'],true);
        }
    }

    // public function massUpdateOrderStatus(Request $request){
    //     $this->orderService->massUpdateOrderStatus($request,true);
    //     return [
    //         'status'=>'Y'
    //     ];
    // }
    // public function massUpdateOrderProductStatusAndETA(Request $request){
    //     $this->orderService->massUpdateOrderProductStatusOrETA($request);
    //     return [
    //         'status'=>'Y'
    //     ];
    // }


}
