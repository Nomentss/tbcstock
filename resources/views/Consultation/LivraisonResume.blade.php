@extends('Layout.app')
@section('contents')

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
                            <th scope="col">#</th>
                            <th scope="col">DESIGNATION</th>
                            <th scope="col">DESIGNATION CATALOGUE</th>
                            <th scope="col">NB Coloris</th>
                            <th scope="col">PRIX UNITAIRE</th>
                            <th scope="col">Commande</th>
                            <th scope="col">Quantite livre</th>
                            <th scope="col">Valeur livre</th>
                            <th scope="col">Reste a livrer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($resume as $r)    
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$r->designation_produit }}</td>
                                <td>{{$r->designation_catalogue }}</td>
                                <td>{{$r->nb_couleur }}</td>
                                <td>{{number_format($r->prix_unitaire, 0, ',', ' ') }}</td>
                                <td>{{number_format($r->commande, 0, ',', ' ') }}</td>
                                <td style="text-align: center">{{$r->qte_livre }}</td>
                                <td>{{number_format($r->valeur, 0, ',', ' ')}}</td>
                                <td>{{$r->delta}}</td>
                            </tr>
                        @endforeach                      
                    </tbody>
                </table>
                {{ $resume->links() }}
                <table class="table table-bordered">
                    <thead>
                        @foreach ($total as $t) 
                        <tr>
                            <th scope="col" style="text-align: center">TOTAL COMMANDE</th>
                            <th scope="col" style="text-align: center">{{number_format($t->commande, 0, ',', ' ')}}</th>
                        </tr>
                        <tr>
                            <th scope="col" style="text-align: center">TOTAL PRODUIT LIVRE</th>
                            <th scope="col" style="text-align: center">{{number_format($t->qte_livre, 0, ',', ' ')}}</th>
                        </tr>
                        <tr>
                            <th scope="col" style="text-align: center">TOTAL VALEUR PRODUIT LIVRE</th>
                            <th scope="col"style="text-align: center">{{number_format($t->valeur, 0, ',', ' ')}}</th>
                        </tr>
                        <tr>
                            <th scope="col" style="text-align: center">TOTAL RESTE A LIVRER</th>
                            <th scope="col"style="text-align: center">{{number_format($t->delta, 0, ',', ' ')}}</th>
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