<?php

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

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
   $visits=Redis::incrBy('visits',5);

   return view('welcome',compact('visits'));
});


Route::get('/article/{id}',function($id){

    $totalvisitors=Redis::incr("articles.{$id}.visists");

    return view('article',compact('totalvisitors'));
});