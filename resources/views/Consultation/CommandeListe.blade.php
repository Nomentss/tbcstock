@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-12">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4" style="text-align: center">LISTE DES COMMANDES</h6>
            <form action="{{ route('CommandeController.listeCommande')}}" method="GET">
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
                <table class="table">
                    <thead>
                        <tr>
                            <td scope="col">Artisans</td>
                            <td scope="col">Saison</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commandes as $commande)
                        <tr>
                            <th>{{$commande-> artisan}}</th>
                            <th>{{$commande-> saison}}</th>
                            <th><a href="{{ route('CommandeController.detailCommande', ['id_artisan' => $commande->id_artisan, 'id_saison' => $commande->id_saison]) }}">Voir detail</a></th>                        
                        </tr>
                        @endforeach                   
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection