@extends('Layout.app')
@section('contents')

<style>
    .hover-card {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.hover-card:hover {
    background-color: #e9f5ff; /* couleur de fond au survol */
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* ombre douce */
    transform: translateY(-3px); /* petit effet de flottement */
    border-color: #0d6efd;
}
</style>
<div class="container-fluid pt-4 px-4">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="row g-4">
        <div class="col-sm-8 col-xl-4">
            <a href="{{ route('LivraisonController.listeLivraison') }}" class="text-decoration-none">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-card">
                {{-- <i class="fa fa-chart-line fa-3x text-primary"></i> --}}
                <i class="bi bi-table fa-3x text-primary"></i>
                <div class="ms-3 text-dark">
                    <p>Livraison</p>
                    <h6 class="mb-0">Consulter</h6>
                </div>
            </div>
            </a>
        </div>
        <div class="col-sm-8 col-xl-4">
            <a href="{{ route('CommandeController.listeCommande')}}" class="text-decoration-none">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-card">
                {{-- <i class="fa fa-chart-bar fa-3x text-primary"></i> --}}
                <i class="bi bi-bag-plus fa-3x text-primary"></i>
                <div class="ms-3 text-dark">
                    <p>Commande</p>
                    <h6 class="mb-0">Consulter</h6>
                </div>
            </div>
            </a>
        </div>
        <div class="col-sm-8 col-xl-4">
            <a href="{{ route('StockController.choix')}}" class="text-decoration-none">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-card">
                {{-- <i class="fa fa-chart-area fa-3x text-primary"></i> --}}
                <i class="bi bi-grid-3x2 fa-3x text-primary "></i>
                <div class="ms-3 text-dark">
                    <p>Stock</p>
                    <h6 >Consulter</h6>
                </div>
            </div>
            </a>
        </div>
        <div class="col-sm-8 col-xl-4">
            <a href="{{ route('ExpeditionController.listeExpedition')}}" class="text-decoration-none">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-card">
                {{-- <i class="fa fa-chart-pie fa-3x text-primary"></i> --}}
                <i class="bi bi-box-seam fa-3x text-primary"></i>
                <div class="ms-3 text-dark">
                    <p>Expedition</p>
                    <h6 class="mb-0">Consulter</h6>
                </div>
            </div>
            </a>
        </div>
        <div class="col-sm-8 col-xl-4">
            <a href="{{ route('PayementController.payementListe') }}" class="text-decoration-none">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-card">
                {{-- <i class="fa fa-chart-pie fa-3x text-primary"></i>--}}
                <i class="bi bi-currency-dollar fa-3x text-primary"></i>
                <div class="ms-3 text-dark">
                    <p>Payement</p>
                    <h6 class="mb-0">Consulter</h6>
                </div>
            </div>
            </a>
        </div>
        <div class="col-sm-8 col-xl-4">
            <a href="{{ route('ArticleRetourController.listeArticleRetour') }}" class="text-decoration-none">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-card">
                {{-- <i class="fa fa-chart-pie fa-3x text-primary"></i>--}}
                <i class="bi bi-box-seam fa-3x text-primary"></i>
                <div class="ms-3 text-dark">
                    <p>Article retour</p>
                    <h6 class="mb-0">Consulter</h6>
                </div>
            </div>
            </a>
        </div>
    </div>
</div>
<x-ClientPicker />
    @unless (session()->has('id_client'))
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                new bootstrap.Modal(document.getElementById('clientPickerModal')).show();
            });
        </script>
        @endpush
    @endunless
@endsection