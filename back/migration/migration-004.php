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
    // Insérer dans la table
    while($row = mysql_fetch_assoc($list_users)) {
        $insert_sql = sprintf("INSERT INTO users_pays(ID_pays, ID_user, permissions)
                       VALUES(%s, %s, %s)",
                      GetSQLValueString($row['ch_use_paysID'], 'int'),
                      GetSQLValueString($row['ch_use_id'], 'int'),
                      GetSQLValueString($row['ch_use_statut'], 'int'));
        $insert_query = mysql_query($insert_sql) or die(mysql_error());
    }

}

// Mettre à jour les permissions
// On a ainsi le niveau de permission :
//  5 = maire de ville
// 10 = dirigeant
$updated_list_users_pays = mysql_query("SELECT * FROM users_pays") or die(mysql_error());
while($row = mysql_fetch_assoc($updated_list_users_pays)) {
    if($row['permissions'] > 10) {
        $insert_sql = sprintf("UPDATE users_pays SET permissions = %s WHERE id = %s",
                   10,
                        GetSQLValueString($row['id'], 'int'));
        $insert_query = mysql_query($insert_sql) or die(mysql_error());
    }
}


/**********
 * Vider les tables stockant les sessions.
 **********/
$queries[] = 'TRUNCATE TABLE users_dispatch_session';
$queries[] = 'TRUNCATE TABLE users_session';


/**********
 * TODO !!!
 * Fusion manuelle users/pays.
 **********/
//$queries[] = "";


foreach($queries as $query) {
    $result_query = mysql_query($query) or die(mysql_error());
}
