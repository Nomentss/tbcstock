<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GeneralImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $ligne = 0;
        foreach ($rows as $row) {
            $ligne++;

            if($ligne === 1){
                dump("miditra 1ere colonne");
            }


            if($ligne === 4){
                dd($row['saison']);
            }

            // DB::table('importsaison')->insert([
            //     'nom_saison' => $row['saison'],
            // ]);




            // DB::table('importartisan')->insert([
            //     'nom' => $row['artisan'],
            //     'telephone' => $row['telephone'],
            //     'mail' => $row['mail'],
            // ]);

            // DB::table('importproduit')->insert([
            //     'designation_produit' => $row['designation_produit'],
            //     'designation_catalogue' => $row['designation_catalogue'],
            //     'commande' => $row['commande'],
            //     'prix_unitaire' => $row['prix_unitaire'],
            // ]);
        }
    }
}