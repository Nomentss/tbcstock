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
        text-align: center;
        font-size: 90%;
        font-family::Arial, Helvetica, sans-serif;
        white-space: nowrap;
        
    }

</style>
<div class="col-sm-12 col-xl-12">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4" style="text-align: center">LISTE DES LIVRAISONS</h6>
            <form action="{{ route('LivraisonController.listeLivraison')}}" method="GET">
            <div class="row mb-3">
                <!-- Filtre par type commande -->
                <div class="col-md-4 mb-2">
                    <select id="typeCommandeSelect" class="form-select" name="saison_select">
                        <option value="">-- Filtrer par saison --</option>
                        @foreach ($saison as $saisons)
                            <option value={{$saisons->id}} {{ $selectionSaison == $saisons->id ? 'selected' : '' }}>{{$saisons->nom_saison}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <select id="typeCommandeSelect" class="form-select" name="artisan_select">
                        <option value="">-- Filtrer par artisan --</option>
                        @foreach ($artisan as $artisans)
                            <option value={{$artisans->id}} {{ $selectionArtisan == $artisans->id ? 'selected' : '' }}>{{$artisans->nom}} </option>
                        @endforeach>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <button type="submit" class="btn btn-primary"> Filtrer </button>
                </div>
            </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">SAISON</th>
                            <th scope="col">ARTISAN</th>
                            <th scope="col" style="text-align: center">OPTION</th>
                            <th scope="col" style="text-align: center">OPTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($livraison as $l)
                        <tr>
                            <th>{{$l->nom_saison }}</th>
                            <th>{{$l->artisan }}</th>
                            <th style="text-align: center"><a href="{{ route('LivraisonController.detailLivraison', ['id_artisan' => $l->id_artisan, 'id_saison' => $l->id_saison]) }}"  class="btn btn-primary w-55">Voir détail par date</a></th>
                            <th style="text-align: center"><a href="{{ route('LivraisonController.resumeLivraison', ['id_artisan' => $l->id_artisan, 'id_saison' => $l->id_saison]) }}"  class="btn btn-primary w-5">Voir résumé quantités livrées</a></th>                        
                        </tr>
                        @endforeach                   
                    </tbody>
                </table>
            </div>
            {{ $livraison->links() }}
        </div>
    </div>
</div>
@endsection