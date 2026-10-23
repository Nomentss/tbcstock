<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Livraison;
use App\Models\Livraison_detail;
use App\Models\Produit;
use App\Models\Stock;


class LivraisonImport implements ToCollection, WithHeadingRow
{
    protected $saison;
    protected $dateLivraison;
    protected $id_artisan;
    protected $bon_reception;


    public function __construct(
        $saison,
        $dateLivraison,
        $id_artisan,
        $bon_reception,
    ) {
        $this->saison = $saison;
        $this->dateLivraison = $dateLivraison;
        $this->id_artisan = $id_artisan;
        $this->bon_reception = $bon_reception;
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            // inserer livraison
            $livraison = $this->getLivraison($this->saison, $this->id_artisan);

            $erreurs = [];
            $donneesLivraison = [];

            foreach($rows as $index => $row){

            // Ignorer les lignes totalement vides
            if (collect($row)->filter(fn($v) => $v !== null && $v !== '')->isEmpty()) {
                continue;
            }
                // 1. Récupérer les données Excel
                $nomProduit = $row['produit'] ?? null;
                $designationCatalogue = $row['designation_catalogue'] ?? null;
                $couleur = $row['couleur'] ?? null;
                $quantite = $row['quantite'] ?? null;

                // 2. Vérifier les données obligatoires
                $champs = [
                    'produit'               => $nomProduit,
                    'designation_catalogue' => $designationCatalogue,
                    'couleur'               => $couleur,
                    'quantite'              => $quantite,
                ];
                // dd($row);

                $manquants = [];
                foreach ($champs as $nom => $valeur) {
                    // on teste vide, mais on accepte 0 pour la quantité
                    if ($valeur === null || trim((string) $valeur) === '') {
                        $manquants[] = $nom;
                    }
                }

                    if (!empty($manquants)) {
                        throw new \Exception(
                            'Fichier invalide : champ(s) manquant(s) [' . implode(', ', $manquants) . ']'
                            . ' à la ligne ' . $numeroLigne
                        );
                    }

                    $produit = $this->getProduit($nomProduit, $designationCatalogue, $couleur, $this->saison, $this->id_artisan);

                    $message = $this->verifierQuantite($produit, $quantite);

                    if ($message !== null) {
                        $erreurs[] = $message;
                    }

                    $donneesLivraison[] = [
                        'id_produit' => $produit->id_produit,
                        'id_couleur' => $produit->id_couleur,
                        'quantite' => $quantite,
                    ];

                    // // insertion detail livraison
                    // $this->insertDetailLivraison($livraison->id, $produit->id_produit, $produit->id_couleur, $quantite, $this->dateLivraison, $this->bon_reception);

                    // // insertion stock
                    // $this->addStock($this->saison, $produit->id_produit, $produit->id_couleur, $quantite, $this->dateLivraison);
            }
                // Après la boucle
            if (!empty($erreurs)) {

                session([
                    'livraisons_en_attente' => $donneesLivraison,
                ]);

                session([
                    'livraison' => $livraison,
                ]);

                session([
                    'parametres_livraison' => [
                        'saison' => $this->saison,
                        'dateLivraison' => $this->dateLivraison,
                        'id_artisan' => $this->id_artisan,
                        'bon_reception' => $this->bon_reception,
                    ]
                ]);

                return back()
                    ->withInput()
                    ->with('erreurs_commande', $erreurs)
                    ->with('route_confirmation', route('importController.confirmerImport'));
            }else{
                //insertion directe
                $this->confirmerLivraison($donneesLivraison, $livraison);
            }
        });   
    }

    public function getLivraison($id_saison, $id_artisan) {
        $livraisonExistante = Livraison::where('id_saison', $id_saison)
        ->where('id_artisan', $id_artisan)
        ->where('id_client', session('id_client'))
        ->first();

        if(!$livraisonExistante){
            $livraison = Livraison::create([
                'id_saison' => $this->saison,
                'id_artisan' => $this->id_artisan,
                'id_client' => session('id_client'),
            ]);

        }else{
                $livraison = $livraisonExistante;
        }

        return $livraison;
    }

    public function insertDetailLivraison($id_livraison, $id_produit, $id_couleur, $quantite, $dateLivraison, $bon_reception) {

        $LivraisonDetail = Livraison_detail::create([
            'id_livraison' => $id_livraison,
            'id_produit' => $id_produit,
            'id_couleur' => $id_couleur,
            'qte_livre' => $quantite,
            'date_livraison' => $dateLivraison,
            'bon_reception' => $bon_reception,
        ]);

    }

    public function getProduit($nomProduit, $designationCatalogue, $couleur, $id_saison, $id_artisan) {
        // $produitExistant = Produit::where('designation_produit', 'ILIKE', $nomProduit)
        // ->where('designation_catalogue', 'ILIKE', $designationCatalogue)
        // ->first();

        // $query = DB::table('v_detail_commande')
        // ->where('designation_produit', 'ILIKE', $nomProduit)
        // ->where('designation_catalogue', 'ILIKE', $designationCatalogue)
        // ->where('couleur', 'ILIKE', $couleur)
        // ->where('id_artisan', $id_artisan)
        // ->where('id_saison', $id_saison);
        // ->first();

        $query = DB::table('v_detail_commande')
        ->whereRaw(
            "REGEXP_REPLACE(TRIM(designation_produit), '\s+', ' ', 'g') ILIKE REGEXP_REPLACE(TRIM(?), '\s+', ' ', 'g')",
            [$nomProduit]
        )
        ->whereRaw(
            "REGEXP_REPLACE(TRIM(designation_catalogue), '\s+', ' ', 'g') ILIKE REGEXP_REPLACE(TRIM(?), '\s+', ' ', 'g')",
            [$designationCatalogue]
        )
        ->whereRaw(
            "REGEXP_REPLACE(TRIM(couleur), '\s+', ' ', 'g') ILIKE REGEXP_REPLACE(TRIM(?), '\s+', ' ', 'g')",
            [$couleur]
        )
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->where('id_client', session('id_client'));

        $produitExistant = $query->first();

        if(!$produitExistant){
        
            throw new \Exception(
                "Le produit \"$nomProduit\" avec le nom catalogue \"$designationCatalogue\" de couleur \"$couleur\" n'existe pas dans la commande."
            );
        }

        return $produitExistant;
    }

    public function addStock($id_saison, $id_produit, $id_couleur, $quantite, $dateLivraison){

        $stock = Stock::create([
            'id_saison' => $id_saison,
            'id_produit' => $id_produit,
            'id_couleur' => $id_couleur,
            'qte_livre' => $quantite,
            'date_livraison' => $dateLivraison,
            'id_client' => session('id_client'),
        ]);
    }

    public function verifierQuantite($produit, $quantite_livre){
        $quantite_commande = $produit->qte;

        if($quantite_commande < $quantite_livre){

            return "le produit \"$produit->designation_produit\" de couleur \"$produit->couleur\", quantité commande = $quantite_commande et quantité livraison = $quantite_livre.";
        }

        return null;
    }

    public function confirmerLivraison($donnees, $livraison){

        if (empty($donnees)) {
            return back()->with('error', 'Aucune livraison en attente');
        }

        DB::transaction(function () use ($donnees, $livraison) {

            foreach ($donnees as $ligne) {

                // Insertion détail livraison
                $this->insertDetailLivraison(
                    $livraison->id,
                    $ligne['id_produit'],
                    $ligne['id_couleur'],
                    $ligne['quantite'],
                    $this->dateLivraison,
                    $this->bon_reception
                );

                // Insertion stock
                $this->addStock(
                    $this->saison,
                    $ligne['id_produit'],
                    $ligne['id_couleur'],
                    $ligne['quantite'],
                    $this->dateLivraison
                );
            }
        });

        session()->forget('livraisons_en_attente');
        session()->forget('livraison');
        session()->forget('erreurs_commande');

        return back()->with('success', 'Livraisons enregistrées');
    }
}