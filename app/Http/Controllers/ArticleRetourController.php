<?php

namespace App\Http\Controllers;

use App\Models\ArticleRetour;
use Illuminate\Http\Request;
use App\Models\Artisan;
use App\Models\Saison;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\ArticleRetourDetail;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Client;
use Carbon\Carbon;


class ArticleRetourController extends Controller
{
        public function ArticleRetourFormulaire(){
        // $artisan = Artisan::all();
        $artisan = DB::table('v_stock_commande')->select('id_artisan as id','artisan as nom')
        ->where('id_client', session('id_client'))
        ->distinct()->get();

        $saison = DB::table('v_stock_commande')->select('id_saison as id','nom_saison')
        ->where('id_client', session('id_client'))
        ->distinct()->get();

        $produit = Produit::all();

        return view('Formulaire.ArticleRetour', compact('artisan','saison'));
    }

    public function getProduits($id_artisan, $id_saison){
        $produits = DB::table('v_stock_commande')->select('id_produit','designation_produit')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->where('id_client', session('id_client'))
        ->distinct()->get();

        return response()->json($produits);
    }

    public function getCatalogues($id_produit){

        $catalogues = DB::table('v_stock_commande')->select('designation_catalogue')
                            ->where('id_produit', $id_produit)
                            ->first();

        return response()->json($catalogues);

    }

    
    public function getCouleurs($id_produit){
        $couleurs = DB::table('v_stock_commande')->select('id_couleur','couleur')
                    ->where('id_produit', $id_produit)->distinct()->get();

        return response()->json($couleurs);
    }

    public function insertArticleRetour(Request $request){

        $id_saison= $request->input('id_saison');
        $id_artisan = $request->input('id_artisan');
        $id_client = session('id_client');
        $date_retour = $request->input('date_retour');
        $bon_sortie = $request->input('bon_sortie');
        $id_article_retour;

        $maxCounter = $request->input('maxCounter');

        $articleRetour = $this->getArticleRetour($id_artisan, $id_saison, $id_client);

        for ($i = 0; $i <= $maxCounter; $i++) {
            $rules['id_produit_' . $i] = 'required';
            $messages['id_produit_' . $i . '.required'] = 'produit' . ($i + 1) . ' est requis.';
    
            $rules['couleur_' . $i] = 'required';
            $messages['couleur_' . $i . '.required'] = 'couleur' . ($i + 1) . ' est requis.';
    
            $rules['qte_' . $i] = 'required';
            $messages['qte_' . $i . '.required'] = 'quantite ' . ($i + 1) . ' est requis.';

        }

        Validator::make($request->all(), [
            'id_saison' => 'required',
            'id_artisan' => 'required',    
            'date_retour' => 'required',   
            'bon_sortie' => 'required',    
        ],[
            'id_saison.required' => 'date requis',
            'id_artisan.required' => 'artisan requis',
            'date_retour.required' => 'date requise',
            'bon_sortie.required' => 'bon de sortie requis',

        ])-> validate();

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

        // insert
        for ($i = 0; $i <= $maxCounter; $i++) {

        $id_produit = $request->input('id_produit_'.$i);
        $id_couleur = $request->input('couleur_'.$i);
        $qte = $request->input('qte_'.$i);

        $this->updateStock($id_produit, $id_couleur, $qte, $id_saison, $id_client, $date_retour);

            $detail = ArticleRetourDetail::create([
                'id_article_retour' => $articleRetour->id,
                'id_produit' => $id_produit,
                'id_couleur' => $id_couleur,
                'qte' => $qte,
                'date_retour' => $date_retour,
                'bon_sortie' => $bon_sortie,
            ]);

        }

        return back()->with('success', 'sauvegardé');
    }

    public function getArticleRetour($id_artisan, $id_saison, $id_client){
        $existe = ArticleRetour::where('id_artisan', $id_artisan)
                            -> where('id_saison', $id_saison)
                            -> where('id_client', $id_client)
                            ->first();

        if(!$existe){
            $articleRetour = ArticleRetour::create([
                'id_saison' => $id_saison,
                'id_artisan' => $id_artisan,
                'id_client' => $id_client,
            ]);

        }else{
            $articleRetour = $existe;
        }

        return $articleRetour;
    }

    public function listeArticleRetour(Request $request){
        $query = DB::table('article_retour as c')
        ->join('artisan as a', 'a.id', '=', 'c.id_artisan')
        ->join('saison as s', 's.id', '=', 'c.id_saison')
        ->select(
            'c.id_saison',
            'c.id_artisan',
            'c.id_client',
            'a.nom as artisan',
            's.nom_saison as saison'
        )
        ->where('c.id_client', session('id_client'))
        ->groupBy('c.id_saison', 'c.id_artisan', 'c.id_client', 'a.nom', 's.nom_saison');

         // Appliquer les filtres dynamiques
        if ($request->filled('saison_select')) {
            $query->where('c.id_saison', $request->saison_select);
        }

        if ($request->filled('artisan_select')) {
            $query->where('c.id_artisan', $request->artisan_select);
        }

        $articleRetours = $query->get();

        $saison = Saison::all();
        $artisan = Artisan::all();

        $selectionArtisan = $request->input('artisan_select');
        $selectionSaison = $request->input('saison_select');

        return view('Consultation.ArticleRetourListe', compact('articleRetours', 'saison', 'artisan','selectionArtisan','selectionSaison'));

    }

    public function detailArticleRetour($id_artisan, $id_saison, $id_client){
        $nom_artisan = Artisan::find($id_artisan);
        session(['nom_artisan' => $nom_artisan->nom ]);

        $query = DB::table('v_article_retour_detail')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison)
                ->where('id_client', $id_client);

        $somme = DB::table('v_article_retour_detail')
                ->select('id_artisan','id_saison', DB::raw('SUM(qte) as total_qte'))
                ->where('id_client', $id_client)
                ->groupBy('id_artisan', 'id_saison')
                ->get();

        // $detail= $query->get();
        $detail= $query->paginate(150);

        // pour eviter la valuer null de selectionType
        $selectionType = "";
        
        return view('Consultation.ArticleRetourDetail', compact('detail', 'somme','selectionType', 'id_artisan', 'id_saison'));
    }

    public function updateStock($id_produit, $id_couleur, $quantite, $id_saison, $id_client, $date_retour){
        $stock = Stock::create([
            'id_saison' => $id_saison,
            'id_produit' => $id_produit,
            'id_couleur' => $id_couleur,
            'qte_livre' => -$quantite,
            'id_client' => $id_client,
            'date_livraison' => $date_retour,
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
            $stockRestant->designation_produit 
            . 
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
        $id_artisan = $request->input('id_artisan'); 
        $id_saison = $request->input('id_saison');
        $id_client = session('id_client');

       $query = DB::table('v_article_retour_detail')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison)
                ->where('id_client', $id_client);

        $total = DB::table('v_article_retour_detail')
                ->select('id_artisan','id_saison', DB::raw('SUM(qte) as total_qte'))
                ->where('id_client', $id_client)
                ->groupBy('id_artisan', 'id_saison')
                ->get();

        $detail = $query->get();

        $pdf = Pdf::loadView('PDF.articleRetour', compact('detail','total'));

        // pour le nom du fichier
        $artisan = Artisan::find($id_artisan);
        $saison = Saison::find($id_saison);
        $client = Client::find(session('id_client'));
        $date = Carbon::now()->format('d-m-Y');

        $nomFichier = "Article_retour_{$artisan->nom}_{$saison->nom_saison}_{$client->nom_client}_{$date}.pdf";

        return $pdf->download($nomFichier);
    }

    public function filtre(Request $request){
        $id_artisan = $request->input('id_artisan') ?? session('id_artisan');
        $id_saison = $request->input('id_saison') ?? session('id_saison');
        $id_client  = session('id_client');
        $recherche  = $request->input('recherche');

        // Enregistrer les identifiants dans la session
        $nom_artisan = $id_artisan ? Artisan::find($id_artisan) : null;

        if ($request->filled('id_artisan') && $request->filled('id_saison')) {
            session([
                'id_artisan' => $id_artisan,
                'id_saison' => $id_saison
            ]);

            if ($nom_artisan) {
                session(['nom_artisan' => $nom_artisan->nom]);
            }
        }

        // Filtre de recherche réutilisable
        $appliquerRecherche = function ($q) use ($recherche) {
            if ($recherche) {
                $pattern = "%{$recherche}%";
                $q->where(function ($sub) use ($pattern) {
                    $sub->where('designation_produit', 'ilike', $pattern)
                        ->orWhere('designation_catalogue', 'ilike', $pattern)
                        ->orWhere('couleur', 'ilike', $pattern)
                        ->orWhereRaw('CAST(bon_sortie AS TEXT) ILIKE ?', [$pattern]);
                });
            }
            return $q;
        };

        // Liste détaillée
        $query = DB::table('v_article_retour_detail')
            ->where('id_artisan', $id_artisan)
            ->where('id_saison', $id_saison)
            ->where('id_client', $id_client);
        $appliquerRecherche($query);

        // Somme (avec la même recherche)
        $queryS = DB::table('v_article_retour_detail')
            ->select('id_artisan', 'id_saison', DB::raw('SUM(qte) as total_qte'))
            ->where('id_client', $id_client)
            ->groupBy('id_artisan', 'id_saison');
        $appliquerRecherche($queryS);
        $somme = $queryS->get();

        $selectionType = "";
        $detail = $query->paginate(150)->appends($request->all());

        return view('Consultation.ArticleRetourDetail', compact('detail', 'somme', 'selectionType', 'id_artisan', 'id_saison'));
    }

    public function modifierListe($id_artisan, $id_saison, $id_client){
        $detail = DB::table('article_retour_detail as rd')
        ->join('produit as p', 'p.id', '=', 'rd.id_produit')
        ->join('couleur as c', 'c.id', '=', 'rd.id_couleur')
        ->join('article_retour as ar', 'ar.id', '=', 'rd.id_article_retour')
        ->select(
            'rd.id',
            'rd.id_article_retour',
            'ar.id_artisan',
            'ar.id_saison',
            'ar.id_client',
            'rd.id_produit',
            'p.designation_produit',
            'p.designation_catalogue',
            'rd.id_couleur',
            'c.nom as couleur',
            'rd.qte',
            'rd.date_retour',
            'rd.bon_sortie'
            )
            ->where('ar.id_saison', $id_saison)
            ->where('ar.id_artisan', $id_artisan)
            ->where('ar.id_client', $id_client)
            ->get();
            return view('Consultation.ArticleRetourModifier', compact('detail'));
    }

     public function update(Request $request, $id){

        $validated = $request->validate([
            'qte' => 'required|integer|min:0',
            'date_retour' => 'required|date'    
        ]);

        $detail = ArticleRetourDetail::findOrFail($id);
        $ancien_qte = $detail->qte;
        $ancien_qte = -$ancien_qte;

        $detail->qte = $validated['qte'];

        $detail->save();

        $id_artisan = $request->input('id_artisan');
        $id_saison = $request->input('id_saison');
        $id_client = $request->input('id_client');

        // modifier stock
        $stock = Stock::where('date_livraison', $validated['date_retour'])
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
        $detail = ArticleRetourDetail::findOrFail($id);
        $ancien_qte = $detail->qte;
        $ancien_qte = -$ancien_qte;

        $date_retour = $request->input('date_retour');
        $id_artisan = $request->input('id_artisan');
        $id_saison = $request->input('id_saison');
        $id_client = $request->input('id_client');

        // modifier stock
        $stock = Stock::where('date_livraison', $date_retour)
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