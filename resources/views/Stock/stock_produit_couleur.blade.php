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
            <h6 style="text-align: center"> Stock regroupé par coloration produit </h6>
            <br>
            <form action="{{ route('StockController.filtreStockProduitCouleur') }}" method="GET">
                <div class="row mb-3">
                    <!-- Recherche texte -->
                    <div class="col-md-10 mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher dans le tableau..." name="recherche">
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                    <!-- Filtre par type commande -->
                    <div class="col-md-3 mb-2">
                        <select id="typeCommandeSelect" class="form-select" name="saison_select">
                            <option value="">-- Filtrer par saison --</option>
                            @foreach ($saison as $saisons)
                                <option value={{$saisons->id}} {{ $selectionSaison == $saisons->id ? 'selected' : '' }}>{{$saisons->nom_saison}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select id="typeCommandeSelect" class="form-select" name="artisan_select">
                            <option value="">-- Filtrer par artisan --</option>
                            @foreach ($artisan as $a)
                                <option value="{{ $a->nom }}"> {{ $a->nom }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <select id="typeCommandeSelect" class="form-select" name="catalogue_select">
                            <option value="">-- Filtrer par designation catalogue David --</option>
                            @foreach ($designationCatalogue as $p)
                            <option value="{{$p->designation_catalogue}}">{{$p->designation_catalogue}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary"> Filtrer </button>
                    </div>
                </div>
            </form>



            <div class="table-responsive" style="margin-top: 25px">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align: center">SAISON</th>
                            <th scope="col" style="text-align: center">ARTISAN</th>
                            <th scope="col" style="text-align: center">DESIGNATION</th>
                            <th scope="col" style="text-align: center">DESIGNATION CATALOGUE</th>
                            <th scope="col" style="text-align: center">TOTAL COLORIS</th>
                            <th scope="col" style="text-align: center">Quantite</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stock as $s)    
                            <tr>
                                <td>{{$s->nom_saison }}</td>
                                <td>{{$s->artisan }}</td>
                                <td>{{$s->designation_produit }}</td>
                                <td>{{$s->designation_catalogue }}</td>
                                <td style="text-align: center">{{$s->nb_couleur }}</td>
                                <td style="text-align: center">{{number_format($s->qte_livre, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach                      
                    </tbody>
                </table>
            </div>
        </div>
        <div>
        {{ $stock->links() }}
        </div>
    </form>
</div>
@endsection