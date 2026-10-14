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

        <div class="bg-light rounded h-100 p-4">
            <h5 class="mb-4" style="text-align: center">Detail des livraison de l'artisan "{{ session('nom_artisan') }}"</h5>
            <form action="{{ route('LivraisonController.filtreLivraison') }}" method="GET">
            <input type="hidden" value={{$id_artisan}} name="id_artisan">
            <input type="hidden" value={{$id_saison}} name="id_saison">
            <div class="row mb-3">
                <!-- Recherche texte -->
                <div class="col-md-10 mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher dans le tableau..." name="recherche">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="date" class="form-control" name="date_filtre" value={{$date_value}}>
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
                            <th scope="col">DESIGNATION</th>
                            <th scope="col">DESIGNATION CATALOGUE</th>
                            <th scope="col">COULEUR</th>
                            <th scope="col">PRIX UNITAIRE </th>
                            <th scope="col">QUANTITE LIVRE</th>
                            <th scope="col">VALEUR</th>
                            <th scope="col">DATE LIVRAISON</th>
                            <th scope="col">Bon reception</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $details)    
                            <tr>
                                <td>{{$details->designation_produit }}</td>
                                <td>{{$details->designation_catalogue }}</td>
                                <td>{{$details->couleur }}</td>
                                <td style="color:rgb(0, 0, 0)">{{number_format($details->prix_unitaire, 0, ',', ' ') }} AR</td>
                                <td style="text-align: center">{{number_format($details->qte_livre, 0, ',', ' ')}}</td>
                                <td style="color:rgb(0, 0, 0)">{{number_format($details->valeur, 0, ',', ' ')}} AR</td>
                                <td style="text-align: center">
                                    {{ \Carbon\Carbon::parse($details->date_livraison)->translatedFormat('d F Y') }}
                                </td>
                                <td>{{$details->bon_reception }}</td
                            </tr>
                        @endforeach                      
                    </tbody>
                </table>
                 <!-- Bouton pour exporter -->
                 <form id="form2" method="GET" action="{{ route('LivraisonController.exportDetail')}}">
                    <input type="hidden" name="id_artisan" value={{$id_artisan}}>
                    <input type="hidden" name="id_saison" value={{$id_saison}}>
                    <input type="hidden" name="date_select" id="date_select"> <!-- Valeur du select -->
                 
                    <button type="button" class="btn btn-primary" id="btnExport"> Exporter en PDF </button>
                    <button type="button" class="btn btn-primary" id="btnExportExcel"> Exporter en EXCEL </button>
                </form>
            </div>
        </div>
        <div>
        {{ $detail->links() }}
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
          <label for="selectDate">Sélection date :</label>
          <input type="date" class="form-control" name="selectDate" id="selectDate">
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
    document.getElementById('btnExport').addEventListener('click', function () {
        routeExport = "{{ route('LivraisonController.exportDetail') }}";

        var myModal = new bootstrap.Modal(document.getElementById('exportModal'));
        myModal.show();
    });

    // Bouton Excel
    document.getElementById('btnExportExcel').addEventListener('click', function () {
        routeExport = "{{ route('LivraisonController.exportDetailExcel') }}";

        var myModal = new bootstrap.Modal(document.getElementById('exportModal'));
        myModal.show();
    });

    document.getElementById('validerExport').addEventListener('click', function () {
        const selectDate = document.getElementById('selectDate').value;
        document.getElementById('date_select').value = selectDate;

         const form = document.getElementById('form2');

        // Modifier la route selon le bouton cliqué
        form.action = routeExport;

        // Envoyer le formulaire
        form.submit();
    });
</script>

@endsection