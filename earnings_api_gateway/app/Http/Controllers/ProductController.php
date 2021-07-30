<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\ProductService;
class ProductController extends Controller
{
    //
    protected $productService ;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function listProduct(){
        $this->productService
    }
}
