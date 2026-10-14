@extends('Layout.app')
@section('contents')

<div class="col-sm-12 col-xl-10">
    <form action=" {{ route('ClientController.insertClient')}}">
    <div class="bg-light rounded h-100 p-4">
        <h5 class="mb-4 text-center">Formulaire pour client</h5>

        <div class="form-floating mb-3">
            <input type="text" class="form-control  @error('nom_client') is-invalid @enderror" id="floatingInput" name="nom_client">
            <label for="floatingInput">Nom client</label>
            @error('nom_client')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary"> Enregistrer </button>
    </div>
    </form>
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
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">Liste de client</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom</th>
                            <th scope="col" style="text-align: center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($client as $clients)
                        <tr>
                            <th scope="row">{{ $i++ }}</th>
                            <td>{{ $clients->nom_client}}</td>
                            <!-- <td style="text-align: center"><a href="{{ route('ClientController.deleteClient', ['id' => $clients->id] )}}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ? Cela entrainera la perte de toutes donnees correspondante.')" class="btn btn-danger">Supprimer</a></td> -->
                             <td style="text-align: center;">

                                <!-- Modifier -->
                                <button type="button" class="btn btn-success btn-sm btn-modifier"
                                    data-url="{{ route('ClientController.updateClient', $clients->id) }}"
                                    data-nom="{{ $clients->nom_client }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <a href="{{ route('ClientController.deleteClient', ['id' => $clients->id]) }}"
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
                    <h5 class="modal-title">Modifier le client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label for="nom_client" class="form-label">Nom du client</label>
                    <input type="text" class="form-control" id="nom_client" name="nom_client" required>
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
    const input = document.getElementById('nom_client');

    document.querySelectorAll('.btn-modifier').forEach(function (btn) {
        btn.addEventListener('click', function () {
            form.action = this.dataset.url;   // URL de mise à jour de la ligne
            input.value = this.dataset.nom;   // pré-remplit l'input
            modal.show();
        });
    });
});
</script>