<?php

use App\Http\Controllers\HomePageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', [ 'as' => 'login', 'uses' => 'OrderController@test']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/category',[HomePageController::class,'listAllCategory']);
Route::get('/productFilter',[HomePageController::class,'listFilter']);
Route::get('/categoryAndSubcat',[HomePageController::class,'listCategoryInLV2']);
Route::get('/product',[ProductController::class,'productDetails']);
Route::get('/productList',[ProductController::class,'listProduct']);
Route::get('/country',[HomePageController::class,'indexCountry']);
Route::get('/businessNature',[HomePageController::class,'getBusiness']);

Route::group(['middleware' => ['auth:jwt']], function () {
    Route::get('/getMyOrder',[OrderController::class,'getOrderBaseByUser']);
    Route::get('/getMyOrderDetail',[OrderController::class,'getOrderProductDetailByUser']);
    Route::post('/getOrderProductDetails',[OrderController::class,'getProductDetailsByOrder']);
    Route::post('/createOrder',[OrderController::class,'createOrder']);
    Route::post('/addToCart',[OrderController::class,'addCartItem']);
    Route::get('/getCart',[OrderController::class,'getRecentCartItems']);
    Route::post('/updateCart',[OrderController::class,'updateCartItem']);
    // Route::get('/getFullCart',[OrderController::class,'getRecentCartItemsFull']);
    Route::get('/getOrderSnippet',[OrderController::class,'getOrderProductSnippet']);
    Route::post('/likeProduct',[ProductController::class,'likeProduct']);
    Route::post('/proceedOrder',[OrderController::class,'proceedCreateOrderHandle']);
    Route::post('/ratefeedback',[OrderController::class,'rateFeedbackOrderProduct']);
    Route::post('/updateOrder',[OrderController::class,'updateOrder']);
    Route::post('/deleteOrder',[OrderController::class,'deleteOrder']);
    Route::post('/indexOrder',[OrderController::class,'indexOrderTransact']);
    Route::post('/indexOrderDetails',[OrderController::class,'indexOrderTransactDetail']);
    Route::post('/adminUpdateOrderProduct',[OrderController::class,'massUpdateOrderProductStatusAndETA']);
    Route::post('/updateOrderProduct',[OrderController::class,'updateOrderProduct']);
    Route::post('/createProduct',[ProductController::class,'insertProduct']);

    // Route::post('/adminUpdateOrder',[OrderController::class,'massUpdateOrderStatus']);
    // Route::post('/adminUpdateProduct',[ProductController::class,'updateProduct']);



    Route::group(['middleware'=>'roleType:User'],function(){
        /**
         * demo for spicific type of user
         * roleType :  User  /  BackendUser
         */
        Route::post('/test2',[OrderController::class,'test']);
    });

    Route::group(['middleware'=>'role:client'],function(){
        /**
         * demo for spicific role of user
         * role  :  please find in database
         */
        Route::post('/test3',[OrderController::class,'test']);
    });
    

});
Route::group(['middleware' => ['guest:jwt']], function () {
    Route::post('/test',[OrderController::class,'test']);
    // Route::get('/category',[HomePageController::class,'listAllCategory']);
    // Route::get('/productFilter',[HomePageController::class,'listFilter']);
    // Route::get('/categoryAndSubcat',[HomePageController::class,'listCategoryInLV2']);
    // Route::get('/product',[ProductController::class,'productDetails']);
    // Route::get('/productList',[ProductController::class,'listProduct']);

});

Route::middleware('auth:jwt')->get('/user', function () {
    // return Auth::user();
});


