<?php

require_once('../../Connections/maconnexion.php');

mysql_select_db($database_maconnexion, $maconnexion);

$query_pays = "SELECT * FROM pays";
$list_pays = mysql_query($query_pays) or die(mysql_error());

// Vérifier que la table users_pays est vide.
$list_users_pays = mysql_query("SELECT * FROM users_pays") or die(mysql_error());

$queries = array();

if(empty(mysql_fetch_assoc($row_users_pays))) {
    while($row = mysql_fetch_assoc($list_pays)) {
        // $queries[] = "INSERT INTO ";
    }
}

// Déplacer le pays de Sebtalus au continent Aldesyl
$queries[] = "UPDATE pays SET ch_pay_continent = 'Aldesyl' WHERE ch_pay_id = 114";

foreach($queries as $query) {
    $stat_ville = mysql_query($query) or die(mysql_error());
}

