<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Saison;
use App\Models\Artisan;
use App\Models\Couleur;
use App\Models\Produit;
use App\Models\Expedition;
use App\Models\Expedition_detail;


class ExpeditionController extends Controller
{
    public function ExpeditionForm(){

        $catalogue = DB::table('v_resume_stock')->get();

        return view('Expedition.FormulaireExpedition',compact('catalogue'));
    }


    public function getArtisans($catalogue){
        $artisans =  DB::table('v_stock')
                    ->select('designation_catalogue', 'id_artisan', 'artisan')
                    ->where('designation_catalogue', $catalogue)
                    ->groupBy('designation_catalogue','id_artisan','artisan')
                    ->get();

        return response()->json($artisans);
    }

    public function getProduits($id_artisan, $catalogue){
        $produits =  DB::table('v_stock')
                    ->select('id_produit','designation_produit', 'designation_catalogue', 'id_artisan', 'artisan')
                    ->where('id_artisan', $id_artisan)
                    ->where('designation_catalogue', $catalogue)
                    ->groupBy('id_produit','designation_produit','id_artisan','artisan', 'designation_catalogue')
                    ->get();

        return response()->json($produits);
    }

    public function getCouleurs($id_artisan, $id_produit){
        $couleur =  DB::table('v_stock')
                    ->select('id_couleur','couleur', 'id_artisan', 'id_produit')
                    ->where('id_artisan', $id_artisan)
                    ->where('id_produit', $id_produit)
                    ->get();

        return response()->json($couleur);
    }

    public function insertExpedition(Request $request){

        $maxCounter = $request->input('maxCounter');
        $date_expedition = $request->input('date_expedition');
        $expedition_depot = $request->input('expedition');
        $catalogue = $request->input('designation_catalogue');


        Validator::make($request->all(), [
            'date_expedition' => 'required',
            'expedition' => 'required',    
            'catalogue' => 'required',    
        ],[
            'date_expedition.required' => 'date expedition requise',
            'expedition.required' => 'lieu expedition requis',
            'catalogue.required' => 'designation catalogue requise',

        ])-> validate();

        for ($i = 0; $i <= $maxCounter; $i++) {
            $rules['id_artisan_'. $i] = 'required';
            $messages['id_artisan_'. $i . '.required'] = 'artisan requis' . ($i + 1) . ' est requis.';

            $rules['id_produit_'. $i] = 'required';
            $messages['id_produit_'. $i . '.required'] = 'produit requis' . ($i + 1) . ' est requis.';
    
            $rules['couleur_'. $i] = 'required';
            $messages['couleur_'. $i . '.required'] = 'couleur requis' . ($i + 1) . ' est requis.';
    
            $rules['qte_' . $i] = 'required|numeric|min:1';
            $messages['qte_' . $i . '.required'] = 'quantité requis.';
            $messages['qte_' . $i . '.min'] = 'La quantité doit être un nombre positif';
            
        }

        $validated = Validator::make($request->all(), $rules, $messages)->validate();  


        $expedition = Expedition::create([
            'expedition' => $expedition_depot,
        ]);

        for ($i=0; $i <= $maxCounter; $i++) { 
            $id_artisan = $request->input('id_artisan_'.$i);
            $id_produit = $request->input('id_produit_'.$i);
            $id_couleur = $request->input('couleur_'.$i);
            $quantite = $request->input('qte_'.$i);

            
            $expedition_detail= Expedition_detail::create([
                'id_expedition' => $expedition->id,
                'id_artisan' => $id_artisan,
                'date_expedition' => $date_expedition,
                'id_produit' => $id_produit,
                'id_couleur' => $id_couleur,
                'qte' => $quantite,  
            ]);    
        }

        return back()->with('success', 'expedition sauvegardé');
    }

    public function SaisonCorrespondante($date){
        $saison = Saison::where('debut', '<=', $date)
                ->where('fin', '>=', $date)
                ->first();

        if($saison) {

            dd($saison->id);
            return $saison->id;
        }
        else{
            return null;
        }
    }

    public function listeExpedition(Request $request) {
        $recherche = $request->input('recherche');

        $query = DB::table('v_expedition');

        if ($request->filled('recherche')) {
            $query->where(function ($q) use ($recherche) {
                $pattern = "%{$recherche}%"; 
            
                $q->whereRaw('UPPER(expedition) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(artisan) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(designation_produit) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(designation_catalogue) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(couleur) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('CAST(qte AS TEXT) LIKE ?', [$pattern]);
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

        if($request->filled('couleur_select')){
            $couleurSelect = $request->input('couleur_select');
            $query->whereRaw('UPPER(couleur) = UPPER(?)', [$couleurSelect]);
        }

        $expedition = $query->paginate(50);

        $artisan = Artisan::all();

        $designationCatalogue = Produit::select('designation_catalogue')
                                ->GroupBy('designation_catalogue')
                                ->get();

        $couleur = Couleur::all();

        return view('Expedition.ListeExpedition', compact('expedition', 'artisan', 'designationCatalogue', 'couleur'));

    }
}