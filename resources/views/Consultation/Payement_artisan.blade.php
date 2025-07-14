@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h5 class="mb-4" style="text-align: center">LISTE PAYEMENT</h5 >
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Saison</th>
                            <th scope="col">Artisan</th>
                            <th scope="col">Montant payé</th>
                            <th scope="col">Total valeur livré</th>
                            <th scope="col">Reste à payer</th>
                            <th scope="col" style="text-align: center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payement as $p)
                        <tr>
                            <th>{{$p->nom_saison}}</th>
                            <td>{{$p->artisan}}</td>
                            <td>{{number_format($p->montant, 0, ',', ' ')}}</td>
                            <td>{{number_format($p->valeur, 0, ',', ' ')}}</td>
                            <td>{{number_format($p->reste, 0, ',', ' ')}}</td>
                            <td style="text-align: center"><a href="{{ route('PayementController.payementDetail', ['id_artisan' => $p->id_artisan, 'id_saison' => $p->id_saison]) }}" class="btn btn-primary w-55">Voir detail</a></td>                          
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection