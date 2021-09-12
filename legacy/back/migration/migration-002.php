<?php

/* *******************
 * Script de migration
 * Version cible : 1.3
 * ******************/

require_once('../../php/init/legacy_init.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array(
    "UPDATE pays SET ch_pay_continent = 'Aurinea' WHERE ch_pay_emplacement = 2",
    "UPDATE pays SET ch_pay_continent = 'Aurinea' WHERE ch_pay_emplacement = 5",
    "UPDATE pays SET ch_pay_continent = 'Aurinea' WHERE ch_pay_emplacement = 1",
    "ALTER TABLE `infrastructures_officielles` CHANGE `ch_inf_off_desc` `ch_inf_off_desc` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL"
);

foreach($queries as $query) {
    $stat_ville = mysql_query($query) or die(mysql_error());
}
