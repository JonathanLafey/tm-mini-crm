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

// show login page for all unauthenticated users
Route::get('/', function () {
    return view('auth/login');
})->middleware('guest');

// Use Laravel make:auth as default bootstrap, but remove the ability to register
Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::resources([
    'clients' => 'ClientController',
    'transactions' => 'TransactionController'
]);