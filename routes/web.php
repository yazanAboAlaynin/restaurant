<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::namespace('Admin')->prefix('admin')->as('admin.')->group(function() {
    Auth::routes(['register' => false]);
    Route::get('/home', 'AdminController@index')->name('home');

    Route::get('/create/casher', 'AdminController@createCasher')->name('create.casher');
    Route::post('/store/casher', 'AdminController@storeCasher')->name('store.casher');
    Route::get('/cashers', 'AdminController@cashers')->name('cashers');
    Route::get('/casher/{casher}/edit', 'AdminController@editCasher')->name('edit.casher');
    Route::post('/casher/{casher}/update', 'AdminController@updateCasher')->name('update.casher');
    Route::post('/casher/delete', 'AdminController@deleteCasher')->name('delete.casher');
});
