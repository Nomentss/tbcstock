<div class="modal fade" id="clientPickerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Choisir un client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('ClientController.select') }}">
            @csrf
            <div class="modal-body">
                <div class="row g-3" id="liste-clients">
                    <!-- ato no excecution anle js -->
                </div>
            </div>
            </form>

        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const conteneur = document.getElementById('liste-clients');

    const boutonAjout = `
        <div class="col-4">
        <a href="{{ url('/client/Formulaire') }}"
           class="btn btn-outline-primary w-100 h-100 py-2 border-1 d-flex flex-column justify-content-center"
           style="border-style: dashed;">
            <div class="fs-1 lh-1">+</div>
            <div class="small"></div>
        </a>
        </div>
    `;

    fetch("{{ url('/client/data') }}")
    
        .then(response => response.json())
        .then(clients => {
            conteneur.innerHTML = clients.map(client => `
                <div class="col-4">
                    <button type="submit" name="id_client" value="${client.id}"
                            class="btn btn-outline-secondary w-100 py-4">
                        ${client.nom_client}
                    </button>
                </div>
            `).join('') +boutonAjout;
        })
        .catch(error => console.error('Erreur :', error));
});
</script>