<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home(){
        return view('Layout.app');
    }

    public function loginform(){
        return view('auth.login');
    }

    public function registerform(){
        return view('auth.register');
    }

    // public function saisonFormulaire() {
    //     return view('Formulaire.saison');
    // }

    // public function CouleurFormulaire() {
    //     return view('Formulaire.Couleur');
    // }

    // public function LivraisonFormulaire() {
    //     return view('Formulaire.Livraison');
    // }

}