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
Route::get('/test1',"TestController@test1");  //测试

Route::get('/wx',"WxController@wx"); //微信接入
Route::get('/wx/token',"WxController@getAccessToken"); //获取accesstoken