<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function loginAction(Request $request){
        Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ],[
            'username.required' => 'L\'identifiant est requis.',
            'password.required' => 'Mot de passe est requis.',

        ])-> validate();

         // entre dans if si authentification echoue
            // verifie colonne email et colonne password dans base de donnee
            if (!Auth::attempt($request->only('username', 'password'), $request->boolean('remember'))) {
                throw ValidationException::withMessages([
                'connexion' => trans('Identifiant ou mot de passe non valide')
                ]);
            }

            return redirect()->route('PageController.home');

    }

    public function registerAction(Request $request){

        Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ],[
            'username.required' => 'L\'identifiant est requis.',
            'password.required' => 'Mot de passe est requis.',
            'confirm_password.required' => 'Champ obligatoire.',
            'confirm_password.same' => 'le mot de passe ne corespond pas.',

        ])-> validate();

        User::create([
            'username' => $request->username,
            // 0 par defaut admin
            'level' => 0,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('PageController.home');

    }
    
    public function logout(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
