@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <div class="bg-light rounded h-100 p-4">
    <form action="{{ route('SaisonController.insert') }}" method="GET">
        <h5 class="mb-4 text-center">Formulaire pour saison</h5>
        
            @error('date_debut')
                <span style="color: red">{{ $message }}</span>
            @enderror
        
        <div class="form-floating mb-3">
            <input type="date" class="form-control" id="floatingInput" placeholder="name@example.com" name="date_debut">
            <label for="floatingInput">Date debut</label>
        </div>
        
        @error('date_fin')
        <span style="color: red">{{ $message }}</span>
        @enderror
        <div class="form-floating mb-3">
            <input type="date" class="form-control" id="floatingInput" placeholder="name@example.com" name="date_fin">
            <label for="floatingInput">Date fin</label>
        </div>

        @error('nom_saison')
        <span style="color: red">{{ $message }}</span>
        @enderror
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" placeholder="nom_saison" name="nom_saison">
            <label for="floatingInput">nom de saison</label>
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
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom de saison</th>
                            <th scope="col">Debut</th>
                            <th scope="col">Fin</th>
                            <th scope="col">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saison as $saisons)
                        <tr>
                            <th scope="row">{{ $saisons->id }}</th>
                            <td>{{ $saisons->nom_saison }}</td>
                            <td>{{ $saisons->debut }}</td>
                            <td>{{ $saisons->fin }}</td>
                            <td><a href="{{ route('SaisonController.delete', ['id' => $saisons->id]) }}">delete</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection