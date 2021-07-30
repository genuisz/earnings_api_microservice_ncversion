<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\GetProductDetailRequest;
use App\Http\Requests\LikeCommentProductRequest;
use App\Http\Requests\ListProductRequest;
use Illuminate\Http\Request;
use App\Service\ProductService;
use App\Http\Requests\UpdateProductRequest;
class ProductController extends Controller
{
    //
    protected $productService ;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function listProduct(ListProductRequest $request){
        return $this->productService->getProductList($request['type'],$request['category_id'],$request['expired_date'],$request['lead_time'],$request['lot_size'],$request['unit_price'],$request['achieve_rate'],$request['port'],$request['keyword'],$request['order'],$request['sort'],$request['start_date'],$request['end_date'],$request['offset'],$request['limit'],$request->header('Accept-Language','en'));
    }



    public function productDetails(GetProductDetailRequest $request){
        return $this->productService->getProductDetails($request);
    }

    public function likeProduct(LikeCommentProductRequest $request){
        return $this->productService->likeProduct($request);
    }

    public function insertProduct(CreateProductRequest $request){
        return $this->productService->insertProduct($request);
    }
    public function updateProduct(UpdateProductRequest $request){
        return $this->productService->updateProduct($request);
    }



}
