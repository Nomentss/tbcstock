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
                <h5 class="mb-4" style="text-align: center">Modification des paiements de "{{ session('nom_artisan') }}"</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>TYPE PAIEMENT</th>
                            <th>NUMERO/PIECE DE CHEQUE</th>
                            <th>DETAIL PAIEMENT</th>
                            <th>MONTANT</th>
                            <th>DATE PAYEMENT</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $details)
                            <tr id="ligne-{{ $details->id }}">
                                <td>{{ $details->type_payement }}</td>
                                <td>{{ $details->numero_piece_cheque }}</td>
                                <td>
                                    <span class="detail-text">
                                        {{ $details->detail_payement }}
                                    </span>
                                    <input type="text"
                                        class="form-control detail-input d-none"
                                        value="{{ $details->detail_payement }}"
                                        min="0" step="1">
                                </td>
                                <!-- <td>{{ $details->detail_payement }}</td> -->
                                <td>
                                    <span class="montant-text">
                                        {{ $details->montant }}
                                    </span>
                                    <input type="text"
                                        class="form-control montant-input d-none"
                                        value="{{ $details->montant }}"
                                        min="0" step="1">
                                </td>

                                <td>
                                    <span class="date-text">
                                        {{ $details->date_payement }}
                                    </span>
                                    <input type="text"
                                        class="form-control date-input d-none"
                                        value="{{ $details->date_payement }}"
                                        min="0" step="1">
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
    ligne.querySelector('.montant-text').classList.toggle('d-none', edition);
    ligne.querySelector('.montant-input').classList.toggle('d-none', !edition);

    ligne.querySelector('.detail-text').classList.toggle('d-none', edition);
    ligne.querySelector('.detail-input').classList.toggle('d-none', !edition);

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

    ligne.querySelector('.montant-input').value =
        ligne.querySelector('.montant-input').defaultValue;

    afficherModeEdition(ligne, false);
}

// Enregistrer
async function enregistrerLigne(id) {
    const ligne = document.getElementById(`ligne-${id}`);

    const montantInput = ligne.querySelector('.montant-input');
    const dateInput = ligne.querySelector('.date-input');

    const detailInput = ligne.querySelector('.detail-input');

    const montant = Number(montantInput.value);
    const date = dateInput.value;
    const detail = detailInput.value;

    if (
        montantInput.value.trim() === '' ||
        !Number.isFinite(montant) ||
        montant < 0
    ) {
        alert('Veuillez saisir une quantité entière positive.');
        return;
    }

    const bouton = ligne.querySelector('.btn-enregistrer');
    bouton.disabled = true;

    try {
        const response = await fetch(`{{ url('/update-payement') }}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                montant: montant,
                detail: detail,
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

        ligne.querySelector('.montant-text').textContent = data.montant;
        ligne.querySelector('.detail-text').textContent = data.detail;

        montantInput.value = data.montant;
        detailInput.value = data.detail;

        montantInput.defaultValue = data.montant;
        detailInput.defaultValue = data.detail;

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

    try {
        const response = await fetch(`{{ url('/delete-payement') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }, 
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