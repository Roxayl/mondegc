<?php

/* *******************
 * Script de migration
 * Version cible : 2.5
 * ******************/

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();

$queries[] = "alter table type_geometries drop column color";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}


/***** Créer les groupes de géométries *****/

$group_geo_queries = array();

$group_geo_queries[] = 'INSERT INTO type_geometries_group(id, intitule, created_at, updated_at)
    VALUES(1, "Zones", NOW(), NOW())';
$group_geo_queries[] = 'INSERT INTO type_geometries_group(id, intitule, created_at, updated_at)
    VALUES(2, "Zones administratives", NOW(), NOW())';
$group_geo_queries[] = 'INSERT INTO type_geometries_group(id, intitule, created_at, updated_at)
    VALUES(3, "Zones réservées à l\'administration", NOW(), NOW())';
$group_geo_queries[] = 'INSERT INTO type_geometries_group(id, intitule, created_at, updated_at)
    VALUES(4, "Lignes", NOW(), NOW())';
$group_geo_queries[] = 'INSERT INTO type_geometries_group(id, intitule, created_at, updated_at)
    VALUES(5, "Lignes réservées à l\'administration", NOW(), NOW())';

// Exécuter la requête
foreach($group_geo_queries as $query) {
    mysql_query($query) or die(mysql_error());
}


/***** Copier les types de géométries ****/

$query_geo = mysql_query("select distinct ch_geo_type from geometries") or die(mysql_error());
$geometries_type = array();
while($row = mysql_fetch_assoc($query_geo)) {
    $geometries_type[] = $row['ch_geo_type'];
}

$zones = ['marecageuse', 'forestiere', 'urbaine', 'protegee', 'cerealiere', 'lagunaire', 'agricole', 'maraichere', 'industrielle', 'elevage', 'prairies', 'periurbaine', 'megapole', 'maritime', 'peche intensive', 'peche traditionnelle', 'maritime protegee'];
$zones_territoires = ['region'];
$zones_admin = ['terre'];
$lignes = ['autoroute', 'voieexpress', 'lgv', 'nationale', 'cheminFer', 'canal', 'ferry', 'peche traditionnelle', 'route maritime'];
$lignes_admin = ['frontiere'];


foreach($geometries_type as $this_type) {

    if(in_array($this_type, $zones)) {
        $geo_type = 1;
        $type_geometrie = 'polygon';
    }
    elseif(in_array($this_type, $zones_territoires)) {
        $geo_type = 2;
        $type_geometrie = 'polygon';
    }
    elseif(in_array($this_type, $zones_admin)) {
        $geo_type = 3;
        $type_geometrie = 'polygon';
    }
    elseif(in_array($this_type, $lignes)) {
        $geo_type = 4;
        $type_geometrie = 'linestring';
    }
    elseif(in_array($this_type, $lignes_admin)) {
        $geo_type = 5;
        $type_geometrie = 'linestring';
    }

    $typeZone = $this_type;
    $budget = $industrie = $commerce = $agriculture = $tourisme = $recherche = $environnement = $education = $label = $population = $emploi = 0;
    ressourcesGeometrie(1, $typeZone, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population, $emploi);

    $this_query = sprintf("INSERT INTO type_geometries(group_id, label, type_geometrie, coef_budget, coef_industrie, coef_commerce, coef_agriculture, coef_tourisme, coef_recherche, coef_environnement, coef_education, coef_population, created_at, updated_at)
VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, NOW(), NOW())",
        GetSQLValueString($geo_type, 'int'),
        GetSQLValueString($label, 'text'),
        GetSQLValueString($type_geometrie, 'text'),
        GetSQLValueString($budget, 'double'),
        GetSQLValueString($industrie, 'double'),
        GetSQLValueString($commerce, 'double'),
        GetSQLValueString($agriculture, 'double'),
        GetSQLValueString($tourisme, 'double'),
        GetSQLValueString($recherche, 'double'),
        GetSQLValueString($environnement, 'double'),
        GetSQLValueString($education, 'double'),
        GetSQLValueString($population, 'double')
    );

    mysql_query($this_query) or die(mysql_error());

    $this_query = sprintf('UPDATE geometries SET type_geometrie_id = %s WHERE ch_geo_type = %s',
        GetSQLValueString($geo_type, 'int'),
        GetSQLValueString($this_type, 'text')
    );
    mysql_query($this_query) or die(mysql_error());

}