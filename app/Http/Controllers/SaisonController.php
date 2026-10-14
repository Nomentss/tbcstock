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
            'nom_saison' => 'required|unique:saison,nom_saison',
        ],[
            'date_debut.required' => 'date requise',
            'date_fin.required' => 'date requise',
            'nom_saison.required' => 'le nom de saison est requis',
            'nom_saison.unique' => 'le nom de saison existe déjà.',
        ])-> validate();

        $saison = Saison::create([
            'debut' => $date_debut,
            'fin' => $date_fin,
            'nom_saison' => $nom_saison,

        ]);

        return back()->with('success', 'saison ajoutée');
    }

    public function saisonFormulaire(){

        $saison = Saison::all();
        return view('Formulaire.Saison', compact('saison'));

    }

    public function deleteSaison($id){
        $saison = Saison::find($id);
        $saison->delete();
        return back()->with('success', 'saison supprimée');
        
    }

    public function updateSaison(Request $request, $id){
        $request->validate([
            'nom_saison' => 'required|string|max:255',
            'debut' => 'required',
            'fin' => 'required',
        ]);

        $saison = Saison::findOrFail($id);
        $saison->nom_saison = $request->nom_saison;
        $saison->debut = $request->debut;
        $saison->fin = $request->fin;
        $saison->save();

        return redirect()->back()->with('success', 'Saison modifié avec succès.');
    }
}