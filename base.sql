create database tbc_stock;

create table saison(
    id serial primary key,
    debut date,
    fin date,
    nom_saison varchar(25),
    created_at timestamp,
    updated_at timestamp
);

create table artisan(
    id serial primary key,
    nom varchar(150),
    telephone varchar(25),
    mail varchar(30),
    created_at timestamp,
    updated_at timestamp
);

create table couleur(
    id serial primary key,
    nom varchar(50),
    created_at timestamp,
    updated_at timestamp
);

create table produit(
    id serial primary key,
    id_artisan int,
    designation_produit varchar(55),
    designation_catalogue varchar(50),
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id) ON DELETE CASCADE
);

create table produit_couleur(
    id serial primary key,
    id_produit int,
    id_couleur int,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE,
    FOREIGN KEY (id_couleur) REFERENCES couleur(id) ON DELETE CASCADE
);

-- correction de la table commande
create table commande(
    id serial primary key,
    id_saison int,
    id_artisan int,
    type_commande varchar(50),
    date_commande date,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES saison(id) ON DELETE CASCADE
);

create table commande_detail(
    id serial primary key,
    id_commande int,
    id_produit int,
    id_couleur int,
    qte double precision,
    prix_unitaire double precision,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE,
    FOREIGN KEY (id_couleur) REFERENCES couleur(id) ON DELETE CASCADE
);
------------------------------------------------------

create table type_livraison(
    id serial primary key,
    nom varchar(10),
    created_at timestamp,
    updated_at timestamp
);

-- correction de la table livraison
create table livraison(
    id serial primary key,
    id_saison int,
    id_artisan int,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES saison(id) ON DELETE CASCADE
);

create table livraison_detail(
    id serial primary key,
    id_livraison int,
    id_produit int,
    id_couleur int,
    qte_livre double precision,
    date_livraison date,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE,
    FOREIGN KEY (id_couleur) REFERENCES couleur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_livraison) REFERENCES livraison(id) ON DELETE CASCADE
);

---------------------------------------------------------

create table payement_artisan(
    id serial primary key,
    id_saison int,
    id_artisan int,
    detail_payement varchar(255),
    numero_piece_cheque varchar(255), 
    montant double precision,
    date_payement date,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES saison(id) ON DELETE CASCADE
);

create table stock(
    id serial primary key,
    id_saison
    id_produit int,
    id_couleur int,
    qte_livre double precision,
    date_livraison date,
    created_at timestamp,
    updated_at timestamp
);

create table conteneur(
    id serial primary key,
    nom varchar (255),
    created_at timestamp,
    updated_at timestamp
);

create table expedition(
    id serial primary key,
    id_conteneur int,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_conteneur) REFERENCES conteneur(id) ON DELETE CASCADE
);

create table expedition_detail(
    id serial primary key,
    id_saison int,
    id_expedition int,
    date_expedition date,
    id_artisan int,
    id_produit int,
    id_couleur int,
    qte double precision,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_expedition) REFERENCES expedition(id) ON DELETE CASCADE,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE,
    FOREIGN KEY (id_couleur) REFERENCES couleur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES saison(id) ON DELETE CASCADE
);

INSERT INTO conteneur (nom) VALUES
('TC n1'),
('TC n2'),
('TC n3'),
('TC n4'),
('TC n5'),
('TC n6'),
('TC n7'),
('TC n8'),
('TC n9'),
('TC n10'),
('TC n11'),
('TC n12');

-- vue pour afficher liste commande dans CommandeListe
select c.id_saison, c.id_artisan, c.date_commande, a.nom as artisan, s.nom_saison as saison from commande c
join artisan a on 
a.id= c.id_artisan
join saison s on
s.id= c.id_saison
group by c.id_saison, c.id_artisan, c.date_commande, a.nom, s.nom_saison;


-- vue pour afficher detail commande dans CommandeListe
create view v_detail_commande as
select c.type_commande, c.date_commande, p.designation_produit, p.designation_catalogue, co.nom as couleur, cd.prix_unitaire, SUM(cd.qte) as qte, c.id_saison, c.id_artisan, cd.prix_unitaire * SUM(cd.qte) as total from commande_detail cd
join commande c on 
c.id= cd.id_commande
join produit p on
p.id= cd.id_produit
join couleur co on
co.id= cd.id_couleur
group by c.type_commande, c.date_commande, p.designation_produit, p.designation_catalogue, co.nom, cd.prix_unitaire, c.id_saison, c.id_artisan, cd.qte;

-- test
select c.type_commande, c.date_commande, p.id, p.designation_produit, p.designation_catalogue, co.nom as couleur, cd.prix_unitaire, SUM(cd.qte) as qte, c.id_saison, c.id_artisan, cd.prix_unitaire * SUM(cd.qte) as total from commande_detail cd
join commande c on 
c.id= cd.id_commande
join produit p on
p.id= cd.id_produit
join couleur co on
co.id= cd.id_couleur
group by c.type_commande, c.date_commande, p.id, p.designation_produit, p.designation_catalogue, co.nom, cd.prix_unitaire, c.id_saison, c.id_artisan, cd.qte;
-- fin test

-- pour afficher liste de couleur par produit
create view v_produit_couleur as
select p.id_produit, p.id_couleur, c.nom from produit_couleur p
join couleur c on
c.id = p.id_couleur; 

-- pour afficher liste de livraison
create view v_liste_livraison as
select s.id as id_saison, a.id as id_artisan, l.id, s.nom_saison, a.nom as artisan from livraison l 
join saison s on 
s.id = l.id_saison
join artisan a on
a.id = l.id_artisan; 

-- pour afficher liste detail livraison

create view v_livraison_detail as
select ld.id, ld.id_livraison, li.id_artisan, li.id_saison, ld.id_produit, p.designation_produit, p.designation_catalogue, ld.id_couleur, c.nom as couleur, ld.qte_livre, ld.date_livraison from livraison_detail ld 
join produit p on
p.id = ld.id_produit
join couleur c on 
c.id = ld.id_couleur
join livraison li on
ld.id_livraison = li.id;

-- vue qui a pour role dans V_commande_livraison
create view v_livraison as 
select l.id_saison, ld.id_produit, ld.id_couleur, ld.id_livraison, l.id_artisan, ld.qte_livre, ld.date_livraison from livraison_detail ld
join livraison l 
on ld.id_livraison = l.id;

-- vue qui a pour role dans V_commande_livraison
create view v_commande as
select c.id_saison, cd.id_produit, cd.id_couleur, cd.prix_unitaire, cd.qte from commande_detail cd
join commande c
on c.id = cd.id_commande;

-- combinaison pour affichage de commande et livraison 
create or replace view v_commande_livraison as
select ld.id_livraison, ld.id_artisan, ld.id_saison, ld.id_produit, p.designation_produit,p.designation_catalogue, ld.id_couleur, c.nom as couleur, cd.prix_unitaire as prix_unitaire, cd.qte as commande, sum(ld.qte_livre) as qte_livre, sum(ld.qte_livre)*cd.prix_unitaire as valeur, ld.date_livraison from v_livraison ld 
join produit p on
p.id = ld.id_produit
join couleur c on 
c.id = ld.id_couleur
join v_commande cd on
cd.id_produit = ld.id_produit AND cd.id_couleur = ld.id_couleur AND cd.id_saison = ld.id_saison
GROUP BY 
    ld.id_livraison,
    ld.id_artisan,
    ld.id_saison,
    ld.id_produit,
    p.designation_produit,
    p.designation_catalogue,
    ld.id_couleur,
    c.nom,
    cd.prix_unitaire,
    cd.qte,
    ld.date_livraison;


-- resume livraison
create or replace view v_resume_livraison as
select id_livraison,id_artisan, id_saison, id_produit,designation_produit,designation_catalogue,COUNT(DISTINCT couleur) as nb_couleur, prix_unitaire, sum(commande) as commande, sum(qte_livre)as qte_livre, sum(qte_livre)*prix_unitaire as valeur, sum(commande)-sum(qte_livre)as delta, (sum(qte_livre)*100)/sum(commande) as pourcentage from v_commande_livraison
group by id_livraison,
        id_artisan,
        id_saison,
        id_produit,
        designation_produit,
        designation_catalogue,
        prix_unitaire;

-- calcul total dans livraison
create view v_total_resume as
select id_artisan, id_saison, sum(qte_livre) as qte_livre, sum(commande)as commande, sum(valeur) as valeur, sum(delta) as delta, (sum(qte_livre)*100)/sum(commande) as pourcentage from v_resume_livraison
group by id_artisan, id_saison;

-- liste payement
create or replace view v_payement as
select p.id, p.id_saison, s.nom_saison, p.id_artisan, a.nom as artisan, p.detail_payement, p.montant, p.date_payement from payement_artisan p
join saison s on
p.id_saison = s.id
join artisan a on
p.id_artisan = a.id;

-- liste payement avec resume montant total
create view v_liste_payement as
select vp.id_saison, vp.nom_saison, vp.id_artisan, vp.artisan, sum(vp.montant)as montant, COALESCE(vt.valeur, 0) as valeur, COALESCE(vt.valeur, 0) - sum(vp.montant) as reste from v_payement vp
left join v_total_resume vt on
vp.id_artisan = vt.id_artisan
and vp.id_saison = vt.id_saison
group by vp.id_saison, vp.nom_saison, vp.id_artisan, vp.artisan, vt.valeur;

-- vue pour stock globale
create view v_stock as
select s.id_saison, sa.nom_saison, p.id_artisan, a.nom as artisan, s.id_produit, p.designation_produit, p.designation_catalogue, s.id_couleur, c.nom as couleur, sum(s.qte_livre)as qte_livre from stock s
join produit p on
p.id = s.id_produit
join couleur c on
c.id = s.id_couleur
join artisan a on 
a.id = p.id_artisan
join saison sa on
sa.id = s.id_saison 
group by s.id_saison, sa.nom_saison, p.id_artisan, a.nom, s.id_produit, p.designation_produit, p.designation_catalogue, s.id_couleur, c.nom;

