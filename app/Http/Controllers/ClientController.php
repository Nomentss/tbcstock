<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;


class ClientController extends Controller
{
    public function select(Request $request)
    {
        // dd("miditra");
        $nom_client = Client::where('id', $request->id_client)->value('nom_client');

        session([
            'id_client'  => $request->id_client,
            'nom_client'  => $nom_client,
        ]);

        return back();
    }

    public function clientFormulaire(){
        $client = Client::all();
        return view('Formulaire.Client', compact('client'));
    }

    public function deleteClient($id){
        $client = Client::find($id);

        $client->delete();

        return back()->with('success', 'Client supprimé');
    }

     public function insertClient(Request $request){        
        $nom_client = $request->input('nom_client');

        Validator::make($request->all(), [
            'nom_client' => 'required|unique:client,nom_client',    
        ],[
            'nom_client.required' => 'nom client requis',
            'nom_client.unique' => 'Ce client existe déjà.',
        ])-> validate();

        $existe = DB::table('client')
            ->whereRaw('LOWER(nom_client) = LOWER(?)', [
                $request->nom_client
            ])
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'nom_client' => 'Ce client existe déjà.'
            ])->withInput();
        }

        $client = Client::create([
            'nom_client' => $nom_client,
        ]);

        return back()->with('success', 'Client ajoutée');
    }

    public function getClient() {
        $client = Client::all();
        return response()->json($client);
    }

    public function updateClient(Request $request, $id){
        $request->validate([
            'nom_client' => 'required|string|max:255',
        ]);

        $client = Client::findOrFail($id);
        $client->nom_client = $request->nom_client;
        $client->save();

        return redirect()->back()->with('success', 'Client modifié avec succès.');
    }
}
