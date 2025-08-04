<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock PDF</title>
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
    <h2>Liste Stock designation catalogue David</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">SAISON</th>
                <th scope="col">DESIGNATION CATALOGUE</th>
                <th scope="col" style="text-align: center">Totale Quantite</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($total as $t)    
                <tr>
                    <td>{{$t->nom_saison }}</td>
                    <td>{{$t->designation_catalogue }}</td>
                    <td style="text-align: center">{{number_format($t->sum, 0, ',', ' ') }}</td>
                </tr>
            @endforeach                      
        </tbody>
    </table>
</html>
