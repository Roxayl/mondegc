<?php

/* *******************
 * Script de migration
 * Version cible : 2.5
 * ******************/

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();


/*************************
 *                       *
 *   ÉDITION DE TABLES   *
 *                       *
 *************************/

$queries[] = "SET FOREIGN_KEY_CHECKS=0";

$queries[] = 'rename table pages to legacy_pages';

$queries[] = 'alter table pays modify ch_pay_id int auto_increment';

$queries[] = 'alter table villes
	add constraint villes_pays_ch_pay_id_fk
		foreign key (ch_vil_paysID) references pays (ch_pay_id)
			on update cascade on delete set null';

$queries[] = 'alter table type_geometries change created created_at datetime not null';

$queries[] = 'alter table type_geometries change updated updated_at datetime not null';

$queries[] = 'alter table type_geometries_group change created created_at datetime not null';

$queries[] = 'alter table type_geometries_group change updated updated_at datetime not null';

$queries[] = 'alter table geometries
	add constraint geometries_pays_ch_pay_id_fk
		foreign key (ch_geo_pay_id) references pays (ch_pay_id)
			on update cascade';

$queries[] = 'create table activations
(
    email      varchar(255)         not null,
    token      varchar(255)         not null,
    used       tinyint(1) default 0 not null,
    created_at timestamp            null
)';

$queries[] = 'create index activations_email_index
    on activations (email)';

$queries[] = 'create table admin_activations
(
	email varchar(255) not null,
	token varchar(255) not null,
	used tinyint(1) default 0 not null,
	created_at timestamp null
)';

$queries[] = 'create index admin_activations_email_index
	on admin_activations (email)';

$queries[] = 'create table admin_password_resets
(
	email varchar(255) not null,
	token varchar(255) not null,
	created_at timestamp null
)';

$queries[] = 'create index admin_password_resets_email_index
	on admin_password_resets (email)';

$queries[] = "create table admin_users
(
	id int unsigned auto_increment
		primary key,
	first_name varchar(255) null,
	last_name varchar(255) null,
	email varchar(255) not null,
	password varchar(255) not null,
	remember_token varchar(100) null,
	activated tinyint(1) default 0 not null,
	forbidden tinyint(1) default 0 not null,
	language varchar(2) default 'en' not null,
	deleted_at timestamp null,
	created_at timestamp null,
	updated_at timestamp null,
	constraint admin_users_email_deleted_at_unique
		unique (email, deleted_at)
)";

$queries[] = 'create table images
(
	id int unsigned auto_increment
		primary key,
	location varchar(255) not null,
	name varchar(255) null,
	original_name varchar(255) not null,
	storage_location varchar(255) default \'local\' not null,
	alt_tag varchar(255) null,
	title_tag varchar(255) null,
	is_published tinyint(1) default 0 not null,
	created_at timestamp null,
	updated_at timestamp null,
	tags text null,
	entity_id int null,
	entity_type varchar(255) null
)';

$queries[] = 'create table media
(
	id bigint unsigned auto_increment
		primary key,
	model_type varchar(255) not null,
	model_id bigint unsigned not null,
	collection_name varchar(255) not null,
	name varchar(255) not null,
	file_name varchar(255) not null,
	mime_type varchar(255) null,
	disk varchar(255) not null,
	size bigint unsigned not null,
	manipulations json not null,
	custom_properties json not null,
	responsive_images json not null,
	order_column int unsigned null,
	created_at timestamp null,
	updated_at timestamp null
)';

$queries[] = 'create index media_model_type_model_id_index
	on media (model_type, model_id)';

$queries[] = 'create table migrations
(
	id int unsigned auto_increment
		primary key,
	migration varchar(255) not null,
	batch int not null
)';

$queries[] = 'create table model_has_permissions
(
	permission_id int unsigned not null,
	model_type varchar(255) not null,
	model_id bigint unsigned not null,
	primary key (permission_id, model_id, model_type),
	constraint model_has_permissions_permission_id_foreign
		foreign key (permission_id) references permissions (id)
			on delete cascade
)';

