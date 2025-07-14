@extends('Layout.app')
@section('contents')

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
                            <option value={{$saisons->id}}>{{$saisons->nom_saison}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <select id="typeCommandeSelect" class="form-select" name="artisan_select">
                        <option value="">-- Filtrer par artisan --</option>
                        @foreach ($artisan as $artisans)
                            <option value={{$artisans->id}}>{{$artisans->nom}} </option>
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
                            <td scope="col">Saison</td>
                            <td scope="col">Artisan</td>
                            <td scope="col" style="text-align: center">option</td>
                            <td scope="col" style="text-align: center">option</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($livraison as $l)
                        <tr>
                            <th>{{$l->nom_saison }}</th>
                            <th>{{$l->artisan }}</th>
                            <th style="text-align: center"><a href="{{ route('LivraisonController.detailLivraison', ['id_artisan' => $l->id_artisan, 'id_saison' => $l->id_saison]) }}"  class="btn btn-primary w-55">Voir detail par date</a></th>
                            <th style="text-align: center"><a href="{{ route('LivraisonController.resumeLivraison', ['id_artisan' => $l->id_artisan, 'id_saison' => $l->id_saison]) }}"  class="btn btn-primary w-5">Voir resume quantites livrees</a></th>                        
                        </tr>
                        @endforeach                   
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection