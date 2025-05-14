-- les listes de tables a utiliser

saison
    id
    debut
    fin
    nom_saison 2024-2025

artisan 
    id
    nom
    telephone
    mail
    
couleur
    id
    nom

produit
    id_produit
    id_artisan
    designation_produit

produit_couleur
    id_produit
    id_couleur

commande
    id
    id_produit
    type_commande
    id_couleur
    qte
    prix_unitaire
    date_commande

bon de commande
    id
    reference
    id_commande
    id_saison

livraison
    id
    type_livraison (entree ou sortie)
    id_artisan
    id_produit
    qte_livre
    date_livraison

bon de livraison
    id
    nom(reference)
    id_livraison
    id_saison

--stock

payement_artisan
    id
    id_artisan
    detail_payement
    montant
    date_payement

    ---------------------------------------------------------------------------------

    