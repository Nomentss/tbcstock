insert into users(username,password) values ('kanty','1234');

create table saison(
    id serial primary key,
    debut date,
    fin date,
    nom_saison varchar(25)

);

create table artisan(
    id serial primary key,
    nom varchar(150),
    telephone varchar(25),
    mail varchar(30)

);

create table couleur(
    id serial primary key,
    nom varchar(50)

);
/* Kanty */
create table produit(
    id_produit serial primary key,
    id_artisan int,
    designation_produit varchar(55),
    FOREIGN KEY (id_artisan) REFERENCES artisan(id)


);

/* Kanty */
create table produit_couleur(
    id_produit int,
    id_couleur int,
    FOREIGN KEY (id_produit) REFERENCES produit(id),
    FOREIGN KEY (id_couleur) REFERENCES couleur(id)

);

/* Kanty */
create table commande(
    id serial primary key,
    id_produit int,
    type_commande varchar(50),
    id_couleur int,
    qte double precision,
    prix_unitaire double precision,
    date_commande date,
    FOREIGN KEY (id_produit) REFERENCES produit(id),
    FOREIGN KEY (id_couleur) REFERENCES couleur(id)

);

/* Kanty */
create table bon_de_commande(
    id serial primary key,
    reference varchar(150),
    id_commande int,
    id_saison int,
    FOREIGN KEY (id_commande) REFERENCES commande(id),
    FOREIGN KEY (id_saison) REFERENCES saison(id)

);

/* Kanty */
create table type_livraison(
    id serial primary key,
    nom varchar(10)
)

create table livraison(
    id serial primary key,
    id_type_livraison int,
    id_artisan int,
    id_produit int,
    qte_livre double precision,
    date_livraison date,
    FOREIGN KEY (id_type_livraison) REFERENCES type_livraison(id),
    FOREIGN KEY (id_artisan) REFERENCES artisan(id),
    FOREIGN KEY (id_produit) REFERENCES produit(id)

);

create table bon_de_livraison(
    id int,
    reference varchar(30),
    id_livraison int,
    id_saison int,
    FOREIGN KEY (id_livraison) REFERENCES livraison(id),
    FOREIGN KEY (id_saison) REFERENCES saison(id)

);

create table payement_artisan(
    id serial primary key,
    id_artisan int,
    detail_payement varchar(255),
    montant double precision,
    date_payement date,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id)

);
