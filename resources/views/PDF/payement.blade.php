<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Commande PDF</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
    
        body {
            font-family: sans-serif;
            font-size: 10px; /* plus petit pour mieux tenir */
        }
    
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
    
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* important pour que les colonnes s'adaptent */
            margin-top: 10px;
        }
    
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            font-size: 10px;
            word-wrap: break-word; /* coupe les longs mots */
            overflow: hidden;
            text-overflow: ellipsis;
        }
    
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h2>Detail payement de l'artisan "{{ session('nom_artisan') }}"</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Date payement</th>
                <th scope="col">Numero cheque / piece de cheque</th>
                <th scope="col">Detail</th>
                <th scope="col">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $d)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($d->date_payement)->translatedFormat('d F Y') }}</td>
                    <td>{{$d->numero_piece_cheque}}</td>
                    <td>{{$d->detail_payement}}</td>
                    <td>{{number_format($d->montant, 0, ',', ' ')}} AR</td>                        
                </tr> 
            @endforeach
        </tbody>
    </table>
    <table class="table table-bordered">
        <thead>
            @foreach ($resume as $r) 
            <tr>
            
                <th scope="col" style="text-align: center">TOTAL PAYEMENT</th>
                <td scope="col" style="text-align: center; color:#4c4c4c; font-weight:bolder">{{number_format($r->montant, 0, ',', ' ')}} AR</td>
            </tr>
            <tr>
     
                <th scope="col" style="text-align: center">VALEUR PRODUIT LIVRE</th>
                <td scope="col" style="text-align: center; color:#4c4c4c; font-weight:bolder">{{number_format($r->valeur, 0, ',', ' ')}} AR</td>
            </tr>
            <tr>
        
                <th scope="col" style="text-align: center">RESTE A PAYER</th>
                <td scope="col" style="text-align: center; color:#4c4c4c; font-weight:bolder">{{number_format($r->reste, 0, ',', ' ')}} AR</td>
            </tr>
            @endforeach
        </thead>
    </table>
</body>
</html>