$queries[] = 'create index model_has_permissions_model_id_model_type_index
	on model_has_permissions (model_id, model_type)';

$queries[] = 'create table model_has_roles
(
	role_id int unsigned not null,
	model_type varchar(255) not null,
	model_id bigint unsigned not null,
	primary key (role_id, model_id, model_type),
	constraint model_has_roles_role_id_foreign
		foreign key (role_id) references roles (id)
			on delete cascade
)';

$queries[] = 'create index model_has_roles_model_id_model_type_index
	on model_has_roles (model_id, model_type)';

$queries[] = 'create table pages
(
    id              int unsigned auto_increment
        primary key,
    title           varchar(255) not null,
    url             varchar(255) not null,
    content         text         null,
    seo_description varchar(255) null,
    seo_keywords    varchar(255) null,
    created_at      timestamp    null,
    updated_at      timestamp    null,
    published_at    datetime     null,
    cover_image     varchar(255) null
)';

$queries[] = 'create table permissions
(
	id int unsigned auto_increment
		primary key,
	name varchar(255) not null,
	guard_name varchar(255) not null,
	created_at timestamp null,
	updated_at timestamp null
)';

$queries[] = 'create table role_has_permissions
(
	permission_id int unsigned not null,
	role_id int unsigned not null,
	primary key (permission_id, role_id),
	constraint role_has_permissions_permission_id_foreign
		foreign key (permission_id) references permissions (id)
			on delete cascade,
	constraint role_has_permissions_role_id_foreign
		foreign key (role_id) references roles (id)
			on delete cascade
)';

$queries[] = 'create table roles
(
	id int unsigned auto_increment
		primary key,
	name varchar(255) not null,
	guard_name varchar(255) not null,
	created_at timestamp null,
	updated_at timestamp null
)';

$queries[] = 'create table translations
(
	id int unsigned auto_increment
		primary key,
	namespace varchar(255) default \'*\' not null,
	`group` varchar(255) not null,
	`key` text not null,
	text json not null,
	metadata json null,
	created_at timestamp null,
	updated_at timestamp null,
	deleted_at timestamp null
)';

$queries[] = 'create index translations_group_index
	on translations (`group`)';

$queries[] = 'create index translations_namespace_index
	on translations (namespace)';

$queries[] = 'create table wysiwyg_media
(
	id int unsigned auto_increment
		primary key,
	file_path varchar(255) not null,
	wysiwygable_id int unsigned null,
	wysiwygable_type varchar(255) null,
	created_at timestamp null,
	updated_at timestamp null
)';

$queries[] = 'create index wysiwyg_media_wysiwygable_id_index
	on wysiwyg_media (wysiwygable_id)';

$queries[] = 'create table organisation
(
    id         int auto_increment
        primary key,
    name       varchar(191) null,
    logo       varchar(191) null,
    flag       varchar(191) null,
    text       text         null,
    created_at datetime     null,
    updated_at datetime     null
)';

$queries[] = 'create index organisation_id_index
    on organisation (id)';

$queries[] = 'create table organisation_members
(
	id int auto_increment,
	organisation_id int null,
	pays_id int null,
    permissions int default 1 not null,
    created_at datetime     null,
    updated_at datetime     null,
	constraint organisation_members_pk
		primary key (id),
	constraint organisation_members_organisation_id_fk
		foreign key (organisation_id) references organisation (id)
			on update cascade on delete cascade,
	constraint organisation_members_pays_ch_pay_id_fk
		foreign key (pays_id) references pays (ch_pay_id)
			on update cascade on delete cascade
)';

$queries[] = 'create index organisation_members_organisation_id_index
	on organisation_members (organisation_id)';

$queries[] = 'create index organisation_members_pays_id_index
	on organisation_members (pays_id)';

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

$queries = array();

$queries[] = 'INSERT INTO mondegc.admin_users (id, first_name, last_name, email, password, remember_token, activated, forbidden, language, deleted_at, created_at, updated_at) VALUES (1, \'Admin\', \'MondeGC\', \'contact@romukulot.fr\', \'$2y$10$VklMSzvg25kPqAi86PS/ROHBWZEHHJ7asTbXPYrYeFRG7mFe.kyae\', \'mdMM8VNSPQZzQJBp5mHN4D20SHEMGYfTVTdxpY27ub2GQDCIFOZqWwTFFta2\', 1, 0, \'en\', null, \'2020-06-13 11:46:41\', \'2020-06-13 12:41:10\')';

$queries[] = 'INSERT INTO mondegc.media (id, model_type, model_id, collection_name, name, file_name, mime_type, disk, size, manipulations, custom_properties, responsive_images, order_column, created_at, updated_at) VALUES (1, \'Brackets\\AdminAuth\\Models\\AdminUser\', 1, \'avatar\', \'avatar\', \'avatar.png\', \'image/png\', \'media\', 23924, \'[]\', \'{"generated_conversions": {"thumb_75": true, "thumb_150": true, "thumb_200": true}}\', \'[]\', 1, \'2020-06-13 11:46:41\', \'2020-06-13 11:46:42\')';

$queries[] = 'INSERT INTO mondegc.migrations (id, migration, batch) VALUES (1, \'2017_08_24_000000_create_activations_table\', 1)';
$queries[] = 'INSERT INTO mondegc.migrations (id, migration, batch) VALUES (2, \'2017_08_24_000000_create_admin_activations_table\', 1)';
$queries[] = 'INSERT INTO mondegc.migrations (id, migration, batch) VALUES (3, \'2017_08_24_000000_create_admin_password_resets_table\', 1)';
$queries[] = 'INSERT INTO mondegc.migrations (id, migration, batch) VALUES (4, \'2017_08_24_000000_create_admin_users_table\', 1)';
$queries[] = 'INSERT INTO mondegc.migrations (id, migration, batch) VALUES (5, \'2018_07_18_000000_create_wysiwyg_media_table\', 1)';
$queries[] = 'INSERT INTO mondegc.migrations (id, migration, batch) VALUES (6, \'2020_06_13_114637_create_media_table\', 1)';
$queries[] = 'INSERT INTO mondegc.migrations (id, migration, batch) VALUES (7, \'2020_06_13_114638_create_permission_tables\', 1)';
$queries[] = 'INSERT INTO mondegc.migrations (id, migration, batch) VALUES (8, \'2020_06_13_114642_fill_default_admin_user_and_permissions\', 1)';
$queries[] = 'INSERT INTO mondegc.migrations (id, migration, batch) VALUES (9, \'2020_06_13_114637_create_translations_table\', 2)';
$queries[] = 'INSERT INTO mondegc.migrations (id, migration, batch) VALUES (10, \'2020_06_13_122109_fill_permissions_for_page\', 3)';

$queries[] = 'INSERT INTO mondegc.model_has_roles (role_id, model_type, model_id) VALUES (1, \'Brackets\\AdminAuth\\Models\\AdminUser\', 1)';

$queries[] = 'INSERT INTO mondegc.permissions (id, name, guard_name, created_at, updated_at) VALUES (1, \'admin\', \'admin\', \'2020-06-13 11:46:41\', \'2020-06-13 11:46:41\')';
$queries[] = 'INSERT INTO mondegc.permissions (id, name, guard_name, created_at, updated_at) VALUES (2, \'admin.translation.index\', \'admin\', \'2020-06-13 11:46:41\', \'2020-06-13 11:46:41\')';
$queries[] = 'INSERT INTO mondegc.permissions (id, name, guard_name, created_at, updated_at) VALUES (3, \'admin.translation.edit\', \'admin\', \'2020-06-13 11:46:41\', \'2020-06-13 11:46:41\')';
$queries[] = 'INSERT INTO mondegc.permissions (id, name, guard_name, created_at, updated_at) VALUES (4, \'admin.translation.rescan\', \'admin\', \'2020-06-13 11:46:41\', \'2020-06-13 11:46:41\')';
$queries[] = 'INSERT INTO mondegc.permissions (id, name, guard_name, created_at, updated_at) VALUES (5, \'admin.admin-user.index\', \'admin\', \'2020-06-13 11:46:41\', \'2020-06-13 11:46:41\')';
$queries[] = 'INSERT INTO mondegc.permissions (id, name, guard_name, created_at, updated_at) VALUES (6, \'admin.admin-user.create\', \'admin\', \'2020-06-13 11:46:41\', \'2020-06-13 11:46:41\')';
$queries[] = 'INSERT INTO mondegc.permissions (id, name, guard_name, created_at, updated_at) VALUES (7, \'admin.admin-user.edit\', \'admin\', \'2020-06-13 11:46:41\', \'2020-06-13 11:46:41\')';
$queries[] = 'INSERT INTO mondegc.permissions (id, name, guard_name, created_at, updated_at) VALUES (8, \'admin.admin-user.delete\', \'admin\', \'2020-06-13 11:46:41\', \'2020-06-13 11:46:41\')';
$queries[] = 'INSERT INTO mondegc.permissions (id, name, guard_name, created_at, updated_at) VALUES (9, \'admin.upload\', \'admin\', \'2020-06-13 11:46:41\', \'2020-06-13 11:46:41\')';
$queries[] = 'INSERT INTO mondegc.permissions (id, name, guard_name, created_at, updated_at) VALUES (10, \'admin.page\', \'admin\', \'2020-06-13 12:21:18\', \'2020-06-13 12:21:18\');';

$queries[] = 'INSERT INTO mondegc.role_has_permissions (permission_id, role_id) VALUES (1, 1)';
$queries[] = 'INSERT INTO mondegc.role_has_permissions (permission_id, role_id) VALUES (2, 1)';
$queries[] = 'INSERT INTO mondegc.role_has_permissions (permission_id, role_id) VALUES (3, 1)';
$queries[] = 'INSERT INTO mondegc.role_has_permissions (permission_id, role_id) VALUES (4, 1)';
$queries[] = 'INSERT INTO mondegc.role_has_permissions (permission_id, role_id) VALUES (5, 1)';
$queries[] = 'INSERT INTO mondegc.role_has_permissions (permission_id, role_id) VALUES (6, 1)';
$queries[] = 'INSERT INTO mondegc.role_has_permissions (permission_id, role_id) VALUES (7, 1)';
$queries[] = 'INSERT INTO mondegc.role_has_permissions (permission_id, role_id) VALUES (8, 1)';
$queries[] = 'INSERT INTO mondegc.role_has_permissions (permission_id, role_id) VALUES (9, 1)';
$queries[] = 'INSERT INTO mondegc.role_has_permissions (permission_id, role_id) VALUES (10, 1)';

$queries[] = 'INSERT INTO mondegc.roles (id, name, guard_name, created_at, updated_at) VALUES (1, \'Administrator\', \'admin\', \'2020-06-13 11:46:41\', \'2020-06-13 11:46:41\')';

