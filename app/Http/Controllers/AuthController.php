<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginAction(Request $request){
        // Validator::make($request->all(), [
        //     'username' => 'required',
        //     'password' => 'required',
        // ],[
        //     'username.required' => 'L\'identifiant est requis.',
        //     'password.required' => 'mot de passe est requis.',

        // ])-> validate();

        return redirect()->route('PageController.home');

    }
}
