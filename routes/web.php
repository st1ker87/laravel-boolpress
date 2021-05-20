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

/* Route::get('/posts', 'PostController@index')->name('posts.index');
Route::get('/posts/{slug}', 'PostController@show')->name('posts.show'); */

Route::prefix('posts')
	->group(function(){
		Route::get('/', 'PostController@index')->name('posts.index');
		Route::get('/{slug}', 'PostController@show')->name('posts.show');
	});

Auth::routes();

Route::prefix('admin')
	->namespace('Admin')
	->middleware('auth')
	->group(function () {
	Route::get('/', 'HomeController@index')
	->name('home');
});

/* Route::get('/admin', 'HomeController@index')->name('admin_homepage')->middleware('auth'); */
