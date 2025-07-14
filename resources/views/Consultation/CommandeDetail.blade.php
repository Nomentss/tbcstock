@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-12">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h5 class="mb-4" style="text-align: center">Detail des commande de l'artisan "{{ session('nom_artisan') }}"</h5>
    <form action="{{ route('CommandeController.filtreCommande') }}" method="GET">
            <div class="row mb-3">
                <!-- Recherche texte -->
                <div class="col-md-10 mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher dans le tableau..." name="recherche">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
                <div class="col-md-4 mb-2">
                <form action="{{ route('CommandeController.filtreCommande') }}" method="GET">
                    <!-- Filtre par type commande -->
                    <input type="hidden" name="id_artisan" value={{ $detail->first()->id_artisan ?? '' }}>
                    <input type="hidden" name="id_saison" value={{ $detail->first()->id_saison ?? '' }}>
                    <select id="typeCommandeSelect" class="form-select" name="type_commande_select">
                        <option value="">-- Filtrer par type de commande --</option>
                        @foreach ($type_commande as $tc)
                            <option value="{{ $tc->type_commande }}">{{ $tc->type_commande }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4 mb-2">
                    <button type="submit" class="btn btn-primary"> Filtrer </button>
                </div>

                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">TYPE COMMANDE</th>
                            <th scope="col">DESIGNATION</th>
                            <th scope="col">DESIGNATION CATALOGUE</th>
                            <th scope="col">COULEUR</th>
                            <th scope="col">PRIX UNITAIRE</th>
                            <th scope="col">quantite</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $details)    
                            <tr>
                                <td>{{$details->type_commande }}</td>
                                <td>{{$details->designation_produit }}</td>
                                <td>{{$details->designation_catalogue }}</td>
                                <td>{{$details->couleur }}</td>
                                <td>{{number_format($details->prix_unitaire, 0, ',', ' ') }}</td>
                                <td>{{$details->qte }}</td>
                                <td>{{ number_format($details->total, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach                      
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                        @foreach ($somme as $s)
                        <tr>
                            <th scope="col" style="text-align: center">TOTAL COMMANDE</th>
                            <th scope="col" style="text-align: center">{{ number_format($s->total_qte, 0, ',', ' ') }}</th>
                        </tr>
                        <tr>
                            <th scope="col" style="text-align: center">VALEUR TOTAL PRODUIT</th>
                            <th scope="col" style="text-align: center">{{ number_format($s->total_prix, 0, ',', ' ') }} AR</th>
                        </tr>
                        @endforeach
                    </thead>
                </table>
                
            </div>
        </div>
        <div>
            {{ $detail->links() }}
        </div>
    </form>
</div>
@endsection