@extends('Layout.app')
@section('contents')
<style>
    .table thead th {
        background-color: #a2a2a2;
        color: rgb(246, 246, 246);
    }

    th{
        text-align: center;
        font-family::Arial, Helvetica, sans-serif;
        font-size: 90%;
        white-space: nowrap;
    }

    td{
        font-size: 90%;
        font-family::Arial, Helvetica, sans-serif;
        white-space: nowrap;
        
    }

</style>
<div class="col-sm-12 col-xl-12">
    <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h5 class="mb-4" style="text-align: center">Modification des article en retour de "{{ session('nom_artisan') }}"</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>DESIGNATION</th>
                            <th>DESIGNATION CATALOGUE</th>
                            <th>COULEUR</th>
                            <th>QUANTITE</th>
                            <th>Date retour</th>
                            <th>Bon sortie</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $details)
                            <tr id="ligne-{{ $details->id }}">
                                <td>{{ $details->designation_produit }}</td>
                                <td>{{ $details->designation_catalogue }}</td>
                                <td>{{ $details->couleur }}</td>

                                <td>
                                    <span class="qte-text">
                                        {{ $details->qte }}
                                    </span>
                                    <input type="text"
                                        class="form-control qte-input d-none"
                                        value="{{ $details->qte }}"
                                        min="0" step="1">
                                </td>
                                <td>
                                    <span class="date-text">
                                        {{ $details->date_retour }}
                                    </span>
                                    <input type="text"
                                        class="form-control date-input d-none"
                                        value="{{ $details->date_retour }}"
                                        min="0" step="1">
                                </td>
                                <!-- <td>
                                    {{ $details->date_retour }}
                                </td> -->
                                <td>
                                    {{ $details->bon_sortie }}
                                </td>

                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button"
                                            class="btn btn-primary btn-sm btn-modifier"
                                            onclick="modifierLigne({{ $details->id }})">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <button type="button"
                                            class="btn btn-success btn-sm btn-enregistrer d-none"
                                            onclick="enregistrerLigne({{ $details->id }})">
                                            <i class="bi bi-check-lg"></i>
                                        </button>

                                        <button type="button"
                                            class="btn btn-secondary btn-sm btn-annuler d-none"
                                            onclick="annulerModification({{ $details->id }})">
                                            <i class="bi bi-x-lg"></i>
                                        </button>

                                        <button type="button"
                                            class="btn btn-danger btn-sm btn-supprimer"
                                            onclick="supprimerLigne({{ $details->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </form>

</div>
<script>

const formatMontant = (valeur) => {
    return new Intl.NumberFormat('fr-FR', {
        maximumFractionDigits: 0
    }).format(valeur) + ' AR';
};

function afficherModeEdition(ligne, edition) {
    ligne.querySelector('.qte-text').classList.toggle('d-none', edition);
    ligne.querySelector('.qte-input').classList.toggle('d-none', !edition);

    ligne.querySelector('.btn-modifier').classList.toggle('d-none', edition);
    ligne.querySelector('.btn-enregistrer').classList.toggle('d-none', !edition);
    ligne.querySelector('.btn-annuler').classList.toggle('d-none', !edition);
    ligne.querySelector('.btn-supprimer').classList.toggle('d-none', edition);
}

// Modifier
function modifierLigne(id) {
    const ligne = document.getElementById(`ligne-${id}`);
    afficherModeEdition(ligne, true);
}

// Annuler
function annulerModification(id) {
    const ligne = document.getElementById(`ligne-${id}`);

    ligne.querySelector('.qte-input').value =
        ligne.querySelector('.qte-input').defaultValue;

    afficherModeEdition(ligne, false);
}

// Enregistrer
async function enregistrerLigne(id) {
    const ligne = document.getElementById(`ligne-${id}`);

    const qteInput = ligne.querySelector('.qte-input');
    const dateInput = ligne.querySelector('.date-input');

    const qte = Number(qteInput.value);
    const date = dateInput.value;


    if (
        qteInput.value === '' ||
        !Number.isInteger(qte) ||
        qte < 0
    ) {
        alert('Veuillez saisir une quantité entière positive.');
        return;
    }

    const bouton = ligne.querySelector('.btn-enregistrer');
    bouton.disabled = true;

    try {
        const response = await fetch(`{{ url('/update-articleRetour') }}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                qte: qte,
                date_retour: date,
                id_artisan: @json($detail->first()->id_artisan),
                id_saison: @json($detail->first()->id_saison),
                id_client: @json($detail->first()->id_client)
            })
        });

        const data = await response.json();

        if (!response.ok) {
            console.error('Erreur Laravel :', data);
            alert(data.message || 'Erreur lors de la modification');
            return;
        }

        ligne.querySelector('.qte-text').textContent = data.qte;

        qteInput.value = data.qte;

        qteInput.defaultValue = data.qte;

        afficherModeEdition(ligne, false);

    } catch (error) {
        console.log("foru");
        alert(error.message);
    } finally {
        bouton.disabled = false;
    }
}

// Supprimer
async function supprimerLigne(id) {
    if (!confirm('Voulez-vous vraiment supprimer cette ligne ?')) {
        return;
    }

    const ligne = document.getElementById(`ligne-${id}`);
    const bouton = ligne.querySelector('.btn-supprimer');
    bouton.disabled = true;

    const qteInput = ligne.querySelector('.qte-input');
    const dateInput = ligne.querySelector('.date-input');

    const qte = Number(qteInput.value);
    const date = dateInput.value;

    try {
        const response = await fetch(`{{ url('/delete-articleRetour') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }, 
            body: JSON.stringify({
                qte: qte,
                date_retour: date,
                id_artisan: @json($detail->first()->id_artisan),
                id_saison: @json($detail->first()->id_saison),
                id_client: @json($detail->first()->id_client)
            })
        });

        if (!response.ok) {
            throw new Error('Erreur lors de la suppression');
        }

        ligne.remove();

    } catch (error) {
        alert(error.message);
        bouton.disabled = false;
    }
}

</script>

@endsection