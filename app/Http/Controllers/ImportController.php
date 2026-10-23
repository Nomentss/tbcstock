<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\GeneralImport;
use App\Imports\LivraisonImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function importAction(Request $request) {
        
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        $saison = $request->saison;
        $typeCommande = $request->type_commande;
        $id_artisan = $request->id_artisan;

        // dd($saison);

        Excel::import(
            new GeneralImport(
                $saison,
                $typeCommande,
                $id_artisan
            ),
            $request->file('file')
        );

        return back()->with('success', 'Importation réussie !');
    }

    public function importLivraison(Request $request){

        $request->validate([
            'file' => 'required|mimes:xlsx',
            'id_saison' => 'required',
            'date_livraison' => 'required',
            'id_artisan' => 'required',
            'bon_reception' => 'required',
        ],[
            'date_livraison.required' => 'La date est obligatoire.',
            'bon_reception.required' => 'bon reception requis.',
        ]);

        $saison = $request->id_saison;
        $dateLivraison = $request->date_livraison;
        $bon_reception = $request->bon_reception;
        $id_artisan = $request->id_artisan;


        try{
            Excel::import(
                new LivraisonImport(
                    $saison,
                    $dateLivraison,
                    $id_artisan,
                    $bon_reception,
                ),
                $request->file('file')
            );
            
            return redirect()->back()->with('success', 'Importation réussie !');

        }catch(\Exception $e){
            return redirect()->back()
            ->with('fail', $e->getMessage())
            ->withInput();
        }
    }

    public function confirmerImport(){
        $donnees = session('livraisons_en_attente', []);
        $parametres = session('parametres_livraison');

        // pour iserer id_livraison
        $livraison = session('parametres_livraison');


        if (empty($donnees)) {
            return back()->with('error', 'Aucune livraison à confirmer');
        }

        DB::transaction(function () use ($donnees, $parametres, $livraison) {

            $import = new LivraisonImport(
                $parametres['saison'],
                $parametres['dateLivraison'],
                $parametres['id_artisan'],
                $parametres['bon_reception']
            );

            $import->confirmerLivraison($donnees, $livraison);
        });

        session()->forget([
            'parametres_livraison',
            'livraisons_en_attente',
            'livraison'
        ]);

        return back()->with('success', 'Livraison enregistrée avec succès');
    }
}