<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expéditions PDF</title>
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
    <h2>Liste des Expéditions</h2>
    <table>
        <thead>
            <tr>
                <th scope="col" style="text-align: center">Saison</th>
                <th scope="col" style="text-align: center">Expedié Dans</th>
                <th scope="col" style="text-align: center">Artisan</th>
                <th scope="col" style="text-align: center">Designation produit</th>
                <th scope="col" style="text-align: center">Designation catalogue David</th>
                <th scope="col" style="text-align: center">Couleur</th>
                <th scope="col" style="text-align: center">Quantité</th>
                <th scope="col" style="text-align: center">P.U</th>
                <th scope="col" style="text-align: center">Date expedition</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expedition as $e)
                <tr>
                    <td>{{ $e->nom_saison}}</td>
                    <td style="text-align: center">{{ $e->conteneur}}</td>
                    <td>{{ $e->artisan}}</td>
                    <td>{{ $e->designation_produit}}</td>
                    <td>{{ $e->designation_catalogue}}</td>
                    <td>{{ $e->couleur}}</td>
                    <td style="text-align: center">{{ $e->qte}}</td>
                    <td style="text-align: center">{{number_format($e->prix_unitaire, 0, ',', ' ')}} AR</td>
                    <td>{{ \Carbon\Carbon::parse($e->date_expedition)->translatedFormat('d F Y')}}</td> 
                </tr>
            @endforeach
        </tbody>
    </table>
    <table class="table table-bordered">
        <thead>
            @foreach ($total as $s)
            <tr>
                <th scope="col" style="text-align: center">TOTAL QUANTITE EXPEDIE</th>
                <td scope="col" style="text-align: center; color:black">{{ $s->qte }}</td>
            </tr>
            <tr>
                <th scope="col" style="text-align: center">VALEUR TOTAL EXPEDITION</th>
                <td scope="col" style="text-align: center; color:black">{{ number_format($s->total, 0, ',', ' ') }} AR</td>
            </tr>
            @endforeach
        </thead>
    </table>
</body>
</html>
