<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::delete('/pdf/delete/{id}', 'PdfController@delete');
Route::get('/pdf/all', 'PdfController@all');
Route::get('/pdf/download/{id}', 'PdfController@download');
Route::post('/pdf/add', 'PdfController@add');

Route::delete('/html/delete/{id}', 'HtmlController@delete');
Route::get('/html/all', 'HtmlController@all');
Route::post('/html/add', 'HtmlController@add');
Route::put('/html/update/{id}', 'HtmlController@update');

Route::delete('/link/delete/{id}', 'LinkController@delete');
Route::get('/link/all', 'LinkController@all');
Route::post('/link/add', 'LinkController@add');
Route::put('/link/update/{id}', 'LinkController@update');