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
                <h5 class="mb-4" style="text-align: center">Modification des commandes de "{{ session('nom_artisan') }}"</h5>
            </div>

            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>TYPE COMMANDE</th>
                            <th>DESIGNATION</th>
                            <th>DESIGNATION CATALOGUE</th>
                            <th>COULEUR</th>
                            <th>PRIX UNITAIRE</th>
                            <th>QUANTITE</th>
                            <th>Date</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $details)
                            <tr id="ligne-{{ $details->id }}">
                                <td>{{ $details->type_commande }}</td>
                                <td>{{ $details->designation_produit }}</td>
                                <td>{{ $details->designation_catalogue }}</td>
                                <td>{{ $details->couleur }}</td>

                                <td>
                                    <span class="prix-text">
                                        {{ number_format($details->prix_unitaire, 0, ',', ' ') }} AR
                                    </span>
                                    <input type="text"
                                        class="form-control prix-input d-none"
                                        value="{{ $details->prix_unitaire }}"
                                        min="0" step="any">
                                </td>

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
                                    {{ \Carbon\Carbon::parse($details->created_at)->translatedFormat('d F Y') }}
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
    ligne.querySelector('.prix-text').classList.toggle('d-none', edition);
    ligne.querySelector('.qte-text').classList.toggle('d-none', edition);

    ligne.querySelector('.prix-input').classList.toggle('d-none', !edition);
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

    ligne.querySelector('.prix-input').value =
        ligne.querySelector('.prix-input').defaultValue;

    ligne.querySelector('.qte-input').value =
        ligne.querySelector('.qte-input').defaultValue;

    afficherModeEdition(ligne, false);
}

// Enregistrer
async function enregistrerLigne(id) {
    const ligne = document.getElementById(`ligne-${id}`);

    const prixInput = ligne.querySelector('.prix-input');
    const qteInput = ligne.querySelector('.qte-input');

    const prix = Number(prixInput.value);
    const qte = Number(qteInput.value);

    console.log('Ligne :', ligne);
    console.log('Prix :', ligne.querySelector('.prix-text'));
    console.log('Quantité :', ligne.querySelector('.qte-text'));


    if (
        prixInput.value === '' ||
        qteInput.value === '' ||
        !Number.isFinite(prix) ||
        !Number.isInteger(qte) ||
        prix < 0 || qte < 0
    ) {
        alert('Veuillez saisir un prix valide et une quantité entière positive.');
        return;
    }

    const bouton = ligne.querySelector('.btn-enregistrer');
    bouton.disabled = true;

    try {
        const response = await fetch(`{{ url('/update-commande') }}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                prix_unitaire: prix,
                qte: qte
            })
        });

        if (!response.ok) {
            console.error('Erreur Laravel :', data);
            alert(data.message || 'Erreur lors de la modification');
            return;
        }

        const data = await response.json();

        ligne.querySelector('.prix-text').textContent =
            formatMontant(data.prix_unitaire);

        ligne.querySelector('.qte-text').textContent = data.qte;

        prixInput.value = data.prix_unitaire;
        qteInput.value = data.qte;

        prixInput.defaultValue = data.prix_unitaire;
        qteInput.defaultValue = data.qte;

        afficherModeEdition(ligne, false);

    } catch (error) {
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
        const response = await fetch(`{{ url('/delete-commande') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
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