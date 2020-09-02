<?php

$migration = new \App\Services\LegacyMigrationService();

$migration
    ->addQuery("alter table organisation
	    add type varchar(16) default 'organisation' not null after text")
    ->addQuery("alter table organisation
	    add type_migrated_at datetime null after allow_temperance")
    ->run();
