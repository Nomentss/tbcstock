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

                // dd($row);

                // 2. Vérifier les données obligatoires
                if (!$nomProduit || !$designationCatalogue || !$couleur || !$quantite ) {
                    throw new \Exception(
                        'Veuillez vérifier votre fichier'
                    );
                }
                    $produit = $this->getProduit($nomProduit, $designationCatalogue, $couleur, $this->saison, $this->id_artisan);
                    // insertion detail livraison
                    $this->insertDetailLivraison($livraison->id, $produit->id_produit, $produit->id_couleur, $quantite, $this->dateLivraison, $this->bon_reception);

                    // insertion stock
                    $this->addStock($this->saison, $produit->id_produit, $produit->id_couleur, $quantite, $this->dateLivraison);
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

        $produitExistant = DB::table('v_detail_commande')
        ->where('designation_produit', 'ILIKE', $nomProduit)
        ->where('designation_catalogue', 'ILIKE', $designationCatalogue)
        ->where('couleur', 'ILIKE', $couleur)
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->first();

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
}