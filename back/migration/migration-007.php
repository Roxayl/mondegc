<?php

require_once('../../Connections/maconnexion.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();


/*************************
 *                       *
 *   ÉDITION DE TABLES   *
 *                       *
 *************************/

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