@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <div class="bg-light rounded h-100 p-4">
    <form action="{{ route('ExpeditionController.insertExpedition') }}" method="POST">
    @csrf
        <h5 class="mb-4 text-center">Formulaire pour expedition</h5>
        <input type="hidden" id="maxCounter" name="maxCounter" value="0">

        <div class="bg-light rounded h-100 p-4" id="content">
            <div class="form-floating mb-3">
                <select class="form-select @error('id_saison') is-invalid @enderror" aria-label="Floating label select example" id="saisonSelect" name="id_saison">
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
                <input type="date" class="form-control  @error('date_expedition') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" name="date_expedition" value="{{ old('date_expedition') }}">
                <label for="floatingInput">Date expedition</label>
                @error('date_expedition')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            {{-- @error('nom_saison')
            <span style="color: red">{{ $message }}</span>
            @enderror --}}
            <div class="form-floating mb-3">
                <input type="text" class="form-control  @error('expedition_route') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" name="expedition_route" value="{{ old('expedition_route') }}">
                <label for="floatingSelect">Expedié dans</label>
                @error('expedition_route')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" aria-label="Floating label select example" id="catalogueSelect" name="catalogue">
                    {{-- @foreach ($catalogue as $c)
                        <option value="{{ $c->designation_catalogue }}">{{ $c->designation_catalogue }}</option>
                    @endforeach --}}
                </select>
                <label for="floatingSelect">Designation catalogue David</label>
            </div>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if(session('fail'))
            <div class="alert alert-danger">
                @if(is_array(session('fail')))
                    <ul>
                        @foreach (session('fail') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>{{ session('fail') }}</p>
                @endif
            </div>
            @endif
            <br>
            @if($errors->any())
                <div class="alert alert-danger">
                    Veuillez remplir le formulaire correctement.
                </div>
            @endif
            <br>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-4">
                        <select class="form-select" aria-label="Floating label select example" id="artisanSelect" name="id_artisan_0">
                        </select>
                        <label for="floatingSelect">Artisan</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-4">
                        <select class="form-select" aria-label="Floating label select example" id="produitSelect" name="id_produit_0">
                        </select>
                        <label for="floatingSelect">Produit</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="Floating label select example" id="couleurSelect" name="couleur_0">
                        </select>
                        <label for="floatingSelect">Couleur</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('qte_0') is-invalid @enderror" id="floatingInput" name="qte_0">
                        <label for="floatingInput">Quantité</label>
                        @error('qte_0')
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
            <button type="submit" class="btn btn-primary"> Enregistrer </button>
        </form>
    </div>
</div>
<script>
let counter= 0;
    document.getElementById('add-section').addEventListener('click', function() {
        counter++;
        console.log(counter);

        var newSection = document.createElement('div');
        newSection.className = 'section';
        newSection.innerHTML = `
        <hr>
        <div class="row" style="margin-top:15px">
                <div class="col-md-12">
                    <div class="form-floating mb-4">
                        <select class="form-select artisan-select" aria-label="Floating label select example" name="id_artisan_${counter}">
                        </select>
                        <label for="floatingSelect">Artisan</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-4">
                        <select class="form-select produit-select" aria-label="Floating label select example" id="produitSelect" name="id_produit_${counter}">
                        </select>
                        <label for="floatingSelect">Produit</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <select class="form-select couleur-select" aria-label="Floating label select example" id="couleurSelect" name="couleur_${counter}">
                        </select>
                        <label for="floatingSelect">Couleur</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control @error('qte_livre_0') is-invalid @enderror" id="floatingInput" name="qte_${counter}">
                        <label for="floatingInput">Quantité</label>
                        @error('qte_0')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
        </div>
        `;

        // Ajouter la nouvelle carte au conteneur
        document.getElementById('content').appendChild(newSection);

        // ajouter dans les input js
        const catalogue = document.getElementById('catalogueSelect').value;
        const id_saison = document.getElementById('saisonSelect').value; // valeur dans saison
        const artisanSel  = newSection.querySelector('.artisan-select');
        const produitSel  = newSection.querySelector('.produit-select');
        const couleurSel  = newSection.querySelector('.couleur-select');

        if (catalogue && id_saison) {
            fetch(`{{ url('/get-artisans') }}/${catalogue}/${id_saison}`)
                .then(r => r.json())
                .then(artisans => {
                    artisanSel.innerHTML =
                        '';

                    artisans.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id_artisan;
                        opt.textContent = p.artisan;
                        artisanSel.appendChild(opt);
                    });

                     // Sélectionner automatiquement le 1er artisans
                     if (artisans.length) {
                        artisanSel.value = artisans[0].id_artisan;
                        artisanSel.dispatchEvent(new Event('change'));
                    }
                });
        }
        artisanSel.addEventListener('change', function () {
            const artisaId = artisanSel.value;
            const id_saison = document.getElementById('saisonSelect').value; // valeur dans saison


            fetch(`{{ url('/get-produits') }}/${artisaId}/${catalogue}/${id_saison}`)
                .then(r => r.json())
                .then(produit => {
                    produitSel.innerHTML =
                        '';
                    produit.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id_produit;
                        opt.textContent = c.designation_produit;
                        produitSel.appendChild(opt);
                    });

                    if (produit.length) {
                        produitSel.value = produit[0].id_produit;
                        produitSel.dispatchEvent(new Event('change'));
                    }
                });
        });

        // pour l'input js couleur
        produitSel.addEventListener('change', function () {
            const produitId = this.value;
            const artisaId = artisanSel.value;
            const id_saison = document.getElementById('saisonSelect').value; // valeur dans saison
            console.log(artisaId);

            fetch(`{{ url('/get-couleurs') }}/${artisaId}/${produitId}/${id_saison}`)
                .then(r => r.json())
                .then(couleur => {
                    couleurSel.innerHTML =
                        '';
                    couleur.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id_couleur;
                        opt.textContent = c.couleur;
                        couleurSel.appendChild(opt);
                    });

                });
        });        


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

    // pour l'input saison dans formulaire non js
    document.getElementById('saisonSelect').addEventListener('change', function () {
        console.log("miditra32");
        const id_saison = this.value;

        if (id_saison) {

            fetch(`{{ url('/get-catalogue') }}/${id_saison}`)
                .then(response => response.json())
                .then(data => {
                    const catalogueSelect = document.getElementById('catalogueSelect');
                    catalogueSelect.innerHTML = ''; // reset
    
                    data.forEach(catalogue => {
                        const option = document.createElement('option');
                        option.value = catalogue.designation_catalogue;
                        option.textContent = catalogue.designation_catalogue;
                        catalogueSelect.appendChild(option);
                    });

                    if (data.length > 0) {
                        catalogueSelect.value = data[0].designation_catalogue;
                        catalogueSelect.dispatchEvent(new Event('change'));
                    }
                });
        }
    });

    //  Déclenche automatiquement le changement dès le chargement de la page
    window.addEventListener('DOMContentLoaded', function () {
        const selectSaison = document.getElementById('saisonSelect');
        if (selectSaison.value) {
            selectSaison.dispatchEvent(new Event('change'));
        }    
    });

    // pour l'input catalogue dans formulaire non js
    document.getElementById('catalogueSelect').addEventListener('change', function () {
        const id_saison = document.getElementById('saisonSelect').value; // valeur dans saison
        const catalogue = this.value;
    
        console.log("miditra");
        if (catalogue && id_saison) {
            fetch(`{{ url('/get-artisans') }}/${catalogue}/${id_saison}`)
                .then(response => response.json())
                .then(data => {
                    const artisanSelect = document.getElementById('artisanSelect');
                    artisanSelect.innerHTML = ''; // reset
    
                    data.forEach(artisan => {
                        const option = document.createElement('option');
                        option.value = artisan.id_artisan;
                        option.textContent = artisan.artisan;
                        artisanSelect.appendChild(option);
                    });

                    if (data.length > 0) {
                        artisanSelect.value = data[0].id_artisan;
                        artisanSelect.dispatchEvent(new Event('change'));
                    }
                });
        }
    });


    // pour l'input produit non js
    document.getElementById('artisanSelect').addEventListener('change', function () {
        const artisaId = this.value;
        const catalogue = document.getElementById('catalogueSelect').value; // valeur dans catalogue
        const id_saison = document.getElementById('saisonSelect').value; // valeur dans saison
    
        // console.log("miditra2");
        if (artisaId && catalogue && id_saison) {
            fetch(`{{ url('/get-produits') }}/${artisaId}/${catalogue}/${id_saison}`)
                .then(response => response.json())
                .then(data => {
                    const produitSelect = document.getElementById('produitSelect');
                    produitSelect.innerHTML = ''; // reset
    
                    data.forEach(produit => {
                        const option = document.createElement('option');
                        option.value = produit.id_produit;
                        option.textContent = produit.designation_produit;
                        produitSelect.appendChild(option);
                    });
                     // Déclenche l'événement 'change' automatiquement
                    produitSelect.dispatchEvent(new Event('change'));
                    
                });
        }

    });
    // pour l'input couleur non js
    document.getElementById('produitSelect').addEventListener('change', function () {
        const produitId = this.value;
        const artisanId = document.getElementById('artisanSelect').value; // valeur dans idArtisan
        const id_saison = document.getElementById('saisonSelect').value; // valeur dans saison

        console.log("miditra 3");
    
        if (artisanId && produitId) {
            fetch(`{{ url('/get-couleurs') }}/${artisanId}/${produitId}/${id_saison}`)
                .then(response => response.json())
                .then(data => {
                    const couleurSelect = document.getElementById('couleurSelect');
                    couleurSelect.innerHTML = ''; // reset
    
                    data.forEach(couleur => {
                        const option = document.createElement('option');
                        option.value = couleur.id_couleur;
                        option.textContent = couleur.couleur;
                        couleurSelect.appendChild(option);
                    });
                    
                
                });
        }
    });
    
</script>
@endsection