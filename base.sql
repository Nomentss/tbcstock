create table saison(
    id serial primary key,
    debut date,
    fin date,
    nom_saison varchar(255),
    created_at timestamp,
    updated_at timestamp
);

create table client(
    id serial primary key,
    nom_client varchar(100),
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
    designation_produit varchar(255),
    designation_catalogue varchar(255),
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
    id_client int,
    type_commande varchar(255),
    date_commande date,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES saison(id) ON DELETE CASCADE,
    FOREIGN KEY (id_client) REFERENCES client(id) ON DELETE CASCADE
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
    nom varchar(255),
    created_at timestamp,
    updated_at timestamp
);

-- correction de la table livraison
create table livraison(
    id serial primary key,
    id_saison int,
    id_artisan int,
    id_client int,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES saison(id) ON DELETE CASCADE,
    FOREIGN KEY (id_client) REFERENCES client(id) ON DELETE CASCADE
);

create table livraison_detail(
    id serial primary key,
    id_livraison int,
    id_produit int,
    id_couleur int,
    qte_livre double precision,
    date_livraison date,
    bon_reception int,
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
    id_client int,
    type_payement varchar(255),
    detail_payement varchar(255),
    numero_piece_cheque varchar(255), 
    montant double precision,
    date_payement date,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES saison(id) ON DELETE CASCADE,
    FOREIGN KEY (id_client) REFERENCES client(id) ON DELETE CASCADE
);

create table stock(
    id serial primary key,
    id_saison int,
    id_client int,
    id_produit int,
    id_couleur int,
    qte_livre double precision,
    date_livraison date,
    created_at timestamp,
    updated_at timestamp
);

create table expedition(
    id serial primary key,
    created_at timestamp,
    updated_at timestamp
);

create table expedition_detail(
    id serial primary key,
    id_saison int,
    id_client int,
    date_expedition date,
    id_artisan int,
    id_produit int,
    id_couleur int,
    qte double precision,
    expedition_route varchar(255),
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_client) REFERENCES client(id) ON DELETE CASCADE,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE,
    FOREIGN KEY (id_couleur) REFERENCES couleur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES saison(id) ON DELETE CASCADE
);

create table article_retour(
    id serial primary key,
    id_saison int,
    id_artisan int,
    id_client int,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_artisan) REFERENCES artisan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES saison(id) ON DELETE CASCADE,
    FOREIGN KEY (id_client) REFERENCES client(id) ON DELETE CASCADE
);

create table article_retour_detail(
    id serial primary key,
    id_article_retour int,
    id_produit int,
    id_couleur int,
    qte double precision,
    date_retour date,
    bon_sortie int,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE,
    FOREIGN KEY (id_couleur) REFERENCES couleur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_article_retour) REFERENCES article_retour(id) ON DELETE CASCADE
);

-- vue pour afficher liste commande dans CommandeListe
select c.id_saison, c.id_artisan, c.id_client, c.date_commande, a.nom as artisan, s.nom_saison as saison from commande c
join artisan a on 
a.id= c.id_artisan
join saison s on
s.id= c.id_saison
group by c.id_saison, c.id_artisan, c.id_client, c.date_commande, a.nom, s.nom_saison;


-- vue pour afficher detail commande dans CommandeListe
create view v_detail_commande as
select p.id as id_produit, c.type_commande, c.id_client, c.date_commande, p.designation_produit, p.designation_catalogue, co.nom as couleur, co.id as id_couleur, cd.prix_unitaire, SUM(cd.qte) as qte, c.id_saison, c.id_artisan, cd.prix_unitaire * SUM(cd.qte) as total from commande_detail cd
join commande c on 
c.id= cd.id_commande
join produit p on
p.id= cd.id_produit
join couleur co on
co.id= cd.id_couleur
group by p.id, c.type_commande, c.id_client, c.date_commande, p.designation_produit, p.designation_catalogue, co.nom, co.id, cd.prix_unitaire, c.id_saison, c.id_artisan;

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
select s.id as id_saison, a.id as id_artisan, l.id, s.nom_saison, a.nom as artisan, l.id_client, cl.nom_client as nom_client from livraison l 
join saison s on 
s.id = l.id_saison
join artisan a on
a.id = l.id_artisan
join client cl on
cl.id = l.id_client; 

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
select l.id_saison, l.id_client, ld.id_produit, ld.id_couleur, ld.id_livraison, l.id_artisan, ld.qte_livre, ld.date_livraison, ld.bon_reception from livraison_detail ld
join livraison l 
on ld.id_livraison = l.id;

-- vue qui a pour role dans V_commande_livraison
create or replace view v_commande as
select c.id_saison, c.id_client, cd.id_produit, cd.id_couleur, cd.prix_unitaire, SUM(cd.qte) as qte from commande_detail cd
join commande c
on c.id = cd.id_commande
GROUP BY c.id_saison, c.id_client, cd.id_produit, cd.id_couleur, cd.prix_unitaire;

