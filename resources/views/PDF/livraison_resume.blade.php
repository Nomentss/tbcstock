<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Livraison par date PDF</title>
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
    <h2>Livraison de "{{ session('nom_artisan') }}"</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">DESIGNATION</th>
                <th scope="col">DESIGNATION CATALOGUE</th>
                <th scope="col">NB COLORIS</th>
                <th scope="col">PRIX UNITAIRE</th>
                <th scope="col">COMMANDE</th>
                <th scope="col">QUANTITE LIVRE</th>
                <th scope="col">VALEUR LIVRE</th>
                <th scope="col">RESTE A LIVRER</th>
                <th scope="col">%GE LIVRE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resume as $r)    
                <tr>
                    <td style="text-align: center">{{$r->designation_produit }}</td>
                    <td style="text-align: center">{{$r->designation_catalogue }}</td>
                    <td style="text-align: center">{{$r->nb_couleur }}</td>
                    <td style="color:rgb(0, 0, 0)">{{number_format($r->prix_unitaire, 0, ',', ' ') }} AR</td>
                    <td style="text-align: center">{{number_format($r->commande, 0, ',', ' ') }}</td>
                    <td style="text-align: center">{{$r->qte_livre }}</td>
                    <td style="color: black">{{number_format($r->valeur, 0, ',', ' ')}} AR</td>
                    <td style="text-align: center">{{$r->delta}}</td>
                    <td style="text-align: center">{{ number_format($r->pourcentage, 1) }} %</td>
                </tr>
            @endforeach                      
        </tbody>
    </table>
    <table class="table table-bordered">
        <thead>
            @foreach ($total as $t) 
            <tr>
                <th scope="col">TOTAL COMMANDE</th>
                <td scope="col" style="text-align: center">{{number_format($t->commande, 0, ',', ' ')}}</td>
            </tr>
            <tr>
                <th scope="col">TOTAL PRODUIT LIVRE</th>
                <td scope="col" style="text-align: center">{{number_format($t->qte_livre, 0, ',', ' ')}}</td>
            </tr>
            <tr>
                <th scope="col">TOTAL VALEUR PRODUIT LIVRE</th>
                <td scope="col"style="text-align: center">{{number_format($t->valeur, 0, ',', ' ')}} AR</td>
            </tr>
            <tr>
                <th scope="col">TOTAL POURCENTAGE LIVRE</th>
                <td scope="col"style="text-align: center">{{ number_format($t->pourcentage, 1) }} %</td>
            </tr>
            <tr>
                <th scope="col">TOTAL RESTE A LIVRER</th>
                <td scope="col"style="text-align: center">{{number_format($t->delta, 0, ',', ' ')}}</td>
            </tr>
            @endforeach
        </thead>
    </table>
</body>
</html>
