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
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Consulter</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="#" class="dropdown-item">Stock</a>
                    <a href="#" class="dropdown-item">Livraison</a>
                    <a href="#" class="dropdown-item">Commande</a>
                    <a href="#" class="dropdown-item">Payement artisan</a>
                </div>
            </div>
            <a href="#" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Historique</a>
            {{-- <a href="table.html" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Tables</a>
            <a href="chart.html" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Charts</a> --}}
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
                    <a href="{{ route('PageController.saisonFormulaire')}}" class="dropdown-item {{ request()->routeIs('PageController.saisonFormulaire') ? 'active' : '' }}">Saison</a>
                    <a href="{{ route('PageController.livraisonFormulaire')}}" class="dropdown-item {{ request()->routeIs('PageController.livraisonFormulaire') ? 'active' : '' }}">Livraison</a>
                    <a href="{{ route('ArtisanController.ArtisanFormulaire')}}" class="dropdown-item {{ request()->routeIs('ArtisanController.ArtisanFormulaire') ? 'active' : '' }}">Artisan</a>
                    <a href="{{ route('PageController.couleurFormulaire')}}" class="dropdown-item {{ request()->routeIs('PageController.couleurFormulaire') ? 'active' : '' }}">Coloration</a>
                    <a href="{{ route('PageController.payementFormulaire')}}" class="dropdown-item {{ request()->routeIs('PageController.payementFormulaire') ? 'active' : '' }}">Payement artisan</a>
                </div>
            </div>
            <a href="#" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Import</a>
        </div>
    </nav>
</div>