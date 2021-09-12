<?php

/* *******************
 * Script de migration
 * Version cible : 2.4
 * ******************/

require_once('../../php/init/legacy_init.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();


/*************************
 *                       *
 *   ÉDITION DE TABLES   *
 *                       *
 *************************/

/****** géométries *******/

$queries[] = "SET FOREIGN_KEY_CHECKS=0";

// Création des tables liées à la gestion des géométries depuis la BDD.
// Table type_geometries, regroupant les types de tracés de carte.
$queries[] = "create table if not exists type_geometries
(
  id                 int auto_increment
    primary key,
  group_id           int                         not null,
  label              varchar(255)                not null,
  type_geometrie     varchar(50)                 null,
  color              varchar(15)                 not null,
  coef_budget        float(8, 5) default 1.00000 not null,
  coef_industrie     float(8, 5) default 1.00000 not null,
  coef_commerce      float(8, 5) default 1.00000 not null,
  coef_agriculture   float(8, 5) default 1.00000 not null,
  coef_tourisme      float(8, 5) default 1.00000 not null,
  coef_recherche     float(8, 5) default 1.00000 not null,
  coef_environnement float(8, 5) default 1.00000 not null,
  coef_education     float(8, 5) default 1.00000 not null,
  coef_population    float(8, 5) default 1.00000 not null,
  created            datetime                    not null,
  updated            datetime                    not null,
  constraint type_geometries_type_geometries_group_id_fk
    foreign key (group_id) references type_geometries_group (id)
      on update cascade on delete cascade
)";

// Table type_geometries_group, correspondant aux groupes de géométries
// (e.g. "Zones aménagées", "Zones naturelles", "Limites administratives"...)
$queries[] = "create table if not exists type_geometries_group
(
  id       int auto_increment
    primary key,
  intitule varchar(50) not null,
  created  datetime    not null,
  updated  datetime    not null
)";

// Ajouter le type de géométrie dans la table geometries ainsi que les contraintes.
$queries[] = "alter table geometries
	add type_geometrie_id int null after ch_geo_id";
$queries[] = "alter table geometries
	add constraint geometries_type_geometries_id_fk
		foreign key (type_geometrie_id) references type_geometries (id)
			on update set null on delete set null";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}


/***** notifications *****/

$queries = array();

$queries[] = "create table if not exists notifications
(
  id           int auto_increment
    primary key,
  recipient_id int                  null,
  type_notif   varchar(25)          not null,
  element      int                  null,
  unread       tinyint(1) default 1 not null,
  created      datetime             not null,
  constraint notifications_users_ch_use_id_fk
    foreign key (recipient_id) references users (ch_use_id)
      on update set null on delete set null
)";

$queries[] = "create index notifications_recipient_id_index
  on notifications (recipient_id)";


/**** infrastructures ****/

$queries[] = "alter table infrastructures
	add user_creator int null after lien_wiki";

$queries[] = "alter table infrastructures
	add constraint infrastructures_users_ch_use_id_fk
		foreign key (user_creator) references users (ch_use_id)
			on update set null on delete set null";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}


/******* pays *******/

$queries = array();

$queries[] = 'alter table pays
	add lien_wiki varchar(250) null after ch_pay_lien_forum';

$queries[] = "SET FOREIGN_KEY_CHECKS=1";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}


/*************************
 *                       *
 *       REQUÊTES        *
 *                       *
 *************************/

$queries = array();

$queries[] = "INSERT INTO `pages` (`this_id`, `content`, `modified`) VALUES ('conseil_ocgc_desc', '<h2>Qu''est-ce que le Conseil de l''OCGC&nbsp;?</h2><p>Description du Conseil de l''OCGC.', '2020-04-20 11:44:58')";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}