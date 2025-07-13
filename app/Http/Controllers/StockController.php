<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Artisan;
use App\Models\Produit;

class StockController extends Controller
{
    public function stockGlobale (Request $request){
        $recherche = $request->input('recherche');

        $query = DB::table('v_stock');

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

        if($request->filled('artisan_select')){
            $artisanSelect = $request->input('artisan_select');
            $query->whereRaw('UPPER(artisan) = UPPER(?)', [$artisanSelect]);
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

        return view('Stock.stock', compact('stock','artisan', 'designationCatalogue'));
    }

    public function stockProduitCouleur(Request $request) {

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

        if($request->filled('artisan_select')){
            $artisanSelect = $request->input('artisan_select');
            $query->whereRaw('UPPER(artisan) = UPPER(?)', [$artisanSelect]);
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

        return view('Stock.stock_produit_couleur', compact('stock','artisan', 'designationCatalogue'));
    }

    public function stockResume(Request $request) {

        $recherche = $request->input('recherche');


        $query = DB::table('v_resume_stock');

        if ($request->filled('recherche')) {
            $query->where(function ($q) use ($recherche) {
                $pattern = "%{$recherche}%";         // le pattern reste bindé, donc protégé
            
                $q->whereRaw('UPPER(designation_catalogue) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('CAST(sum AS TEXT) LIKE ?', [$pattern]);
            });

        }

        if($request->filled('catalogue_select')){
            $catalogueSelect = $request->input('catalogue_select');
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogueSelect]);
        }

        $total= $query->paginate(50);

        $designationCatalogue = Produit::select('designation_catalogue')
                                ->GroupBy('designation_catalogue')
                                ->get();

        return view('Stock.stock_resume', compact('total', 'designationCatalogue'));
    }

    public function choixStock(){
        return view ('Stock.accueil');
    } 
}