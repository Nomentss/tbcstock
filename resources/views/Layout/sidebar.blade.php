<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="index.html" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary">TBC</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            {{-- <div class="position-relative">
                <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">Jhon Doe</h6>
                <span>Admin</span>
            </div> --}}
        </div>
        <div class="navbar-nav w-100">
            <a href="{{ route('PageController.home')}}" class="nav-item nav-link {{ request()->routeIs('PageController.home') ? 'active' : '' }}"><i class="fa fa-tachometer-alt me-2"></i>Accueil</a>
            @php
            $formRoutes = [
                'PageController.saisonFormulaire',
                'PageController.livraisonFormulaire',
                'ArtisanController.ArtisanFormulaire',
                'PageController.couleurFormulaire',
                'PageController.payementFormulaire',
            ];
            @endphp
            <div class="nav-item dropdown ">
                <a href="#" class="nav-link dropdown-toggle {{ in_array(Route::currentRouteName(), $formRoutes) ? 'active' : '' }}" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Formulaire</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('SaisonController.saisonFormulaire')}}" class="dropdown-item {{ request()->routeIs('SaisonController.saisonFormulaire') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Saison</a>
                    <a href="{{ route('LivraisonController.livraisonFormulaire')}}" class="dropdown-item {{ request()->routeIs('LivraisonController.livraisonFormulaire') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Livraison</a>
                    <a href="{{ route('CommandeController.CommandeFormulaire')}}" class="dropdown-item {{ request()->routeIs('CommandeController.CommandeFormulaire') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Commande</a>
                    <a href="{{ route('ArtisanController.ArtisanFormulaire')}}" class="dropdown-item {{ request()->routeIs('ArtisanController.ArtisanFormulaire') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Artisan</a>
                    <a href="{{ route('CouleurController.couleurFormulaire')}}" class="dropdown-item {{ request()->routeIs('CouleurController.couleurFormulaire') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Coloration</a>
                    <a href="{{ route('PayementController.payementFormulaire')}}" class="dropdown-item {{ request()->routeIs('PayementController.payementFormulaire') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Payement artisan</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Consulter</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('LivraisonController.listeLivraison') }}" class="dropdown-item {{ request()->routeIs('LivraisonController.listeLivraison') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Livraison</a>
                    <a href="{{ route('CommandeController.listeCommande')}}" class="dropdown-item {{ request()->routeIs('CommandeController.listeCommande') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Commande</a>
                    <a href="{{ route('PayementController.payementListe') }}" class="dropdown-item {{ request()->routeIs('PayementController.payementListe') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Payement artisan</a>
                </div>
            </div>
            <a href="{{ route('StockController.choix')}}" class="nav-item nav-link"><i class="bi bi-grid-3x2 me-2"></i>Stock</a>


            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-box-seam me-2"></i>Expedition</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('ExpeditionController.ExpeditionForm')}}" class="dropdown-item {{ request()->routeIs('ExpeditionController.ExpeditionForm') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Formulaire expedition</a>
                    <a href="{{ route('ExpeditionController.listeExpedition')}}" class="dropdown-item {{ request()->routeIs('ExpeditionController.listeExpedition') ? 'active' : '' }}"><i class="bi bi-dot me-4"></i>Liste expedition</a>
                </div>
            </div>
           
            {{-- <a href="#" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Import</a> --}}
        </div>
    </nav>
</div>