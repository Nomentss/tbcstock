<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Couleur;
use Illuminate\Support\Facades\DB;

class CouleurController extends Controller
{
    public function insert(Request $request){        
        $nom_couleur = $request->input('nom_couleur');

        Validator::make($request->all(), [
            'nom_couleur' => 'required|unique:couleur,nom',    
        ],[
            'nom_couleur.required' => 'couleur requise',
            'nom_couleur.unique' => 'Cette couleur existe déjà',
        ])-> validate();

        $existe = DB::table('couleur')
            ->whereRaw('LOWER(nom) = LOWER(?)', [
                $request->nom_couleur
            ])
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'nom_couleur' => 'Cette couleur existe déjà'
            ])->withInput();
        }

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

    public function updateCouleur(Request $request, $id){
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $couleur = Couleur::findOrFail($id);
        $couleur->nom = $request->nom;
        $couleur->save();

        return redirect()->back()->with('success', 'Couleur modifié avec succès.');
    }
}