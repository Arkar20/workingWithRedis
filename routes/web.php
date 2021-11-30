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

    $totalvisitors=Redis::incr("articles.{$id}.visists");  //* also : works fine

    return view('article',compact('totalvisitors'));
});




Route::get('/videos',function(){

    $videos=  Redis::zrevrange('trending_videos',0,2);

    return $videos;
});
Route::get('/videos/{id}',function($id){

      Redis::zincrby('trending_videos',1,$id);

    //!   Redis::zremrangebyrank('trending_videos',0,-2); limiting the set 

    return back();
});



//! flushall to remove all 

//*List = Array
//*Hash = Object
//*Sets= Unique Array or List 
//*Sorted Sets= Sorted according to score, which we defined date, number, etc