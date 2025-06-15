@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form action="{{ route('ArtisanController.insert') }}" method="GET">
    <div class="bg-light rounded h-100 p-4">
        <h5 class="mb-4 text-center">Formulaire Artisan</h5>

        @error('nom_artisan')
            <span style="color: red">{{ $message }}</span>
        @enderror
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" placeholder="nom de l'artisan" name="nom_artisan">
            <label for="floatingInput">Nom de l'artisan</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" placeholder="nom_saison" name="telephone">
            <label for="floatingInput">Telephone</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" placeholder="nom_saison" name="mail">
            <label for="floatingInput">Mail</label>
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
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom artisan</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Mail</th>
                            <th scope="col">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($artisan as $artisans)
                        <tr>
                            <th scope="row">{{ $artisans->id }}</th>
                            <td>{{ $artisans->nom }}</td>
                            <td>{{ $artisans->telephone }}</td>
                            <td>{{ $artisans->mail }}</td>
                            <td><a href="{{ route('ArtisanController.delete', ['id' => $artisans->id]) }}" >Delete</a></td>
                           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection