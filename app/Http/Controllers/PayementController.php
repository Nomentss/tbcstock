<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Payement;
use App\Models\Artisan;
use App\Models\Saison;
use Barryvdh\DomPDF\Facade\Pdf;


class PayementController extends Controller
{
    public function payementFormulaire(){
        $artisan = Artisan::all();
        $saison = Saison::all();

        return view('Formulaire.Payement', compact('artisan', 'saison'));
    }

    public function insertPayement(Request $request){

        $id_saison = $request->input('id_saison');
        $id_artisan = $request->input('id_artisan');
        $montant = $request->input('montant');
        $detail_payement = $request->input('detail_payement');
        $date_payement = $request->input('date_payement');
        $numero_piece = $request->input('numero_piece');
        $type_payement = $request->input('type_payement');
        $id_client = session('id_client');

        Validator::make($request->all(), [ 
            'montant' => 'required|numeric|min:1',
            'detail_payement' => 'required',
            // 'numero_piece' => 'required',
            'date_payement' => 'required',
        ],[
            'montant.required' => 'montant requis',
            'montant.numeric' => 'montant en chiffre uniquement',
            'montant.min' => 'le montant doit etre superieur à 0',
            'detail_payement.required' => 'detail requis',
            // 'numero_piece.required' => 'numero ou piece de cheque requis',
            'date_payement.required' => 'la date est requise',
        ])-> validate();

        $payement = Payement::create([
            'id_saison' => $id_saison,
            'id_artisan' => $id_artisan,
            'type_payement' => $type_payement,
            'detail_payement' => $detail_payement,
            'numero_piece_cheque' => $numero_piece,
            'montant' => $montant,
            'date_payement' => $date_payement,
            'id_client' => $id_client,
        ]);
        return back()->with('success', 'Payement sauvegardé');
    }

    public function payementListe(Request $request){
        $artisan_select= $request->input('artisan_select');
        $saison_select= $request->input('saison_select');

        $query = DB::table('v_liste_payement')->where('id_client', session('id_client'));

        if($saison_select){
            $query->where('id_saison', $saison_select);
        }

        if($artisan_select){
            $query->where('id_artisan', $artisan_select);
        }

        $payement = $query->get();
        $saison = Saison::all();
        $artisan = Artisan::all();

        $selectionSaison = $saison_select; 
        $selectionArtisan = $artisan_select; 

        return view('Consultation.Payement_artisan', compact('payement', 'saison', 'artisan', 'selectionSaison', 'selectionArtisan'));
    }    

    public function detailPayement($id_artisan, $id_saison){
        $detail = DB::table('v_payement')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison)
                ->where('id_client', session('id_client'))
                ->get();

        $resume = DB::table('v_liste_payement')
                ->where('id_artisan', $id_artisan)
                ->where('id_saison', $id_saison)
                ->where('id_client', session('id_client'))
                ->get();

        $nom_artisan = Artisan::find($id_artisan);
        session(['nom_artisan' => $nom_artisan->nom ]);

        return view('Consultation.Detail_payement', compact('detail','resume', 'id_artisan', 'id_saison'));
    }

    public function exportPdfPayement(Request $request) {

        $id_artisan = $request->input('id_artisan');
        $id_saison = $request->input('id_saison');

        $detail = DB::table('v_payement')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->get();

        $resume = DB::table('v_liste_payement')
        ->where('id_artisan', $id_artisan)
        ->where('id_saison', $id_saison)
        ->get();

        $pdf = Pdf::loadView('PDF.payement', compact('detail','resume'));
        return $pdf->download('payement_detail.pdf'); 
    }

    public function modifierListe($id_artisan, $id_saison, $id_client){
        $detail = Payement::where('id_saison', $id_saison)
            ->where('id_artisan', $id_artisan)
            ->where('id_client', $id_client)
            ->get();

        return view('Consultation.PayementModifier', compact('detail'));
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'montant' => 'required|min:0',
            'detail' => 'required',
        ]);

        $detail = Payement::findOrFail($id);

        $detail->montant = $validated['montant'];
        $detail->detail_payement = $validated['detail'];

        $detail->save();

        return response()->json([
            'success' => true,
            'montant' => $detail->montant,
            'detail' => $detail->detail_payement
        ]);
    }

    public function delete($id){
        $detail = Payement::findOrFail($id);

        $detail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ligne supprimée avec succès'
        ]);
    }
}