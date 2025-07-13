@extends('Layout.app')
@section('contents')

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
                <div class="col-md-5 mb-2">
                    <select id="typeCommandeSelect" class="form-select" name="catalogue_select">
                        <option value="">-- Filtrer par designation catalogue David --</option>
                        @foreach ($designationCatalogue as $p)
                        <option value="{{$p->designation_catalogue}}">{{$p->designation_catalogue}}</option>
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
                        <th scope="col">#</th>
                        <th scope="col">DESIGNATION CATALOGUE</th>
                        <th scope="col" style="text-align: center">Totale Quantite</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($total as $t)    
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$t->designation_catalogue }}</td>
                            <td style="text-align: center">{{number_format($t->sum, 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach                      
                </tbody>
            </table>
        </div>
        <hr>
    </div>
    <div>
        {{ $total->links() }}
    </div>
    </form>
</div>
@endsection