@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form method="POST" enctype="multipart/form-data">
    @csrf

    <input type="hidden" id="maxCounter" name="maxCounter" value="0">

    <div class="bg-light rounded h-100 p-4" id="content">
        <h5 class="mb-4 text-center">Formulaire pour article en retour</h5>
        <div class="form-floating mb-3">
            <select class="form-select @error('id_saison') is-invalid @enderror" aria-label="Floating label select example" id="saisonSelect" name="id_saison" value="{{ old('id_saison') }}">
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
            <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="id_artisan">
                @foreach ($artisan as $artisans)
                    <option value="{{ $artisans->id }}">{{ $artisans->nom }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Artisan</label>
        </div>
        
        <div class="form-floating mb-3">
            <input type="date" class="form-control @error('date_retour') is-invalid @enderror" id="floatingInput" name="date_retour" value="{{ old('date_retour') }}">
            <label for="floatingInput">Date retour</label>
            @error('date_retour')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="bon_sortie">
            <label>Numero BS</label>
            @error('bon_sortie')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <br>

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
        <hr>
        <br>
        <h5 class="mb-4 text-center">Section produit</h5>
        <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-4">
                    <select class="form-select" aria-label="Floating label select example" id="produitSelect" name="id_produit_0">
                    </select>
                    <label for="floatingSelect">Produit</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="catalogueSelect" disabled>
                    <label for="floatingSelect">Designation catalogue</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <select class="form-select" aria-label="Floating label select example" id="couleurSelect" name="couleur_0">
                    </select>
                    <label for="floatingSelect">Couleur</label>
                </div>
            </div>
            <div class="col-md-6">
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
    <div style="margin-top: 25px" >
        <button type="submit" class="btn btn-primary" formaction="{{ route('ArticleRetourController.insertArticleRetour') }}"> Enregistrer </button>
    </div>
    </form>
</div>

<script>
    let counter= 0;
    document.getElementById('add-section').addEventListener('click', function() {
        counter++;
        // console.log(counter);

        var newSection = document.createElement('div');
        newSection.className = 'section';
        newSection.innerHTML = `
        <hr>
        <div class="row" style="margin-top:15px">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <select class="form-select produit-select" aria-label="Floating label select example" name="id_produit_${counter}">
                    </select>
                    <label for="floatingSelect">Produit</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control catalogue-select" disabled>
                    <label for="floatingSelect">Designation catalogue</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <select class="form-select couleur-select" aria-label="Floating label select example" name="couleur_${counter}">
                    </select>
                    <label for="floatingInput">Couleur</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control qte-input" id="floatingInput" name="qte_${counter}">
                    <label for="floatingInput">Quantité</label>
                </div>
            </div>
        </div>        
        `;


        // Ajouter la nouvelle carte au conteneur
        document.getElementById('content').appendChild(newSection);

// ajouter getProduit et get couleur dans les input js
        const artisaId = document.getElementById('floatingSelect').value;
        const saisonId = document.getElementById('saisonSelect').value;
        const produitSel   = newSection.querySelector('.produit-select');
        const couleurSel   = newSection.querySelector('.couleur-select');
        const catalogueSel   = newSection.querySelector('.catalogue-select');

        if (artisaId && saisonId) {
            fetch(`{{ url('/get-produits-stock') }}/${artisaId}/${saisonId}`)
                .then(r => r.json())
                .then(produits => {
                    produitSel.innerHTML =
                        '';

                    produits.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id_produit;
                        opt.textContent = p.designation_produit;
                        produitSel.appendChild(opt);
                    });

                    
                    // Sélectionner automatiquement le 1er produit et charger ses couleurs
                    if (produits.length) {
                        produitSel.value = produits[0].id_produit;
                        produitSel.dispatchEvent(new Event('change'));
                    }
                });
        }
        produitSel.addEventListener('change', function () {
            const produitId = this.value;
            if (!produitId) {
                couleurSel.innerHTML =
                    '<option value="">-- Choisir une couleur --</option>';
                return;
            }

            fetch(`{{ url('/get-couleurs-stock') }}/${produitId}`)
                .then(r => r.json())
                .then(couleurs => {
                    couleurSel.innerHTML =
                        '';
                    couleurs.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id_couleur;
                        opt.textContent = c.couleur;
                        couleurSel.appendChild(opt);
                    });
                });

                fetch(`{{ url('/get-catalogues-stock') }}/${produitId}`)
                .then(r => r.json())
                .then(catalogues => {
                    catalogueSel.innerHTML ='';
                    
                    catalogueSel.value = catalogues.designation_catalogue;
                });
        });        
// ---------------------------------------------------

        // Mettre à jour la valeur de l'input hidden
        document.getElementById('maxCounter').value = counter;
        });

        // retirer un input
        document.getElementById('remove-section').addEventListener('click', function() {
                const container = document.getElementById('content');
                const sections = container.querySelectorAll('.section');

                if (sections.length > 0) {
                    const lastSection = sections[sections.length - 1];
                    lastSection.remove();

                    // Optionnel : mettre à jour le compteur s’il est utilisé
                    counter = sections.length - 1;
                    document.getElementById('maxCounter').value = counter;
                }
        });
       

    // pour l'input artisan dans formulaire non js

    //  Déclenche automatiquement le changement dès le chargement de la page
    window.addEventListener('DOMContentLoaded', function () {
            const selectArtisan = document.getElementById('floatingSelect');
            if (selectArtisan.value) {
                selectArtisan.dispatchEvent(new Event('change'));
            }
    });


    document.getElementById('floatingSelect').addEventListener('change', function () {
        loadProduit();
    });

    document.getElementById('saisonSelect').addEventListener('change', function () {
        loadProduit();
    });

    // pour l'input couleur non js
    document.getElementById('produitSelect').addEventListener('change', function () {
        const produitId = this.value;
    
        // console.log("miditra2");
        if (produitId) {
            fetch(`{{ url('/get-couleurs-stock') }}/${produitId}`)
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

    document.getElementById('produitSelect').addEventListener('change', function () {
        const produitId = this.value;
    
        // console.log("miditra5");
        if (produitId) {
            fetch(`{{ url('/get-catalogues-stock') }}/${produitId}`)
                .then(response => response.json())
                .then(data => {
                    const catalogueSelect = document.getElementById('catalogueSelect');
                    catalogueSelect.innerHTML = ''; // reset
    
                    catalogueSelect.value = data.designation_catalogue;
                });
        }
    });

    function loadProduit(){
        const artisanId = document.getElementById('floatingSelect').value;
        const saison = document.getElementById('saisonSelect').value;

        // console.log("artisan :", artisanId);
        // console.log("saison :", saison);

        if (artisanId && saison) {
            fetch(`{{ url('/get-produits-stock') }}/${artisanId}/${saison}`)
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

                    if (data.length > 0) {
                        produitSelect.value = data[0].id_produit;
                        produitSelect.dispatchEvent(new Event('change'));
                    }
            });
        }
    }

</script>
@endsection