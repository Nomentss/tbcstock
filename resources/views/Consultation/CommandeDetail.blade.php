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
                            <option value="{{ $tc->type_commande }}"  {{ $selectionType == $tc->type_commande ? 'selected' : '' }} >{{ $tc->type_commande }}</option>
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
                            <th scope="col">QUANTITE</th>
                            <th scope="col">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $details)    
                            <tr>
                                <td >{{$details->type_commande }}</td>
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
                        @foreach ($somme as $s)
                        <tr>
                            <th scope="col" style="text-align: center">TOTAL COMMANDE</th>
                            <td scope="col" style="text-align: center; color:black">{{ number_format($s->total_qte, 0, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <th scope="col" style="text-align: center">VALEUR TOTAL PRODUIT</th>
                            <td scope="col" style="text-align: center; color:black">{{ number_format($s->total_prix, 0, ',', ' ') }} AR</td>
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