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

/*Route::get('/', function () {
    return view('list');
});
*/

Route::pattern('id', '[0-9]+');
Route::get('delete/{id}', 'ContactsController@delete');
Route::get('edit/{id}', 'ContactsController@showEdit');
Route::get('list/', 'ContactsController@list');
Route::resource('/', 'ContactsController', ['only' => ['index', 'store', 'show']]);