@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form method="POST" enctype="multipart/form-data">
    @csrf

    <input type="hidden" id="maxCounter" name="maxCounter" value="0">

    <div class="bg-light rounded h-100 p-4" id="content">
        <h5 class="mb-4 text-center">Formulaire de livraison des commmandes</h5>
        <div class="form-floating mb-3">
            <select class="form-select @error('id_saison') is-invalid @enderror" aria-label="Floating label select example" name="id_saison" id="saisonSelect" value="{{ old('id_saison') }}">
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
            <input type="date" class="form-control @error('date_livraison') is-invalid @enderror" id="floatingInput" name="date_livraison" value="{{ old('date_livraison') }}">
            <label for="floatingInput">Date de livraison</label>
            @error('date_livraison')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('bon_reception') is-invalid @enderror" name="bon_reception" value="{{ old('bon_reception') }}">
            <label>Numero BR</label>
            @error('bon_reception')
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
        <button type="submit" class="btn btn-primary w-100" formaction="{{ route('ImportController.importLivraison') }}">Importer</button>
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
                    <input type="text" class="form-control @error('qte_livre_0') is-invalid @enderror" id="floatingInput" name="qte_livre_0">
                    <label for="floatingInput">Quantité</label>
                    @error('qte_livre_0')
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
        <button type="submit" class="btn btn-primary" formaction="{{ route('LivraisonController.insert') }}"> Enregistrer </button>
    </div>
    </form>
@if(session('erreurs_commande'))
<div class="modal fade" id="modalErreurs"
     tabindex="-1"
     data-bs-backdrop="static">

    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    Erreurs de quantité détectées
                </h5>
            </div>

            <div class="modal-body">

                <p>Les erreurs suivantes ont été détectées :</p>

                <ul class="text-danger">
                    @foreach(session('erreurs_commande') as $erreur)
                        <li>{{ $erreur }}</li>
                    @endforeach
                </ul>

                <p class="fw-bold">
                    Voulez-vous continuer a inserer la livraison?
                </p>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Annuler
                </button>

                <form action="{{ session('route_confirmation') }}"
                      method="POST">
                    @csrf

                    <button type="submit"
                            class="btn btn-primary">
                        Continuer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
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
                    <input type="text" class="form-control qte-input" id="floatingInput" name="qte_livre_${counter}">
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
            fetch(`{{ url('/get-produits') }}/${artisaId}/${saisonId}`)
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

            fetch(`{{ url('/get-couleurs') }}/${produitId}`)
                .then(r => r.json())
                .then(couleurs => {
                    couleurSel.innerHTML =
                        '';
                    couleurs.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id_couleur;
                        opt.textContent = c.nom;
                        couleurSel.appendChild(opt);
                    });
                });

                fetch(`{{ url('/get-catalogues') }}/${produitId}`)
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

        document.getElementById('floatingSelect').addEventListener('change', function () {
            loadProduit();
        });

        document.getElementById('saisonSelect').addEventListener('change', function () {
            loadProduit();
        });

        
    //  Déclenche automatiquement le changement dès le chargement de la page
    window.addEventListener('DOMContentLoaded', function () {
            const selectArtisan = document.getElementById('floatingSelect');
            if (selectArtisan.value) {
                selectArtisan.dispatchEvent(new Event('change'));
            }
    });

    // pour l'input couleur non js
    document.getElementById('produitSelect').addEventListener('change', function () {
        const produitId = this.value;
    
        if (produitId) {
            fetch(`{{ url('/get-couleurs') }}/${produitId}`)
                .then(response => response.json())
                .then(data => {
                    const couleurSelect = document.getElementById('couleurSelect');
                    couleurSelect.innerHTML = ''; // reset
    
                    data.forEach(couleur => {
                        const option = document.createElement('option');
                        option.value = couleur.id_couleur;
                        option.textContent = couleur.nom;
                        couleurSelect.appendChild(option);
                    });
                });
        }
    });

    document.getElementById('produitSelect').addEventListener('change', function () {
        const produitId = this.value;
    
        // console.log("miditra5");
        if (produitId) {
            fetch(`{{ url('/get-catalogues') }}/${produitId}`)
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
        const saisonId = document.getElementById('saisonSelect').value;

        if (artisanId && saisonId) {
            fetch(`{{ url('/get-produits') }}/${artisanId}/${saisonId}`)
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
@if(session('erreurs_commande'))
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = new bootstrap.Modal(
        document.getElementById('modalErreurs')
    );

    modal.show();

});
</script>
@endif
@endsection