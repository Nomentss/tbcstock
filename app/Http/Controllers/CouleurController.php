<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Couleur;

class CouleurController extends Controller
{
    public function insert(Request $request){        
        $nom_couleur = $request->input('nom_couleur');

        Validator::make($request->all(), [
            'nom_couleur' => 'required',    
        ],[
            'nom_couleur.required' => 'couleur requise',
        ])-> validate();

        $couleur = Couleur::create([
            'nom' => $nom_couleur,
        ]);

        return back()->with('success', 'Couleur ajoutée');
    }

    public function CouleurFormulaire(){
        $couleur = Couleur::all();
        return view('Formulaire.Couleur', compact('couleur'));
    }

    public function deleteCouleur($id){
        $couleur = Couleur::find($id);

        $couleur->delete();

        return back()->with('success', 'Couleur supprimée');
    }
}