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
Route::get('/reservation', 'HomeController@reservation')->name('reservation');
Route::post('/reserve', 'HomeController@reserve')->name('reserve');


Route::namespace('Admin')->prefix('admin')->as('admin.')->group(function() {
    Auth::routes(['register' => false]);
    Route::get('/home', 'AdminController@index')->name('home');

    Route::get('/create/casher', 'AdminController@createCasher')->name('create.casher');
    Route::post('/store/casher', 'AdminController@storeCasher')->name('store.casher');
    Route::get('/cashers', 'AdminController@cashers')->name('cashers');
    Route::get('/casher/{casher}/edit', 'AdminController@editCasher')->name('edit.casher');
    Route::post('/casher/{casher}/update', 'AdminController@updateCasher')->name('update.casher');
    Route::post('/casher/delete', 'AdminController@deleteCasher')->name('delete.casher');

    Route::get('/create/category', 'AdminController@createCategory')->name('create.category');
    Route::post('/store/category', 'AdminController@storeCategory')->name('store.category');
    Route::get('/categories', 'AdminController@categories')->name('categories');
    Route::get('/category/{category}/edit', 'AdminController@editCategory')->name('edit.category');
    Route::post('/category/{category}/update', 'AdminController@updateCategory')->name('update.category');
    Route::post('/category/delete', 'AdminController@deleteCategory')->name('delete.category');

    Route::get('/create/meal', 'AdminController@createMeal')->name('create.meal');
    Route::post('/store/meal', 'AdminController@storeMeal')->name('store.meal');
    Route::get('/meals', 'AdminController@meals')->name('meals');
    Route::get('/meal/{meal}/edit', 'AdminController@editMeal')->name('edit.meal');
    Route::post('/meal/{meal}/update', 'AdminController@updateMeal')->name('update.meal');
    Route::post('/meal/delete', 'AdminController@deleteMeal')->name('delete.meal');

    Route::get('/reservations', 'AdminController@reservations')->name('reservations');
    Route::get('/reservation/{reservation}/edit', 'AdminController@editReservation')->name('edit.reservation');
    Route::post('/reservation/{reservation}/update', 'AdminController@updateReservation')->name('update.reservation');
    Route::post('/reservation/delete', 'AdminController@deleteReservation')->name('delete.reservation');


});

Route::namespace('Casher')->prefix('casher')->as('casher.')->group(function() {
    Auth::routes(['register' => false]);
    Route::get('/home', 'CasherController@index')->name('home');

    Route::get('/reservations', 'CasherController@reservations')->name('reservations');
    Route::get('/create/reservation', 'CasherController@createReservation')->name('create.reservation');
    Route::get('/reservation/{reservation}/edit', 'CasherController@editReservation')->name('edit.reservation');
    Route::post('/reservation/{reservation}/update', 'CasherController@updateReservation')->name('update.reservation');
    Route::post('/reservation/delete', 'CasherController@deleteReservation')->name('delete.reservation');
});

