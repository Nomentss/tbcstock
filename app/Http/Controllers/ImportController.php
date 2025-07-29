<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\GeneralImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importAction(Request $request) {
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new GeneralImport, $request->file('file'));

        return back()->with('success', 'Importation réussie !');
    }
}