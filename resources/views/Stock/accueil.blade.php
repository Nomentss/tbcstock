@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-12">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4" style="text-align: center">LISTE DE STOCK</h6>
            <div class="table-responsive">
                <table class="table table-hover ">
                    <thead>
                        <tr>
                            <td scope="col"></td>
                            <td scope="col" style="text-align: center"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Stock globale et valeur totale</th>
                            <th style="text-align: center"><a href="{{ route('StockController.stockGlobale') }}" class="btn btn-primary w-75"> Voir </a></th>                       
                        </tr>
                        <tr>
                            <th>Stock regroupé par designation catalogue David</th>
                            <th style="text-align: center"><a href="{{ route('StockController.stockResume')}}" class="btn btn-primary w-75">Voir</a></th>                       
                        </tr> 
                        <tr>
                            <th>Stock regroupé par coloris de produit</th>
                            <th style="text-align: center"><a href="{{ route('StockController.stockProduitCouleur')}}" class="btn btn-primary w-75">Voir</a></th>                       
                        </tr>                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection