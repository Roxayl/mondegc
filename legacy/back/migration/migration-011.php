<?php

/* *******************
 * Script de migration
 * Version cible : 2.6
 * ******************/

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();

$queries[] = "SET FOREIGN_KEY_CHECKS=0";

$queries[] = "rename table notifications to notifications_legacy";

$queries[] = 'create table notifications
(
    id              char(36)        not null
        primary key,
    type            varchar(255)    not null,
    notifiable_type varchar(255)    not null,
    notifiable_id   bigint unsigned not null,
    data            text            not null,
    read_at         timestamp       null,
    created_at      timestamp       null,
    updated_at      timestamp       null
)';

$queries[] = 'create index notifications_notifiable_type_notifiable_id_index
    on notifications (notifiable_type, notifiable_id)';

$queries[] = "SET FOREIGN_KEY_CHECKS=1";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}