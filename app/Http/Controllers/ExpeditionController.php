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
use App\Models\Stock;
use App\Models\Expedition;
use App\Models\Expedition_detail;
use App\Models\Conteneur;
use Barryvdh\DomPDF\Facade\Pdf;


class ExpeditionController extends Controller
{
    public function ExpeditionForm(){

        $catalogue = DB::table('v_resume_stock')->get();
        $saison = Saison::all();
        $conteneur = Conteneur::all();

        return view('Expedition.FormulaireExpedition',compact('catalogue', 'saison', 'conteneur'));
    }


    public function getCatalogue($id_saison){
        $catalogues = DB::table('v_stock')
                    ->select('designation_catalogue', 'id_saison')
                    ->where('id_saison', $id_saison)
                    ->groupBy('designation_catalogue', 'id_saison')
                    ->get();

        return response()->json($catalogues);

    }


    public function getArtisans($catalogue, $id_saison){
        $artisans =  DB::table('v_stock')
                    ->select('designation_catalogue', 'id_artisan', 'artisan', 'id_saison')
                    ->where('designation_catalogue', $catalogue)
                    ->where('id_saison', $id_saison)
                    ->groupBy('designation_catalogue','id_artisan','artisan', 'id_saison')
                    ->get();

        return response()->json($artisans);
    }

    public function getProduits($id_artisan, $catalogue, $id_saison){
        $produits =  DB::table('v_stock')
                    ->select('id_produit','designation_produit', 'designation_catalogue', 'id_artisan', 'artisan', 'id_saison')
                    ->where('id_artisan', $id_artisan)
                    ->where('designation_catalogue', $catalogue)
                    ->where('id_saison', $id_saison)
                    ->groupBy('id_produit','designation_produit','id_artisan','artisan', 'designation_catalogue', 'id_saison')
                    ->get();

        return response()->json($produits);
    }

    public function getCouleurs($id_artisan, $id_produit, $id_saison){
        $couleur =  DB::table('v_stock')
                    ->select('id_couleur','couleur', 'id_artisan', 'id_produit', 'id_saison')
                    ->where('id_artisan', $id_artisan)
                    ->where('id_produit', $id_produit)
                    ->where('id_saison', $id_saison)
                    ->get();

        return response()->json($couleur);
    }

