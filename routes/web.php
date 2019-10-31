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
Route::get('/User/Logout', "UserController@logout");
Route::get("User/changePassword","UserController@changePassword");



Route::get("/Main/{year?}/{month?}/{day?}/{next?}","MainController@Main");

Route::get("/Produkt/Add","AddDrugsController@AddDrugs");
Route::get("/Produkt/Edit","EditDrugsController@EditDrugs");
Route::get("/Produkt/editGroup","EditDrugsController@EditGroup");
Route::get("/Produkt/editSubstance","EditDrugsController@EditSubstance");
Route::get("/Produkt/editProduct","EditDrugsController@EditProduct");

Route::get("/ajax/addGroup","AddDrugsController@addGroupAction");
Route::get("/ajax/addSubstances","AddDrugsController@addSubstancesAction");
Route::get("/ajax/addProduct","AddDrugsController@addProductAction");



Route::post("/ajax/addDrugs","MainDrugsController@addDrugsAction");
Route::post("/ajax/addDescriptions","MainDrugsController@addDescriptionsAction");
Route::get("/ajax/show_description_submit","MainDrugsController@showDescriptionsAction");
Route::get("/ajax/delete_drugs","MainDrugsController@deleteDrugs");
Route::get("/ajax/sum_average","MainDrugsController@sumAverage");
Route::get("/ajax/sum_average2","MainDrugsController@sumAverage2");
Route::get("/ajax/sum_benzo","MainDrugsController@sumBenzo");
Route::get("/ajax/changeGroup","EditDrugsController@changeGroup");
Route::get("/ajax/changeSubstance","EditDrugsController@changeSubstance");
Route::get("/ajax/changeProduct","EditDrugsController@changeProduct");
Route::get("/ajax/edit_drugs","MainDrugsController@editRegistration");
Route::get("/ajax/update_drugs","MainDrugsController@updateRegistration");
Route::get("/ajax/show_update_drugs","MainDrugsController@updateShowRegistration");
Route::get("/ajax/closeForm","MainDrugsController@closeForm");

Route::get("/Produkt/Search","SearchController@searchMain");
Route::get("/Produkt/searchAction","SearchController@searchAction");
Route::get("/Produkt/selectDrugs","SearchController@selectDrugs");