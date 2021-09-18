<?php

set_time_limit(180);

$migration = new \App\Services\LegacyMigrationService();

/*************************
 *                       *
 *   ÉDITION DE TABLES   *
 *                       *
 *************************/

$migration
    ->addQuery("create table influence
        (
            id int not null,
            influencable_type varchar(191) not null,
            influencable_id int null,
            budget int default 0 not null,
            agriculture int default 0 not null,
            commerce int default 0 not null,
            education int default 0 not null,
            environnement int default 0 not null,
            industrie int default 0 not null,
            recherche int default 0 not null,
            tourisme int default 0 not null,
            generates_influence_at datetime not null,
            created_at datetime not null,
            updated_at datetime not null,
            constraint influence_pk
                primary key (id)
        )")
    ->addQuery("create index influence_influencable_type_influencable_id_index
	    on influence (influencable_type, influencable_id)")
    ->addQuery("alter table organisation
	    add type varchar(16) default 'organisation' not null after text")
    ->addQuery("alter table organisation
	    add type_migrated_at datetime null after allow_temperance")
    ->addQuery('drop table monuments')
    ->addQuery("alter table influence modify id int auto_increment");


/*************************
 *                       *
 * Génère les influences *
 *                       *
 *************************/

$migration->addQuery('truncate table influence');

$migration->run();

$patrimoines = \App\Models\Patrimoine::all();
foreach($patrimoines as $patrimoine) {
    $patrimoine->generateInfluence();
}
unset($patrimoines);

$infrastructures = \App\Models\Infrastructure::all();
foreach($infrastructures as $infrastructure) {
    $infrastructure->generateInfluence();
}
unset($infrastructures);

$paysMaps = \App\Models\Pays::all();
foreach($paysMaps as $paysMap) {
    $paysMap->getMapManager()->generateInfluence();
}
unset($paysMaps);
