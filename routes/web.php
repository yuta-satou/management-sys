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

Route::get('/','ManagementController@index')->name('managements')->middleware('auth');
Route::get('/management/create', 'ManagementController@create')->name('create');
Route::post('/management/store', 'ManagementController@store')->name('store');
Route::get('/management/edit/{id}', 'ManagementController@edit')->name('edit');
Route::post('/management/update', 'ManagementController@update')->name('update');
Route::get('/management/{id}', 'ManagementController@show')->name('show');
Route::post('/management/destroy/{id}', 'ManagementController@destroy')->name('destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
