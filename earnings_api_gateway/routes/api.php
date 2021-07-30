<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('/login',[AuthController::class,'attemptLogin']);
    Route::get('/email/verify/{id}/{hash}',[AuthController::class,'emailVerify'])->name('verification.verify');
    Route::post('/register',[UserController::class,'registerClient']);
    Route::post('/forgot-password',[AuthController::class,'forgorPassword'])->name('password.email');
    Route::get('/reset-password/{token}/{id}/{email}',[AuthController::class,'firstStepPassword'])->name('password.reset');
    Route::post('/reset-password',[AuthController::class,'resetPassword'])->name('password.update');
    Route::get('/category',[HomePageController::class,'category']);
    Route::get('/product',[ProductController::class,'listProduct']);
    // Route::get('/getHomePageBar',[HomePageController::class,'getHomePageBarData']);


});

Route::group(['middleware'=>['auth:api']],function(){
    Route::get('/getHomePageBar',[HomePageController::class,'getHomePageBarData']);
    Route::get('/getMyProfile',[UserController::class,'myProfile']);
    Route::post('/updateMyUserProfile',[UserController::class,'updateMyProfile']);
    Route::post('/addAddressBook',[UserController::class,'addAddress']);
    Route::post('/changePwd',[UserController::class,'changePwd']);
    Route::post('/deleteAddress',[UserController::class,'deleteAddress']);
    Route::post('/getMyOrder',[OrderController::class,'getMyOrder']);
    Route::post('/getOrderProductDetails',[OrderController::class,'getMyOrderDetails']);
    Route::post('/createOrder',[OrderController::class,'createOrder']);
    Route::post('/addToCart',[OrderController::class,'addToCart']);
    Route::get('/getCart',[OrderController::class,'getRecentCart']);
    Route::get('/getFullCart',[OrderController::class,'getRecentCartFull']);
    Route::get('/getOrderSnippet',[OrderController::class,'getOrderSnippet']);
    Route::post('/likeProduct',[ProductController::class,'likeProduct']);
    Route::post('/proceedOrder',[OrderController::class,'proceedOrder']);
    Route::post('ratefeedback',[OrderController::class,'rateFeedback']);
    Route::group(['middleware'=>['role:client']],function(){
        Route::post('/test',[AuthController::class,'testClient']);
    });
    //Route::post('/test',[AuthController::class,'testClient']);
    


});


Route::group(['middleware' => ['guest:api_admin']], function () {
    
    Route::post('/adminlogin',[AuthController::class,'attemptLoginAdmin']);

});

Route::group(['middleware' => ['auth:api_admin',]], function () {
    // Route::group(['middleware'=>['role:admin,developer']],function(){
    //     Route::post('/test',[AdminAuthController::class,'testAdmin']);
    // });
    
});


Route::group([
    'prefix' => 'auth'
], function () {
    // Route::post('login', 'AuthController@login');


    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

