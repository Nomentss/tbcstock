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
        
        
        <div class="navbar-nav w-100">
            <a href="{{ route('PageController.home')}}" class="nav-item nav-link {{ request()->routeIs('PageController.home') ? 'active' : '' }}"><i class="fa fa-tachometer-alt me-2"></i>Accueil</a>
            @php
            $formRoutes = [
                'CommandeController.CommandeFormulaire',
                'LivraisonController.livraisonFormulaire',
                'ArticleRetourController.articleRetourFormulaire',
                'ArtisanController.ArtisanFormulaire',
                'SaisonController.saisonFormulaire',
                'CouleurController.couleurFormulaire',
                'PayementController.payementFormulaire',
                'ClientController.clientFormulaire',
            ];
            $formRoutes2 = [
                'LivraisonController.listeLivraison',
                'CommandeController.listeCommande',
                'PayementController.payementListe',
                'ArticleRetourController.listeArticleRetour',
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
                    <a href="{{ route('ArticleRetourController.listeArticleRetour') }}" class="dropdown-item {{ request()->routeIs('ArticleRetourController.listeArticleRetour') ? 'active' : '' }}">Article retour</a>
                    <a href="{{ route('PayementController.payementListe') }}" class="dropdown-item {{ request()->routeIs('PayementController.payementListe') ? 'active' : '' }}">Paiement artisan</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle {{ in_array(Route::currentRouteName(), $formRoutes) ? 'active' : '' }}" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Formulaire</a>
                <div class="dropdown-menu bg-transparent border-0 {{ in_array(Route::currentRouteName(), $formRoutes) ? 'show' : '' }}">
                    <a href="{{ route('CommandeController.CommandeFormulaire')}}" class="dropdown-item {{ request()->routeIs('CommandeController.CommandeFormulaire') ? 'active' : '' }}">Commande</a>
                    <a href="{{ route('LivraisonController.livraisonFormulaire')}}" class="dropdown-item {{ request()->routeIs('LivraisonController.livraisonFormulaire') ? 'active' : '' }}">Livraison</a>
                    <a href="{{ route('ArticleRetourController.articleRetourFormulaire')}}" class="dropdown-item {{ request()->routeIs('ArticleRetourController.articleRetourFormulaire') ? 'active' : '' }}">Article retour</a>
                    <a href="{{ route('ClientController.clientFormulaire')}}" class="dropdown-item {{ request()->routeIs('ClientController.clientFormulaire') ? 'active' : '' }}">Client</a>
                    <a href="{{ route('ArtisanController.ArtisanFormulaire')}}" class="dropdown-item {{ request()->routeIs('ArtisanController.ArtisanFormulaire') ? 'active' : '' }}">Artisan</a>
                    <a href="{{ route('SaisonController.saisonFormulaire')}}" class="dropdown-item {{ request()->routeIs('SaisonController.saisonFormulaire') ? 'active' : '' }}">Saison</a>
                    <a href="{{ route('CouleurController.couleurFormulaire')}}" class="dropdown-item {{ request()->routeIs('CouleurController.couleurFormulaire') ? 'active' : '' }}">Coloration</a>
                    <a href="{{ route('PayementController.payementFormulaire')}}" class="dropdown-item {{ request()->routeIs('PayementController.payementFormulaire') ? 'active' : '' }}">Paiement artisan</a>
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
        </div>
        
    </nav>

    <a href="#" class="nav-link d-flex align-items-center justify-content-between"
       style="position: fixed; bottom: 0; left: 0; width: 250px; padding: 22px 20px; background: #ebececfb; border-top: 1px solid #e7dddd; text-decoration: none; z-index: 1000;"
       title="Changer de client" data-bs-toggle="modal" data-bs-target="#clientPickerModal">
        <span style="font-size: 0.88rem; line-height: 1.2; min-width: 0; color:black">
            Client actuel : 
            <strong class="text-truncate" style="display: block; max-width: 150px;">{{ session('nom_client') }}</strong>
        </span>
        <i class="bi bi-arrow-repeat ms-2"></i>
    </a>
</div>
<x-ClientPicker />
