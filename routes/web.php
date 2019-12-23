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
    return view('show_queue');
});

Route::post('admin/queue-list', 'QueueListController@index');
Route::post('admin/get-selected-class', 'QueueListController@index');
Route::post('admin/call-queue-user', 'QueueListController@store');
Route::post('admin/import-queue', 'QueueListController@import');

Route::get('/queue-list', 'QueueListController@getQueueList')->name('queue');
Route::get('admin/get-classes', 'QueueListController@getClasses');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
