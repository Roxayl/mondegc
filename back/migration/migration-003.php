<?php

/* *******************
 * Script de migration
 * Version cible : 1.5
 * ******************/

require_once('../../php/init/legacy_init.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();


/**********
 * Requêtes à permission avancée.
 **********/

// Table infrastructures_groupes
$queries[] = "CREATE TABLE infrastructures_groupes
(
  id         INT AUTO_INCREMENT
    PRIMARY KEY,
  nom_groupe VARCHAR(191) NULL,
  CONSTRAINT infrastructures_groupes_id_uindex
  UNIQUE (id)
)
  ENGINE = InnoDB";

// Table infrastructures_officielles_groupes
$queries[] = "CREATE TABLE infrastructures_officielles_groupes
(
  id                  INT AUTO_INCREMENT
    PRIMARY KEY,
  ID_groupes          INT NULL,
  ID_infra_officielle INT NULL,
  CONSTRAINT infrastructures_officielles_groupes_id_uindex
  UNIQUE (id)
)
  ENGINE = InnoDB";

// Ajouter 3 colonnes à la table villes
$queries[] = "ALTER TABLE villes ADD ch_vil_transports TEXT NULL";
$queries[] = "ALTER TABLE villes ADD ch_vil_administration TEXT NULL";
$queries[] = "ALTER TABLE villes ADD ch_vil_culture TEXT NULL";


/**********
 * TODO !!!
 * Permettre de gérer plusieurs pays simultanément.
 **********/
$query_pays = "SELECT * FROM pays";
$list_pays = mysql_query($query_pays) or die(mysql_error());

// Vérifier que la table users_pays est vide.
$list_users_pays = mysql_query("SELECT * FROM users_pays") or die(mysql_error());


if(empty(mysql_fetch_assoc($row_users_pays))) {
    while($row = mysql_fetch_assoc($list_pays)) {
        // $queries[] = "INSERT INTO ";
    }
}


/**********
 * Autres requêtes
 **********/

// Déplacer le pays de Sebtalus au continent Aldesyl.
$queries[] = "UPDATE pays SET ch_pay_continent = 'Aldesyl' WHERE ch_pay_id = 114";
// Ajout des groupes d'infrastructures.
$queries[] = "INSERT INTO mgvx_generationcitycom3.infrastructures_groupes (id, nom_groupe) VALUES (1, 'Non catégorisé')";
$queries[] = "INSERT INTO mgvx_generationcitycom3.infrastructures_groupes (id, nom_groupe) VALUES (2, 'Transports urbains')";
$queries[] = "INSERT INTO mgvx_generationcitycom3.infrastructures_groupes (id, nom_groupe) VALUES (3, 'Transports interurbains')";
$queries[] = "INSERT INTO mgvx_generationcitycom3.infrastructures_groupes (id, nom_groupe) VALUES (4, 'Zone militaire')";
$queries[] = "INSERT INTO mgvx_generationcitycom3.infrastructures_groupes (id, nom_groupe) VALUES (5, 'Site de culture, de sport et de loisirs')";
$queries[] = "INSERT INTO mgvx_generationcitycom3.infrastructures_groupes (id, nom_groupe) VALUES (6, 'Centre administratif et politique')";
$queries[] = "INSERT INTO mgvx_generationcitycom3.infrastructures_groupes (id, nom_groupe) VALUES (7, 'Zone naturelle')";
$queries[] = "INSERT INTO mgvx_generationcitycom3.infrastructures_groupes (id, nom_groupe) VALUES (8, 'Échanges')";
$queries[] = "INSERT INTO mgvx_generationcitycom3.infrastructures_groupes (id, nom_groupe) VALUES (9, 'Centre économique')";
// Mise à jour des icônes pour éviter d'avoir des images cassées (franchement ils pourraient
// se bouger le fion :aie: )
$queries[] = "UPDATE monument_categories SET ch_mon_cat_icon = 'http://generation-city.com/monde/assets/img/IconesBDD/100/monument1.png'";

foreach($queries as $query) {
    $stat_ville = mysql_query($query) or die(mysql_error());
}
