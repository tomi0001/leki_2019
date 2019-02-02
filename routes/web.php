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

Route::get('/', "MainController@Main");
Route::get('/User/Register', "UserController@register");
Route::post('/User/RegisterSubmit', "UserController@registerSubmit");
Route::get('/User/Login', "UserController@login");
Route::post('/User/Login_action', "UserController@loginAction");

Route::get("/Main/{year?}/{month?}/{day?}/{next?}","MainController@Main");

Route::get("/Produkt/Add","AddDrugsController@AddDrugs");

Route::get("/ajax/addGroup","AddDrugsController@addGroupAction");
Route::get("/ajax/addSubstances","AddDrugsController@addSubstancesAction");
Route::get("/ajax/addProduct","AddDrugsController@addProductAction");

Route::get("/ajax/addDrugs","MainDrugsController@addDrugsAction");
