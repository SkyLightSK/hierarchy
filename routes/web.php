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

Route::get('/', 'ProfileController@welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users', 'UserController@index')->name('users');
Route::post('/search', 'UserController@search')->name('search');
Route::post('/searchPagination', 'UserController@searchPagination')->name('searchPagination');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::post('/profile', 'ProfileController@update');


Route::post('/subordinates','HomeController@unload');
Route::post('/updateHierarchy','HomeController@updateHierarchy');
Route::post('/update','AjaxController@index');