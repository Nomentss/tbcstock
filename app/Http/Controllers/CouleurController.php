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

        $couleur = Couleur::create([
            'nom' => $nom_couleur,
        ]);
    }
}