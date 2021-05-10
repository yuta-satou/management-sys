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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/','ManagementController@index')->name('managements');
    Route::get('/management/create', 'ManagementController@create')->name('create');
    Route::post('/management/store', 'ManagementController@store')->name('store');
    Route::get('/management/edit/{id}', 'ManagementController@edit')->name('edit');
    Route::post('/management/update', 'ManagementController@update')->name('update');
    Route::get('/management/{id}', 'ManagementController@show')->name('show');
    Route::get('/management/destroy/{id}', 'ManagementController@destroy');
    Route::get('/management/index/{keyword}/{search_id}/{min_price}/{max_price}/{min_stock}/{max_stock}','ManagementController@getKeyword');
    Route::get('/management/sort/{get_sort}/{sort_list}','ManagementController@getSort');
    Route::post('/management/pay/{id}','ManagementController@pay');
});
Auth::routes();
