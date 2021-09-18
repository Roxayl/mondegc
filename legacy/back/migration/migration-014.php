<?php

$migration = new \App\Services\LegacyMigrationService();

$migration->addQuery('alter table infrastructures
	add judged_at datetime null after ch_inf_commentaire_juge');

$migration->run();
