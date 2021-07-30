<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Auth::routes(['verify' => true]);

Route::get('/ping',[UserController::class,'ping']);
Route::post('/getUserInfo',[UserController::class,'getUserInfo']);
Route::group(['middleware' => ['auth:jwt']], function () {
    Route::post('/updateUserInfo',[UserController::class,'updateUserInfo']);
    Route::post('/changePassword',[UserController::class,'updateUserPassword']);
    Route::post('/addAddress',[UserController::class,'createOrUpdateAddressBook']);
    Route::post('/deleteAddress',[UserController::class,'deleteAddressBook']); 

    // Route::post('/getUserInfo',[UserController::class,'getUserInfo']);

});
Route::get('/email/verify/{id}/{hash}',[AuthController::class,'authEmail'])->middleware(['signed:relative'])->name('verification.verify');


Route::group(['middleware' => ['guest:api']], function () {
    Route::post('/register',[UserController::class,'register']);
    Route::post('/test',[UserController::class,'test']);
    Route::post('/forgot-password',[AuthController::class,'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}/{id}/{email}',[AuthController::class,'firstStepResetPassword'])->middleware(['signed:relative'])->name('password.reset');
    Route::post('/reset-password',[AuthController::class,'resetPassword'])->middleware(['postSigned'])->name('password.update');


    // Route::get('/login',function(Request $request){

    // })->name('login');
    // Route::post('/login', [\App\Http\Controllers\AuthController::class, 'clientLogin']);
});




