@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form action="{{ route('LivraisonController.insert') }}" method="POST">
    @csrf

    <input type="hidden" id="maxCounter" name="maxCounter" value="0">

    <div class="bg-light rounded h-100 p-4" id="content">
        <h5 class="mb-4 text-center">Formulaire Livraison</h5>
        <div class="form-floating mb-3">
            <select class="form-select @error('id_saison') is-invalid @enderror" aria-label="Floating label select example" name="id_saison">
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
            <input type="date" class="form-control @error('date_livraison') is-invalid @enderror" id="floatingInput" name="date_livraison">
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
                <div class="form-floating mb-4">
                    <select class="form-select" aria-label="Floating label select example" id="produitSelect" name="id_produit_0">
                    </select>
                    <label for="floatingSelect">Produit</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <select class="form-select" aria-label="Floating label select example" id="couleurSelect" name="couleur_0">
                    </select>
                    <label for="floatingSelect">Couleur</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control @error('qte_livre_0') is-invalid @enderror" id="floatingInput" name="qte_livre_0">
                    <label for="floatingInput">Quantité</label>
                    @error('qte_livre_0')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
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
            
        </div>
    </div>
    <div style="margin-top: 25px" >
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </div>
    </form>
</div>

<script>
    let counter= 0;
    document.getElementById('add-section').addEventListener('click', function() {
        counter++;
        console.log(counter);

        var newSection = document.createElement('div');
        newSection.className = 'section';
        newSection.innerHTML = `
        <div class="row" style="margin-top:15px">
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <select class="form-select produit-select" aria-label="Floating label select example" name="id_produit_${counter}">
                    </select>
                    <label for="floatingSelect">Produit</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <select class="form-select couleur-select" aria-label="Floating label select example" name="couleur_${counter}">
                    </select>
                    <label for="floatingInput">Couleur</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control qte-input" id="floatingInput" name="qte_livre_${counter}">
                    <label for="floatingInput">Quantité</label>
                </div>
            </div>
        </div>        
        `;


        // Ajouter la nouvelle carte au conteneur
        document.getElementById('content').appendChild(newSection);

// ajouter getProduit et get couleur dans les input js
        const artisaId = document.getElementById('floatingSelect').value;
        const produitSel   = newSection.querySelector('.produit-select');
        const couleurSel   = newSection.querySelector('.couleur-select');
        if (artisaId) {
            fetch(`/get-produits/${artisaId}`)
                .then(r => r.json())
                .then(produits => {
                    produitSel.innerHTML =
                        '<option value=""></option>';

                    produits.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = p.designation_produit;
                        produitSel.appendChild(opt);
                    });

                    
                    // Sélectionner automatiquement le 1er produit et charger ses couleurs
                    if (produits.length) {
                        produitSel.value = produits[0].id;
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

            fetch(`/get-couleurs/${produitId}`)
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
        });        
// ---------------------------------------------------

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
       

    // pour l'input artisan dans formulaire non js
    document.getElementById('floatingSelect').addEventListener('change', function () {
        const artisanId = this.value;
    
        console.log("miditra");
        if (artisanId) {
            fetch(`/get-produits/${artisanId}`)
                .then(response => response.json())
                .then(data => {
                    const produitSelect = document.getElementById('produitSelect');
                    produitSelect.innerHTML = ''; // reset
    
                    data.forEach(produit => {
                        const option = document.createElement('option');
                        option.value = produit.id;
                        option.textContent = produit.designation_produit;
                        produitSelect.appendChild(option);
                    });

                    if (data.length > 0) {
                        produitSelect.value = data[0].id;
                        produitSelect.dispatchEvent(new Event('change'));
                    }
                });
        }
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
    
        console.log("miditra2");
        if (produitId) {
            fetch(`/get-couleurs/${produitId}`)
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

    
    
    
</script>
@endsection