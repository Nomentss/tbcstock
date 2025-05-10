<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;

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
    return view('auth.login');
});

Route::controller(PageController::class)->group(function () {
    Route::get('/Acceuil', 'home')->name('PageController.home');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/home', function () {
    return view('Layout.app');
});

// controlleur pour login
Route::controller(AuthController::class)->group(function () {
    Route::get('/LoginAction', 'LoginAction')->name('AuthController.LoginAction');
});