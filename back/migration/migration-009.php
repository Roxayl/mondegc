<?php

/* *******************
 * Script de migration
 * Version cible : 2.5
 * ******************/

require_once('../../Connections/maconnexion.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();

$queries = 'rename table pages to legacy_pages';