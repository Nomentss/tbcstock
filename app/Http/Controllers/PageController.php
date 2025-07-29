<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home(){
        return view('Home.home');
    }

    public function loginform(){
        return view('auth.login');
    }

    public function registerform(){
        return view('auth.register');
    }

    public function saisonFormulaire() {
        return view('Formulaire.saison');
    }

    public function CouleurFormulaire() {
        return view('Formulaire.Couleur');
    }

    public function LivraisonFormulaire() {
        return view('Formulaire.Livraison');
    }

    public function payementFormulaire() {
        return view('Formulaire.Payement');   
    }

    public function ArtisanFormulaire() {
        return view('Formulaire.Artisan');
    }

    public function importPage(){
        return view('Import.test_import');
    }
    
}