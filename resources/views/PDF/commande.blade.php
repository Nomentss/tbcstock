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
    <h2>Liste des Commandes de "{{ session('nom_artisan') }}"</h2>
    <table>
        <thead>
            <tr>
                <th scope="col">TYPE COMMANDE</th>
                <th scope="col">DESIGNATION</th>
                <th scope="col">DESIGNATION CATALOGUE</th>
                <th scope="col">COULEUR</th>
                <th scope="col">PRIX UNITAIRE</th>
                <th scope="col">QUANTITE</th>
                <th scope="col">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $details)    
                <tr>
                    <td>{{$details->type_commande }}</td>
                    <td>{{$details->designation_produit }}</td>
                    <td>{{$details->designation_catalogue }}</td>
                    <td>{{$details->couleur }}</td>
                    <td style="color: black">{{number_format($details->prix_unitaire, 0, ',', ' ') }} AR</td>
                    <td>{{$details->qte }}</td>
                    <td style="color: black">{{ number_format($details->total, 0, ',', ' ') }} AR</td>
                </tr>
            @endforeach     
        </tbody>
    </table>
    <table class="table table-bordered">
        <thead>
            @foreach ($total as $s)
            <tr>
                <th scope="col" style="text-align: center">TOTAL QUANTITE EXPEDIE</th>
                <td scope="col" style="text-align: center; color:black">{{ $s->total_qte }}</td>
            </tr>
            <tr>
                <th scope="col" style="text-align: center">VALEUR TOTAL EXPEDITION</th>
                <td scope="col" style="text-align: center; color:black">{{ number_format($s->total_prix, 0, ',', ' ') }} AR</td>
            </tr>
            @endforeach
        </thead>
    </table>
</body>
</html>
