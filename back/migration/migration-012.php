<?php

/* *******************
 * Script de migration
 * Version cible : 2.7
 * ******************/

/*************************
 *                       *
 *   ÉDITION DE TABLES   *
 *                       *
 *************************/

$queries = array();

$queries[] = "SET FOREIGN_KEY_CHECKS=0";

$queries[] = "drop view if exists temperance_organisation";

$queries[] = "CREATE VIEW `temperance_organisation` AS SELECT o.id, o.name,
   SUM(budget) AS budget,
   SUM(agriculture) AS agriculture,
   SUM(commerce) AS commerce,
   SUM(education) AS education,
   SUM(environnement) AS environnement,
   SUM(industrie) AS industrie,
   SUM(recherche) AS recherche,
   SUM(tourisme) AS tourisme
FROM organisation_members
INNER JOIN organisation o on organisation_members.organisation_id = o.id
INNER JOIN pays p on organisation_members.pays_id = p.ch_pay_id
LEFT JOIN temperance_pays tp on organisation_members.pays_id = tp.id
WHERE organisation_members.permissions >= 10
  AND o.allow_temperance = 1
GROUP BY o.id";

$queries[] = "SET FOREIGN_KEY_CHECKS=1";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}


/*************************
 *                       *
 *       REQUÊTES        *
 *                       *
 *************************/

/* Remettre la Helvénie en Philicie */
$helvenie = \App\Models\Pays::findOrFail(89);
$helvenie->ch_pay_continent = 'Philicie';
$helvenie->save();