    public function insertExpedition(Request $request){

        $maxCounter = $request->input('maxCounter');
        $date_expedition = $request->input('date_expedition');
        $expedition_depot = $request->input('expedition');
        $catalogue = $request->input('designation_catalogue');
        $id_saison =  $request->input('id_saison');

        Validator::make($request->all(), [
            'date_expedition' => 'required',
            'expedition' => 'required',    
            'catalogue' => 'required',
            'id_saison' => 'required',    
        ],[
            'date_expedition.required' => 'date expedition requise',
            'expedition.required' => 'lieu expedition requis',
            'catalogue.required' => 'designation catalogue requise',
            'id_saison.required' => 'saison requise',

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

        // verifier si conteneur deja dans expedition
        $verifierExpedition = Expedition::select('id')
                            ->where('id_conteneur', $expedition_depot)
                            ->first();

        if($verifierExpedition == null ){
            // dump("tsy mi existe");
            $expedition = Expedition::create([
                'id_conteneur' => $expedition_depot,
            ]);

            $id_expedition = $expedition->id;
            // dd($id_expedition);

        }
        else{
            // dump("mi existe");
            $id_expedition = $verifierExpedition->id;
            // dd($id_expedition);
        }

        // boucle pour verifier l'etat de stock avant l'insertion
        $messages = [];
        for ($i=0; $i <= $maxCounter; $i++) { 
            $id_produit = $request->input('id_produit_'.$i);
            $id_couleur = $request->input('couleur_'.$i);
            $quantite = $request->input('qte_'.$i);

            $erreur = $this->verifierStock($id_produit, $id_couleur, $quantite, $id_saison);
            if($erreur != null){
                $messages[] = $erreur;
            }
        }
        if (!empty($messages)) {
            return back()->with('fail', $messages);
        }
        // fin de verification

        // boucle pour insertion
        for ($i=0; $i <= $maxCounter; $i++) { 
            $id_artisan = $request->input('id_artisan_'.$i);
            $id_produit = $request->input('id_produit_'.$i);
            $id_couleur = $request->input('couleur_'.$i);
            $quantite = $request->input('qte_'.$i);

            $this->updateStock($id_produit, $id_couleur, $quantite, $id_saison);
            
            $expedition_detail= Expedition_detail::create([
                'id_saison' => $id_saison,
                'id_expedition' => $id_expedition,
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
        $selectionSaison = "";
        $selectionConteneur = "";
        
        $conteneur = $request->input('conteneur_select');
        $saison = $request->input('saison_select');

        $total = DB::table('v_somme_expedition_all')->get();  
        

        $query = DB::table('v_expedition');

        if ($request->filled('recherche')) {
            $query->where(function ($q) use ($recherche) {
                $pattern = "%{$recherche}%"; 
            
                $q->whereRaw('UPPER(conteneur) LIKE UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(artisan) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(designation_produit) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(designation_catalogue) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(couleur) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('CAST(qte AS TEXT) LIKE ?', [$pattern]);
            });
        }

        if($request->filled('saison_select') && empty($conteneur)){
            $saisonSelect = $request->input('saison_select');
            $selectionSaison = $saisonSelect;

            $table='v_somme_expedition';

            $query2 = DB::table('v_somme_expedition')
                    ->where('id_saison', $saisonSelect)        
                    ->get();

            $total=$query2;

            $query->where('id_saison', $saisonSelect);
        }

        if($request->filled('conteneur_select') && empty($saison)){
            $conteneurSelect = $request->input('conteneur_select');
            $selectionConteneur = $conteneurSelect;
            
            $query2 = DB::table('v_somme_expedition_conteneur')
                    ->selectRaw('SUM(qte) as qte, SUM(total) as total')
                    ->where('id_conteneur', $conteneurSelect)        
                    ->get();

            $total=$query2;

            $query->where('id_conteneur', $conteneurSelect);
        }

        if($request->filled('saison_select') && $request->filled('conteneur_select')){
            $saisonSelect = $request->input('saison_select');
            $conteneurSelect = $request->input('conteneur_select');

            $selectionSaison = $saisonSelect;
            $selectionConteneur = $conteneurSelect;

            $query2 = DB::table('v_somme_expedition_conteneur')
            ->selectRaw('SUM(qte) as qte, SUM(total) as total')
            ->where('id_saison', $saisonSelect)
            ->where('id_conteneur', $conteneurSelect)        
            ->get();

            $total=$query2;

            $query->where('id_saison', $saisonSelect);
            $query->where('id_conteneur', $conteneurSelect);
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

        $expedition = $query->paginate(100);

        $artisan = Artisan::all();

        $designationCatalogue = Produit::select('designation_catalogue')
                                ->GroupBy('designation_catalogue')
                                ->get();

        $couleur = Couleur::all();
        $saison = Saison::all();
        $conteneur = Conteneur::all();

        $selectionArtisan = $request->input('artisan_select');
        $selectionCatalogue = $request->input('catalogue_select');
        $selectionCouleur = $request->input('couleur_select');

        

        return view('Expedition.ListeExpedition', compact('expedition', 'artisan', 'designationCatalogue', 'couleur', 'selectionArtisan', 'selectionCatalogue', 'selectionCouleur', 'saison','selectionSaison','conteneur', 'selectionConteneur', 'total'));

    }

    public function updateStock($id_produit, $id_couleur, $quantite, $id_saison){
        // verification de stock
            $stock = Stock::create([
                'id_saison' => $id_saison,
                'id_produit' => $id_produit,
                'id_couleur' => $id_couleur,
                'qte_livre' => -$quantite,
            ]);
    }

    public function verifierStock($id_produit, $id_couleur, $quantite, $id_saison){
        $stockRestant = DB::table('v_stock')
        ->select('qte_livre', 'designation_produit', 'artisan', 'qte_livre', 'id_saison')
        ->where('id_saison', $id_saison)
        ->where('id_produit', $id_produit)
        ->where('id_couleur', $id_couleur)
        ->first();

        if($stockRestant->qte_livre < $quantite){
            // return back()->with('fail', 'stock insuffisant pour le produit : "'.$stockRestant->designation_produit. '" de : "' .$stockRestant->artisan. '" stock restant:'.$stockRestant->qte_livre);
            return 'Stock insuffisant pour le produit : "' . 
            $stockRestant->designation_produit . 
            '" de : "' . 
            $stockRestant->artisan. 
            '", Stock restant : ' . 
            $stockRestant->qte_livre;
        }
        else{
            return null;
        }

    }

    public function exportPdf(Request $request) {
        $saisonSelect = $request->input('saison_select');
        $conteneurSelect = $request->input('conteneur_select');
        $artisanSelect = $request->input('artisan_select');
        $catalogueSelect = $request->input('catalogue_select');

        $query = DB::table('v_expedition');


        if($saisonSelect){
            $query->where('id_saison', $saisonSelect);
        }

        if($conteneurSelect){
            $query->where('id_conteneur', $conteneurSelect);
        }

        if($artisanSelect){
            $query->where('id_artisan', $artisanSelect);
        }

        if($catalogueSelect){
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogueSelect]);
        }

        $expedition = $query->get();

        $total = DB::table('v_somme_expedition_all')->get();

        $pdf = Pdf::loadView('Expedition.pdf', compact('expedition','total'));
        return $pdf->download('Expedition.pdf');
    }
}