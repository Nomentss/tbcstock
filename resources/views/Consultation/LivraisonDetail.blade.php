@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-12">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h5 class="mb-4" style="text-align: center">Detail des livraison de l'artisan "{{ session('nom_artisan') }}"</h5>
            <form action="{{ route('LivraisonController.filtreLivraison') }}" method="GET">
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
                            <th scope="col">COULEUR</th>
                            <th scope="col">PRIX UNITAIRE</th>
                            <th scope="col">Quantite livre</th>
                            <th scope="col">Valeur produit</th>
                            <th scope="col">DATE LIVRAISON</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $details)    
                            <tr>
                                <td>{{$details->designation_produit }}</td>
                                <td>{{$details->designation_catalogue }}</td>
                                <td>{{$details->couleur }}</td>
                                <td>{{number_format($details->prix_unitaire, 0, ',', ' ') }}</td>
                                <td>{{$details->qte_livre }}</td>
                                <td>{{number_format($details->valeur, 0, ',', ' ')}}</td>
                                <td>{{$details->date_livraison }}</td>
                            </tr>
                        @endforeach                      
                    </tbody>
                </table>
            </div>
        </div>
        <div>
        {{ $detail->links() }}
        </div>
    </form>
</div>
@endsection