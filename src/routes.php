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

Route::namespace('Yashiroiori\AclManagerTabler\Http\Controllers')->prefix('admin')->middleware('web','auth','verified')->group(function() {
    Route::prefix('aclmanager')->group(function(){
        // Role routes
        Route::get('role','RoleController@index')->name('role.index');
        Route::get('role/create','RoleController@create')->name('role.create');
        Route::post('role','RoleController@store')->name('role.store');
        Route::get('role/{role}/edit','RoleController@edit')->name('role.edit');
        Route::put('role/{role}','RoleController@update')->name('role.update');
        Route::get('role/{role}/show','RoleController@show')->name('role.show');
    
        Route::patch('role/{role}','RoleController@restore')->name('role.restore');
        Route::post('role/{role}/delete','RoleController@destroy')->name('role.destroy');
        Route::post('role/{role}/archive','RoleController@archive')->name('role.archive');
        Route::get('role/list','RoleController@list')->name('role.list');
    
        Route::post('role/batch/action','RoleController@batchAction')->name('role.batch_action');

        // User routes
        Route::get('user','UserController@index')->name('user.index');
        Route::get('user/create','UserController@create')->name('user.create');
        Route::post('user','UserController@store')->name('user.store');
        Route::get('user/{user}/edit','UserController@edit')->name('user.edit');
        Route::put('user/{user}','UserController@update')->name('user.update');
        Route::get('user/{user}/show','UserController@show')->name('user.show');
    
        Route::patch('user/{user}','UserController@restore')->name('user.restore');
        Route::post('user/{user}/delete','UserController@destroy')->name('user.destroy');
        Route::post('user/{user}/archive','UserController@archive')->name('user.archive');
        Route::get('user/list','UserController@list')->name('user.list');
    
        Route::post('user/batch/action','UserController@batchAction')->name('user.batch_action');
    });
});
