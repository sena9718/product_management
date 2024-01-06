<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
// use Illuminate\Support\Fecades\Auth;


// Route::get('/', function () {
//     if(Auth::check()) {
//         return redirect()->route('products.index');
//     } else {
//         return redirect()->route('login');
//     }
// });

Auth::routes();

// Route::group(['middleware' => 'auth'], function() {
//     Route::resource('products', ProductController::class);
// });

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products/index', 'App\Http\Controllers\ProductController@index')->name('products.index');

Route::get('/products/create', 'App\Http\Controllers\ProductController@create')->name('products.create');
Route::post('/products/store', 'App\Http\Controllers\ProductController@store')->name('products.store');

Route::get('/products/show/{product}', 'App\Http\Controllers\ProductController@show')->name('products.show');

Route::get('/products/edit/{id}', 'App\Http\Controllers\ProductController@edit')->name('products.edit');
Route::put('/products/edit/{id}', 'App\Http\Controllers\ProductController@update')->name('products.update');

Route::delete('/products/{product}', 'App\Http\Controllers\ProductController@destroy')->name('products.destroy');

Route::get('/products/search', 'App\Http\Controllers\ProductController@search')->name('products.search');