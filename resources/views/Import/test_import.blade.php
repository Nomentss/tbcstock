@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form action="{{ route('ImportController.importAction')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="bg-light rounded h-100 p-4">
        <h5 class="mb-4 text-center">Formulaire import</h5>

        <h6 class="mb-4">File Input</h6>
        <div class="mb-3">
            <label for="formFile" class="form-label @error('file') is-invalid @enderror">Default file input example</label>
            <input class="form-control" type="file" id="formFile" name="file">
            @error('file')
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