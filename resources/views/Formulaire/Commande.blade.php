@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="maxCounter" name="maxCounter" value="0">

    <div class="bg-light rounded h-100 p-4" id="content">
        <h5 class="mb-4 text-center">Formulaire Commande</h5>
        <div class="form-floating mb-3">
            <select class="form-select @error('id_artisan') is-invalid @enderror" id="floatingSelect" aria-label="Floating label select example" name="id_artisan">
                @foreach ($artisan as $artisans)
                    <option value="{{ $artisans->id }}">{{ $artisans->nom }}</option>
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
            <select class="form-select @error('saison') is-invalid @enderror" id="floatingSelect" aria-label="Floating label select example" name="saison">
                @foreach ($saison as $s)
                    <option value="{{ $s->id }}">{{ $s->nom_saison }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Saison</label>
            @error('saison')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        {{-- <div class="form-floating mb-3">
            <input type="date" class="form-control @error('date_commande') is-invalid @enderror" id="floatingInput" name="date_commande" value="{{ old('date_commande') }}">
            <label for="floatingInput">Date commande</label>
            @error('date_commande')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div> --}}
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('type_commande') is-invalid @enderror" id="floatingInput" name="type_commande" value="{{ old('type_commande') }}">
            <label for="floatingInput">Type commande</label>
            @error('type_commande')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <input class="form-control @error('file') is-invalid @enderror" type="file" id="formFile" name="file">
         @error('file')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        <div style="margin-top: 25px" >
        <button type="submit" class="btn btn-primary w-100" formaction="{{ route('ImportController.importAction') }}">Importer</button>
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
        <br>
        <h5 class="mb-4 text-center">Section produit</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('produit_0') is-invalid @enderror" id="floatingInput" name="produit_0">
                    <label for="floatingInput">Produit</label>
                    @error('produit_0')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
               
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('designation_0') is-invalid @enderror" id="floatingInput" name="designation_0">
                    <label for="floatingInput">Designation catalogue</label>
                    @error('designation_0')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <select class="form-select @error('id_couleur_0') is-invalid @enderror" id="floatingSelect" aria-label="Floating label select example" name="id_couleur_0">
                        @foreach ($couleur as $couleurs)
                            <option value="{{ $couleurs->id }}">{{ $couleurs->nom }}</option>
                        @endforeach
                    </select>
                    <label for="floatingSelect">Couleur</label>
                    @error('id_couleur_0')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('qte_commande_0') is-invalid @enderror" id="floatingInput" name="qte_commande_0">
                    <label for="floatingInput">Quantité</label>
                    @error('qte_commande_0')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control  @error('prix_unitaire_0') is-invalid @enderror" id="floatingInput" name="prix_unitaire_0">
                    <label for="floatingInput">Prix unitaire</label>
                    @error('prix_unitaire_0')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div> 
        </div>
    </div>
    <div class="row justify-content-center mt-3" style="margin-left: 0px">
        <div class="col-auto">
            <button type="button" id="add-section" class="btn btn-success">+ ajouter section </button>
        </div>
        <div class="col-auto">
            <button type="button" id="remove-section" class="btn btn-danger">- réduire section </button>
        </div>
    </div>
    <div style="margin-top: 25px" >
        <button type="submit" class="btn btn-primary" formaction="{{ route('CommandeController.insertCommande') }}"> Enregistrer </button>
    </div>
    </form>
</div>

<script>
let counter = 0;
    document.getElementById('add-section').addEventListener('click', function() {
        counter++;
        console.log(counter);

        var newSection = document.createElement('div');
        newSection.className = 'section';
        newSection.innerHTML = `
        <div class="row" style="margin-top:20px">
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" name="produit_${counter}">
                    <label for="floatingInput">Produit</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" name="designation_${counter}">
                    <label for="floatingInput">Designation catalogue</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="id_couleur_${counter}">
                        @foreach ($couleur as $couleurs)
                            <option value="{{ $couleurs->id }}">{{ $couleurs->nom }}</option>
                        @endforeach
                    </select>
                    <label for="floatingSelect">Couleur</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" name="qte_commande_${counter}">
                    <label for="floatingInput">Quantité</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" name="prix_unitaire_${counter}">
                    <label for="floatingInput">Prix unitaire</label>
                </div>
            </div>        
        </div>
        <hr>
        `;

        // Ajouter la nouvelle carte au conteneur
        document.getElementById('content').appendChild(newSection);

        // Mettre à jour la valeur de l'input hidden
        document.getElementById('maxCounter').value = counter;
        });

        // retirer un input
        document.getElementById('remove-section').addEventListener('click', function() {
                const container = document.getElementById('content');
                const sections = container.querySelectorAll('.section');

                console.log(sections.length);

                if (sections.length > 0) {
                    const lastSection = sections[sections.length - 1];
                    lastSection.remove();

                    // Optionnel : mettre à jour le compteur s’il est utilisé
                    counter = sections.length - 1;
                    document.getElementById('maxCounter').value = counter;
                }
        });

</script>
@endsection