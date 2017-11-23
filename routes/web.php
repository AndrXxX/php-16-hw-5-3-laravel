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
    return redirect()->route('contacts');
});
Route::get('contacts', 'ContactsController@index')->name('contacts');
Route::get('contacts/{id}/delete', 'ContactsController@delete')->name('deleteContact');
Route::get('contacts/{id}/edit', 'ContactsController@edit')->name('editContact');
Route::resource('contacts', 'ContactsController', ['except' => ['index', 'create', 'update', 'destroy']]);
