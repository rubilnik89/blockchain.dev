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
    return view('welcome');
});

Route::get('/blockchain', 'MusicController@blockchain')->name('blockchain');
Route::get('/get_address', 'MusicController@get_address')->name('get_address');
Route::get('/fill_addresses', 'MusicController@fill_addresses')->name('fill_addresses');
Route::get('/webhook', 'MusicController@webhook')->name('webhook');
