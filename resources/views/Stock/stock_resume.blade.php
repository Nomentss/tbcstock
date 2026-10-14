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
            <h6 style="text-align: center;"> Stock regroupé par designation catalogue David </h6>
            <br>
            <form action="{{ route('StockController.filtreStockResume') }}" method="GET">
            <div class="row mb-3">
                <!-- Recherche texte -->
                <div class="col-md-10 mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher dans le tableau..." name="recherche">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
                {{-- filtre --}}
                <div class="col-md-3 mb-2">
                    <select id="typeCommandeSelect" class="form-select" name="saison_select">
                        <option value="">-- Filtrer par saison --</option>
                        @foreach ($saison as $saisons)
                            <option value={{$saisons->id}} {{ $selectionSaison == $saisons->id ? 'selected' : '' }}>{{$saisons->nom_saison}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 mb-2">
                    <select id="typeCommandeSelect" class="form-select" name="catalogue_select">
                        <option value="">-- Filtrer par designation catalogue David --</option>
                        @foreach ($designationCatalogue as $p)
                            <option value="{{$p->designation_catalogue}}"  {{ $selectionCatalogue == $p->designation_catalogue ? 'selected' : '' }}>{{$p->designation_catalogue}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary"> Filtrer </button>
                </div>
        </div>
            </form>


        <div class="table-responsive" style="margin-top: 25px">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">SAISON</th>
                        <th scope="col">DESIGNATION CATALOGUE</th>
                        <th scope="col" style="text-align: center">Totale Quantite</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($total->isNotEmpty())
                    @foreach ($total as $t)    
                        <tr>
                            <td>{{$t->nom_saison }}</td>
                            <td>{{$t->designation_catalogue }}</td>
                            <td style="text-align: center">{{number_format($t->sum, 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">Aucune donnée disponible.</td>
                        </tr>
                    @endif                      
                </tbody>
            </table>
            <!-- Bouton pour exporter -->
            <form id="form2" method="GET" action="{{ route('StockController.exportRegroupe')}}">
                <input type="hidden" name="saison_select" id="saison_select">
                <input type="hidden" name="catalogue_select" id="catalogue_select">
             
                <button type="button" class="btn btn-primary" id="btnExport"> Exporter en PDF </button>
            </form>
        </div>
    </div>
    <div>
        {{ $total->links() }}
    </div>
    </form>
</div>

<!-- Modal ---------------------------------------------------------------------------------------------------------------------------->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Choisir filtre stock</h5>
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
          <label for="selectExport">Sélection designation catalogue :</label>
          <select id="selectCatalogue" class="form-select">
            <option value="">Tous</option>
                @foreach ($designationCatalogue as $p)
                <option value="{{$p->designation_catalogue}}">{{$p->designation_catalogue}}</option>
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

        const selectCatalogue = document.getElementById('selectCatalogue').value;
        document.getElementById('catalogue_select').value = selectCatalogue;


        document.getElementById('form2').submit();
    });
</script>
@endsection