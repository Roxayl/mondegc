<?php

$migration = new \App\Services\LegacyMigrationService();

$migration
    ->addQuery("alter table organisation
	    add type varchar(16) default 'organisation' not null after text")
    ->run();
