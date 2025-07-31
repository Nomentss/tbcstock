@extends('Layout.app')
@section('contents')

<style>

    .table thead th {
        background-color: #a2a2a2;
        color: rgb(246, 246, 246);
    }

    th{
        text-align: center;
        font-family::Arial, Helvetica, sans-serif;
        font-size: 90%;
        white-space: nowrap;
    }

    td{
        font-size: 90%;
        font-family::Arial, Helvetica, sans-serif;
        white-space: nowrap;
        
    }

</style>
<div class="col-sm-12 col-xl-12">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h5 class="mb-4" style="text-align: center">resumée des livraison de l'artisan "{{session('nom_artisan') }}"</h5>
            <form action="{{ route('LivraisonController.filtreResumeLivraison') }}" method="GET">
                <input type="hidden" value={{$id_artisan}} name="id_artisan">
                <input type="hidden" value={{$id_saison}} name="id_saison">
            <div class="row mb-3">
                <!-- Recherche texte -->
                <div class="col-md-10 mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher dans le tableau..." name="recherche">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </div>
            </form>

            <div class="table-responsive">
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
                {{ $resume->links() }}
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
            </div>
        </div>
        <div>
        </div>
    </form>
</div>
@endsection