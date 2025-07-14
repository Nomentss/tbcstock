@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form action="{{ route('ArtisanController.insert') }}" method="GET">
    <div class="bg-light rounded h-100 p-4">
        <h5 class="mb-4 text-center">Formulaire Artisan</h5>
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('nom_artisan') is-invalid @enderror" id="floatingInput" placeholder="nom de l'artisan" name="nom_artisan">
            <label for="floatingInput">Nom de l'artisan</label>
            @error('nom_artisan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control  @error('telephone') is-invalid @enderror" id="floatingInput" placeholder="nom_saison" name="telephone">
            <label for="floatingInput">Telephone</label>
            @error('telephone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('mail') is-invalid @enderror" id="floatingInput" placeholder="nom_saison" name="mail">
            <label for="floatingInput">Mail</label>
            @error('mail')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </div>
    </form>

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
            <h6 class="mb-4">Liste des artisan</h6>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nom artisan</th>
                            <th scope="col" style="text-align: center">Contact</th>
                            <th scope="col" style="text-align: center">Mail</th>
                            <th scope="col" style="text-align: center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($artisan as $artisans)
                        <tr>
                            <td>{{ $artisans->nom }}</td>
                            <td style="text-align: center">{{ $artisans->telephone }}</td>
                            <td style="text-align: center">{{ $artisans->mail }}</td>
                            <td style="text-align: center"><a href="{{ route('ArtisanController.delete', ['id' => $artisans->id]) }}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ? Cela entrainera la perte de toutes donnees correspondante.')" class="btn btn-danger">Supprimer</a></td>
                           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection