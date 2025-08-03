<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expéditions PDF</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; }
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
                    <td>{{ \Carbon\Carbon::parse($e->date_expedition)->translatedFormat('d F Y')}}</td> 
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
