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
        text-align: center;
        font-size: 90%;
        font-family::Arial, Helvetica, sans-serif;
        white-space: nowrap;
        
    }

</style>
<div class="col-sm-12 col-xl-12">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4" style="text-align: center">LISTE PAYEMENT</h6>
            <form action="{{ route('PayementController.payementListe') }}" method="GET">
            <div class="row mb-3">
                {{-- Filtre --}}
                <div class="col-md-4 mb-2">
                    <select id="typeCommandeSelect" class="form-select" name="saison_select">
                        <option value="">-- Filtrer par saison --</option>
                        @foreach ($saison as $saisons)
                            <option value={{$saisons->id}} {{ $selectionSaison == $saisons->id ? 'selected' : '' }}>{{$saisons->nom_saison}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <select id="typeCommandeSelect" class="form-select" name="artisan_select">
                        <option value="">-- Filtrer par artisan --</option>
                        @foreach ($artisan as $artisans)
                            <option value={{$artisans->id}} {{ $selectionArtisan == $artisans->id ? 'selected' : '' }}>{{$artisans->nom}} </option>
                        @endforeach>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <button type="submit" class="btn btn-primary"> Filtrer </button>
                </div>
            </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Saison</th>
                            <th scope="col">Artisan</th>
                            <th scope="col">Montant payé</th>
                            <th scope="col">Total valeur livré</th>
                            <th scope="col">Reste à payer</th>
                            <th scope="col">Statut</th>
                            <th scope="col" style="text-align: center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payement as $p)
                        <tr>
                            <th>{{$p->nom_saison}}</th>
                            <td>{{$p->artisan}}</td>
                            <td>{{number_format($p->montant, 0, ',', ' ')}} AR</td>
                            <td>{{number_format($p->valeur, 0, ',', ' ')}} AR</td>
                            <td>{{number_format($p->reste, 0, ',', ' ')}} AR</td>
                            @if($p->montant < $p->valeur)
                                <td><label class="badge bg-danger" style="width: 80%; height:20px; text-align:center">A PAYER</label></td>
                            @endif
                            @if($p->montant == $p->valeur)
                                <td><label class="badge bg-success" style="width: 80%; height:20px; text-align:center"> OK </label></td>
                            @endif
                            @if($p->montant > $p->valeur)
                                <td><label class="badge bg-warning" style="width: 80%; height:20px; text-align:center">DOIT LIVRER</label></td>
                            @endif
                            <td style="text-align: center"><a href="{{ route('PayementController.payementDetail', ['id_artisan' => $p->id_artisan, 'id_saison' => $p->id_saison]) }}" class="btn btn-primary w-55">Voir détail</a></td>                          
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection