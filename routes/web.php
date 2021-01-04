<?php

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

Route::get('/chats', 'MessageController@index');

Route::post('/send/message', 'MessageController@sendMessage');

Route::get('/getImages', 'GameController@index');


Route::post('/sendGuess', 'GameController@sendGuess');

Route::get('/checkIfDataInSession', 'GameController@checkIfDataNeededInSession');


Route::get('/getDataInSession', 'GameController@getDataInSession');


Route::post('/correctGuess', 'GameController@correctGuess');


Route::get('/gameScores', 'GameController@getScores');
