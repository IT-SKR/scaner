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

Route::get('/', function () {
    return view('welcome');
});

/*
 * 小程序登陆
 * */
Route::get('/sxc/login', 'UserController@scx_login');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
