@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <div class="bg-light rounded h-100 p-4">
    <form action="{{ route('SaisonController.insert') }}" method="GET">
        <h5 class="mb-4 text-center">Formulaire pour saison</h5>

        <div class="form-floating mb-3">
            <input type="date" class="form-control  @error('date_debut') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" name="date_debut"  value="{{ old('date_debut') }}">
            <label for="floatingInput">Date debut</label>
            @error('date_debut')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div class="form-floating mb-3">
            <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" name="date_fin" value="{{ old('date_fin') }}">
            <label for="floatingInput">Date fin</label>
            @error('date_fin')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('nom_saison') is-invalid @enderror" id="floatingInput" placeholder="nom_saison" name="nom_saison" value="{{ old('nom_saison') }}">
            <label for="floatingInput">nom de saison</label>
            @error('nom_saison')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </form>
    </div>

    <br>
    <div class="col-12">
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
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">Liste Saison</h6>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nom de saison</th>
                            <th scope="col">Debut</th>
                            <th scope="col">Fin</th>
                            <th scope="col" style="text-align: center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saison as $saisons)
                        <tr>
                            <td>{{ $saisons->nom_saison }}</td>
                            <td>{{ $saisons->debut }}</td>
                            <td>{{ $saisons->fin }}</td>
                            <td style="text-align: center"><a href="{{ route('SaisonController.delete', ['id' => $saisons->id]) }}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer la saison? Cela entrainera la perte de toutes donnees correspondante.')" class="btn btn-danger" >supprimer</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection