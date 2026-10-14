@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <div class="bg-light rounded h-100 p-4">
    <form action="{{ route('SaisonController.insert') }}" method="GET">
        <h5 class="mb-4 text-center">Formulaire pour saison</h5>

        <div class="form-floating mb-3">
            <input type="date" class="form-control  @error('date_debut') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" name="date_debut"  value="{{ old('date_debut') }}">
            <label for="floatingInput">Date debut</label>
            @error('date_debut')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div class="form-floating mb-3">
            <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" name="date_fin" value="{{ old('date_fin') }}">
            <label for="floatingInput">Date fin</label>
            @error('date_fin')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('nom_saison') is-invalid @enderror" id="floatingInput" placeholder="nom_saison" name="nom_saison" value="{{ old('nom_saison') }}">
            <label for="floatingInput">nom de saison</label>
            @error('nom_saison')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </form>
    </div>

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
            <h6 class="mb-4">Liste Saison</h6>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nom de saison</th>
                            <th scope="col">Debut</th>
                            <th scope="col">Fin</th>
                            <th scope="col" style="text-align: center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saison as $saisons)
                        <tr>
                            <td>{{ $saisons->nom_saison }}</td>
                            <td>{{ $saisons->debut }}</td>
                            <td>{{ $saisons->fin }}</td>
                            <td style="text-align: center;">

                                <!-- Modifier -->
                                <button type="button" class="btn btn-success btn-sm btn-modifier"
                                    data-url="{{ route('SaisonController.updateSaison', $saisons->id) }}"
                                    data-nom="{{ $saisons->nom_saison }}"
                                    data-debut="{{ $saisons->debut }}"
                                    data-fin="{{ $saisons->fin }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <a href="{{ route('SaisonController.delete', ['id' => $saisons->id]) }}"
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
                    <h5 class="modal-title">Modifier la saison</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom_client" class="form-label">Nom saison</label>
                        <input type="text" class="form-control" id="nom_saison" name="nom_saison" required>
                    </div>
                    <div class="mb-3">
                        <label for="debut" class="form-label">date debut</label>
                        <input type="date" class="form-control" id="debut" name="debut">
                    </div>
                    <div class="mb-3">
                        <label for="fin" class="form-label">date fin</label>
                        <input type="date" class="form-control" id="fin" name="fin">
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
            document.getElementById('nom_saison').value = this.dataset.nom;   // pré-remplit l'input
            document.getElementById('debut').value = this.dataset.debut;   // pré-remplit l'input
            document.getElementById('fin').value = this.dataset.fin;   // pré-remplit l'input
            modal.show();
        });
    });
});
</script>