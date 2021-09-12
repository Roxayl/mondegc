<?php

$migration = new \App\Services\LegacyMigrationService();

$migration->addQuery('alter table users
	add last_activity datetime null after ch_use_last_log');
$migration->addQuery('update users
    set last_activity = ch_use_last_log');

$migration->run();
