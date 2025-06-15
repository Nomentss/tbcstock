@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <div class="bg-light rounded h-100 p-4">
        <h5 class="mb-4 text-center">Formulaire ajout couleur</h5>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput"
                placeholder="nom_saison">
            <label for="floatingInput">Nom couleur</label>
        </div>
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </div>
    <br>
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">Couleur existant</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>John</td>
                            <td>Delete</td>
                           
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Mark</td>
                            <td>Delete</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection