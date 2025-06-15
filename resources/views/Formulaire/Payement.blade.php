@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <div class="bg-light rounded h-100 p-4">
        <h5 class="mb-4 text-center">Formulaire payement artisan</h5>
        <div class="form-floating mb-3">
            <select class="form-select" id="floatingSelect"
                aria-label="Floating label select example">
                <option value="1">Brigitte</option>
                <option value="2">Rappy</option>
                <option value="2">Abel</option>
            </select>
            <label for="floatingSelect">Artisan</label>
        </div>
        <div class="form-floating mb-3">
            <textarea type="text" class="form-control" id="floatingInput"></textarea>
            <label for="floatingInput">Detail payement</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="floatingInput">
            <label for="floatingInput">Montant</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" id="floatingInput">
            <label for="floatingInput">Date payement</label>
        </div>
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </div>
</div>

@endsection