-- les listes de tables a utiliser

saison
    id
    nom_saison

artisan 
    id
    nom
    telephone
    mail

couleur
    id
    nom

commande
    id
    id_artisan
    designation_produit
    designation_david
    type_commande
    id_couleur
    qte
    prix_unitaire
    date_commande
    nom_commande

bon de commande
    id
    nom
    id_commande

livraison
    id
    type_livraison (entree ou sortie)
    id_artisan
    designation_produit
    designation_david
    bon_reception
    date_livraison


payement_artisan
    id
    id_artisan
    detail_payement
    montant
    date_payement


reste a voir
saison, bon de reception(livraison), bon de commande