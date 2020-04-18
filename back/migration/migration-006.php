<?php

/* *******************
 * Script de migration
 * Version cible : 2.3
 * ******************/

require_once('../../Connections/maconnexion.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();


/*************************
 *                       *
 *   ÉDITION DE TABLES   *
 *                       *
 *************************/

// Augmenter nbr caractères titre communiqué
$queries[] = "alter table communiques modify ch_com_titre varchar(100) null";

// Index sur les infras ; on créé l'infra s'il n'existait pas encore.
$check_index = mysql_query("SELECT COUNT(1) IndexIsThere FROM INFORMATION_SCHEMA.STATISTICS
  WHERE table_schema=DATABASE() AND table_name='infrastructures' AND index_name='ch_inf_off_id__index'");
$result_index = mysql_fetch_assoc($check_index);
if($result_index['IndexIsThere'] == 0) {
    $queries[] = "create index ch_inf_off_id__index
        on infrastructures (ch_inf_off_id)";
}

$check_index = mysql_query("SELECT COUNT(1) IndexIsThere FROM INFORMATION_SCHEMA.STATISTICS
  WHERE table_schema=DATABASE() AND table_name='infrastructures' AND index_name='ch_inf_villeid__index'");
$result_index = mysql_fetch_assoc($check_index);
if($result_index['IndexIsThere'] == 0) {
    $queries[] = "create index ch_inf_villeid__index
        on infrastructures (ch_inf_villeid)";
}

// Exécuter cette première série de requêtes
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}


/*************************
 *                       *
 *       REQUÊTES        *
 *                       *
 *************************/

$queries = array();

$queries[] = "UPDATE users SET
  ch_use_predicat_dirigeant = NULL,
  ch_use_titre_dirigeant = NULL,
  ch_use_nom_dirigeant = NULL,
  ch_use_prenom_dirigeant = NULL,
  ch_use_biographie_dirigeant = NULL";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}