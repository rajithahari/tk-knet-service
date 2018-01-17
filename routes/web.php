<?php

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

Route::name('home')->get('/', 'PaymentController@index');
Route::name('response')->get('response', 'PaymentController@response');
Route::get('error', 'PaymentController@error');
Route::name('result')->get('result', 'PaymentController@result');
