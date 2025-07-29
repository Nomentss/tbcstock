@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form action=" {{ route('CouleurController.insert')}}">
    <div class="bg-light rounded h-100 p-4">
        <h5 class="mb-4 text-center">Formulaire ajout couleur</h5>

        <div class="form-floating mb-3">
            <input type="text" class="form-control  @error('nom_couleur') is-invalid @enderror" id="floatingInput" name="nom_couleur">
            <label for="floatingInput">Nom couleur</label>
            @error('nom_couleur')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </div>
    </form>
    <br>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('fail'))
        <div class="alert alert-danger">
            {{ session('fail') }}
        </div>
    @endif
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">Couleur existant</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom couleur</th>
                            <th scope="col" style="text-align: center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($couleur as $couleurs)
                        <tr>
                            <th scope="row">{{ $i++ }}</th>
                            <td>{{ $couleurs->nom}}</td>
                            <td style="text-align: center"><a href="{{ route('CouleurController.delete', ['id' => $couleurs->id] )}}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ? Cela entrainera la perte de toutes donnees correspondante.')" class="btn btn-danger">Supprimer</a></td>                          
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection