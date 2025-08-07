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

class CommandeController extends Controller
{
    public function CommandeFormulaire(){
        $artisan = Artisan::all();

        $couleur = Couleur::all();

        $saison = Saison::all();

        return view('Formulaire.commande', compact('artisan','couleur','saison'));  
    }

    public function insertCommande(Request $request){
        $id_artisan = $request->input('id_artisan');
        $date_commande = $request->input('date_commande');
        $saison = $request->input('saison');
        $type_commande = $request->input('type_commande');
    
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

                // $commande = Commande::create([
                //     'id_saison' => $saison,
                //     'id_artisan' => $id_artisan,
                //     'type_commande' => $type_commande,
                //     // 'date_commande' => $date_commande,
                // ]);

                // $commande_detail = Commande_detail::create([
                //     'id_commande' => $commande->id,
                //     'id_produit'=> $id_produit_commande,
                //     'id_couleur'=> $id_couleur,
                //     'qte'=> $qte_commande,
                //     'prix_unitaire'=> $prix_unitaire,
                
                // ]);
                
            // }
            // else{
            //     return back()->with('fail', 'La date ne correspond pas à une saison !');
            // }
        }
        return back()->with('success', ' Commande sauvegardée !');
    }

    // public function SaisonCorrespondante($date){
    //     $saison = Saison::where('debut', '<=', $date)
    //             ->where('fin', '>=', $date)
    //             ->first();

    //     if($saison) {

    //         return $saison->id;
    //     }
    //     else{
    //         return null;
    //     }
    // }

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
        ->select(
            'c.id_saison',
            'c.id_artisan',
            'a.nom as artisan',
            's.nom_saison as saison'
        )
        ->groupBy('c.id_saison', 'c.id_artisan', 'a.nom', 's.nom_saison');

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

    public function detailCommande($id_artisan, $id_saison){
        $nom_artisan = Artisan::find($id_artisan);
        session(['nom_artisan' => $nom_artisan->nom ]);

        // pour avoir la liste de typr commande dans filtre
        $type_commande = DB::table('v_detail_commande')
                    ->select('type_commande')
                    ->where('id_artisan', $id_artisan)
                    ->where('id_saison', $id_saison)
                    ->groupBy('type_commande')
                    ->get();

        $query = DB::table('v_detail_commande')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison);

        $somme = DB::table('v_somme_commande')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison)
                ->get();

        // $detail= $query->get();
        $detail= $query->paginate(150);

        // pour eviter la valuer null de selectionType
        $selectionType = "";
        
        return view('Consultation.CommandeDetail', compact('detail', 'type_commande', 'somme','selectionType'));
    }

    public function filtreCommande(Request $request){
        $id_artisan= $request->input('id_artisan');
        $id_saison= $request->input('id_saison');
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
                    ->groupBy('type_commande')
                    ->get();

        $query = DB::table('v_detail_commande')
                ->where('id_artisan', session('id_artisan'))
                ->where('id_saison', session('id_saison'));

        if ($type_commande_select) {
            $query->where('type_commande', $type_commande_select);

            $somme = DB::table('v_somme_commande_type')
            ->where('id_artisan', $id_artisan)
            ->where('id_saison', $id_saison)
            ->where('type_commande', $type_commande_select)
            ->get();
        }else{
            $somme = DB::table('v_somme_commande')
            ->where('id_artisan', $id_artisan)
            ->where('id_saison', $id_saison)
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
        return view('Consultation.CommandeDetail', compact('detail', 'type_commande', 'somme', 'selectionType'));

    }

    public function exportPdf(Request $request) {
        $typeSelect = $request->input('type_select');
        $id_artisan = $request->input('id_artisan'); 
        $id_saison = $request->input('id_saison');

        $total = DB::table('v_somme_commande')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->get();

        $query = DB::table('v_detail_commande')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison);


        if($typeSelect){
            $query->where('type_commande', $typeSelect);

            $query2 =  DB::table('v_somme_commande_type')
            ->where('id_artisan', $id_artisan)
            ->where('id_saison', $id_saison)
            ->where('type_commande', $typeSelect)
            ->get();

            $total=$query2;
        }

        $detail = $query->get();

        $pdf = Pdf::loadView('PDF.commande', compact('detail','total'));
        return $pdf->download('commande.pdf');
    }
}