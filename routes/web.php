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
Route::get('hello','HomeController@hello');
Route::get('return-book-view/{id}', 'Admin\MemberstatusCrudController@returnBook');
//Route::get('return-book/{id}', 'Admin\MemberstatusCrudController@returnBook');
//Route::get('hello', 'HomeController@hello')->name('hello');
