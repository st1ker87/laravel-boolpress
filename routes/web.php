<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', 'HomeController@index')->name('guests_homepage');

Route::get('/categories', 'CategoryController@index')->name('categories.index');
Route::get('/categories/{slug}', 'CategoryController@show')->name('categories.show');

Route::prefix('posts')
	->group(function(){
		Route::get('/', 'PostController@index')->name('posts.index');
		Route::get('/{slug}', 'PostController@show')->name('posts.show');
	});

Auth::routes();

Route::prefix('admin')
	->name('admin.')
	->namespace('Admin')
	->middleware('auth')
	->group(function () {
	Route::get('/', 'HomeController@index')->name('admin_homepage');
	Route::resource('/posts', 'PostController');
});

/* Route::get('/admin', 'HomeController@index')->name('admin_homepage')->middleware('auth'); */
