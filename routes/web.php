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
Auth::routes();
Route::get('/', function () {
    return view('welcome');
});
Route::get('/threads/', 'ThreadsController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/threads/{channel}', 'ThreadsController@index');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');
Route::get('thread/create', 'ThreadsController@create')->name('threads_create');

Route::post('/threads', 'ThreadsController@store');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::post('/replies/{reply}/favorites','FavoritesController@store')->name('favorite');
Route::post('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::post('/threads/{channel}/{thread}/subscribe', 'SubscribeController@store');
Route::delete('/threads/{channel}/{thread}/subscribe', 'SubscribeController@destroy');
Route::patch('/replies/{reply}','RepliesController@update');

Route::delete('/replies/{reply}/favorites','FavoritesController@destroy')->name('unfavorite');
Route::delete('/replies/{reply}','RepliesController@destroy');