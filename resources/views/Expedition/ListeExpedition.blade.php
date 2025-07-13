@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-12">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4" style="text-align: center">Liste des expeditions </h6>
            <form action="{{ route('ExpeditionController.filtreExpedition') }}" method="GET">
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
                        <select id="typeCommandeSelect" class="form-select" name="artisan_select">
                            <option value="">-- Filtrer par artisan --</option>
                            @foreach ($artisan as $a)
                                <option value="{{ $a->nom }}"> {{ $a->nom }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select id="typeCommandeSelect" class="form-select" name="catalogue_select">
                            <option value="">-- Filtrer par designation catalogue David --</option>
                            @foreach ($designationCatalogue as $p)
                            <option value="{{$p->designation_catalogue}}">{{$p->designation_catalogue}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select id="typeCommandeSelect" class="form-select" name="couleur_select">
                            <option value="">-- Filtrer par couleur --</option>
                            @foreach ($couleur as $c)
                            <option value="{{ $c->nom}}">{{$c->nom}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary"> Filtrer </button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
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
                        @foreach ($expedition as $e)
                        <tr>
                            <td style="text-align: center">{{ $e->expedition}}</td>
                            <td>{{ $e->artisan}}</td>
                            <td style="text-align: center">{{ $e->designation_produit}}</td>
                            <td style="text-align: center">{{ $e->designation_catalogue}}</td>
                            <td>{{ $e->couleur}}</td>
                            <td>{{ $e->qte}}</td>
                            <td>{{ $e->date_expedition}}</td>                         
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection