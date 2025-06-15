<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saison;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SaisonController extends Controller
{
    public function insert(Request $request){
        $date_debut = $request->input('date_debut');
        $date_fin = $request->input('date_fin');
        $nom_saison = $request->input('nom_saison');


        Validator::make($request->all(), [
            'date_debut' => 'required',    
            'date_fin' => 'required',
            'nom_saison' => 'required',
        ],[
            'date_debut.required' => 'date requise',
            'date_fin.required' => 'date requise',
            'nom_saison.required' => 'le nom de saison est requis',
        ])-> validate();

        $saison = Saison::create([
            'debut' => $date_debut,
            'fin' => $date_fin,
            'nom_saison' => $nom_saison,

        ]);

        return back()->with('success', 'saison ajoutée');
    }
}