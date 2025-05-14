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

Route::middleware(['auth', 'role:0'])->group(function () {
    Route::controller(PageController::class)->group(function () {
        Route::get('/Acceuil', 'home')->name('PageController.home');
        // Route::get('/login', 'loginform')->name('PageController.loginform');
        Route::get('/register', 'registerform')->name('PageController.registerform');
    });

});

Route::controller(PageController::class)->group(function () {
    Route::get('/login', 'loginform')->name('PageController.loginform');
    // Route::get('/formulaireSaison', 'saisonFormulaire')->name('PageController.saisonFormulaire');
});

// controlleur pour login
Route::controller(AuthController::class)->group(function () {
    Route::get('/LoginAction', 'LoginAction')->name('AuthController.LoginAction');
    Route::get('/RegisterAction', 'registerAction')->name('AuthController.registerAction');
    Route::get('/Logout', 'logout')->name('AuthController.logout');
});


// esssai formulaire
Route::get('/saison', function () {
    return view('Formulaire.Saison');
});

Route::get('/artisan', function () {
    return view('Formulaire.Artisan');
});

Route::get('/couleur', function () {
    return view('Formulaire.Couleur');
});

Route::get('/payement', function () {
    return view('Formulaire.Payement');
});

Route::get('/livraison', function () {
    return view('Formulaire.Livraison');
});