@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form action="{{ route('PayementController.insert') }}" method="POST">
    @csrf
    <div class="bg-light rounded h-100 p-4">
        <h5 class="mb-4 text-center">Formulaire paiement artisan</h5>

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
        
        <div class="form-floating mb-3">
            <select class="form-select @error('id_saison') is-invalid @enderror" id="floatingSelect" name="id_saison">
                @foreach ($saison as $s)
                    <option value={{ $s->id }}>{{ $s->nom_saison}}</option>
                @endforeach
            </select>
            <label for="floatingInput">Saison</label>
            @error('id_saison')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <select class="form-select  @error('id_artisan') is-invalid @enderror" id="floatingSelect" aria-label="Floating label select example" name="id_artisan">
                @foreach ($artisan as $artisans)
                <option value={{ $artisans->id }}>{{ $artisans->nom}}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Artisan</label>
            @error('id_artisan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <select class="form-select @error('type_payement') is-invalid @enderror" name="type_payement">
                    <option value="Espèce">Espèce</option>
                    <option value="Chèque">Chèque</option>
            </select>
            <label for="floatingInput">Type paiement</label>
            @error('type_payement')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <textarea type="text" class="form-control @error('numero_piece') is-invalid @enderror" id="floatingInput" name="numero_piece" value="{{ old('numero_piece') }}"></textarea>
            <label for="floatingInput">Numero/piece de cheque</label>
            @error('numero_piece')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <textarea type="text" class="form-control @error('detail_payement') is-invalid @enderror" id="floatingInput" name="detail_payement" value="{{ old('detail_payement') }}"></textarea>
            <label for="floatingInput">Detail paiement</label>
            @error('detail_payement')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('montant') is-invalid @enderror" id="floatingInput" name="montant" value="{{ old('montant') }}">
            <label for="floatingInput">Montant</label>
            @error('montant')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control @error('date_payement') is-invalid @enderror" id="floatingInput" name="date_payement" value="{{ old('date_payement') }}">
            <label for="floatingInput">Date paiement</label>
            @error('date_payement')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </div>
    </form>
</div>

@endsection