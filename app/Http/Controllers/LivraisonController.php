<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Livraison;
use App\Models\Livraison_detail;
use App\Models\Artisan;
use App\Models\Saison;
use App\Models\Produit;
use App\Models\Produit_couleur;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LivraisonDetailExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\Client;

class LivraisonController extends Controller
{
    public function insertLivraison(Request $request){
        $id_saison= $request->input('id_saison');
        $id_artisan = $request->input('id_artisan');
        $id_client = session('id_client');
        $date_livraison = $request->input('date_livraison');
        $bon_reception = $request->input('bon_reception');
        $id_livraison;

        $maxCounter = $request->input('maxCounter');

        $verifierBase= $this->verifierBase($id_artisan, $id_saison, $id_client);

        if($verifierBase == false){

            // dd("tsisy lesy ao ral a, mi inserer");
            
            $livraison= Livraison::create([
                'id_artisan' => $id_artisan,
                'id_saison' => $id_saison,
                'id_client' => $id_client,
            ]);

            $id_livraison = $livraison->id;

        }else{
            $id_livraison = $verifierBase;
        }


        // dump("id_artisan=".$id_artisan, "id_produit=".$id_produit,
        // "maxCounter=".$maxCounter);
        for ($i = 0; $i <= $maxCounter; $i++) {
            $rules['id_produit_' . $i] = 'required';
            $messages['id_produit_' . $i . '.required'] = 'produit requis' . ($i + 1) . ' est requis.';
    
            $rules['couleur_' . $i] = 'required';
            $messages['couleur_' . $i . '.required'] = 'couleur requis' . ($i + 1) . ' est requis.';
    
            $rules['qte_livre_' . $i] = 'required';
            $messages['qte_livre_' . $i . '.required'] = 'quantite requis' . ($i + 1) . ' est requis.';

        }

        Validator::make($request->all(), [
            'id_saison' => 'required',
            'id_artisan' => 'required',    
            'date_livraison' => 'required',
            'bon_reception' => 'required',    
        ],[
            'id_saison.required' => 'date requis',
            'id_artisan.required' => 'artisan requis',
            'date_livraison.required' => 'date requise',
            'bon_reception.required' => 'bon reception requis',
        ])-> validate();

        $validated = Validator::make($request->all(), $rules, $messages)->validate();       

        for ($i = 0; $i <= $maxCounter; $i++) {

        $id_produit = $request->input('id_produit_'.$i);
        $id_couleur = $request->input('couleur_'.$i);
        $qte_livre = $request->input('qte_livre_'.$i);

            $detail = Livraison_detail::create([
                'id_livraison' => $id_livraison,
                'id_produit' => $id_produit,
                'id_couleur' => $id_couleur,
                'qte_livre' => $qte_livre,
                'date_livraison' => $date_livraison,
                'bon_reception' => $bon_reception,
            ]);

            $stock = Stock::create([
                'id_saison' => $id_saison,
                'id_client' => $id_client,
                'id_produit' => $id_produit,
                'id_couleur' => $id_couleur,
                'qte_livre' => $qte_livre,
                'date_livraison' => $date_livraison,
            ]);
        }

        return back()->with('success', 'livraison sauvegardé');
    }

    public function verifierBase($id_artisan, $id_saison, $id_client){
        $existe = Livraison::where('id_artisan', $id_artisan)
                            -> where('id_saison', $id_saison)
                            -> where('id_client', $id_client)
                            ->first();

        if($existe){
            return $existe->id;
        }

        return false;
    }

    public function LivraisonFormulaire(){
        $artisan = Artisan::all();

        $livraisonType= DB::table('type_livraison')
                        ->get();

        $saison = Saison::all();

        $produit = Produit::all();

        return view('Formulaire.Livraison', compact('artisan','livraisonType','saison'));

    }

    public function getProduits($id_artisan, $id_saison){

        $produits = DB::table('v_detail_commande')->select('id_produit', 'designation_produit')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->where('id_client', session('id_client'))
        ->distinct()->get();

        return response()->json($produits);
    }

    public function getCatalogues($id_produit){

        $catalogues = DB::table('v_detail_commande')->select('designation_catalogue')
        ->where('id_produit', $id_produit)
        ->first();

        return response()->json($catalogues);

    }

    
    public function getCouleurs($id_produit){
        $couleurs = DB::table('v_produit_couleur')
                    ->where('id_produit', $id_produit)->get();

        return response()->json($couleurs);
    }

    public function listeLivraison(Request $request){
        $query = DB::table('v_liste_livraison')->where('id_client', session('id_client'));
        
        if ($request->filled('artisan_select')) {
            $query->where('id_artisan', $request->artisan_select);
        }

        if ($request->filled('saison_select')) {
            $query->where('id_saison', $request->saison_select);
        }

        $livraison = $query->paginate(150);

        $saison = Saison::all();
        $artisan = Artisan::all();

        $selectionArtisan = $request->input('artisan_select');
        $selectionSaison = $request->input('saison_select');

        return view('Consultation.LivraisonListe', compact('livraison','saison','artisan','selectionArtisan','selectionSaison'));
        
    }

    public function detailLivraison($id_artisan, $id_saison, $id_client){

        $nom_artisan = Artisan::find($id_artisan);
        session(['nom_artisan' => $nom_artisan->nom ]);

        $date_value = "";

        $type_livraison = DB::table('type_livraison')->get();

        $detail= DB::table('v_commande_livraison')
                 ->where('id_artisan', $id_artisan)
                 ->where('id_saison', $id_saison)
                 ->where('id_client', $id_client)
                 ->orderBy('date_livraison', 'asc')
                 ->paginate(150);

                return view('Consultation.LivraisonDetail', compact('detail', 'type_livraison', 'id_artisan', 'id_saison', 'date_value'));
    }

    public function filtreLivraison(Request $request){
        $id_saison = $request->input('id_saison');
        $id_artisan = $request->input('id_artisan');
        $recherche = $request->input('recherche');
        $date_filtre = $request->input('date_filtre');
        $date_value = "";

        $nom_artisan = Artisan::find($id_artisan);

        if($id_artisan != null){
            session(['nom_artisan' => $nom_artisan->nom]);         
        }

        $query = DB::table('v_commande_livraison')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison);
                
                if ($request->filled('recherche')) {
                    $query->where(function ($q) use ($recherche) {
                        $pattern = "%{$recherche}%";         // le pattern reste bindé, donc protégé
                    
                        $q->whereRaw('UPPER(designation_produit)    LIKE UPPER(?)', [$pattern])
                          ->orWhereRaw('UPPER(designation_catalogue) LIKE UPPER(?)', [$pattern])
                          ->orWhereRaw('UPPER(couleur)              LIKE UPPER(?)', [$pattern])
                          ->orWhereRaw('CAST(prix_unitaire AS TEXT) LIKE ?', [$pattern])
                          ->orWhereRaw('CAST(bon_reception AS TEXT) LIKE ?', [$pattern]);
                    }); 
                }
                if ($request->filled('date_filtre')) {
                    $date_value = $date_filtre;
                    $query->where('date_livraison', $date_filtre);
                }

                $detail= $query->paginate(150);

        return view('Consultation.LivraisonDetail', compact('detail', 'id_artisan', 'id_saison', 'date_value'));

    }

    public function resumeLivraison($id_artisan, $id_saison, $id_client){
        $query = DB::table('v_resume_livraison')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->where('id_client', $id_client);

        $total =  DB::table('v_total_resume')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->where('id_client', $id_client)
        ->get();

        $resume= $query->paginate(150);

        $nom_artisan = Artisan::find($id_artisan);
        session(['nom_artisan' => $nom_artisan->nom ]);

        return view('Consultation.LivraisonResume', compact('resume', 'total', 'id_artisan' ,'id_saison', 'id_client'));
    }

    public function filtreResumeLivraison(Request $request){
        $id_artisan = $request->input('id_artisan');
        $id_saison = $request->input('id_saison');
        $id_client = $request->input('id_client');
        $recherche = $request->input('recherche');

        $query = DB::table('v_resume_livraison')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->where('id_client', $id_client);

        if($request->filled('recherche')){
            $query->where(function ($q) use ($recherche) {
                $pattern = "%{$recherche}%";         // le pattern reste bindé, donc protégé
            
                $q->whereRaw('UPPER(designation_produit)    LIKE UPPER(?)', [$pattern])
                  ->orWhereRaw('UPPER(designation_catalogue) LIKE UPPER(?)', [$pattern])
                  ->orWhereRaw('CAST(prix_unitaire AS TEXT) LIKE ?', [$pattern]);
            });
        }

        $total =  DB::table('v_total_resume')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->where('id_client', $id_client)
        ->get();

        $resume= $query->paginate(150);

        $nom_artisan = Artisan::find($id_artisan);
        session(['nom_artisan' => $nom_artisan->nom ]);

        return view('Consultation.LivraisonResume', compact('resume', 'total', 'id_artisan', 'id_saison', 'id_client'));
    }

    public function exportPdfDetail(Request $request) {
        $date_select = $request->input('date_select');
        $id_artisan = $request->input('id_artisan'); 
        $id_saison = $request->input('id_saison');
        $id_client = session('id_client');

        $query = DB::table('v_commande_livraison')
                 ->where('id_artisan', $id_artisan)
                 ->where('id_saison', $id_saison)
                 ->where('id_client', $id_client)
                 ->orderBy('date_livraison', 'asc');

        if($date_select){
            $query->where('date_livraison', $date_select);
        }

        $detail = $query->get();

        $pdf = Pdf::loadView('PDF.livraison_detail', compact('detail'));

        // pour le nom du fichier
        $artisan = Artisan::find($id_artisan);
        $saison = Saison::find($id_saison);
        $client = Client::find(session('id_client'));
        $date = Carbon::now()->format('d-m-Y');

        $nomFichier = "Livraison_par_date_{$artisan->nom}_{$saison->nom_saison}_{$client->nom_client}_{$date}.pdf";

        return $pdf->download($nomFichier);
    }

    public function exportPdfResume(Request $request){
        $id_artisan = $request->input('id_artisan'); 
        $id_saison = $request->input('id_saison');

        $resume = DB::table('v_resume_livraison')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->get();

        $total =  DB::table('v_total_resume')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->get();

        $pdf = Pdf::loadView('PDF.livraison_resume', compact('resume', 'total'));
        return $pdf->download('livraison_resume.pdf');

    }

    public function exportExcel(Request $request){
        $date_select = $request->input('date_select');
        $id_artisan = $request->input('id_artisan'); 
        $id_saison = $request->input('id_saison');

        // pour le nom du fichier
        $artisan = Artisan::find($id_artisan);
        $saison = Saison::find($id_saison);
        $client = Client::find(session('id_client'));
        $date = Carbon::now()->format('d-m-Y');

        $nomFichier = "Commande_{$artisan->nom}_{$saison->nom_saison}_{$client->nom_client}_{$date}.xlsx";
        
        return Excel::download(
            new LivraisonDetailExport($date_select, $id_artisan, $id_saison),
            $nomFichier
        );
    }
}