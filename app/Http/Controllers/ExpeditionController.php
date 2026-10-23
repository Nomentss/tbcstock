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
use App\Models\Client;
use Carbon\Carbon;


class ExpeditionController extends Controller
{
    public function ExpeditionForm(){

        $catalogue = DB::table('v_resume_stock')->get();
        $saison = Saison::all();

        return view('Expedition.FormulaireExpedition',compact('catalogue', 'saison'));
    }


    public function getCatalogue($id_saison){
        $catalogues = DB::table('v_stock')
                    ->select('designation_catalogue', 'id_saison')
                    ->where('id_saison', $id_saison)
                    ->where('id_client', session('id_client'))
                    ->groupBy('designation_catalogue', 'id_saison')
                    ->get();

        return response()->json($catalogues);

    }


    public function getArtisans($catalogue, $id_saison){
        $artisans =  DB::table('v_stock')
                    ->select('designation_catalogue', 'id_artisan', 'artisan', 'id_saison')
                    ->where('designation_catalogue', $catalogue)
                    ->where('id_saison', $id_saison)
                    ->where('id_client', session('id_client'))
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
                    ->where('id_client', session('id_client'))
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
                    ->where('id_client', session('id_client'))
                    ->get();

        return response()->json($couleur);
    }

    public function insertExpedition(Request $request){

        $maxCounter = $request->input('maxCounter');
        $date_expedition = $request->input('date_expedition');
        $expedition_depot = $request->input('expedition_depot');
        $catalogue = $request->input('designation_catalogue');
        $id_saison =  $request->input('id_saison');
        $expedition_route =  $request->input('expedition_route');
        $id_client =  session('id_client');

        Validator::make($request->all(), [
            'date_expedition' => 'required',
            'expedition_route' => 'required',    
            'catalogue' => 'required',
            'id_saison' => 'required',    
        ],[
            'date_expedition.required' => 'date expedition requise',
            'expedition_route.required' => 'lieu expedition requis',
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

        // boucle pour verifier l'etat de stock avant l'insertion
        $messages = [];
        for ($i=0; $i <= $maxCounter; $i++) { 
            $id_produit = $request->input('id_produit_'.$i);
            $id_couleur = $request->input('couleur_'.$i);
            $quantite = $request->input('qte_'.$i);

            $erreur = $this->verifierStock($id_produit, $id_couleur, $quantite, $id_saison, $id_client);
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

            $this->updateStock($id_produit, $id_couleur, $quantite, $id_saison, $id_client, $date_expedition);
            
            $expedition_detail= Expedition_detail::create([
                'id_saison' => $id_saison,
                'id_artisan' => $id_artisan,
                'id_client' => $id_client,
                'date_expedition' => $date_expedition,
                'expedition_route' => $expedition_route,
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
        $selectionArtisan = "";
        $selectionCatalogue = "";
        $selectionCouleur = "";
        
        $saison = $request->input('saison_select');

        $query2 = DB::table('v_reference_somme_expedition')->where('id_client', session('id_client'))
                  ->selectRaw('SUM(qte) as qte, SUM(qte*prix_unitaire) as total'); 
        

        // $query = DB::table('v_expedition');
        $query = DB::table('v_reference_somme_expedition')->where('id_client', session('id_client'));


        if ($request->filled('recherche')) {
            $query->where(function ($q) use ($recherche) {
                $pattern = "%{$recherche}%"; 
            
                $q->WhereRaw('UPPER(artisan) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(designation_produit) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(designation_catalogue) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('UPPER(couleur) LIKE  UPPER(?)', [$pattern])
                    ->orWhereRaw('CAST(qte AS TEXT) LIKE ?', [$pattern]);
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

            $query->where('id_artisan', $artisanSelect);
            $query2->where('id_artisan', $artisanSelect);

            $artisaka = Artisan::select('nom')
                                ->where('id',$artisanSelect)
                                ->first();

            $selectionArtisan = $artisaka->nom;
            
        }

        if($request->filled('catalogue_select')){
            $catalogueSelect = $request->input('catalogue_select');
            $selectionCatalogue = $catalogueSelect;
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogueSelect]);
            $query2->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogueSelect]);
        }

        if($request->filled('couleur_select')){
            $couleurSelect = $request->input('couleur_select');
            $selectionCouleur = $couleurSelect;
            $query->whereRaw('UPPER(couleur) = UPPER(?)', [$couleurSelect]);
        }

        $expedition = $query->paginate(150);

        $artisan = Artisan::all();

        $designationCatalogue = Produit::select('designation_catalogue')
                                ->GroupBy('designation_catalogue')
                                ->get();

        $couleur = Couleur::all();
        $saison = Saison::all();
        // $conteneur = Conteneur::all();

        $total = $query2->get();

        return view('Expedition.ListeExpedition', compact('expedition', 'artisan', 'designationCatalogue', 'couleur', 'selectionArtisan', 'selectionCatalogue', 'selectionCouleur', 'saison','selectionSaison', 'total'));

    }

    public function updateStock($id_produit, $id_couleur, $quantite, $id_saison, $id_client, $date_livraison){
            $stock = Stock::create([
                'id_saison' => $id_saison,
                'id_produit' => $id_produit,
                'id_couleur' => $id_couleur,
                'qte_livre' => -$quantite,
                'id_client' => $id_client,
                'date_livraison' => $date_livraison
            ]);
    }

    public function verifierStock($id_produit, $id_couleur, $quantite, $id_saison, $id_client){
        $stockRestant = DB::table('v_stock')
        ->select('qte_livre', 'designation_produit', 'artisan', 'qte_livre', 'id_saison')
        ->where('id_saison', $id_saison)
        ->where('id_produit', $id_produit)
        ->where('id_couleur', $id_couleur)
        ->where('id_client', $id_client)
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
        $artisanSelect = $request->input('artisan_select');
        $catalogueSelect = $request->input('catalogue_select');
        $id_client = session('id_client');

        $query = DB::table('v_reference_somme_expedition')
        ->where('id_client', $id_client);

        $query2 = DB::table('v_reference_somme_expedition')
                  ->selectRaw('SUM(qte) as qte, SUM(qte*prix_unitaire) as total')
                  ->where('id_client', $id_client);
                   

        if($saisonSelect){
            $query->where('id_saison', $saisonSelect);
            $query2->where('id_saison', $saisonSelect);
        }

        if($artisanSelect){
            $query->where('id_artisan', $artisanSelect);
            $query2->where('id_artisan', $artisanSelect);
        }

        if($catalogueSelect){
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogueSelect]);
            $query2->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$catalogueSelect]);
        }

        $expedition = $query->get();

        $total = $query2->get();

        $pdf = Pdf::loadView('Expedition.pdf', compact('expedition','total'));

        $client = Client::find($id_client);
        $date = Carbon::now()->format('d-m-Y');

        $nomFichier = "Expedition_{$client->nom_client}_{$date}.pdf";

        return $pdf->download($nomFichier);
    }

    public function modifierListe($id_client){
        
        $detail = DB::table('expedition_detail as ed')
            ->join('produit as p', 'p.id', '=', 'ed.id_produit')
            ->join('couleur as c', 'c.id', '=', 'ed.id_couleur')
            ->join('artisan as a', 'a.id', '=', 'ed.id_artisan')
            ->join('saison as sa', 'sa.id', '=', 'ed.id_saison')
            ->select(
                'ed.id',
                'ed.id_saison',
                'sa.nom_saison',
                'ed.expedition_route',
                'ed.id_artisan',
                'a.nom as artisan',
                'ed.id_client',
                'ed.id_produit',
                'p.designation_produit',
                'p.designation_catalogue',
                'ed.id_couleur',
                'c.nom as couleur',
                'ed.qte',
                'ed.date_expedition'
            )
            ->where('ed.id_client', $id_client)
            ->get();

        return view('Consultation.ExpeditionModifier', compact('detail'));
    }

    public function update(Request $request, $id){

        $validated = $request->validate([
            'qte' => 'required|integer|min:0',
            'date_expedition' => 'required|date'
        ]);

        $detail = Expedition_detail::findOrFail($id);
        $ancien_qte = $detail->qte;
        $ancien_qte = -$ancien_qte;

        $detail->qte = $validated['qte'];

        $detail->save();

        $id_artisan = $request->input('id_artisan');
        $id_saison = $request->input('id_saison');
        $id_client = $request->input('id_client');

        // modifier stock
        $stock = Stock::where('date_livraison', $validated['date_expedition'])
        ->where('id_saison', $id_saison)
        ->where('id_client', $id_client)
        ->where('id_produit', $detail->id_produit)
        ->where('id_couleur', $detail->id_couleur)
        ->where('qte_livre', $ancien_qte)
        ->first();
        
        $stock->qte_livre = -$validated['qte'];
        $stock->save();

        return response()->json([
            'success' => true,
            'qte' => $detail->qte
        ]);
    }

    public function delete(Request $request, $id){
        $detail = Expedition_detail::findOrFail($id);
        $ancien_qte = $detail->qte;
        $ancien_qte = -$ancien_qte;

        $date_expedition = $request->input('date_expedition');
        $id_artisan = $request->input('id_artisan');
        $id_saison = $request->input('id_saison');
        $id_client = $request->input('id_client');

        // modifier stock
        $stock = Stock::where('date_livraison', $date_expedition)
        ->where('id_saison', $id_saison)
        ->where('id_client', $id_client)
        ->where('id_produit', $detail->id_produit)
        ->where('id_couleur', $detail->id_couleur)
        ->where('qte_livre', $ancien_qte)
        ->first();

        if (!$stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock correspondant introuvable.'
            ], 404);
        }

        $detail->delete();
        $stock->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ligne supprimée avec succès'
        ]);
    }
}