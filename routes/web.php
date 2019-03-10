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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', '\Mybakery\Http\Controllers\Auth\LoginController@logout')->name("logout");
Route::group(['middleware' => 'auth'], function() {
    Route::post('/resendotp', 'TransferController@resendotp')->name("transfer.resendotp");
    Route::post('/confirmotp', 'TransferController@confirmotp')->name("transfer.confirmotp");
    Route::get('/balance', 'TransferRecipientController@getbalance')->name("getbalance");
    Route::get('/resolveaccount', 'TransferRecipientController@resolveaccount')->name('resolveaccount');
    Route::get('/transferrecipientlist', 'TransferRecipientController@getDataList')->name("gettransferrecipientlist");
    Route::get('/transferlist', 'TransferController@getDataList')->name("gettransferlist");
    Route::resource('/transferrecipient', 'TransferRecipientController');
    Route::resource('/transfer', 'TransferController');

});
