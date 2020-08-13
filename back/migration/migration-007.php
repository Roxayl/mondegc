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

/*
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
			on update set null on delete set null";*/

// Modifier type ch_use_id dans 'users'
$queries[] = "alter table users modify ch_use_id int auto_increment";

// Table log pour la journalisation.
$queries[] = "create table if not exists log
(
  id           int auto_increment
    primary key,
  target       varchar(100) not null,
  type_action  varchar(100) not null,
  user_id      int          null,
  data_changes text         null,
  created      datetime     not null,
  constraint log_users_ch_use_id_fk
    foreign key (user_id) references users (ch_use_id)
      on update set null on delete set null
)";
$queries[] = "alter table log
	add target_id int null after target";
$queries[] = "create index log_user_id_index
  on log (user_id)";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}


// Changer le moteur de BDD de MyISAM à InnoDB.
$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'mgvx_generationcitycom3' 
        AND ENGINE = 'MyISAM'";

$rs = mysql_query($sql);

while($row = mysql_fetch_array($rs)) {
    $tbl = $row[0];
    $sql = "ALTER TABLE `$tbl` ENGINE=INNODB";
    mysql_query($sql);
}


/*************************
 *                       *
 *       REQUÊTES        *
 *                       *
 *************************/

$queries = array();

// On déplace le pays d'Alex en Helvenie.
$queries[] = "UPDATE pays SET ch_pay_continent = 'Philicie' WHERE ch_pay_id = 89";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}