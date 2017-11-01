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

Route::get('/blockchain', 'BlockchainController@blockchain')->name('blockchain');
Route::get('/get_address', 'BlockchainController@get_address')->name('get_address');
Route::get('/fill_addresses', 'BlockchainController@fill_addresses')->name('fill_addresses');
Route::get('/webhook', 'BlockchainController@webhook')->name('webhook');

//Route::post('/handle_webhook/btc', 'BlockchainController@handle_webhook_btc')->name('handle_webhook_btc');
Route::get('/handle_webhook/btc', 'BlockchainController@handle_webhook_btc')->name('handle_webhook_btc');