$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (1, 'brackets/admin-ui', 'admin', 'operation.succeeded', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (2, 'brackets/admin-ui', 'admin', 'operation.failed', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (3, 'brackets/admin-ui', 'admin', 'operation.not_allowed', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (4, '*', 'admin', 'admin-user.columns.first_name', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (5, '*', 'admin', 'admin-user.columns.last_name', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (6, '*', 'admin', 'admin-user.columns.email', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (7, '*', 'admin', 'admin-user.columns.password', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (8, '*', 'admin', 'admin-user.columns.password_repeat', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (9, '*', 'admin', 'admin-user.columns.activated', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (10, '*', 'admin', 'admin-user.columns.forbidden', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (11, '*', 'admin', 'admin-user.columns.language', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (12, 'brackets/admin-ui', 'admin', 'forms.select_an_option', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (13, '*', 'admin', 'admin-user.columns.roles', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (14, 'brackets/admin-ui', 'admin', 'forms.select_options', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (15, '*', 'admin', 'admin-user.actions.create', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (16, 'brackets/admin-ui', 'admin', 'btn.save', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (17, '*', 'admin', 'admin-user.actions.edit', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (18, '*', 'admin', 'admin-user.actions.index', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (19, 'brackets/admin-ui', 'admin', 'placeholder.search', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (20, 'brackets/admin-ui', 'admin', 'btn.search', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (21, '*', 'admin', 'admin-user.columns.id', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (22, 'brackets/admin-ui', 'admin', 'btn.edit', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (23, 'brackets/admin-ui', 'admin', 'btn.delete', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (24, 'brackets/admin-ui', 'admin', 'pagination.overview', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (25, 'brackets/admin-ui', 'admin', 'index.no_items', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (26, 'brackets/admin-ui', 'admin', 'index.try_changing_items', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (27, 'brackets/admin-ui', 'admin', 'btn.new', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (28, 'brackets/admin-ui', 'admin', 'profile_dropdown.account', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (29, 'brackets/admin-auth', 'admin', 'profile_dropdown.logout', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (30, 'brackets/admin-ui', 'admin', 'sidebar.content', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (31, 'brackets/admin-ui', 'admin', 'sidebar.settings', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (32, '*', 'admin', 'admin-user.actions.edit_password', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (33, '*', 'admin', 'admin-user.actions.edit_profile', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (34, 'brackets/admin-auth', 'activations', 'email.line', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (35, 'brackets/admin-auth', 'activations', 'email.action', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (36, 'brackets/admin-auth', 'activations', 'email.notRequested', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (37, 'brackets/admin-auth', 'admin', 'activations.activated', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (38, 'brackets/admin-auth', 'admin', 'activations.invalid_request', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (39, 'brackets/admin-auth', 'admin', 'activations.disabled', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (40, 'brackets/admin-auth', 'admin', 'activations.sent', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (41, 'brackets/admin-auth', 'admin', 'passwords.sent', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (42, 'brackets/admin-auth', 'admin', 'passwords.reset', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (43, 'brackets/admin-auth', 'admin', 'passwords.invalid_token', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (44, 'brackets/admin-auth', 'admin', 'passwords.invalid_user', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (45, 'brackets/admin-auth', 'admin', 'passwords.invalid_password', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (46, 'brackets/admin-auth', 'admin', 'activation_form.title', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (47, 'brackets/admin-auth', 'admin', 'activation_form.note', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (48, 'brackets/admin-auth', 'admin', 'auth_global.email', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (49, 'brackets/admin-auth', 'admin', 'activation_form.button', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (50, 'brackets/admin-auth', 'admin', 'login.title', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (51, 'brackets/admin-auth', 'admin', 'login.sign_in_text', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (52, 'brackets/admin-auth', 'admin', 'auth_global.password', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (53, 'brackets/admin-auth', 'admin', 'login.button', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (54, 'brackets/admin-auth', 'admin', 'login.forgot_password', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (55, 'brackets/admin-auth', 'admin', 'forgot_password.title', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (56, 'brackets/admin-auth', 'admin', 'forgot_password.note', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (57, 'brackets/admin-auth', 'admin', 'forgot_password.button', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (58, 'brackets/admin-auth', 'admin', 'password_reset.title', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (59, 'brackets/admin-auth', 'admin', 'password_reset.note', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (60, 'brackets/admin-auth', 'admin', 'auth_global.password_confirm', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (61, 'brackets/admin-auth', 'admin', 'password_reset.button', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (62, '*', '*', 'Manage access', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (63, '*', '*', 'Translations', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (64, '*', '*', 'Configuration', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (65, '*', '*', 'Login', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (66, '*', '*', 'Username', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (67, '*', '*', 'Password', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (68, '*', '*', 'Remember Me', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (69, '*', '*', 'Forgot Your Password?', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (70, '*', '*', 'Reset Password', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (71, '*', '*', 'E-Mail Address', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (72, '*', '*', 'Send Password Reset Link', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (73, '*', '*', 'Confirm Password', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (74, '*', '*', 'Register', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (75, '*', '*', 'Name', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (76, '*', '*', 'Verify Your Email Address', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (77, '*', '*', 'A fresh verification link has been sent to your email address.', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (78, '*', '*', 'Before proceeding, please check your email for a verification link.', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (79, '*', '*', 'If you did not receive the email', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (80, '*', '*', 'click here to request another', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (81, '*', '*', 'Toggle navigation', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";
$queries[] = "INSERT INTO mondegc.translations (id, namespace, `group`, `key`, text, metadata, created_at, updated_at, deleted_at) VALUES (82, '*', '*', 'Logout', '[]', null, '2020-06-13 11:46:46', '2020-06-13 11:46:46', null)";

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}


/*************************
 *                       *
 *    CRÉATION DE VUES   *
 *                       *
 *************************/

/* Création de vues liées aux ressources Tempérance */

$queries = array();

$queries[] = /** @lang SQL */
    <<<'TAG'
CREATE VIEW `temperance_ville` AS SELECT ch_vil_ID as id, ch_vil_paysID as pays_id, ch_vil_nom as nom,

       (COALESCE((SELECT SUM(ch_inf_off_budget) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         WHERE infrastructures.ch_inf_villeid = ch_vil_ID
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_budget) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         WHERE ch_pat_villeID = ch_vil_ID), 0))
       as budget,

       (COALESCE((SELECT SUM(ch_inf_off_Agriculture) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         WHERE infrastructures.ch_inf_villeid = ch_vil_ID
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_agriculture) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         WHERE ch_pat_villeID = ch_vil_ID), 0))
       as agriculture,

       (COALESCE((SELECT SUM(ch_inf_off_Commerce) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         WHERE infrastructures.ch_inf_villeid = ch_vil_ID
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_commerce) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         WHERE ch_pat_villeID = ch_vil_ID), 0))
       as commerce,

       (COALESCE((SELECT SUM(ch_inf_off_Education) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         WHERE infrastructures.ch_inf_villeid = ch_vil_ID
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_education) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         WHERE ch_pat_villeID = ch_vil_ID), 0))
       as education,

       (COALESCE((SELECT SUM(ch_inf_off_Environnement) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         WHERE infrastructures.ch_inf_villeid = ch_vil_ID
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_environnement) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         WHERE ch_pat_villeID = ch_vil_ID), 0))
       as environnement,

       (COALESCE((SELECT SUM(ch_inf_off_Industrie) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         WHERE infrastructures.ch_inf_villeid = ch_vil_ID
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_industrie) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         WHERE ch_pat_villeID = ch_vil_ID), 0))
       as industrie,

       (COALESCE((SELECT SUM(ch_inf_off_Recherche) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         WHERE infrastructures.ch_inf_villeid = ch_vil_ID
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_recherche) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         WHERE ch_pat_villeID = ch_vil_ID), 0))
       as recherche,

       (COALESCE((SELECT SUM(ch_inf_off_Tourisme) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         WHERE infrastructures.ch_inf_villeid = ch_vil_ID
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_tourisme) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         WHERE ch_pat_villeID = ch_vil_ID), 0))
       as tourisme

FROM villes
TAG;

$queries[] = /** @lang SQL */
    <<<TAG
CREATE VIEW `temperance_pays` AS SELECT ch_pay_id AS id, ch_pay_nom AS nom,

       (COALESCE((SELECT SUM(ch_inf_off_budget) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         INNER JOIN villes ON infrastructures.ch_inf_villeid = villes.ch_vil_ID
         WHERE villes.ch_vil_paysID = ch_pay_id
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_budget) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         INNER JOIN villes ON ch_vil_ID = ch_pat_villeID
         WHERE villes.ch_vil_paysID = ch_pay_id), 0))
       + ch_pay_budget_carte as budget,

       (COALESCE((SELECT SUM(ch_inf_off_Agriculture) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         INNER JOIN villes ON infrastructures.ch_inf_villeid = villes.ch_vil_ID
         WHERE villes.ch_vil_paysID = ch_pay_id
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_agriculture) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         INNER JOIN villes ON ch_vil_ID = ch_pat_villeID
         WHERE villes.ch_vil_paysID = ch_pay_id), 0))
       + ch_pay_agriculture_carte as agriculture,

       (COALESCE((SELECT SUM(ch_inf_off_Commerce) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         INNER JOIN villes ON infrastructures.ch_inf_villeid = villes.ch_vil_ID
         WHERE villes.ch_vil_paysID = ch_pay_id
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_commerce) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         INNER JOIN villes ON ch_vil_ID = ch_pat_villeID
         WHERE villes.ch_vil_paysID = ch_pay_id), 0))
       + ch_pay_commerce_carte as commerce,

       (COALESCE((SELECT SUM(ch_inf_off_Education) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         INNER JOIN villes ON infrastructures.ch_inf_villeid = villes.ch_vil_ID
         WHERE villes.ch_vil_paysID = ch_pay_id
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_education) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         INNER JOIN villes ON ch_vil_ID = ch_pat_villeID
         WHERE villes.ch_vil_paysID = ch_pay_id), 0))
       + ch_pay_education_carte as education,

       (COALESCE((SELECT SUM(ch_inf_off_Environnement) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         INNER JOIN villes ON infrastructures.ch_inf_villeid = villes.ch_vil_ID
         WHERE villes.ch_vil_paysID = ch_pay_id
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_environnement) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         INNER JOIN villes ON ch_vil_ID = ch_pat_villeID
         WHERE villes.ch_vil_paysID = ch_pay_id), 0))
       + ch_pay_environnement_carte as environnement,

       (COALESCE((SELECT SUM(ch_inf_off_Industrie) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         INNER JOIN villes ON infrastructures.ch_inf_villeid = villes.ch_vil_ID
         WHERE villes.ch_vil_paysID = ch_pay_id
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_industrie) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         INNER JOIN villes ON ch_vil_ID = ch_pat_villeID
         WHERE villes.ch_vil_paysID = ch_pay_id), 0))
       + ch_pay_industrie_carte as industrie,

       (COALESCE((SELECT SUM(ch_inf_off_Recherche) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         INNER JOIN villes ON infrastructures.ch_inf_villeid = villes.ch_vil_ID
         WHERE villes.ch_vil_paysID = ch_pay_id
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_recherche) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         INNER JOIN villes ON ch_vil_ID = ch_pat_villeID
         WHERE villes.ch_vil_paysID = ch_pay_id), 0))
       + ch_pay_recherche_carte as recherche,

       (COALESCE((SELECT SUM(ch_inf_off_Tourisme) FROM infrastructures
         INNER JOIN infrastructures_officielles ON
           infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
         INNER JOIN villes ON infrastructures.ch_inf_villeid = villes.ch_vil_ID
         WHERE villes.ch_vil_paysID = ch_pay_id
           AND ch_inf_statut = 2
         ), 0)) +
       (COALESCE((SELECT SUM(ch_mon_cat_tourisme) FROM monument_categories
         INNER JOIN dispatch_mon_cat
             ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
         INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id
         INNER JOIN villes ON ch_vil_ID = ch_pat_villeID
         WHERE villes.ch_vil_paysID = ch_pay_id), 0))
       + ch_pay_tourisme_carte as tourisme

FROM pays;
TAG;

$queries[] = /** @lang SQL */
    <<<TAG
CREATE VIEW `temperance_organisation` AS SELECT o.id, o.name,
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
GROUP BY o.id;
TAG;

// Exécuter la requête
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}