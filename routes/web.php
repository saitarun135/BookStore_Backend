<?php

use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/fileupload','FileUploadController@index');
// Route::post('/save-image','FileUploadController@saveImage');
// Route::get('image', 'ImageController@index');
//  Route::post('store', [ImageController::class,'store']);
//  Route::get('image',[ImageController::class, 'index']);

 Route::get('/', 'FileController@index');
Route::resource('images', 'FileController', ['only' => ['store', 'destroy']]);