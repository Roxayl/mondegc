<?php

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

// Index sur les infras
$queries[] = "create index ch_inf_off_id__index
	on infrastructures (ch_inf_off_id)";
$queries[] = "create index ch_inf_villeid__index
	on infrastructures (ch_inf_villeid)";

// Exécuter cette première série de requêtes
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}