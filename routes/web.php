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
use App\Http\Controllers\ArticleRetourController;
use App\Http\Controllers\ClientController;


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
        Route::put('/artisan_update/{id}', 'updateArtisan')->name('ArtisanController.updateArtisan');
    });
    
    Route::controller(SaisonController::class)->group(function () {
        Route::get('/saison', 'saisonFormulaire')->name('SaisonController.saisonFormulaire');
        Route::get('/saison_delete/{id}', 'deleteSaison')->name('SaisonController.delete');
        Route::put('/saison_update/{id}', 'updateSaison')->name('SaisonController.updateSaison');
    
    });  
    
    Route::controller(CouleurController::class)->group(function () {
        Route::get('/insert_couleur', 'insert')->name('CouleurController.insert');
        Route::get('/couleur', 'CouleurFormulaire')->name('CouleurController.couleurFormulaire');
        Route::get('/couleur_delete/{id}', 'deleteCouleur')->name('CouleurController.delete');
        Route::put('/couleur_update/{id}', 'updateCouleur')->name('CouleurController.update');
    });
    
    Route::controller(PayementController::class)->group(function () {
        Route::get('/payementForm', 'payementFormulaire')->name('PayementController.payementFormulaire');
        Route::post('/insert_payement', 'insertPayement')->name('PayementController.insert');
    // -------------------------------------- consulter Payment -------------------------------------
        Route::get('/payementListe', 'payementListe')->name('PayementController.payementListe');
        Route::get('/payementDetail/{id_artisan}/{id_saison}', 'DetailPayement')->name('PayementController.payementDetail');
        Route::get('/payement/pdf', 'exportPdfPayement')->name('PayementController.export');
    });
    
    Route::controller(LivraisonController::class)->group(function () {
        Route::get('/livraisonForm', 'livraisonFormulaire')->name('LivraisonController.livraisonFormulaire');
        Route::post('/insert_livraison', 'insertLivraison')->name('LivraisonController.insert');
        Route::get('/get-produits/{id_artisan}/{id_saison}', 'getProduits')->name('LivraisonController.getProduits');
        Route::get('/get-catalogues/{id_produit}', 'getCatalogues')->name('LivraisonController.getCatalogues');
        Route::get('/get-couleurs/{id_produit}', 'getCouleurs')->name('LivraisonController.getCouleurs');
        Route::get('/listeLivraison', 'listeLivraison')->name('LivraisonController.listeLivraison');
        Route::get('/listeDetail/{id_artisan}/{id_saison}/{id_client}', 'detailLivraison')->name('LivraisonController.detailLivraison');
        Route::get('/listeDetail/filtre', 'filtreLivraison')->name('LivraisonController.filtreLivraison');
        Route::get('/listeResumeLivraison/{id_artisan}/{id_saison}/{id_client}', 'resumeLivraison')->name('LivraisonController.resumeLivraison');
        Route::get('/listeDetail/filtreResume', 'filtreResumeLivraison')->name('LivraisonController.filtreResumeLivraison');
        Route::get('/livraison/pdfDetail', 'exportPdfDetail')->name('LivraisonController.exportDetail');
        Route::get('/livraison/pdfResume', 'exportPdfResume')->name('LivraisonController.exportResume');
        Route::get('/livraisonChoix', 'livraisonChoix')->name('LivraisonController.livraisonChoix');
        Route::get('/livraisonSansCommandeForm', 'livraisonSansCommandeFormulaire')->name('LivraisonController.livraisonSansCommandeFormulaire');
        Route::get('/livraison/excelDetail', 'exportExcel')->name('LivraisonController.exportDetailExcel');
    });  
    
    Route::controller(CommandeController::class)->group(function () {
        Route::get('/commandeForm', 'CommandeFormulaire')->name('CommandeController.CommandeFormulaire');
        Route::post('/insert_commande', 'insertCommande')->name('CommandeController.insertCommande');
        Route::get('/liste_commande', 'listeCommande')->name('CommandeController.listeCommande');
        Route::get('/detail_commande/{id_artisan}/{id_saison}/{id_client}', 'detailCommande')->name('CommandeController.detailCommande');
        Route::get('/filtre_commande', 'filtreCommande')->name('CommandeController.filtreCommande');
        Route::get('/commande/pdf', 'exportPdf')->name('CommandeController.export');
        Route::get('/commandeModifier/{id_artisan}/{id_saison}/{id_client}', 'modifierListe')->name('CommandeController.modifierListe');
        Route::PUT('/update-commande/{id}', 'update')->name('CommandeController.update');
        Route::DELETE('/delete-commande/{id}', 'delete')->name('CommandeController.delete');
        Route::get('/commande/excel', 'exportExcel')->name('CommandeController.exportExcel');
    });  
    
    Route::controller(StockController::class)->group(function () {
        Route::get('/stock', 'StockGlobale')->name('StockController.stockGlobale');
        Route::get('/stock_filtre_globale', 'StockGlobale')->name('StockController.filtreStockGlobale');
        Route::get('/choixStock', 'choixStock')->name('StockController.choix');
        Route::get('/stock_produit_couleur', 'stockProduitCouleur')->name('StockController.stockProduitCouleur');
        Route::get('/stock_resume', 'stockResume')->name('StockController.stockResume');
        Route::get('/stock_filtre_pc', 'StockProduitCouleur')->name('StockController.filtreStockProduitCouleur');
        Route::get('/stock_filtre_resume', 'StockResume')->name('StockController.filtreStockResume');
        Route::get('/stock/pdf_globale', 'exportPdfGlobale')->name('StockController.exportGlobale');
        Route::get('/stock/pdf_regroupe', 'exportPdfRegroupe')->name('StockController.exportRegroupe');
        Route::get('/stock/pdf_regroupe_couleur', 'exportPdfRegroupeCouleur')->name('StockController.exportRegroupeCouleur');
        Route::get('/stock/excelGlobale', 'exportExcelGlobale')->name('StockController.exportGlobaleExcel');

    });  
    
    Route::controller(ExpeditionController::class)->group(function () {
        Route::get('/formulaire_expedition', 'ExpeditionForm')->name('ExpeditionController.ExpeditionForm');
        Route::get('/get-artisans/{catalogue}/{saison_id}', 'getArtisans')->name('ExpeditionController.getArtisans');
        Route::get('/get-produits/{artisan_id}/{catalogue}/{saison_id}', 'getProduits')->name('ExpeditionController.getProduits');
        Route::get('/get-couleurs/{artisan_id}/{produit_id}/{saison_id}', 'getCouleurs')->name('ExpeditionController.getCouleurs');
        Route::get('/get-catalogue/{saison_id}', 'getCatalogue')->name('ExpeditionController.getCatalogue');
        Route::post('/insert_expedition', 'insertExpedition')->name('ExpeditionController.insertExpedition');
        Route::get('/liste_expedition', 'listeExpedition')->name('ExpeditionController.listeExpedition');
        Route::get('/expedition/pdf', 'exportPdf')->name('ExpeditionController.export');

    });

    Route::controller(ArticleRetourController::class)->group(function () {
        Route::get('/articleRetourForm', 'ArticleRetourFormulaire')->name('ArticleRetourController.articleRetourFormulaire');
        Route::get('/get-produits-stock/{id_artisan}/{id_saison}', 'getProduits')->name('ArticleRetourController.getProduits');
        Route::get('/get-catalogues-stock/{id_produit}', 'getCatalogues')->name('ArticleRetourController.getCatalogues');
        Route::get('/get-couleurs-stock/{id_produit}', 'getCouleurs')->name('ArticleRetourController.getCouleurs');
        Route::post('/insertArticleRetour', 'insertArticleRetour')->name('ArticleRetourController.insertArticleRetour');
        Route::get('/listeArticleRetour', 'listeArticleRetour')->name('ArticleRetourController.listeArticleRetour');
        Route::get('/listeArticleRetourDetail/{id_artisan}/{id_saison}/{id_client}', 'detailArticleRetour')->name('ArticleRetourController.detailArticleRetour');
        Route::get('/ArticleRetour/pdf', 'exportPdf')->name('ArticleRetourController.exportPdf');
        Route::get('/filtre', 'filtre')->name('ArticleRetourController.filtre');

    });

    Route::controller(ClientController::class)->group(function () {
        Route::post('/client/select', 'select')->name('ClientController.select');
        Route::get('/client/Formulaire', 'clientFormulaire')->name('ClientController.clientFormulaire');
        Route::get('/client/Delete/{id}', 'deleteClient')->name('ClientController.deleteClient');
        Route::get('/client/Insert', 'insertClient')->name('ClientController.insertClient');
        Route::get('/client/data', 'getClient')->name('clientController.getClient');
        Route::put('/client/Update/{id}', 'updateClient')->name('ClientController.updateClient');
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
    Route::post('/importLivraison', 'importLivraison')->name('ImportController.importLivraison');
});

// // controlleur pour login
Route::controller(AuthController::class)->group(function () {
    Route::get('/LoginAction', 'LoginAction')->name('AuthController.LoginAction');
});


