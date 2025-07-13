@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form action=" {{ route('CouleurController.insert')}}">
    <div class="bg-light rounded h-100 p-4">
        <h5 class="mb-4 text-center">Formulaire ajout couleur</h5>
        @error('nom_couleur')
        <span style="color: red">{{ $message }}</span>
        @enderror
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" name="nom_couleur">
            <label for="floatingInput">Nom couleur</label>
        </div>
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </div>
    </form>
    <br>
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">Couleur existant</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Nom couleur</th>
                            <th scope="col">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($couleur as $couleurs)
                        <tr>
                            <th scope="row">{{ $couleurs->id }}</th>
                            <td>{{ $couleurs->nom}}</td>
                            <td><a href="{{ route('CouleurController.delete', ['id' => $couleurs->id] )}}">Delete</a></td>                          
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection