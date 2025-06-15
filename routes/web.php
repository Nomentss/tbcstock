<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SaisonController;
use App\Http\Controllers\ArtisanController;


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

    Route::controller(SaisonController::class)->group(function () {
        Route::get('/insert_saison', 'insert')->name('SaisonController.insert');
       
    });

    Route::controller(ArtisanController::class)->group(function () {
        Route::get('/insert_artisan', 'insert')->name('ArtisanController.insert');
       
    });
});

Route::controller(PageController::class)->group(function () {
    Route::get('/login', 'loginform')->name('PageController.loginform');
    Route::get('/saison', 'saisonFormulaire')->name('PageController.saisonFormulaire');
    Route::get('/couleur', 'couleurFormulaire')->name('PageController.couleurFormulaire');
    Route::get('/livraisonForm', 'livraisonFormulaire')->name('PageController.livraisonFormulaire');
    Route::get('/payementForm', 'payementFormulaire')->name('PageController.payementFormulaire');

});

// controlleur pour login
Route::controller(AuthController::class)->group(function () {
    Route::get('/LoginAction', 'LoginAction')->name('AuthController.LoginAction');
    Route::get('/RegisterAction', 'registerAction')->name('AuthController.registerAction');
    Route::get('/Logout', 'logout')->name('AuthController.logout');
});

Route::controller(ArtisanController::class)->group(function () {
    Route::get('/artisan', 'ArtisanFormulaire')->name('ArtisanController.ArtisanFormulaire');
    Route::get('/artisan_delete/{id}', 'deleteArtisan')->name('ArtisanController.delete');
});