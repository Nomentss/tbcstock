<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Artisan;
use App\Models\Produit;
use App\Models\Saison;
use Barryvdh\DomPDF\Facade\Pdf;


class StockController extends Controller
{
    public function stockGlobale (Request $request){
        $selectionSaison = "";
        $recherche = $request->input('recherche');

        $query = DB::table('v_stock_commande');

        $query2 = DB::table('v_stock_commande')
                  ->selectRaw('SUM(qte_livre) as qte, SUM(qte_livre*prix_unitaire) as total');

        if ($request->filled('recherche')) {
            $query->where(function ($q) use ($recherche) {
                $pattern = "%{$recherche}%";         // le pattern reste bindé, donc protégé
            
                $q->whereRaw('UPPER(artisan) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(designation_produit) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(designation_catalogue) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(couleur) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('CAST(qte_livre AS TEXT) LIKE ?', [$pattern]);
            }); 
        }

        if($request->filled('saison_select')){
            $saisonSelect = $request->input('saison_select');
            $selectionSaison = $saisonSelect;
            
            $query->where('id_saison', $saisonSelect);

            $query2->where('id_saison', $saisonSelect);
        }

        if($request->filled('artisan_select')){
            $artisanSelect = $request->input('artisan_select');
            $query->where('id_artisan', [$artisanSelect]);

            $query2->where('id_artisan', $artisanSelect);

        }

        if($request->filled('catalogue_select')){
            $catalogueSelect = $request->input('catalogue_select');
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogueSelect]);
            
            $query2->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogueSelect]);
        }

        $stock= $query->paginate(50);

        $artisan = Artisan::all();
        $designationCatalogue = Produit::select('designation_catalogue')
                                ->GroupBy('designation_catalogue')
                                ->get();
                                
        $saison = Saison::all();
        
        $total= $query2->get();

        return view('Stock.stock', compact('stock','artisan', 'designationCatalogue','saison','selectionSaison','total'));
    }

    public function stockProduitCouleur(Request $request) {
        $selectionSaison = "";
        $recherche = $request->input('recherche');

        $query = DB::table('v_regroupement_stock');

        if ($request->filled('recherche')) {
            $query->where(function ($q) use ($recherche) {
                $pattern = "%{$recherche}%";         // le pattern reste bindé, donc protégé
            
                $q->whereRaw('UPPER(artisan) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(designation_produit) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(designation_catalogue) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('CAST(nb_couleur AS TEXT) LIKE ?', [$pattern])
                    ->orWhereRaw('CAST(qte_livre AS TEXT) LIKE ?', [$pattern]);
            }); 
        }

        if($request->filled('saison_select')){
            $saisonSelect = $request->input('saison_select');
            $selectionSaison = $saisonSelect; 
            $query->where('id_saison', $saisonSelect);
        }

        if($request->filled('artisan_select')){
            $artisanSelect = $request->input('artisan_select');
            $query->where('id_artisan', $artisanSelect);
        }

        if($request->filled('catalogue_select')){
            $catalogueSelect = $request->input('catalogue_select');
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogueSelect]);
        }

        $stock= $query->paginate(50);

        $artisan = Artisan::all();
        $designationCatalogue = Produit::select('designation_catalogue')
                                ->GroupBy('designation_catalogue')
                                ->get(); 

        $saison = Saison::all();
        return view('Stock.stock_produit_couleur', compact('stock','artisan', 'designationCatalogue', 'saison', 'selectionSaison'));
    }

    public function stockResume(Request $request) {
        $selectionSaison = "";
        $recherche = $request->input('recherche');


        $query = DB::table('v_resume_stock');

        if ($request->filled('recherche')) {
            $query->where(function ($q) use ($recherche) {
                $pattern = "%{$recherche}%";         // le pattern reste bindé, donc protégé
            
                $q->whereRaw('UPPER(designation_catalogue) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('CAST(sum AS TEXT) LIKE ?', [$pattern]);
            });

        }

        if($request->filled('saison_select')){
            $saisonSelect = $request->input('saison_select');
            $selectionSaison = $saisonSelect; 
            $query->where('id_saison', $saisonSelect);
        }

        if($request->filled('catalogue_select')){
            $catalogueSelect = $request->input('catalogue_select');
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogueSelect]);
        }

        $total= $query->paginate(50);

        $designationCatalogue = Produit::select('designation_catalogue')
                                ->GroupBy('designation_catalogue')
                                ->get();

        $saison = Saison::all();

        return view('Stock.stock_resume', compact('total', 'designationCatalogue', 'saison', 'selectionSaison'));
    }

    public function choixStock(){
        return view ('Stock.accueil');
    }

    public function exportPdfRegroupeCouleur(Request $request){
        $saison_select = $request->input('saison_select');
        $artisan_select=  $request->input('artisan_select'); 
        $catalogue_select=  $request->input('catalogue_select');

        $query = DB::table('v_regroupement_stock');

        if($saison_select){
            $query->where('id_saison', $saison_select);
        }

        if($artisan_select){
            $query->where('id_artisan', $artisan_select);
        }

        if($catalogue_select){
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogue_select]);
        }

        $stock = $query->get();

        $pdf = Pdf::loadView('PDF.stock_couleur', compact('stock'));
        return $pdf->download('stock_regroupe_couleur.pdf');
    }
    
    public function exportPdfGlobale(Request $request){
        $saison_select = $request->input('saison_select');
        $artisan_select=  $request->input('artisan_select'); 
        $catalogue_select=  $request->input('catalogue_select');

        $query = DB::table('v_stock_commande');

        $query2 = DB::table('v_stock_commande')
                  ->selectRaw('SUM(qte_livre) as qte, SUM(qte_livre*prix_unitaire) as total');

        if($saison_select){
            $query->where('id_saison', $saison_select);
            $query2->where('id_saison', $saison_select);
        }

        if($artisan_select){
            $query->where('id_artisan', $artisan_select);
            $query2->where('id_artisan', $artisan_select);
        }

        if($catalogue_select){
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogue_select]);
            $query2->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogue_select]);

        }

        $stock = $query->get();

        $total = $query2->get();

        $pdf = Pdf::loadView('PDF.stock_globale', compact('stock', 'total'));
        return $pdf->download('stock_globale.pdf');
    }

    public function exportPdfRegroupe(Request $request){
        $saison_select = $request->input('saison_select');
        $catalogue_select=  $request->input('catalogue_select');

        $query = DB::table('v_resume_stock');

        if($saison_select){
            $query->where('id_saison', $saison_select);
        }
        
        if($catalogue_select){
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogue_select]);
        }

        $total = $query->get();

        $pdf = Pdf::loadView('PDF.stock_resume', compact('total'));
        return $pdf->download('stock_resume.pdf');
    }
}