-- combinaison pour affichage de commande et livraison 
create or replace view v_commande_livraison as
select ld.id_livraison, ld.id_artisan, ld.id_saison, ld.id_client, ld.id_produit, p.designation_produit,p.designation_catalogue, ld.id_couleur, c.nom as couleur, cd.prix_unitaire as prix_unitaire, cd.qte as commande, sum(ld.qte_livre) as qte_livre, sum(ld.qte_livre)*cd.prix_unitaire as valeur, ld.date_livraison, ld.bon_reception from v_livraison ld 
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
    ld.id_client,
    ld.id_produit,
    p.designation_produit,
    p.designation_catalogue,
    ld.id_couleur,
    c.nom,
    cd.prix_unitaire,
    cd.qte,
    ld.date_livraison,
    ld.bon_reception;


-- resume livraison
create or replace view v_resume_livraison as
SELECT
    id_livraison,
    id_artisan,
    id_saison,
    id_client,
    id_produit,
    designation_produit,
    designation_catalogue,
    COUNT(DISTINCT couleur) AS nb_couleur,
    prix_unitaire,
    SUM(commande) AS commande,
    SUM(qte_livre) AS qte_livre,
    SUM(qte_livre) * prix_unitaire AS valeur,
    SUM(commande) - SUM(qte_livre) AS delta,
    (SUM(qte_livre) * 100.0) / NULLIF(SUM(commande), 0) AS pourcentage
FROM (
    SELECT
        id_livraison,
        id_artisan,
        id_saison,
        id_client,
        id_produit,
        designation_produit,
        designation_catalogue,
        prix_unitaire,
        couleur,
        commande,
        SUM(qte_livre) AS qte_livre
    FROM v_commande_livraison
    GROUP BY
        id_livraison,
        id_artisan,
        id_saison,
        id_client,
        id_produit,
        designation_produit,
        designation_catalogue,
        couleur,
        commande,
        prix_unitaire
) AS sous_requete
GROUP BY
    id_livraison,
    id_artisan,
    id_saison,
    id_client,
    id_produit,
    designation_produit,
    designation_catalogue,
    prix_unitaire;


-- calcul total dans livraison
create view v_total_resume as
select id_artisan, id_saison, id_client, sum(qte_livre) as qte_livre, sum(commande)as commande, sum(valeur) as valeur, sum(delta) as delta, (sum(qte_livre)*100)/sum(commande) as pourcentage from v_resume_livraison
group by id_artisan, id_saison, id_client;

-- liste payement
create or replace view v_payement as
select p.id, p.id_saison, p.id_client, s.nom_saison, p.id_artisan, a.nom as artisan, p.detail_payement, p.numero_piece_cheque, p.montant, p.date_payement, p.type_payement from payement_artisan p
join saison s on
p.id_saison = s.id
join artisan a on
p.id_artisan = a.id;

-- liste payement avec resume montant total
create view v_liste_payement as
select vp.id_saison, vp.nom_saison, vp.id_artisan, vp.artisan, vp.id_client, sum(vp.montant)as montant, COALESCE(vt.valeur, 0) as valeur, COALESCE(vt.valeur, 0) - sum(vp.montant) as reste from v_payement vp
left join v_total_resume vt on
vp.id_artisan = vt.id_artisan
and vp.id_saison = vt.id_saison
and vp.id_saison = vt.id_saison
and vp.id_client = vt.id_client
group by vp.id_saison, vp.nom_saison, vp.id_artisan, vp.artisan, vp.id_client, vt.valeur;

-- vue pour stock globale
create view v_stock as
select s.id_saison, s.id_client, sa.nom_saison, p.id_artisan, a.nom as artisan, s.id_produit, p.designation_produit, p.designation_catalogue, s.id_couleur, c.nom as couleur, sum(s.qte_livre)as qte_livre from stock s
join produit p on
p.id = s.id_produit
join couleur c on
c.id = s.id_couleur
join artisan a on 
a.id = p.id_artisan
join saison sa on
sa.id = s.id_saison 
group by s.id_saison, s.id_client, sa.nom_saison, p.id_artisan, a.nom, s.id_produit, p.designation_produit, p.designation_catalogue, s.id_couleur, c.nom;

-- vue pour avoir le stock groupé par couleur
create view v_regroupement_stock as
select id_saison, nom_saison, id_artisan, artisan, id_client, id_produit, designation_produit, designation_catalogue, count(DISTINCT couleur) as nb_couleur, sum(qte_livre) as qte_livre from v_stock
GROUP BY id_saison,
nom_saison, 
id_artisan, 
artisan,
id_client,
id_produit,
designation_produit,
designation_catalogue;

-- vue pour avoir la somme par designation
create view v_resume_stock as
select id_saison, id_client, nom_saison, designation_catalogue, sum(qte_livre) from v_regroupement_stock 
GROUP BY id_saison,
id_client,
nom_saison,
designation_catalogue; 

-- vue pour somme valeur stock_commande  vue de jointure pour avoir prix dans stock
create view v_stock_commande as 
select vs.id_saison, vs.id_client, vs.nom_saison, vs.id_artisan, vs.artisan, vs.id_produit, vs.id_couleur, vs.couleur, vs.designation_produit, vs.designation_catalogue, vs.qte_livre, co.prix_unitaire from v_stock vs
left join (
    select c.id_saison, c.id_artisan, c.id_client, cd.id_produit, cd.id_couleur, cd.prix_unitaire, cd.qte from commande_detail cd
    join commande c
    on c.id = cd.id_commande
    group by c.id_saison, c.id_artisan, c.id_client, cd.id_produit, cd.id_couleur, cd.prix_unitaire, cd.qte
) as co
on vs.id_saison = co.id_saison and vs.id_produit = co.id_produit and vs.id_couleur = co.id_couleur and vs.id_artisan = co.id_artisan and vs.id_client = co.id_client
group by vs.id_saison, vs.nom_saison, vs.id_artisan, vs.artisan, vs.id_client, vs.id_produit, vs.id_couleur, vs.couleur, vs.designation_produit, vs.designation_catalogue, vs.qte_livre, co.prix_unitaire;

-- vue pour expedition
create view v_expedition as
select ed.id_saison, sa.nom_saison, ed.expedition_route, ed.id_artisan, a.nom as artisan, ed.id_client, ed.id_produit, p.designation_produit, p.designation_catalogue, ed.id_couleur, c.nom as couleur, sum(ed.qte) as qte, ed.date_expedition from expedition_detail ed
join produit p on
p.id = ed.id_produit
join couleur c on
c.id = ed.id_couleur
join artisan a on
a.id = ed.id_artisan
join saison sa on
ed.id_saison = sa.id
group by ed.id_saison, sa.nom_saison, ed.expedition_route, ed.id_artisan, a.nom, ed.id_client, ed.id_produit, p.designation_produit, p.designation_catalogue, ed.id_couleur, c.nom, ed.date_expedition;


-- vue pour la somme dans commande par artisan
create view v_somme_commande as
select id_artisan, id_saison, id_client, sum(qte) as total_qte, sum(total) as total_prix from v_detail_commande
group by id_artisan, id_saison, id_client;

-- vue pour la somme dans commande par type_commande
create view v_somme_commande_type as
select type_commande, id_artisan, id_saison, id_client, sum(qte) as total_qte, sum(total) as total_prix from v_detail_commande
group by id_artisan, id_saison, id_client, type_commande;

-- vue necessaire pour somme total valeur expedition par saison
create view v_reference_somme_expedition as
select ve.id_saison, ve.nom_saison, ve.id_artisan, ve.artisan, ve.expedition_route, ve.id_client, ve.id_produit, ve.id_couleur, ve.couleur, SUM(ve.qte) as qte, ve.designation_produit, ve.designation_catalogue, vc.prix_unitaire, ve.date_expedition from v_expedition ve
join ( 
select c.id_saison, c.id_artisan, c.id_client, cd.id_produit, cd.id_couleur, cd.prix_unitaire, cd.qte from commande_detail cd
join commande c
on c.id = cd.id_commande
group by c.id_saison, c.id_artisan, c.id_client, cd.id_produit, cd.id_couleur, cd.prix_unitaire, cd.qte
) as vc
on ve.id_saison = vc.id_saison and ve.id_artisan=vc.id_artisan and ve.id_produit = vc.id_produit and ve.id_couleur = vc.id_couleur and ve.id_client = vc.id_client
group by ve.id_saison, ve.nom_saison, ve.id_artisan, ve.artisan, ve.expedition_route, ve.id_client, ve.id_produit, ve.id_couleur, ve.couleur, ve.designation_produit, ve.designation_catalogue, vc.prix_unitaire, ve.date_expedition;


create view v_article_retour_detail as
select rd.id_article_retour, ar.id_artisan, ar.id_saison, ar.id_client, rd.id_produit, p.designation_produit, p.designation_catalogue, rd.id_couleur, c.nom as couleur, SUM(rd.qte) as qte, rd.date_retour, rd.bon_sortie from article_retour_detail rd 
join produit p on
p.id = rd.id_produit
join couleur c on 
c.id = rd.id_couleur
join article_retour ar on
rd.id_article_retour = ar.id
group by rd.id_article_retour, ar.id_artisan, ar.id_saison, ar.id_client, rd.id_produit, p.designation_produit, p.designation_catalogue, rd.id_couleur, c.nom, rd.date_retour, rd.bon_sortie ;

-- vue pour afficher liste commande_detail pour modification
select c.id as id_commande, c.id_client as id_client, c.id_saison as id_saison, c.id_artisan as id_artisan, cd.id, p.id as id_produit, c.type_commande, c.date_commande, p.designation_produit, p.designation_catalogue, co.nom as couleur, co.id as id_couleur, cd.prix_unitaire, cd.qte, cd.created_at from commande_detail cd
join commande c on 
c.id= cd.id_commande
join produit p on
p.id= cd.id_produit
join couleur co on
co.id= cd.id_couleur

-- insert into users (username, password, level) values ('Admin', '$2y$10$sOuApahdYD3FmIzKE8tZd.9eOznQlAYrxZzs4BisX013VW3l/J5.2', 0); 

-- php -r "echo password_hash('0000', PASSWORD_BCRYPT);"
