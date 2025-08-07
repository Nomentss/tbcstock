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
            <h6 class="mb-4" style="text-align: center">DETAIL PAYEMENT DE L'ARTISAN "{{ session('nom_artisan') }}"</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Date payement</th>
                            <th scope="col">Numero cheque / piece de cheque</th>
                            <th scope="col">Detail</th>
                            <th scope="col">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $d)
                            <tr>
                                <th>{{ \Carbon\Carbon::parse($d->date_payement)->translatedFormat('d F Y') }}</th>
                                <td>{{$d->numero_piece_cheque}}</td>
                                <td>{{$d->detail_payement}}</td>
                                <th>{{number_format($d->montant, 0, ',', ' ')}} AR</th>                        
                            </tr> 
                        @endforeach
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                        @foreach ($resume as $r) 
                        <tr>
                        
                            <th scope="col" style="text-align: center">TOTAL PAYEMENT</th>
                            <td scope="col" style="text-align: center; color:#4c4c4c; font-weight:bolder">{{number_format($r->montant, 0, ',', ' ')}} AR</td>
                        </tr>
                        <tr>
                 
                            <th scope="col" style="text-align: center">VALEUR PRODUIT LIVRE</th>
                            <td scope="col" style="text-align: center; color:#4c4c4c; font-weight:bolder">{{number_format($r->valeur, 0, ',', ' ')}} AR</td>
                        </tr>
                        <tr>
                    
                            <th scope="col" style="text-align: center">RESTE A PAYER</th>
                            <td scope="col" style="text-align: center; color:#4c4c4c; font-weight:bolder">{{number_format($r->reste, 0, ',', ' ')}} AR</td>
                        </tr>
                        @endforeach
                    </thead>
                </table>
                 <!-- Bouton pour exporter -->
                 <form id="form2" method="GET" action="{{ route('PayementController.export')}}">
                    <input type="hidden" name="id_artisan" value={{ $id_artisan}}>
                    <input type="hidden" name="id_saison" value={{ $id_saison}}> 
                    <button type="submit" class="btn btn-primary" id="btnExport"> Exporter en PDF </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection