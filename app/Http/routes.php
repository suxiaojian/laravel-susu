<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
//    $article = \App\Article::find(3);
//    $article_tag = $article->tags()->attach(4);
//    dd($article_tag);

    return view('welcome');
});

Route::get('/login', 'UserController@login');

Route::get('/github/login', 'UserController@github');


Route::get('/aa', 'UserController@aa');