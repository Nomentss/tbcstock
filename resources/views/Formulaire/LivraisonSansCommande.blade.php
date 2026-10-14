@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="maxCounter" name="maxCounter" value="0">

    <div class="bg-light rounded h-100 p-4" id="content">
        <h5 class="mb-4 text-center">Formulaire livraison sans commande</h5>
        <div class="form-floating mb-3">
            <select class="form-select @error('id_saison') is-invalid @enderror" aria-label="Floating label select example" name="id_saison" value="{{ old('id_saison') }}">
                 @foreach ($saison as $s)
                <option value={{$s->id}}>{{ $s->nom_saison }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Choix de saison</label>
            @error('id_saison')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control @error('date_livraison') is-invalid @enderror" id="floatingInput" name="date_livraison" value="{{ old('date_livraison') }}">
            <label for="floatingInput">Date de livraison</label>
            @error('date_livraison')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="id_artisan">
                @foreach ($artisan as $artisans)
                    <option value="{{ $artisans->id }}">{{ $artisans->nom }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Artisan</label>
        </div>
        
        
        <input class="form-control @error('file') is-invalid @enderror" type="file" id="formFile" name="file">
         @error('file')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        <div style="margin-top: 25px" >
        <button type="submit" class="btn btn-primary w-100" formaction="#">Importer</button>
        </div>
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
        <br>
        @if($errors->any())
            <div class="alert alert-danger">
                Veuillez remplir le formulaire correctement.
            </div>
        @endif
        <hr>
    </div>
    </form>
</div>

@endsection