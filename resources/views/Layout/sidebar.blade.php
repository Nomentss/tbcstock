<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <div style="background-color: #47a9ea; padding: 8px 20px; margin-top:-8px">
            <a href="#" class="navbar-brand mx-3 mb-2" style="text-decoration: none; display: inline-block;">
                <span style="font-weight: bold; font-size: 18px; color: #ffffff;">
                    <i class="bi bi-hand-thumbs-up"></i>  TBC
                </span>
                <span style="
                    font-family: cursive;
                    font-size: 14px;
                    color: #ebebeb;
                    
                ">
                    Tsara be company.
                </span>
            </a>
        </div>
        

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
                'CommandeController.CommandeFormulaire',
                'LivraisonController.livraisonFormulaire',
                'ArtisanController.ArtisanFormulaire',
                'SaisonController.saisonFormulaire',
                'CouleurController.couleurFormulaire',
                'PayementController.payementFormulaire',
            ];
            $formRoutes2 = [
                'LivraisonController.listeLivraison',
                'CommandeController.listeCommande',
                'PayementController.payementListe',
            ];
            $formRoutes3 = [
                'ExpeditionController.ExpeditionForm',
                'ExpeditionController.listeExpedition',
            ];
            @endphp
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle {{ in_array(Route::currentRouteName(), $formRoutes2) ? 'active' : '' }}" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Consulter</a>
                <div class="dropdown-menu bg-transparent border-0 {{ in_array(Route::currentRouteName(), $formRoutes2) ? 'show' : '' }}">
                    <a href="{{ route('CommandeController.listeCommande')}}" class="dropdown-item {{ request()->routeIs('CommandeController.listeCommande') ? 'active' : '' }}">Commande</a>
                    <a href="{{ route('LivraisonController.listeLivraison') }}" class="dropdown-item {{ request()->routeIs('LivraisonController.listeLivraison') ? 'active' : '' }}">Livraison</a>
                    <a href="{{ route('PayementController.payementListe') }}" class="dropdown-item {{ request()->routeIs('PayementController.payementListe') ? 'active' : '' }}">Payement artisan</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle {{ in_array(Route::currentRouteName(), $formRoutes) ? 'active' : '' }}" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Formulaire</a>
                <div class="dropdown-menu bg-transparent border-0 {{ in_array(Route::currentRouteName(), $formRoutes) ? 'show' : '' }}">
                    <a href="{{ route('CommandeController.CommandeFormulaire')}}" class="dropdown-item {{ request()->routeIs('CommandeController.CommandeFormulaire') ? 'active' : '' }}">Commande</a>
                    <a href="{{ route('LivraisonController.livraisonFormulaire')}}" class="dropdown-item {{ request()->routeIs('LivraisonController.livraisonFormulaire') ? 'active' : '' }}">Livraison</a>
                    <a href="{{ route('ArtisanController.ArtisanFormulaire')}}" class="dropdown-item {{ request()->routeIs('ArtisanController.ArtisanFormulaire') ? 'active' : '' }}">Artisan</a>
                    <a href="{{ route('SaisonController.saisonFormulaire')}}" class="dropdown-item {{ request()->routeIs('SaisonController.saisonFormulaire') ? 'active' : '' }}">Saison</a>
                    <a href="{{ route('CouleurController.couleurFormulaire')}}" class="dropdown-item {{ request()->routeIs('CouleurController.couleurFormulaire') ? 'active' : '' }}">Coloration</a>
                    <a href="{{ route('PayementController.payementFormulaire')}}" class="dropdown-item {{ request()->routeIs('PayementController.payementFormulaire') ? 'active' : '' }}">Payement artisan</a>
                </div>
            </div>
            <a href="{{ route('StockController.choix')}}" class="nav-item nav-link {{ request()->routeIs('StockController.choix') ? 'active' : '' }}"><i class="bi bi-grid-3x2 me-2"></i>Stock</a>


            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle {{ in_array(Route::currentRouteName(), $formRoutes3) ? 'active' : '' }}" data-bs-toggle="dropdown"><i class="bi bi-box-seam me-2"></i>Expedition</a>
                <div class="dropdown-menu bg-transparent border-0 {{ in_array(Route::currentRouteName(), $formRoutes3) ? 'show' : '' }}">
                    <a href="{{ route('ExpeditionController.ExpeditionForm')}}" class="dropdown-item {{ request()->routeIs('ExpeditionController.ExpeditionForm') ? 'active' : '' }}">Formulaire expedition</a>
                    <a href="{{ route('ExpeditionController.listeExpedition')}}" class="dropdown-item {{ request()->routeIs('ExpeditionController.listeExpedition') ? 'active' : '' }}">Liste expedition</a>
                </div>
            </div>
           
            {{-- <a href="#" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Import</a> --}}
        </div>
    </nav>
</div>