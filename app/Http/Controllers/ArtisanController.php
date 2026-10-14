<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;


class ArtisanController extends Controller
{
    public function insert(Request $request){

        $nom = $request->input('nom_artisan'); 
        $telephone = $request->input('telephone'); 
        $mail = $request->input('mail');

        Validator::make($request->all(), [
            'nom_artisan' => 'required|unique:artisan,nom',  
        ],[
            'nom_artisan.required' => 'le nom est requis',
            'nom_artisan.unique' => 'ce nom existe déjà.',
        ])-> validate();

        $existe = DB::table('artisan')
            ->whereRaw('LOWER(nom) = LOWER(?)', [
                $request->nom_artisan
            ])
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'nom_artisan' => 'Ce nom existe déjà.'
            ])->withInput();
        }

        $artisan = Artisan::create([
            'nom' => $nom,
            'telephone' => $telephone,
            'mail' => $mail,
        ]);

        return back()->with('success', 'Artisan ajouté(e)');
    }

    public function listeArtisan(){
        $artisan = Artisan::all();
        return view('Formulaire.Artisan', compact('artisan'));
    }

    public function deleteArtisan($id){
        $artisan = Artisan::find($id);
        $artisan->delete();
        return back()->with('success', 'Artisan supprimé');
    }

    public function ArtisanFormulaire(){
        $artisan = Artisan::all();
        return view('Formulaire.Artisan', compact('artisan'));
    }

    public function updateArtisan(Request $request, $id){
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $artisan = Artisan::findOrFail($id);
        $artisan->nom = $request->nom;
        $artisan->telephone = $request->telephone;
        $artisan->mail = $request->mail;
        $artisan->save();

        return redirect()->back()->with('success', 'Artisan modifié avec succès.');
    }
}