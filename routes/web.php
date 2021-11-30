<?php

use App\Models\Article;
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

// Route::get('/', function () {
//    $visits=Redis::incrBy('visits',5);

//    return view('welcome',compact('visits'));
// });


// Route::get('/article/{id}',function($id){

//     $totalvisitors=Redis::incr("articles.{$id}.visists");  //* also : works fine

//     return view('article',compact('totalvisitors'));
// });




Route::get('/articles',function(){
    
 

    $articles=  Redis::zrevrange('trending_videos',0,2);

    $aritcles_view=Article::hydrate(
        array_map('json_decode',$articles)
    );

    return $aritcles_view;
   
});

Route::get('/articles/{article}',function(Article $article){
     
      Redis::zincrby('trending_videos',1, $article);

    //! Redis::zremrangebyrank('trending_videos',0,-2); limiting the set 

    return $article;
});

Route::get('/profile/{id}',function($id){
    $profiles=[
       'id'=>$id,
        'followers'=>1000,
        'friends'=> 20,
        'likes'=>200
    ];

  Redis::hmset("users.{$id}.stats",$profiles);

    $userprofiles=Redis::hgetall("users.{$id}.stats");
    return  $userprofiles;

})->name('profile.show');

Route::get('/addfavourites/{id}',function($id){

    Redis::hincrby("users.{$id}.stats",'favourites',1);

    $userprofiles=Redis::hgetall("users.{$id}.stats");

    return redirect()->route('profile.show',$id);

});



//! flushall to remove all 

//*List = Array
//*Hash = Object
//*Sets= Unique Array or List 
//*Sorted Sets= Sorted according to score, which we defined date, number, etc