-- vue pour avoir le stock groupé par couleur
create view v_regroupement_stock as
select id_saison, nom_saison, id_artisan, artisan, id_produit, designation_produit, designation_catalogue, count(DISTINCT couleur) as nb_couleur, sum(qte_livre) as qte_livre from v_stock
GROUP BY id_saison,
nom_saison, 
id_artisan, 
artisan,
id_produit,
designation_produit,
designation_catalogue;

-- vue pour avoir la somme par designation
create view v_resume_stock as
select id_saison, nom_saison, designation_catalogue, sum(qte_livre) from v_regroupement_stock 
GROUP BY id_saison,
nom_saison,
designation_catalogue; 

-- vue pour somme valeur stock_commande  vue de jointure pour avoir prix dans stock
create view v_stock_commande as 
select vs.id_saison, vs.nom_saison, vs.id_artisan, vs.artisan, vs.id_produit, vs.id_couleur, vs.couleur, vs.designation_produit, vs.designation_catalogue, vs.qte_livre, co.prix_unitaire from v_stock vs
join (
    select c.id_saison, c.id_artisan, cd.id_produit, cd.id_couleur, cd.prix_unitaire, cd.qte from commande_detail cd
    join commande c
    on c.id = cd.id_commande
    group by c.id_saison, c.id_artisan, cd.id_produit, cd.id_couleur, cd.prix_unitaire, cd.qte
) as co
on vs.id_saison = co.id_saison and vs.id_produit = co.id_produit and vs.id_couleur = co.id_couleur and vs.id_artisan = co.id_artisan;

-- vue pour expedition
create view v_expedition as
select ed.id_saison, sa.nom_saison, co.id as id_conteneur, co.nom as conteneur, ed.id_artisan, a.nom as artisan, ed.id_produit, p.designation_produit, p.designation_catalogue, ed.id_couleur, c.nom as couleur, sum(ed.qte) as qte, ed.date_expedition from expedition_detail ed
join expedition e on
e.id = ed.id_expedition
join produit p on
p.id = ed.id_produit
join couleur c on
c.id = ed.id_couleur
join artisan a on
a.id = ed.id_artisan
join saison sa on
ed.id_saison = sa.id
join conteneur co on
co.id = e.id_conteneur
group by ed.id_saison, sa.nom_saison, co.id, co.nom, ed.id_artisan, a.nom, ed.id_produit, p.designation_produit, p.designation_catalogue, ed.id_couleur, c.nom, ed.date_expedition;


-- vue pour la somme dans commande par artisan
create view v_somme_commande as
select id_artisan, id_saison, sum(qte) as total_qte, sum(total) as total_prix from v_detail_commande
group by id_artisan, id_saison;

-- vue pour la somme dans commande par type_commande
create view v_somme_commande_type as
select type_commande, id_artisan, id_saison, sum(qte) as total_qte, sum(total) as total_prix from v_detail_commande
group by id_artisan, id_saison, type_commande;

-- vue necessaire pour somme total valeur expedition par saison
create view v_reference_somme_expedition as
select ve.id_saison, ve.id_conteneur, ve.conteneur, ve.nom_saison, ve.id_artisan, ve.id_produit, ve.id_couleur, ve.qte, ve.designation_produit, ve.designation_catalogue, vc.prix_unitaire from v_expedition ve
join ( 
select c.id_saison, c.id_artisan, cd.id_produit, cd.id_couleur, cd.prix_unitaire, cd.qte from commande_detail cd
join commande c
on c.id = cd.id_commande
group by c.id_saison, c.id_artisan, cd.id_produit, cd.id_couleur, cd.prix_unitaire, cd.qte
) as vc
on ve.id_saison = vc.id_saison and ve.id_artisan=vc.id_artisan and ve.id_produit = vc.id_produit and ve.id_couleur = vc.id_couleur;

-- vue somme valeur expedition rehetra par saison
create view v_somme_expedition as
select id_saison, nom_saison, sum(qte) as qte, sum(qte*prix_unitaire) as total from v_reference_somme_expedition
group by id_saison, nom_saison;

-- vue somme valeur expedition par conteneur
create view v_somme_expedition_conteneur as
select id_saison, nom_saison, id_conteneur, conteneur, sum(qte) as qte, sum(qte*prix_unitaire) as total from v_reference_somme_expedition
group by id_saison, nom_saison, id_conteneur, conteneur; 

-- vue somme valeur expedition rehetra
create view v_somme_expedition_all as
select sum(qte) as qte, sum(qte*prix_unitaire) as total from v_reference_somme_expedition;


select sum(qte_livre) as qte, sum(qte_livre*prix_unitaire) from v_stock_commande
where id_saison = 4;