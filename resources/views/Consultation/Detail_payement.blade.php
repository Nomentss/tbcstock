@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4" style="text-align: center">DETAIL PAYEMENT DE L'ARTISAN "{{ session('nom_artisan') }}"</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <td scope="col">Date payement</td>
                            <td scope="col">Detail</td>
                            <td scope="col">Montant</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $d)
                            <tr>
                                <th>{{$d->date_payement}}</th>
                                <td>{{$d->detail_payement}}</td>
                                <th>{{number_format($d->montant, 0, ',', ' ')}}</th>                        
                            </tr> 
                        @endforeach
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                        @foreach ($resume as $r) 
                        <tr>
                        
                            <th scope="col" style="text-align: center">TOTAL PAYEMENT</th>
                            <th scope="col">{{number_format($r->montant, 0, ',', ' ')}}</th>
                        </tr>
                        <tr>
                 
                            <th scope="col" style="text-align: center">VALEUR PRODUIT LIVRE</th>
                            <th scope="col">{{number_format($r->valeur, 0, ',', ' ')}}</th>
                        </tr>
                        <tr>
                    
                            <th scope="col" style="text-align: center">RESTE A PAYER</th>
                            <th scope="col">{{number_format($r->reste, 0, ',', ' ')}}</th>
                        </tr>
                        @endforeach
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection