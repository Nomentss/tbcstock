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
                    <input type="hidden" name="id_client" value={{ $detail->first()->id_client ?? '' }}>
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
                <!-- Bouton pour exporter -->
                <form id="form2" method="GET" action="{{ route('CommandeController.export')}}">
                    <input type="hidden" name="id_artisan" value={{ $detail->first()->id_artisan ?? '' }}>
                    <input type="hidden" name="id_saison" value={{ $detail->first()->id_saison ?? '' }}>
                    <input type="hidden" name="type_select" id="type_select"> <!-- Valeur du select -->
                 
                    <button type="button" class="btn btn-primary" id="btnExportPdf"> Exporter en PDF </button>
                    <button type="button" class="btn btn-primary" id="btnExportExcel"> Exporter en EXCEL </button>
                    <a href="{{ route('CommandeController.modifierListe', [
                        'id_artisan' => $id_artisan,
                        'id_saison' => $id_saison,
                        'id_client' => $id_client
                        ]) }}" class="btn btn-success">
                        Modifier la liste
                    </a>
                </form>
            </div>
        </div>
        <div>
            {{ $detail->links() }}
        </div>
    </form>

</div>
<!-- Modal ---------------------------------------------------------------------------------------------------------------------------->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Choisir un filtre de commande</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <label for="selectSaison">Sélection type_commande :</label>
          <select id="selectType" class="form-select">
            <option value="">Tous</option>
                @foreach ($type_commande as $tc)
                <option value="{{ $tc->type_commande }}"  {{ $selectionType == $tc->type_commande ? 'selected' : '' }} >{{ $tc->type_commande }}</option>
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
    let routeExport = "";

    // Bouton PDF
    document.getElementById('btnExportPdf').addEventListener('click', function () {
        routeExport = "{{ route('CommandeController.export') }}";

        var myModal = new bootstrap.Modal(document.getElementById('exportModal'));
        myModal.show();
    });

    // Bouton Excel
    document.getElementById('btnExportExcel').addEventListener('click', function () {
        routeExport = "{{ route('CommandeController.exportExcel') }}";

        var myModal = new bootstrap.Modal(document.getElementById('exportModal'));
        myModal.show();
    });

    // Bouton Valider du modal
    document.getElementById('validerExport').addEventListener('click', function () {
        const selectType = document.getElementById('selectType').value;

        const form = document.getElementById('form2');

        // Récupérer le filtre
        document.getElementById('type_select').value = selectType;

        // Modifier la route selon le bouton cliqué
        form.action = routeExport;

        // Envoyer le formulaire
        form.submit();
    });
</script>

@endsection