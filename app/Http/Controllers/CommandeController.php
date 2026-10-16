<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Artisan;
use App\Models\Couleur;
use App\Models\Produit;
use App\Models\Produit_couleur;
use App\Models\Commande;
use App\Models\Commande_detail;
use App\Models\Saison;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CommandeExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\Client;

class CommandeController extends Controller
{
    public function CommandeFormulaire(){
        $artisan = Artisan::all();

        $couleur = Couleur::all();

        $saison = Saison::all();

        return view('Formulaire.Commande', compact('artisan','couleur','saison'));  
    }

    public function insertCommande(Request $request){
        $id_artisan = $request->input('id_artisan');
        $date_commande = $request->input('date_commande');
        $saison = $request->input('saison');
        $type_commande = $request->input('type_commande');
        $id_client = session('id_client');
    
        $maxCounter = $request->input('maxCounter');

        $rules = [];
        $messages = [];


        // gestion validator
        for ($i = 0; $i <= $maxCounter; $i++) {
            $rules['produit_' . $i] = 'required';
            $messages['produit_' . $i . '.required'] = 'Le produit est requis.';
    
            $rules['designation_' . $i] = 'required';
            $messages['designation_' . $i . '.required'] = 'La désignation du produit est requise.';
    
            $rules['id_couleur_' . $i] = 'required';
            $messages['id_couleur_' . $i . '.required'] = 'La couleur du produit est requise.';
    
            $rules['qte_commande_' . $i] = 'required|numeric|min:1';
            $messages['qte_commande_' . $i . '.required'] = 'La quantité du produit est requise.';
            $messages['qte_commande_' . $i . '.numeric'] = 'La quantité doit être un nombre.';
            $messages['qte_commande_' . $i . '.min'] = 'La quantité doit être au moins 1.';
    
            $rules['prix_unitaire_' . $i] = 'required|numeric|min:0';
            $messages['prix_unitaire_' . $i . '.required'] = 'Le prix unitaire du produit est requis.';
        }
        
        Validator::make($request->all(), [
            // 'date_commande' => 'required',
            'saison' => 'required',    
            'type_commande' => 'required',    
        ],[
            // 'date_commande.required' => 'date requis',
            'saison.required' => 'saison requis',
            'type_commande.required' => 'champ requis',
        ])-> validate();

        $validated = Validator::make($request->all(), $rules, $messages)->validate();


        for ($i = 0; $i <= $maxCounter; $i++) {
            $produit = $request->input('produit_'.$i);
            $designation = $request->input('designation_'.$i);
            $id_couleur = $request->input('id_couleur_'.$i);
            $qte_commande = $request->input('qte_commande_'.$i);
            $prix_unitaire = $request->input('prix_unitaire_'.$i);

            //variable pour inserer dans commande , qui change selon verfication produit 
            $id_produit_commande;

            // dump("maxCounter=".$maxCounter, "produit =".$produit, "designation =".$designation, "id_couleur =".$id_couleur, "qte_commande =".$qte_commande, "prix_unitaire =".$prix_unitaire);
            // dump("BOUCLE =".$i);

            // verifier si le produit et id_artisan n'a pas deja ete rentree
            $verifier_produit = $this->verifierProduit($produit, $id_artisan);

            if($verifier_produit == -1){

                // dump("miditra produit anao");

                // miditra ny couleur avieo
                    $pdt = Produit::create([
                    'id_artisan' => $id_artisan,
                    'designation_produit' => $produit,
                    'designation_catalogue' => $designation,
                    ]);

                    $id_produit_commande=$pdt->id;

                // miditra ny couleur avieo
                    $produit_couleur = Produit_couleur::create([
                    'id_produit' => $pdt->id,
                    'id_couleur' => $id_couleur,
                    ]);

            }
            else{

                $id_produit = $verifier_produit;
                // verfier couleur si c'est deja inseré ou non
                $verifier_couleur = $this->verififerProduitCouleur($id_produit, $id_couleur);

                if($verifier_couleur != null){
                    $produit_couleur = Produit_couleur::create([
                            'id_produit' => $id_produit,
                            'id_couleur' => $id_couleur,
                        ]);
                        // dump("mi-inserer coulera avec produit");
                }

                $id_produit_commande = $id_produit;
                // dump("miverifier coulera");  
            }

                $verifier_commande = Commande::select('id')
                                ->where('id_saison', $saison)
                                ->where('id_artisan', $id_artisan)
                                ->where('id_client', $id_client)
                                ->whereRaw('UPPER(type_commande) = ?', [strtoupper($type_commande)])
                                ->first();

                if($verifier_commande){
                    // dump($verifier_commande->id);
                    $commande_detail = Commande_detail::create([
                        'id_commande' => $verifier_commande->id,
                        'id_produit'=> $id_produit_commande,
                        'id_couleur'=> $id_couleur,
                        'qte'=> $qte_commande,
                        'prix_unitaire'=> $prix_unitaire,
                    
                    ]);

                }else{
                    // dump("tsy misy");
                    $commande = Commande::create([
                        'id_saison' => $saison,
                        'id_artisan' => $id_artisan,
                        'id_client' => $id_client,
                        'type_commande' => $type_commande,
                        // 'date_commande' => $date_commande,
                    ]);

                    $commande_detail = Commande_detail::create([
                        'id_commande' => $commande->id,
                        'id_produit'=> $id_produit_commande,
                        'id_couleur'=> $id_couleur,
                        'qte'=> $qte_commande,
                        'prix_unitaire'=> $prix_unitaire,
                    
                    ]);
                }
                
        }
        return back()->with('success', ' Commande sauvegardée !');
    }

    public function verifierProduit($produit, $id_artisan){
    // convertir la chaine en miniscule pour la comparaison 
        $produit = Produit::whereRaw('LOWER(designation_produit) = ?', [strtolower($produit)])
                            ->where('id_artisan', '=', $id_artisan)
                            ->first();
                            
        if($produit){
            return $produit->id;
        }
        else{
            // tsisy ilay produit
            return -1;
        }
    }

    public function verififerProduitCouleur($id_produit, $id_couleur){

        $couleur = Produit_couleur::where('id_produit', $id_produit)
                                            ->get();
                                            
        foreach($couleur as $couleurs){
            if($couleurs->id_couleur == $id_couleur){
                // tsy ampidirina intsony
                return null;
            }
        }
        return 1;
    }

    public function listeCommande(Request $request){
        $query = DB::table('commande as c')
        ->join('artisan as a', 'a.id', '=', 'c.id_artisan')
        ->join('saison as s', 's.id', '=', 'c.id_saison')
        ->join('client as cl', 'cl.id', '=', 'c.id_client')
        ->select(
            'c.id_saison',
            'c.id_artisan',
            'c.id_client',
            'a.nom as artisan',
            's.nom_saison as saison',
            'cl.nom_client as client',
        )
        ->where('c.id_client', session('id_client'))
        ->groupBy('c.id_saison', 'c.id_artisan', 'a.nom', 's.nom_saison', 'c.id_client', 'cl.nom_client');

         // Appliquer les filtres dynamiques
        if ($request->filled('saison_select')) {
            $query->where('c.id_saison', $request->saison_select);
        }

        if ($request->filled('artisan_select')) {
            $query->where('c.id_artisan', $request->artisan_select);
        }

        $commandes = $query->get();

        $saison = Saison::all();
        $artisan = Artisan::all();

        $selectionArtisan = $request->input('artisan_select');
        $selectionSaison = $request->input('saison_select');

        return view('Consultation.CommandeListe', compact('commandes', 'saison', 'artisan','selectionArtisan','selectionSaison'));

    }

    public function detailCommande($id_artisan, $id_saison, $id_client){
        $nom_artisan = Artisan::find($id_artisan);
        session(['nom_artisan' => $nom_artisan->nom ]);

        // pour avoir la liste de typr commande dans filtre
        $type_commande = DB::table('v_detail_commande')
                    ->select('type_commande')
                    ->where('id_artisan', $id_artisan)
                    ->where('id_saison', $id_saison)
                    ->where('id_client', $id_client)
                    ->groupBy('type_commande')
                    ->get();

        $query = DB::table('v_detail_commande')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison)
                ->where('id_client', $id_client);

        $somme = DB::table('v_somme_commande')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison)
                ->where('id_client', $id_client)
                ->get();

        // $detail= $query->get();
        $detail= $query->paginate(150);

        // pour eviter la valuer null de selectionType
        $selectionType = "";
        
        return view('Consultation.CommandeDetail', compact('detail', 'type_commande', 'somme','selectionType', 'id_artisan', 'id_saison', 'id_client'));
    }

    public function filtreCommande(Request $request){
        $id_artisan= $request->input('id_artisan');
        $id_saison= $request->input('id_saison');
        $id_client= session('id_client');
        $type_commande_select= $request->input('type_commande_select');
        $recherche = $request->input('recherche');

        $nom_artisan = Artisan::find($id_artisan);

        if($id_artisan != null){
            session(['nom_artisan' => $nom_artisan->nom]);
            session(['id_artisan' => $id_artisan]);
            session(['id_saison' => $id_saison]);            
        }

        $type_commande = DB::table('v_detail_commande')
                    ->select('type_commande')
                    ->where('id_artisan', session('id_artisan'))
                    ->where('id_saison', session('id_saison'))
                    ->where('id_client', session('id_client'))
                    ->groupBy('type_commande')
                    ->get();

        $query = DB::table('v_detail_commande')
                ->where('id_artisan', session('id_artisan'))
                ->where('id_saison', session('id_saison'))
                ->where('id_client', session('id_client'));

        if ($type_commande_select) {
            $query->where('type_commande', $type_commande_select);

            $somme = DB::table('v_somme_commande_type')
            ->where('id_artisan', $id_artisan)
            ->where('id_saison', $id_saison)
            ->where('id_client', $id_client)
            ->where('type_commande', $type_commande_select)
            ->get();
        }else{
            $somme = DB::table('v_somme_commande')
            ->where('id_artisan', $id_artisan)
            ->where('id_saison', $id_saison)
            ->where('id_client', $id_client)
            ->get();

        }

        if ($recherche){
            $query->where(function ($q) use ($recherche) {
                $pattern = "%{$recherche}%";        // reste lié, donc protégé
            
                $q->where('designation_produit',    'ilike', $pattern)
                  ->orWhere('type_commande',        'ilike', $pattern)
                  ->orWhere('designation_catalogue','ilike', $pattern)
                  ->orWhere('couleur',              'ilike', $pattern);
            });
        }

        $detail= $query->paginate(150);

        $selectionType = $type_commande_select;
        return view('Consultation.CommandeDetail', compact('detail', 'type_commande', 'somme', 'selectionType', 'id_artisan', 'id_saison', 'id_client'));

    }

    public function exportPdf(Request $request) {
        $typeSelect = $request->input('type_select');
        $id_artisan = $request->input('id_artisan'); 
        $id_saison = $request->input('id_saison');

        $total = DB::table('v_somme_commande')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->where('id_client', session('id_client'))
        ->get();

        $query = DB::table('v_detail_commande')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison)
                ->where('id_client', session('id_client'));


        if($typeSelect){
            $query->where('type_commande', $typeSelect);

            $query2 =  DB::table('v_somme_commande_type')
            ->where('id_artisan', $id_artisan)
            ->where('id_saison', $id_saison)
            ->where('type_commande', $typeSelect)
            ->where('id_client', session('id_client'))
            ->get();

            $total=$query2;
        }

        $detail = $query->get();

        $pdf = Pdf::loadView('PDF.commande', compact('detail','total'));


        // pour le nom du fichier
        $artisan = Artisan::find($id_artisan);
        $saison = Saison::find($id_saison);
        $client = Client::find(session('id_client'));
        $date = Carbon::now()->format('d-m-Y');

        $nomFichier = "Commande_{$artisan->nom}_{$saison->nom_saison}_{$client->nom_client}_{$date}.pdf";

        return $pdf->download($nomFichier);
    }

    public function modifierListe($id_artisan, $id_saison, $id_client){
        $detail = DB::table('commande_detail as cd')
            ->join('commande as c', 'c.id', '=', 'cd.id_commande')
            ->join('produit as p', 'p.id', '=', 'cd.id_produit')
            ->join('couleur as co', 'co.id', '=', 'cd.id_couleur')
            ->select(
                'c.id as id_commande',
                'c.id_saison as id_saison',
                'c.id_artisan as id_artisan',
                'c.id_client as id_client',
                'cd.id',
                'p.id as id_produit',
                'c.type_commande',
                'c.date_commande',
                'p.designation_produit',
                'p.designation_catalogue',
                'co.nom as couleur',
                'co.id as id_couleur',
                'cd.prix_unitaire',
                'cd.qte',
                'cd.created_at'
            )
            ->where('c.id_saison', $id_saison)
            ->where('c.id_artisan', $id_artisan)
            ->where('c.id_client', $id_client)
            ->get();

        return view('Consultation.CommandeModifier', compact('detail'));

    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'prix_unitaire' => 'required|numeric|min:0',
            'qte' => 'required|integer|min:0',
        ]);

        $detail = Commande_detail::findOrFail($id);

        $detail->prix_unitaire = $validated['prix_unitaire'];
        $detail->qte = $validated['qte'];

        $detail->save();

        return response()->json([
            'success' => true,
            'prix_unitaire' => $detail->prix_unitaire,
            'qte' => $detail->qte
        ]);
    }

    public function delete($id){
        $detail = Commande_detail::findOrFail($id);

        $detail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ligne supprimée avec succès'
        ]);
    }

    public function exportExcel(Request $request){
        $typeSelect = $request->input('type_select');
        $id_artisan = $request->input('id_artisan');
        $id_saison = $request->input('id_saison');

        // pour le nom du fichier
        $artisan = Artisan::find($id_artisan);
        $saison = Saison::find($id_saison);
        $client = Client::find(session('id_client'));
        $date = Carbon::now()->format('d-m-Y');

        $nomFichier = "Commande_{$artisan->nom}_{$saison->nom_saison}_{$client->nom_client}_{$date}.xlsx";
        
        return Excel::download(
            new CommandeExport($typeSelect, $id_artisan, $id_saison),
            $nomFichier
        );
    }
 
}