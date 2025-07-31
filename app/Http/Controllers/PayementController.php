<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Payement;
use App\Models\Artisan;
use App\Models\Saison;

class PayementController extends Controller
{
    public function payementFormulaire(){
        $artisan = Artisan::all();
        $saison = Saison::all();

        return view('Formulaire.Payement', compact('artisan', 'saison'));
    }

    public function insertPayement(Request $request){

        $id_saison = $request->input('id_saison');
        $id_artisan = $request->input('id_artisan');
        $montant = $request->input('montant');
        $detail_payement = $request->input('detail_payement');
        $date_payement = $request->input('date_payement');


        Validator::make($request->all(), [ 
            'montant' => 'required|numeric|min:1',
            'date_payement' => 'required',
            'detail_payement' => 'required',
        ],[
            'montant.required' => 'montant requis',
            'montant.min' => 'le montant doit etre superieur à 0',
            'date_payement.required' => 'la date est requise',
            'detail_payement.required' => 'detail requis',
        ])-> validate();

        $payement = Payement::create([
            'id_saison' => $id_saison,
            'id_artisan' => $id_artisan,
            'detail_payement' => $detail_payement,
            'montant' => $montant,
            'date_payement' => $date_payement,
        ]);
        return back()->with('success', 'Payement sauvegardé');
    }

    public function payementListe(Request $request){
        $artisan_select= $request->input('artisan_select');
        $saison_select= $request->input('saison_select');

        $query = DB::table('v_liste_payement');

        if($saison_select){
            $query->where('id_saison', $saison_select);
        }

        if($artisan_select){
            $query->where('id_artisan', $artisan_select);
        }

        $payement = $query->get();
        $saison = Saison::all();
        $artisan = Artisan::all();

        $selectionSaison = $saison_select; 
        $selectionArtisan = $artisan_select; 

        return view('Consultation.Payement_artisan', compact('payement', 'saison', 'artisan', 'selectionSaison', 'selectionArtisan'));
    }    

    public function detailPayement($id_artisan, $id_saison){
        $detail = DB::table('v_payement')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison)
                ->get();

        $resume = DB::table('v_liste_payement')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison)
                ->get();

        $nom_artisan = Artisan::find($id_artisan);
        session(['nom_artisan' => $nom_artisan->nom ]);

        return view('Consultation.Detail_payement', compact('detail','resume'));
    }
}