<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Produit;
use App\Models\Couleur;
use App\Models\Produit_couleur;
use App\Models\Commande;
use App\Models\Commande_detail;

class GeneralImport implements ToCollection, WithHeadingRow
{
    protected $saison;
    protected $typeCommande;
    protected $id_artisan;

    public function __construct(
        $saison,
        $typeCommande,
        $id_artisan
    ) {
        $this->saison = $saison;
        $this->typeCommande = $typeCommande;
        $this->id_artisan = $id_artisan;
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

                // insert commande (une fois)
                $commande = Commande::create([
                    'id_saison' => $this->saison,
                    'id_artisan' => $this->id_artisan,
                    'id_client' => session('id_client'),
                    'type_commande' => $this->typeCommande,
                ]);

            foreach($rows as $row){
                // 1. Récupérer les données Excel
                $nomProduit = $row['produit'] ?? null;
                $designationCatalogue = $row['designation_catalogue'] ?? null;
                $couleur = $row['couleur'] ?? null;
                $quantite = $row['quantite'] ?? null;
                $prixUnitaire = $row['prix_unitaire'] ?? null;
                
                // 2. Vérifier les données obligatoires
                if (!$nomProduit || !$designationCatalogue || !$couleur || !$quantite || !$prixUnitaire) {
                        continue;
                }

                // verifier si il y a deja sinon inserer
                $produit_cree = $this->create_produit($nomProduit, $this->id_artisan, $designationCatalogue);

                // verifier si il y a deja sinon inserer
                $couleur_cree = $this->create_couleur($couleur);

                // verifier si il y a deja sinon inserer
                $produit_couleur = $this->create_produit_couleur($produit_cree->id, $couleur_cree->id);

                // inserer commande_detail
                $commande_detail = Commande_detail::create([
                    'id_commande' => $commande->id,
                    'id_produit' => $produit_cree->id,
                    'id_couleur' => $couleur_cree->id,
                    'qte' => $quantite,
                    'prix_unitaire' => $prixUnitaire,
                ]);
            }
        });
    }

    // fonction pour verifier si il y deja la couleur sinon inserer 
    public function create_couleur($couleur){

        $couleurExistante = Couleur::where('nom', 'ILIKE', $couleur)->first();

        if(!$couleurExistante){
            $couleur = Couleur::create([
                'nom' => $couleur,
            ]);
        }else{
            $couleur = $couleurExistante;
        }
        return $couleur;
    }

    // fonction pour verifier si il y deja un produit sinon inserer
    public function create_produit($produit, $id_artisan, $designationCatalogue){

        $produitExistant = Produit::where('designation_produit', 'ILIKE', $produit)
        ->where('id_artisan', 'ILIKE', $id_artisan)
        ->first();

        if(!$produitExistant){
            $produit = Produit::create([
                'id_artisan' => $id_artisan,
                'designation_produit' => $produit,
                'designation_catalogue' => $designationCatalogue,
            ]);
        }else{

            $produit = $produitExistant;
        }

        return $produit;
    }

    public function create_produit_couleur($idproduit, $idcouleur){
        $produitCouleurExistant = Produit_couleur::where('id_produit', 'ILIKE', $idproduit)
        ->where('id_couleur', 'ILIKE', $idcouleur)
        ->first();

        if(!$produitCouleurExistant){
            $produit_couleur = Produit_couleur::create([
                'id_produit' => $idproduit,
                'id_couleur' => $idcouleur,
            ]);

        }else{
            $produit_couleur = $produitCouleurExistant;
        }

        return $produit_couleur;
    }
}