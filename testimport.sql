-- import test ---------------------------------------------------------------------
create table importSaison(
    id serial primary key,
    debut date,
    fin date,
    nom_saison varchar(25)

);

create table importArtisan(
    id serial primary key,
    nom varchar(150),
    telephone varchar(25),
    mail varchar(30)
);


create table importCouleur(
    id serial primary key,
    nom varchar(50)
);


create table importProduit(
    id serial primary key,
    designation_produit varchar(55),
    designation_catalogue varchar(50),
    commande double precision,
    prix_unitaire double precision
);

create table importProduitCouleur(
    id serial primary key,
    id_produit int,
    id_couleur int,
    FOREIGN KEY (id_produit) REFERENCES importProduit(id),
    FOREIGN KEY (id_couleur) REFERENCES importCouleur(id)

);

create table importCommande(
    id serial primary key,
    id_saison int,
    id_artisan int,
    type_commande varchar(50),
    date_commande date,
    FOREIGN KEY (id_artisan) REFERENCES importArtisan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES importSaison(id) ON DELETE CASCADE
);

create table importCommandeDetail(
    id serial primary key,
    id_commande int,
    id_produit int,
    id_couleur int,
    qte double precision,
    prix_unitaire double precision,
    FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE,
    FOREIGN KEY (id_couleur) REFERENCES couleur(id) ON DELETE CASCADE
);

-------------------------------fin table import -------------------------------------------------------
