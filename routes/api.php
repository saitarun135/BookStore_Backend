<?php

use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\FileController;
use Facade\FlareClient\Stacktrace\File;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [
   UserController::class, 'register'
]);
Route::post('/login', [UserController::class, 'login']);




Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/sendPasswordResetLink', 'App\Http\Controllers\PasswordResetRequestController@forgotPassword');
    Route::post('/resetPassword', 'App\Http\Controllers\ChangePasswordController@resetPassword');

});

Route::group(["middleware"=>['auth.jwt']],function(){
    Route::post('addBooks',[FileController::class,'upload']);
    Route::get('getBooks',[FileController::class,'displayBooks']);
    Route::get('showBook/{id}',[FileController::class,'display_Book']);
    Route::put('updateBook/{id}',[FileController::class,'updateBook']);
    Route::delete('deleteBook/{id}',[FileController::class,'DeleteBook']);
    Route::get('searchBooksbyName/{name}',[FileController::class,'searchbooks']);
    Route::get('searchBookbyAuthor/{author}',[FileController::class,'searchBooksByAuthor']);
    Route::get('sortHightoLow',[FileController::class,'sortBooksHighToLow']);
    Route::get('sortLowtoHigh',[FileController::class,'sortBooksLowToHigh']);
    Route::get('searchBookbyprice/{price}',[FileController::class,'searchBooksbyPrice']);
    Route::get('cart',[FileController::class,'cartItem']);
    Route::put('addtocart/{id}',[FileController::class,'AddToCart']);
    Route::put('removecart/{id}',[FileController::class,'RemoveFromCart']);
});
Route::post('customerRegister', [CustomersController::class, 'customerRegistration']);
Route::delete('customerdelete/{id}', [CustomersController::class,'DeleteCustomer']);
Route::post('mail',[CustomersController::class,'orderSuccessfull']);
Route::get('orderid/{customer_id}',[CustomersController::class,'getOrderID']);