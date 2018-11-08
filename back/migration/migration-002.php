<?php

require_once('../../Connections/maconnexion.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array(
    "UPDATE pays SET ch_pay_continent = 'Aurinea' WHERE ch_pay_emplacement = 2",
    "UPDATE pays SET ch_pay_continent = 'Aurinea' WHERE ch_pay_emplacement = 5",
    "UPDATE pays SET ch_pay_continent = 'Aurinea' WHERE ch_pay_emplacement = 1"
);

foreach($queries as $query) {
    $stat_ville = mysql_query($query) or die(mysql_error());
}
