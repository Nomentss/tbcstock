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
            <h5 class="mb-4" style="text-align: center">Liste des expeditions </h5>
            <form id="form1" action="{{ route('ExpeditionController.listeExpedition') }}" method="GET">
                <div class="row mb-3">
                    <!-- Recherche texte -->
                    <div class="col-md-10 mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher dans le tableau..." name="recherche">
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                    <!-- Filtre par type commande -->
                    <div class="col-md-2 mb-2">
                        <select id="typeCommandeSelect" class="form-select" name="saison_select">
                            <option value="">-- Filtre saison --</option>
                            @foreach ($saison as $saisons)
                                <option value={{$saisons->id}} {{ $selectionSaison == $saisons->id ? 'selected' : '' }} >{{$saisons->nom_saison}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select id="typeCommandeSelect" class="form-select" name="artisan_select">
                            <option value="">-- Filtre artisan --</option>
                            @foreach ($artisan as $a)
                                <option value="{{ $a->id }}" {{ $selectionArtisan == $a->nom ? 'selected' : '' }}> {{ $a->nom }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <select id="typeCommandeSelect" class="form-select" name="catalogue_select">
                            <option value="">-- Filtrer par designation catalogue David --</option>
                            @foreach ($designationCatalogue as $p)
                            <option value="{{$p->designation_catalogue}}" {{ $selectionCatalogue == $p->designation_catalogue ? 'selected' : '' }}>{{$p->designation_catalogue}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select id="typeCommandeSelect" class="form-select" name="couleur_select">
                            <option value="">-- Filtre couleur --</option>
                            @foreach ($couleur as $c)
                            <option value="{{ $c->nom}}" {{ $selectionCouleur == $c->nom ? 'selected' : '' }}>{{$c->nom}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary"> Filtrer </button>
                    </div>
                    
                    
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align: center">Saison</th>
                            <th scope="col" style="text-align: center">Expedié Dans</th>
                            <th scope="col" style="text-align: center">Artisan</th>
                            <th scope="col" style="text-align: center">Designation produit</th>
                            <th scope="col" style="text-align: center">Designation catalogue David</th>
                            <th scope="col" style="text-align: center">Couleur</th>
                            <th scope="col" style="text-align: center">Quantité</th>
                            <th scope="col" style="text-align: center">P.U</th>
                            <th scope="col" style="text-align: center">Date expedition</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($expedition->isNotEmpty())
                        @foreach ($expedition as $e)
                        <tr>
                            <td>{{ $e->nom_saison}}</td>
                            <td style="text-align: center">{{ $e->expedition_route}}</td>
                            <td>{{ $e->artisan}}</td>
                            <td>{{ $e->designation_produit}}</td>
                            <td>{{ $e->designation_catalogue}}</td>
                            <td>{{ $e->couleur}}</td>
                            <td style="text-align: center">{{ $e->qte}}</td>
                            <td style="text-align: center">{{number_format($e->prix_unitaire, 0, ',', ' ')}} AR</td>
                            <td>{{ \Carbon\Carbon::parse($e->date_expedition)->translatedFormat('d F Y')}}</td>                      
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">Aucune donnée disponible.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                @if ($expedition->isNotEmpty())
                <table class="table table-bordered">
                    <thead>
                        @foreach ($total as $s)
                        <tr>
                            <th scope="col" style="text-align: center">TOTAL QUANTITE EXPEDIE</th>
                            <td scope="col" style="text-align: center; color:black">{{ $s->qte }}</td>
                        </tr>
                        <tr>
                            <th scope="col" style="text-align: center">VALEUR TOTAL EXPEDITION</th>
                            <td scope="col" style="text-align: center; color:black">{{ number_format($s->total, 0, ',', ' ') }} AR</td>
                        </tr>
                        @endforeach
                    </thead>
                </table>
                @endif
                <!-- Bouton pour exporter -->
                <form id="form2" method="GET" action="{{ route('ExpeditionController.export')}}">
                    <input type="hidden" name="saison_select" id="saison_select"> <!-- Valeur du select -->
                    <input type="hidden" name="conteneur_select" id="conteneur_select"> <!-- Valeur du select -->
                    <input type="hidden" name="artisan_select" id="artisan_select"> <!-- Valeur du select -->
                    <input type="hidden" name="catalogue_select" id="catalogue_select"> <!-- Valeur du select -->

                    <button type="button" class="btn btn-primary" id="btnExport"> Exporter en PDF </button>
                </form>
            </div>
            
            {{-- <a href="{{ route('ExpeditionController.export')}}" class="btn btn-primary">Exporter en PDF</a> --}}
        </div>
    </div>
</div>
<!-- Modal ---------------------------------------------------------------------------------------------------------------------------->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Choisir un filtre d’export</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <label for="selectSaison">Sélection saison :</label>
          <select id="selectSaison" class="form-select">
            <option value="">Tous</option>
                @foreach ($saison as $saisons)
                <option value={{$saisons->id}} {{ $selectionSaison == $saisons->id ? 'selected' : '' }}>{{$saisons->nom_saison}} </option>
                @endforeach
          </select>
          <br>
          
          <label for="selectExport">Sélection artisan :</label>
          <select id="selectArtisan" class="form-select">
            <option value="">Tous</option>
                @foreach ($artisan as $a)
                <option value="{{ $a->id }}" {{ $selectionArtisan == $a->nom ? 'selected' : '' }}> {{ $a->nom }} </option>
                @endforeach
          </select>

          
          <br>
          <label for="selectExport">Sélection designation_catalogue David:</label>
          <select id="selectCatalogue" class="form-select">
            <option value="">Tous</option>
                @foreach ($designationCatalogue as $p)
                <option value="{{$p->designation_catalogue}}" {{ $selectionCatalogue == $p->designation_catalogue ? 'selected' : '' }}>{{$p->designation_catalogue}}</option>
                @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-primary" id="validerExport">Valider</button>
        </div>
      </div>
    </div>
  </div>


  <script>
    document.getElementById('btnExport').addEventListener('click', function () {
        var myModal = new bootstrap.Modal(document.getElementById('exportModal'));
        myModal.show();
    });
    
    document.getElementById('validerExport').addEventListener('click', function () {
        const selectSaison = document.getElementById('selectSaison').value;
        document.getElementById('saison_select').value = selectSaison;

        const selectConteneur = document.getElementById('selectConteneur').value;
        document.getElementById('conteneur_select').value = selectConteneur;

        const selectArtisan = document.getElementById('selectArtisan').value;
        document.getElementById('artisan_select').value = selectArtisan;

        const selectCatalogue = document.getElementById('selectCatalogue').value;
        document.getElementById('catalogue_select').value = selectCatalogue;

        document.getElementById('form2').submit();
    });
    </script>
@endsection