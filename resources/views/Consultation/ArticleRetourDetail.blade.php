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
            <h5 class="mb-4" style="text-align: center">Detail des articles retournés de l'artisan "{{ session('nom_artisan') }}"</h5>
    <form action="{{ route('ArticleRetourController.filtre') }}" method="GET">
            <div class="row mb-3">
                <!-- Recherche texte -->
                <div class="col-md-10 mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher dans le tableau..." name="recherche">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
                <div class="col-md-4 mb-2">
                <form action="{{ route('ArticleRetourController.filtre') }}" method="GET">
                    <!-- Filtre par type commande -->
                    <input type="hidden" name="id_artisan" value={{ $detail->first()->id_artisan ?? '' }}>
                    <input type="hidden" name="id_saison" value={{ $detail->first()->id_saison ?? '' }}>
                </div>

                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">DESIGNATION</th>
                            <th scope="col">DESIGNATION CATALOGUE</th>
                            <th scope="col">COULEUR</th>
                            <th scope="col">QUANTITE</th>
                            <th scope="col">DATE RETOUR</th>
                            <th scope="col">Bon sortie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $details)    
                            <tr>
                                <td>{{$details->designation_produit }}</td>
                                <td>{{$details->designation_catalogue }}</td>
                                <td>{{$details->couleur }}</td>
                                <td>{{$details->qte }}</td>
                                <td style="text-align: center">
                                    {{ \Carbon\Carbon::parse($details->date_retour)->translatedFormat('d F Y') }}
                                </td>
                                <td>{{$details->bon_sortie }}</td>
                            </tr>
                        @endforeach                      
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                        @foreach ($somme as $s)
                        <tr>
                            <th scope="col" style="text-align: center">TOTAL RETOUR</th>
                            <td scope="col" style="text-align: center; color:black">{{ number_format($s->total_qte, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                    </thead>
                </table>
                <!-- Bouton pour exporter -->
                <form id="form2" method="GET" action="{{ route('ArticleRetourController.exportPdf')}}">
                    <input type="hidden" name="id_artisan" value={{ $detail->first()->id_artisan ?? '' }}>
                    <input type="hidden" name="id_saison" value={{ $detail->first()->id_saison ?? '' }}>

                    <button type="submit" class="btn btn-primary" id="btnExport"> Exporter en PDF </button>
                </form>
            </div>
        </div>
        <div>
            {{ $detail->links() }}
        </div>
    </form>

</div>

@endsection