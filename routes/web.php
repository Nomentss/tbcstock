<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SaisonController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\CouleurController;
use App\Http\Controllers\PayementController;
use App\Http\Controllers\LivraisonController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ExpeditionController;
use App\Http\Controllers\ImportController;


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
        Route::get('/artisan', 'ArtisanFormulaire')->name('ArtisanController.ArtisanFormulaire');
        Route::get('/artisan_delete/{id}', 'deleteArtisan')->name('ArtisanController.delete');
    });
    
    Route::controller(SaisonController::class)->group(function () {
        Route::get('/saison', 'saisonFormulaire')->name('SaisonController.saisonFormulaire');
        Route::get('/saison_delete/{id}', 'deleteSaison')->name('SaisonController.delete');
    
    });  
    
    Route::controller(CouleurController::class)->group(function () {
        Route::get('/insert_couleur', 'insert')->name('CouleurController.insert');
        Route::get('/couleur', 'CouleurFormulaire')->name('CouleurController.couleurFormulaire');
        Route::get('/couleur_delete/{id}', 'deleteCouleur')->name('CouleurController.delete');
    
    });
    
    Route::controller(PayementController::class)->group(function () {
        Route::get('/payementForm', 'payementFormulaire')->name('PayementController.payementFormulaire');
        Route::post('/insert_payement', 'insertPayement')->name('PayementController.insert');
    // -------------------------------------- consulter Payment -------------------------------------
        Route::get('/payementListe', 'payementListe')->name('PayementController.payementListe');
        Route::get('/payementDetail/{id_artisan}/{id_saison}', 'DetailPayement')->name('PayementController.payementDetail');
    });
    
    Route::controller(LivraisonController::class)->group(function () {
        Route::get('/livraisonForm', 'livraisonFormulaire')->name('LivraisonController.livraisonFormulaire');
        Route::post('/insert_livraison', 'insertLivraison')->name('LivraisonController.insert');
        Route::get('/get-produits/{id_artisan}', 'getProduits')->name('LivraisonController.getProduits');
        Route::get('/get-couleurs/{id_produit}', 'getCouleurs')->name('LivraisonController.getCouleurs');
        Route::get('/listeLivraison', 'listeLivraison')->name('LivraisonController.listeLivraison');
        Route::get('/listeDetail/{id_artisan}/{id_saison}', 'detailLivraison')->name('LivraisonController.detailLivraison');
        Route::get('/listeDetail/filtre', 'filtreLivraison')->name('LivraisonController.filtreLivraison');
        Route::get('/listeResumeLivraison/{id_artisan}/{id_saison}', 'resumeLivraison')->name('LivraisonController.resumeLivraison');
        Route::get('/listeDetail/filtreResume', 'filtreResumeLivraison')->name('LivraisonController.filtreResumeLivraison');
        
    });  
    
    Route::controller(CommandeController::class)->group(function () {
        Route::get('/commandeForm', 'CommandeFormulaire')->name('CommandeController.CommandeFormulaire');
        Route::post('/insert_commande', 'insertCommande')->name('CommandeController.insertCommande');
        Route::get('/liste_commande', 'listeCommande')->name('CommandeController.listeCommande');
        Route::get('/detail_commande/{id_artisan}/{id_saison}', 'detailCommande')->name('CommandeController.detailCommande');
        Route::get('/filtre_commande', 'filtreCommande')->name('CommandeController.filtreCommande');
    });  
    
    Route::controller(StockController::class)->group(function () {
        Route::get('/stock', 'StockGlobale')->name('StockController.stockGlobale');
        Route::get('/stock_filtre_globale', 'StockGlobale')->name('StockController.filtreStockGlobale');
        Route::get('/choixStock', 'choixStock')->name('StockController.choix');
        Route::get('/stock_produit_couleur', 'stockProduitCouleur')->name('StockController.stockProduitCouleur');
        Route::get('/stock_resume', 'stockResume')->name('StockController.stockResume');
        Route::get('/stock_filtre_pc', 'StockProduitCouleur')->name('StockController.filtreStockProduitCouleur');
        Route::get('/stock_filtre_resume', 'StockResume')->name('StockController.filtreStockResume');
    
    });  
    
    Route::controller(ExpeditionController::class)->group(function () {
        Route::get('/formulaire_expedition', 'ExpeditionForm')->name('ExpeditionController.ExpeditionForm');
        Route::get('/get-artisans/{catalogue}', 'getArtisans')->name('ExpeditionController.getArtisans');
        Route::get('/get-produits/{artisan_id}/{catalogue}', 'getProduits')->name('ExpeditionController.getProduits');
        Route::get('/get-couleurs/{artisan_id}/{produit_id}', 'getCouleurs')->name('ExpeditionController.getCouleurs');
        Route::post('/insert_expedition', 'insertExpedition')->name('ExpeditionController.insertExpedition');
        Route::get('/liste_expedition', 'listeExpedition')->name('ExpeditionController.listeExpedition');
        Route::get('/filtre_expedition', 'listeExpedition')->name('ExpeditionController.filtreExpedition');
    });

        // controlleur pour login
        Route::controller(AuthController::class)->group(function () {
            Route::get('/RegisterAction', 'registerAction')->name('AuthController.registerAction');
            Route::get('/Logout', 'logout')->name('AuthController.logout');
        });
});

Route::controller(PageController::class)->group(function () {
    Route::get('/login', 'loginform')->name('PageController.loginform');
    Route::get('/import', 'importPage')->name('PageController.importPage');
});

Route::controller(ImportController::class)->group(function () {
    Route::post('/importAction', 'importAction')->name('ImportController.importAction');
});

// // controlleur pour login
Route::controller(AuthController::class)->group(function () {
    Route::get('/LoginAction', 'LoginAction')->name('AuthController.LoginAction');
});