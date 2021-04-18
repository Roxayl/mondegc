<?php

$migration = new \App\Services\LegacyMigrationService();

$migration->addQuery("ALTER TABLE `monument_categories` ADD `bg_image_url` VARCHAR(191) NULL AFTER `ch_mon_cat_budget`;");

$queries = explode("\r\n", /** @lang MySQL */
    "INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (32, 'mon_cat', 1, '2021-03-01 00:07:50', '2021-03-13 18:49:28', 6, ' Présenter au moins 3 parcs', 'La ville s''est faite validée au moins trois infrastructures Parcs urbains.', 'https://romukulot.fr/kaleera/images/yAYrt.png', '22a', 0, 0, 0, 0, 0, 100, 0, 50, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (33, 'mon_cat', 1, '2021-03-03 21:30:42', '2021-03-13 18:49:40', 6, ' Avoir une balance Environnement positive', 'La ville dispose dans sa balance Tempérance d''un montant égal ou supérieur à 0 pour la ressource Environnement.', 'https://romukulot.fr/kaleera/images/rcTuE.png', '22b', null, null, null, null, null, 50, null, null, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (34, 'mon_cat', 1, '2021-03-03 21:32:13', '2021-03-13 18:55:07', 11, 'VILLE VERTE', 'La ville a rempli tous les objectifs de la quête Ville verte, félicitations !', 'https://romukulot.fr/kaleera/images/m2F4n.png', '22z', 0, 0, 0, 0, 0, 250, 0, 100, 'https://romukulot.fr/kaleera/images/xA3m9.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (35, 'mon_cat', 2, '2021-03-03 21:33:21', '2021-03-13 16:12:50', 4, 'Présenter sa première zone pétrolière', 'Le pays dispose d''un moins une raffinerie de taille importante, reconnue dans Tempérance comme infrastructure bâtie.', 'https://romukulot.fr/kaleera/images/kzehp.png', '311a', 20, null, null, null, null, null, null, null, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (36, 'mon_cat', 0, '2021-03-04 09:53:51', '2021-04-09 20:56:36', 10, 'MULTINATIONALE INCONTOURNABLE', 'Cette entreprise a validé au moins 80% des objectifs de la quête Entreprise, félicitations !', 'https://i11.servimg.com/u/f11/18/33/87/18/0z_ico11.png', '999', 0, 100, 0, 0, 0, 0, 0, 150, 'https://i11.servimg.com/u/f11/18/33/87/18/z_lum_10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (37, 'mon_cat', 0, '2021-03-04 10:17:02', '2021-04-09 20:59:35', 10, 'Présenter le siège social', 'L''entreprise a présenté les locaux dans lesquels se trouvent sa direction centrale.', 'https://i11.servimg.com/u/f11/18/33/87/18/02_ico10.png', '201', 0, 100, 0, 0, 0, 0, 0, 1000, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (38, 'mon_cat', 2, '2021-03-04 15:57:30', '2021-03-13 15:53:23', 3, 'Test', 'Lolilol, je teste les méta catég', 'https://s2.qwant.com/thumbr/0x0/6/d/1da118e2fd1a0276c7b97a72143b110422eb38cb448f7f35030e9e15698d53/chap1_3.png?u=https%3A%2F%2Fwww.editions-eni.fr%2FOpen%2Fdownload%2F900f308d-e550-4eaf-acf0-4874eef79f5b%2Fimages%2Fchap1_3.png&q=0&b=1&p=0&a=1', '1', null, null, null, null, null, null, null, null, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (39, 'mon_cat', 0, '2021-03-04 16:53:53', '2021-04-09 09:19:54', 10, 'Relier à un sujet sur le forum', 'L''entreprise dispose d''un sujet attitré sur le forum de Génération City, accessible par un lien ajouté dans la présentation.', 'https://i11.servimg.com/u/f11/18/33/87/18/00a_ic15.png', '0', 5, 50, 0, 0, 0, 0, 0, 500, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (40, 'mon_cat', 1, '2021-03-04 18:22:02', '2021-03-13 18:52:46', 6, 'Avoir une balance en environnement d''au moins +500', 'La ville dispose d''une balance en environnement supérieure ou égale à 500 points en environnement.', 'https://romukulot.fr/kaleera/images/2iePj.png', '22c', 0, 0, 0, 0, 0, 100, 0, 0, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (41, 'mon_cat', 1, '2021-03-13 17:11:21', '2021-03-13 17:25:11', 1, 'Valider 2 monuments', 'La ville dispose d''au moins 2 monuments validés par le Comité Culture.', 'https://romukulot.fr/kaleera/images/7qffj.png', '21a', 0, 0, 0, 10, 10, 5, 5, 50, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (42, 'mon_cat', 1, '2021-03-13 17:25:48', '2021-03-13 17:27:20', 2, 'Valider 5 monuments', 'La ville dispose d''au moins 5 monuments validés par le Comité Culture.', 'https://romukulot.fr/kaleera/images/xqz9V.png', '21b', 0, 5, 0, 20, 20, 0, 10, 50, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (43, 'mon_cat', 1, '2021-03-13 17:27:51', '2021-03-13 20:21:47', 9, 'VILLE HISTORIQUE', 'La ville a rempli tous les objectifs de la quête Ville historique, félicitations !', 'https://romukulot.fr/kaleera/images/9gci7.png', '21z', 5, 100, 0, 200, 200, 50, 100, 1000, 'https://romukulot.fr/kaleera/images/jPEvr.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (44, 'mon_cat', 0, '2021-03-25 21:00:50', '2021-04-09 11:06:12', 12, 'Agriculture et agroalimentaire', 'Cette entreprise a une activité dans des secteurs liés à l''agriculture, à l''élevage, à la chasse, à la sylviculture et exploitation forestière, ou encore à la pêche et l''aquaculture ainsi qu''à la fabrication de produits alimentaires, de boissons ou de tabac.', 'https://i11.servimg.com/u/f11/18/33/87/18/01a_ic13.png', '104', 250, 100, 500, 0, 0, 0, 0, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/01a_fo12.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (45, 'mon_cat', 0, '2021-03-25 21:27:04', '2021-04-09 11:06:25', 7, 'Activités extractives', 'Cette entreprise a une activité dans des secteurs liés à l''extraction de charbon, de pétrole brut et de gaz naturel, de minerais métalliques ou de toute autre activité extractive ainsi que la cokéfaction et la fabrication de produits pétroliers raffinés ou d''autres produits chimiques.', 'https://i11.servimg.com/u/f11/18/33/87/18/01b_ic11.png', '101', 600, 250, 0, 0, 0, -100, 0, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/01b_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (46, 'mon_cat', 0, '2021-03-25 21:42:20', '2021-04-09 09:14:48', 6, 'Relier à une page sur le WikiGC', 'L''entreprise dispose d''un sujet attitré sur le forum de Génération City, accessible par un lien ajouté dans la présentation.', 'https://i11.servimg.com/u/f11/18/33/87/18/00a_ic15.png', '0', 5, 50, 0, 0, 0, 0, 0, 500, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (47, 'mon_cat', 0, '2021-03-25 22:33:28', '2021-04-09 20:59:54', 4, 'Présenter une personnalité liée à l''entreprise', 'Un personnage RP relié à l''entreprise de manière directe dispose de sa propre page wiki.', 'https://i11.servimg.com/u/f11/18/33/87/18/02_ico10.png', '202', 0, 50, 0, 0, 10, 0, 10, 500, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (48, 'mon_cat', 0, '2021-03-25 22:36:45', '2021-04-09 20:56:19', 2, 'Être présent dans au moins 2 pays', 'L''entreprise dispose d''au moins deux RP concernant deux pays étrangers différents du Monde GC dans lesquels l''entreprise a une activité.', 'https://i11.servimg.com/u/f11/18/33/87/18/04_ico10.png', '401', 0, 250, 0, 0, 0, 0, 0, 2500, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (49, 'mon_cat', 0, '2021-04-09 04:55:54', '2021-04-09 09:33:02', 2, 'Activités financières et d''assurance', 'Cette entreprise a une activité dans des secteurs liés aux services financiers, aux caisses de retraites et aux assurances.', 'https://i11.servimg.com/u/f11/18/33/87/18/01b_ic12.png', '102', 0, 200, 0, 0, 50, 0, 250, 300, 'https://i11.servimg.com/u/f11/18/33/87/18/01b_fo11.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (50, 'mon_cat', 0, '2021-04-09 06:12:19', '2021-04-11 05:05:21', 5, 'Activités professionnelles et techniques', 'Cette entreprise a une activité dans des secteurs liés aux affaires juridiques et comptables, aux services administratifs, aux cabinets d’architecture et d’ingénierie, à la publicité et études de marché, ainsi qu''aux services vétérinaires.', 'https://i11.servimg.com/u/f11/18/33/87/18/01c_ic10.png', '103', 50, 250, 0, 0, 150, 0, 300, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/01c_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (51, 'mon_cat', 0, '2021-04-09 06:14:26', '2021-04-09 11:06:01', 2, 'Hébergement, restauration et tourisme', 'Cette entreprise a une activité dans des secteurs liés à l''hébergement, aux services de restauration et de consommation de boissons, à la location de loisirs, aux agences de voyages, voyagistes et services de réservation.', 'https://i11.servimg.com/u/f11/18/33/87/18/01h_ic10.png', '108', 0, 200, 0, 400, 0, 0, 150, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/01h_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (52, 'mon_cat', 0, '2021-04-09 06:28:30', '2021-04-09 09:32:10', 4, 'Commerce de gros et de détail', 'Cette entreprise a une activité dans des secteurs liés au commerce de gros et de détail, en magasin spécialisé ou en grossiste, ainsi qu''en réparation de véhicules automobiles et de motocycles.', 'https://i11.servimg.com/u/f11/18/33/87/18/01e_ic11.png', '105', 200, 500, 0, 0, 50, 0, 0, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/01e_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (53, 'mon_cat', 0, '2021-04-11 01:12:15', '2021-04-11 01:23:01', 1, 'Construction et immobilier', 'Cette entreprise a une activité dans des secteurs liés à la construction de bâtiments, au génie civil, ainsi qu''aux activités immobilières.', 'https://i11.servimg.com/u/f11/18/33/87/18/106_ic10.png', '106', 300, 300, 0, 0, 100, -50, 100, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/106_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (54, 'mon_cat', 0, '2021-04-11 01:27:03', '2021-04-11 01:33:09', 1, 'Énergies et eaux', 'Cette entreprise a une activité dans des secteurs liés à la production et distribution d’électricité, de gaz, de vapeur et climatisation, ainsi qu''à la collecte, distribution et traitement des eaux, aux réseau d’assainissement, à la collecte, remise en état et autres services de traitement des déchets, d’évacuation et à la récupération des matières.', 'https://i11.servimg.com/u/f11/18/33/87/18/107_ic10.png', '107', 400, 250, 0, 0, 150, -50, 0, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/107_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (55, 'mon_cat', 0, '2021-04-11 01:33:50', '2021-04-11 05:15:07', 2, 'Industies lourdes et manufacturières', 'Cette entreprise a une activité dans des secteurs liés à la fabrication de produits métallurgiques, d’ouvrages en métaux, de produits chimiques, ainsi qu''à la fabrication de textiles, de papier et de meubles.', 'https://i11.servimg.com/u/f11/18/33/87/18/109_ic10.png', '109', 600, 250, 0, 0, 0, -100, 0, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/109_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (56, 'mon_cat', 0, '2021-04-11 01:47:48', '2021-04-11 01:54:42', 1, 'Industries culturelles et éducation', 'Cette entreprise a une activité dans des secteurs liés à l''édition, à la production de films cinématographiques et vidéo, de programmes de télévision, d’enregistrements sonores et d’édition musicale, aux activités créatives, arts et spectacles, bibliothèques, archives, musées et autres activités culturelles, aux jeux de hasard et de pari, aux sports ainsi qu''à l''éducation.', 'https://i11.servimg.com/u/f11/18/33/87/18/110_ic10.png', '110', 0, 0, 0, 250, 0, 0, 500, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/110_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (57, 'mon_cat', 0, '2021-04-11 01:55:38', '2021-04-11 02:02:19', 1, 'Machines et électronique', 'Cette entreprise a une activité dans des secteurs liés à la fabrication de machines et matériels, dont ceux électriques comme des ordinateurs, des articles électroniques et optiques, ainsi que la réparation et installation de machines et de matériel.', 'https://i11.servimg.com/u/f11/18/33/87/18/111_ic10.png', '111', 200, 150, 0, 0, 350, -100, 150, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/111_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (58, 'mon_cat', 0, '2021-04-11 04:39:07', '2021-04-11 04:39:18', 1, 'Santé et industrie pharmaceutique', 'Cette entreprise a une activité dans des secteurs liés aux activités relatives à la santé, à l''action sociale, ainsi qu''à la fabrication de préparations pharmaceutiques, de produits chimiques à usage médicinal et de produits d’herboristerie.', 'https://i11.servimg.com/u/f11/18/33/87/18/112_ic10.png', '112', 100, 100, 0, 0, 350, 0, 200, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/112_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (59, 'mon_cat', 0, '2021-04-11 04:41:05', '2021-04-11 05:04:24', 1, 'Sécurité et défense', 'Cette entreprise a une activité dans des secteurs liés à la défense militaire et paramilitaire, au maintien de l’ordre et de la sécurité publics, ainsi qu''aux activités d’enquêtes et de sécurité.', 'https://i11.servimg.com/u/f11/18/33/87/18/113_ic10.png', '113', 400, 200, 0, 0, 100, -50, 100, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/113_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (60, 'mon_cat', 0, '2021-04-11 05:05:37', '2021-04-11 05:09:54', 2, 'Télécommunications et services d''information', 'Cette entreprise a une activité dans des secteurs liés aux télécommunications, à la programmation informatique, aux conseils et activités connexes, ainsi qu''aux activités de services d’information.', 'https://i11.servimg.com/u/f11/18/33/87/18/114_ic10.png', '114', 50, 250, 0, 0, 350, -50, 150, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/114_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (61, 'mon_cat', 0, '2021-04-11 05:09:57', '2021-04-11 05:18:57', 3, 'Transports de passagers et de marchandises', 'Cette entreprise a une activité dans des secteurs liés aux transports terrestres, par eau, aérien, ainsi qu''aux activités de poste et de courrier, de magasinage et activités annexes des transports, ainsi qu''à la construction de véhicules, de remorques et semi-remorques.', 'https://i11.servimg.com/u/f11/18/33/87/18/115_ic10.png', '115', 200, 600, 0, 0, 50, -100, 0, 50, 'https://i11.servimg.com/u/f11/18/33/87/18/115_fo10.jpg');
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (62, 'mon_cat', 0, '2021-04-11 19:08:02', '2021-04-11 19:13:12', 2, 'Relier au Patrimoine', 'L''entreprise est impliquée dans l''histoire et/ou le rayonnement d''un élément culturel reconnu par le Comité Culture comme Patrimoine.', 'https://i11.servimg.com/u/f11/18/33/87/18/212_ic10.png', '212', 0, 25, 0, 10, 0, 0, 50, 500, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (63, 'mon_cat', 0, '2021-04-11 19:09:57', '2021-04-11 20:09:54', 2, 'Relier au Patrimoine mondial', 'L''entreprise est impliquée dans l''histoire et/ou le rayonnement d''un élément culturel reconnu par le C. Culture comme Patrimoine mondial.', 'https://i11.servimg.com/u/f11/18/33/87/18/213_ic10.png', '212b', 0, 50, 0, 20, 0, 0, 100, 1000, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (64, 'mon_cat', 0, '2021-04-11 19:39:56', '2021-04-11 20:31:06', 4, 'Relier à une invention mondiale', 'L''entreprise est impliquée dans la création et/ou le développement d''un élément reconnu par le Comité Histoire comme Invention mondiale.', 'https://i11.servimg.com/u/f11/18/33/87/18/212b_i10.png', '213b', 0, 20, 0, 0, 100, 0, 50, 1000, null);
INSERT INTO monument_categories (ch_mon_cat_ID, ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur, ch_mon_cat_industrie, ch_mon_cat_commerce, ch_mon_cat_agriculture, ch_mon_cat_tourisme, ch_mon_cat_recherche, ch_mon_cat_environnement, ch_mon_cat_education, ch_mon_cat_budget, bg_image_url) VALUES (65, 'mon_cat', 0, '2021-04-11 20:30:35', '2021-04-11 20:31:25', 1, 'Relier à une invention', 'L''entreprise est impliquée dans la création et/ou le développement d''un élément reconnu par le Comité Histoire comme Invention.', 'https://i11.servimg.com/u/f11/18/33/87/18/213_ic11.png', '213a', 0, 10, 0, 0, 50, 0, 25, 500, null);");

foreach($queries as $query) {
    $migration->addQuery($query);
}

$migration->run();
