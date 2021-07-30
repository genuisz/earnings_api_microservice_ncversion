<?php

namespace App\Http\Controllers;

use App\Service\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService  = $orderService;
    }

    public function getMyOrder(Request $request){
        return $this->orderService->getMyOrder($request);
    }

    public function getMyOrderDetails(Request $request){
        return $this->orderService->getMyOrderDetails($request);
    }
    
    public function createOrder(Request $request){
        return $this->orderService->createOrder($request);
    }

    public function getFullCart(Request $request){
        return $this->orderService->getFullCart($request);
    }
    
}
