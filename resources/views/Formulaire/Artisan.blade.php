@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form action="{{ route('ArtisanController.insert') }}" method="GET">
    <div class="bg-light rounded h-100 p-4">
        <h5 class="mb-4 text-center">Formulaire Artisan</h5>
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('nom_artisan') is-invalid @enderror" id="floatingInput" placeholder="nom de l'artisan" name="nom_artisan">
            <label for="floatingInput">Nom de l'artisan</label>
            @error('nom_artisan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control  @error('telephone') is-invalid @enderror" id="floatingInput" placeholder="nom_saison" name="telephone">
            <label for="floatingInput">Telephone</label>
            @error('telephone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('mail') is-invalid @enderror" id="floatingInput" placeholder="nom_saison" name="mail">
            <label for="floatingInput">Mail</label>
            @error('mail')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </div>
    </form>

    <br>
    <div class="col-12">
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
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">Liste des artisan</h6>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nom artisan</th>
                            <th scope="col" style="text-align: center">Contact</th>
                            <th scope="col" style="text-align: center">Mail</th>
                            <th scope="col" style="text-align: center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($artisan as $artisans)
                        <tr>
                            <td>{{ $artisans->nom }}</td>
                            <td style="text-align: center">{{ $artisans->telephone }}</td>
                            <td style="text-align: center">{{ $artisans->mail }}</td>
                            <!-- <td style="text-align: center"><a href="{{ route('ArtisanController.delete', ['id' => $artisans->id]) }}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ? Cela entrainera la perte de toutes donnees correspondante.')" class="btn btn-danger">Supprimer</a></td> -->
                            <td style="text-align: center;">

                                    <!-- Modifier -->
                                    <button type="button" class="btn btn-success btn-sm btn-modifier"
                                        data-url="{{ route('ArtisanController.updateArtisan', $artisans->id) }}"
                                        data-nom="{{ $artisans->nom }}"
                                        data-telephone="{{ $artisans->telephone }}"
                                        data-mail="{{ $artisans->mail }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <a href="{{ route('ArtisanController.delete', ['id' => $artisans->id]) }}"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ? Cela entrainera la perte de toutes donnees correspondante.')"
                                        class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalModifier" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formModifier" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'artisan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom_client" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom_artisan" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="mail">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Telephone</label>
                        <input class="form-control" id="telephone" name="telephone">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalModifier');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formModifier');

    document.querySelectorAll('.btn-modifier').forEach(function (btn) {
        btn.addEventListener('click', function () {
            form.action = this.dataset.url;   // URL de mise à jour de la ligne
            document.getElementById('nom_artisan').value = this.dataset.nom;   // pré-remplit l'input
            document.getElementById('telephone').value = this.dataset.telephone;   // pré-remplit l'input
            document.getElementById('email').value = this.dataset.mail;   // pré-remplit l'input
            modal.show();
        });
    });
});
</script>