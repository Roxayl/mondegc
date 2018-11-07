<?php

require_once('../Connections/maconnexion.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array(
    "UPDATE pays SET ch_pay_emplacement = 44 WHERE ch_pay_id = 30",
    "UPDATE pays SET ch_pay_emplacement = 54 WHERE ch_pay_id = 51",
    "UPDATE pays SET ch_pay_emplacement = 55 WHERE ch_pay_id = 106",
    "UPDATE pays SET ch_pay_emplacement = 24 WHERE ch_pay_id = 46",
    "UPDATE pays SET ch_pay_emplacement = 25 WHERE ch_pay_id = 113",
    "UPDATE pays SET ch_pay_emplacement = 45 WHERE ch_pay_id = 107"
);

foreach($queries as $query) {
    $stat_ville = mysql_query($query) or die(mysql_error());
}

