<?php

namespace App\Http\Controllers;

use App\Service\OrderService;
use App\Service\ProductService;
use App\Traits\ApiRequest;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    //
    use ApiRequest;
    protected $orderService;
    protected $productService;
    protected $client;
    public function __construct(OrderService $orderService, ProductService $productService)
    {
        $this->orderService = $orderService;
        $this->productService  = $productService;

        $this->baseUri = env('SERVICE_ORDER_BASE_URL');
        $this->secret = "";
    }

    public function getHomePageBarData(Request $request)
    {
        $promise = [];
        $category = $this->productService->getCategoryList($request);
        $productFilter = $this->productService->getFilterProductList($request);
        $promise['category'] = $category;
        $promise['product_filter'] = $productFilter;
        if($request->header('authorization')!=null){
            $cart  = $this->orderService->getCart($request);
            $order = $this->orderService->getOrderSnippet($request);
            $promise['cart'] = $cart;
            $promise['order'] = $order;
        }
        
        $response = $this->performAsyncRequest($promise, false);
        //dd(json_decode($response['category']['value']->getBody()->getContents()));
        return [
            'category'=>json_decode($response['category']['value']->getBody()->getContents()),
            'cart'=>json_decode($response['cart']['value']->getBody()->getContents()),
            'order'=>json_decode($response['order']['value']->getBody()->getContents()),
            'product_filter'=>json_decode($response['product_filter']['value']->getBody()->getContents())
        ];

        //dd($response['category']['value']->getBody());
    }
}
