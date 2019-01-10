<?php

require_once('../../Connections/maconnexion.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();


/**********
 * Ajouter les pays à la nouvelle table.
 **********/
$list_users = mysql_query("SELECT * FROM users") or die(mysql_error());

// Vérifier que la table users_pays est vide.
$list_users_pays = mysql_query("SELECT * FROM users_pays") or die(mysql_error());
$row_users_pays = mysql_fetch_assoc($list_users_pays);

if(empty($row_users_pays)) {
    while($row = mysql_fetch_assoc($list_users)) {
        $insert_sql = sprintf("INSERT INTO users_pays(ID_pays, ID_user, permissions)
                       VALUES(%s, %s, %s)",
                      GetSQLValueString($row['ch_use_paysID'], 'int'),
                      GetSQLValueString($row['ch_use_id'], 'int'),
                      GetSQLValueString($row['ch_use_statut'], 'int'));
        $insert_query = mysql_query($insert_sql) or die(mysql_error());
    }
}


/**********
 * TODO !!!
 * Fusion manuelle users/pays.
 **********/
//$queries[] = "";


foreach($queries as $query) {

    $result_query = mysql_query($query) or die(mysql_error());
}
