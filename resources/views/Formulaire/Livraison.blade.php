@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <div class="bg-light rounded h-100 p-4" id="content">
        <h5 class="mb-4 text-center">Formulaire Livraison</h5>
        <div class="form-floating mb-3">
            <select class="form-select" id="floatingSelect"
                aria-label="Floating label select example">
                <option value="1">Entrée</option>
                <option value="2">Sortie</option>
            </select>
            <label for="floatingSelect">Type Livraison</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" id="floatingInput">
            <label for="floatingInput">Date de livraison</label>
        </div>
        <div class="form-floating mb-3">
            <select class="form-select" id="floatingSelect"
                aria-label="Floating label select example">
                <option value="1">Brigitte</option>
                <option value="2">Rappy</option>
                <option value="2">Abel</option>
            </select>
            <label for="floatingSelect">Artisan</label>
        </div>
        <br>
        <hr>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelect"
                        aria-label="Floating label select example">
                        <option value="1">Panier Aravola GM luxe</option>
                        <option value="2">Panier Aravola PM</option>
                        <option value="2">Panier Giffi Tutti frutty</option>
                    </select>
                    <label for="floatingSelect">Produit</label>
                </div>
            </div>
            <div class="col-md-6">

                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput">
                    <label for="floatingInput">Quantité</label>
                </div>
            </div>
            <div style="margin-left: 40%">
                <button type="button" id="add-section"> + ajouter section</button>
            </div>
        </div>
    </div>
    <div style="margin-top: 25px">
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </div>
</div>

<script>
    document.getElementById('add-section').addEventListener('click', function() {

        var newSection = document.createElement('div');
        newSection.className = 'section';
        newSection.innerHTML = `
        <div class="row" style="margin-top:15px">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelect"
                        aria-label="Floating label select example">
                        <option value="1">Panier Aravola GM luxe</option>
                        <option value="2">Panier Aravola PM</option>
                        <option value="2">Panier Giffi Tutti frutty</option>
                    </select>
                    <label for="floatingSelect">Produit</label>
                </div>
            </div>
            <div class="col-md-6">

                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput">
                    <label for="floatingInput">Quantité</label>
                </div>
            </div>
        </div>        
        `;
        console.log("ao ve zay ve e");

        // Ajouter la nouvelle carte au conteneur
        document.getElementById('content').appendChild(newSection);
        });

</script>


@endsection