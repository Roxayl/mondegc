<?php

require_once('../../Connections/maconnexion.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();

/**********
 * Création de tables : requêtes à permission avancée, à envoyer à Youcef.
 **********/
$queries[] = "create table if not exists personnage
(
	id int auto_increment,
	entity text default null null,
	entity_id int null,
	nom_personnage varchar(191) null,
    prenom_personnage varchar(191) null,
    titre_personnage varchar(191) null,
	predicat varchar(191) null,
    lien_img varchar(191) null,
    biographie text null,
	constraint personnage_pk
		primary key (id)
)";
$queries[] = "create table if not exists users_pays
(
  id          int auto_increment
    primary key,
  ID_pays     int not null,
  ID_user     int not null,
  permissions int not null
)";
$queries[] = "create table if not exists ocgc_proposals
(
	id int auto_increment,
	ID_pays int null,
	question text null,
	is_valid boolean default false null,
	reponse_1 text null,
	reponse_2 text null,
	reponse_3 text null,
	reponse_4 text null,
	reponse_5 text null,
	created int null,
	updated int null,
	constraint ocgc_proposals_pk
		primary key (id)
)
comment 'Propositions de loi à l''Assemblée Générale'
";
$queries[] = "create table if not exists ocgc_votes
(
	id int auto_increment,
	ID_proposal int null,
	ID_pays int null,
	reponse_choisie int null,
	created int null,
	constraint ocgc_votes_pk
		primary key (id)
)
comment 'Votes aux propositions de l''Assemblée Générale'
";


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


/**********
 * Ajouter les personnages à la nouvelle table.
 **********/
mysql_data_seek($list_users, 0);
// Vérifier que la table 'personnages' est vide.
$query_personnages = mysql_query('SELECT * FROM personnage');

if(empty(mysql_fetch_assoc($query_personnages))) {
    // Faire en sorte à ne pas ajouter un deuxième personnage pour un même pays.
    $added_pays = array();

    // Ajouter les personnages
    while($row = mysql_fetch_assoc($list_users)) {
        if(in_array($row['ch_use_paysID'], $added_pays, true)) continue;
        mysql_query(sprintf("INSERT INTO personnage(entity, entity_id, nom_personnage,
                                prenom_personnage, predicat, titre_personnage, lien_img, biographie)
                            VALUES(%s, %s, %s, %s, %s, %s, %s, %s)",
                    GetSQLValueString('pays', 'text'),
                    GetSQLValueString($row['ch_use_paysID'], 'int'),
                    GetSQLValueString($row['ch_use_nom_dirigeant'], 'text'),
                    GetSQLValueString($row['ch_use_prenom_dirigeant'], 'text'),
                    GetSQLValueString($row['ch_use_predicat_dirigeant'], 'text'),
                    GetSQLValueString($row['ch_use_titre_dirigeant'], 'text'),
                    GetSQLValueString($row['ch_use_lien_imgpersonnage'], 'text'),
                    GetSQLValueString($row['ch_use_biographie_dirigeant'], 'text'))) or die(mysql_error());
        $added_pays[] = $row['ch_use_paysID'];
    }

}


/**********
 * Mettre à jour les permissions.
 * 5 = co-dirigeant
 * 10 = dirigeant
 **********/
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
 * On précise l'ID du pays dans la table 'communiques', depuis l'ancienne version du site.
 **********/
$com_update = 'UPDATE communiques SET ch_com_pays_id = (SELECT ch_use_paysID FROM users WHERE ch_use_id = communiques.ch_com_user_id)';
mysql_query($com_update) or die(mysql_error());


/**********
 * Vider les tables stockant les sessions.
 **********/
$queries[] = 'TRUNCATE TABLE users_dispatch_session';
$queries[] = 'TRUNCATE TABLE users_session';

// Tous les utilisateurs avec un compte de maire basculent vers un compte classique (ex compte dirigeant)
$queries[] = "UPDATE users SET ch_use_statut = 10 WHERE ch_use_statut = 5";


/**********
 * TODO !!!
 * Fusion manuelle users/pays.
 **********/
//$queries[] = "";


foreach($queries as $query) {
    $result_query = mysql_query($query) or die(mysql_error());
}
