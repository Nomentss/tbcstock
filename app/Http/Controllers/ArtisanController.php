<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ArtisanController extends Controller
{
    public function insert(Request $request){

        $nom = $request->input('nom_artisan'); 
        $telephone = $request->input('telephone'); 
        $mail = $request->input('mail');

        Validator::make($request->all(), [
            'nom_artisan' => 'required',  
        ],[
            'nom_artisan.required' => 'le nom est requis',
        ])-> validate();

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
        return back()->with('success', 'artisan supprimé');
    }

    public function updateArtisan(){
        
    }

    public function ArtisanFormulaire(){
        $artisan = Artisan::all();
        return view('Formulaire.Artisan', compact('artisan'));
    }
}