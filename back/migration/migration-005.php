<?php

/* *******************
 * Script de migration
 * Version cible : 2.2
 * ******************/

require_once('../../php/init/legacy_init.php');

mysql_select_db($database_maconnexion, $maconnexion);

$queries = array();


/*************************
 *                       *
 *   ÉDITION DE TABLES   *
 *                       *
 *************************/

// infrastructures
$queries[] = "alter table infrastructures
	add nom_infra varchar(191) not null after ch_inf_statut";
$queries[] = "alter table infrastructures
	add lien_wiki varchar(250) null after ch_inf_lien_forum;
";
$queries[] = "alter table infrastructures alter column nom_infra set default ''";

// infrastructures_groupes
$queries[] = "alter table infrastructures_groupes
	add url_image varchar(191) not null";
$queries[] = "alter table infrastructures_groupes
	add `order` int default 1 not null";
$queries[] = "alter table infrastructures_groupes
	add created datetime not null";

// Table pages
$queries[] = "create table if not exists pages
(
  id       int auto_increment
    primary key,
  this_id  varchar(50) null,
  content  text        null,
  modified datetime    null,
  constraint pages_this_id_uindex
    unique (this_id)
);";

// Exécuter cette première série de requêtes
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}


/*************************
 *                       *
 *    INFRASTRUCTURES    *
 *                       *
 *************************/

$queries = array();

// Définir le nom par défaut des infrastructures
$queries[] = "UPDATE infrastructures inf SET nom_infra = (
                  SELECT ch_inf_off_nom FROM infrastructures_officielles
                  WHERE inf.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
                )";

// Vider infrastructures_officielles_groupes
$queries[] = "TRUNCATE infrastructures_officielles_groupes";

// Nouveaux groupes d'infrastructures
$queries[] = 'UPDATE infrastructures_groupes SET created = NOW()';
$queries[] = "UPDATE infrastructures_groupes SET nom_groupe = 'Infra bâtie' WHERE id = 1";
$queries[] = "UPDATE infrastructures_groupes SET nom_groupe = 'Infra RP' WHERE id = 2";
$queries[] = "UPDATE infrastructures_groupes SET nom_groupe = 'Labels' WHERE id = 3";
$queries[] = "UPDATE infrastructures_groupes SET nom_groupe = 'Autres' WHERE id = 4";
$queries[] = "DELETE FROM infrastructures_groupes WHERE id >= 5";

// Insérer dans infrastructures_officielles_groupes
$i = 3;
while($i <= 65) {
    if($i !== 4 || $i !== 57)
        $queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
                    VALUES(1, $i)";
    $i++;
}

$queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
              VALUES(3, 67)";
$queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
              VALUES(2, 68)";
$queries[] = "DELETE FROM infrastructures_officielles WHERE ch_inf_off_id = 69";
$queries[] = "DELETE FROM infrastructures WHERE ch_inf_off_id = 69";

$i = 70;
while($i <= 73) {
    $queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
                  VALUES(1, $i)";
    $i++;
}

$i = 74;
while($i <= 80) {
    if($i !== 79)
        $queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
                      VALUES(3, $i)";
    $i++;
}

$i = 81;
while($i <= 84) {
    $queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
                  VALUES(4, $i)";
    $i++;
}

$i = 85;
while($i <= 86) {
    $queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
                  VALUES(3, $i)";
    $i++;
}

$i = 87;
while($i <= 95) {
    $queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
                  VALUES(4, $i)";
    $i++;
}

$queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
              VALUES(1, 96)";
$queries[] = "DELETE FROM infrastructures_officielles WHERE ch_inf_off_id = 98";
$queries[] = "DELETE FROM infrastructures WHERE ch_inf_off_id = 98";
$queries[] = "DELETE FROM infrastructures_officielles WHERE ch_inf_off_id = 99";
$queries[] = "DELETE FROM infrastructures WHERE ch_inf_off_id = 99";
$queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
              VALUES(1, 100)";
$queries[] = "DELETE FROM infrastructures_officielles WHERE ch_inf_off_id = 101";
$queries[] = "DELETE FROM infrastructures WHERE ch_inf_off_id = 101";
$queries[] = "DELETE FROM infrastructures_officielles WHERE ch_inf_off_id = 102";
$queries[] = "DELETE FROM infrastructures WHERE ch_inf_off_id = 102";
$queries[] = "DELETE FROM infrastructures_officielles WHERE ch_inf_off_id = 103";
$queries[] = "DELETE FROM infrastructures WHERE ch_inf_off_id = 103";

$i = 104;
while($i <= 128) {
    $queries[] = "INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle)
                  VALUES(2, $i)";
    $i++;
}

// Exécuter requêtes infra
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}


/*************************
 *                       *
 *         PAGES         *
 *                       *
 *************************/

$queries = array();

$queries[] = "INSERT INTO pages (id, this_id, content, modified) VALUES (1, 'participer', '', '2020-03-03 18:31:41')";
$queries[] = "INSERT INTO pages (id, this_id, content, modified) VALUES (2, 'participer_cadre', '', '2020-03-03 18:31:51')";

// Exécuter requêtes pages
foreach($queries as $query) {
    mysql_query($query) or die(mysql_error());
}

// Mettre à jour les contenus de pages
$participer_content = /** @lang html */
    <<<EOL

  <div class="titre-vert anchor" id="faq">
    <h1>Foire aux questions</h1>
  </div>
  <div class="well">
    <h3>Le Monde de GC qu'est ce que c'est ?</h3>
    <p> Il s'agit d'un projet cr&eacute;&eacute; par les membres du forum de <a href="http://www.forum-gc.com/">G&eacute;n&eacute;ration City</a>. Ce forum r&eacute;unit des amateurs de jeux de simulation de construction de villes comme <a href="http://www.simcity.com/fr_FR" target="_blank" rel="nofollow">Sim City</a> ou <a href="http://www2.citiesxl.com/?lang=fr" target="_blank" rel="nofollow">Cities XL</a>.  Apr&egrave;s avoir construit plusieurs villes et les avoir pr&eacute;sent&eacute;es sur le  forum, certains joueurs ont eu envie de placer leurs cit&eacute;s dans une  entit&eacute; plus grande afin de donner de la coh&eacute;rence entre toutes les  villes qu'ils avaient construites. C'est pour cette raison qu'il ont  commenc&eacute;s &agrave; imaginer des pays r&eacute;unissant les villes qu'ils avaient cr&eacute;es  dans ces diff&eacute;rents jeux.    </p>
    <p> Des sujets consacr&eacute;s &agrave; ces &quot;pays&quot; se  sont multipli&eacute;s sur le forum et finalement une partie leur a &eacute;t&eacute;  consacr&eacute;e. Cette partie s'est d'abord intitul&eacute;e &quot;Etats-Unis de  G&eacute;n&eacute;ration City&quot; puis &quot;Monde de G&eacute;n&eacute;ration City&quot;. Dans le m&ecirc;me temps,  les joueurs ont continu&eacute;s &agrave; alimenter la page consacr&eacute;e &agrave; leurs pays et  ce sont imagin&eacute;s des personnages de dirigeants, ont cr&eacute;&eacute;s des  &quot;alliances&quot;, des &eacute;v&eacute;nements mondiaux... La volont&eacute; &agrave; toujours &eacute;t&eacute;  d'accroitre les &eacute;changes, d'animer le forum et de stimuler sa cr&eacute;ativit&eacute;  pour construire de nouvelles villes.    </p>
    <p> Le projet a pris une nouvelle dimension sous l'impulsion de <em><a href="http://monde.generation-city.com/page-pays.php?ch_pay_id=33#diplomatie" target="_blank" rel="nofollow">Jeff10</a></em> et <em><a href="http://monde.generation-city.com/page-pays.php?ch_pay_id=30#diplomatie" target="_blank" rel="nofollow">Clamato</a></em> avec la mise en place de premi&egrave;res r&egrave;gles et la cr&eacute;ation d'une <a href="http://www.forum-gc.com/t1934-cartes-officielles-de-gecee-v-30">Carte du Monde de G&eacute;n&eacute;ration City</a>.  Des continents ont &eacute;t&eacute; trac&eacute; et d&eacute;coup&eacute;s en plusieurs territoires.  Chaque joueur pouvait placer son pays imaginaire sur cette carte et lui  donner encore plus de r&eacute;alit&eacute;. Dans le m&ecirc;me temps, des r&egrave;gles ont &eacute;t&eacute;  cr&eacute;&eacute;es pour organiser le placement des pays sur la carte avec deux  grands principes : </p>
    <ul>
      <li>Pour demander un &quot;territoire&quot;, il faut avoir d&eacute;j&agrave; pr&eacute;sent&eacute; au moins 2 villes sur le forum. <br />
      </li>
      <li>Chaque pays doit conserver les fronti&egrave;res pr&eacute;alablement dessin&eacute;es sur la carte.    </li>
    </ul>
    <p> Enfin,  une instance de &quot;r&eacute;gulation&quot; a &eacute;t&eacute; cr&eacute;&eacute;e : le Haut Conseil. Il s'agit  d'un groupe de 5 membres dont la mission est d'animer la partie  consacr&eacute;e au monde GC sur le forum, mettre &agrave; jour la carte et arbitrer  d'&eacute;ventuels conflits entre les participants.    </p>
    <p> Le &quot;Monde de G&eacute;n&eacute;ration City&quot; est alors devenu de plus en plus d&eacute;taill&eacute; et s'est transform&eacute; en v&eacute;ritable &quot;<a href="http://www.jeuxonline.info/lexique/mot/Roleplay" target="_blank" rel="nofollow">Roleplay</a>&quot;.  Les membres du forum qui participent se sont de plus en plus pris au  jeu de leur personnage dirigeant de pays. Ils se sont r&eacute;unis en  diff&eacute;rents &quot;blocs&quot; inspir&eacute;s de l'histoire du monde r&eacute;el (communiste -  capitaliste - &quot;pays non-align&eacute;s&quot;). Certains ont imagin&eacute;s ensemble de  v&eacute;ritables &quot;sc&eacute;narios&quot; qui &eacute;voluaient en fonction des r&eacute;actions des  autres dirigeants. L'un des sc&eacute;narios les plus r&eacute;ussit fut celui  intitul&eacute; &quot;<em>la crise de Tamya</em>&quot;. </p>
    <p> Malheureusement, il s'est  av&eacute;r&eacute; de plus en plus difficile de donner de la coh&eacute;rence &agrave; un tel  projet. Le roleplay entre les pays &eacute;voluait rapidement avec des nouveaux  joueurs, de nouvelles villes, des changements de noms dans les pays...  Le suivit de ces changements sur la carte mondiale demandait &eacute;norm&eacute;ment  de temps et le projet s'est un peu essouffl&eacute;. </p>
    <p> Pour relancer le  projet, il a donc &eacute;t&eacute; d&eacute;cid&eacute; de sortir du forum et de d&eacute;velopper un  outil qui permet aux participants de faire &eacute;voluer ce monde imaginaire  en temps r&eacute;el : <a href="http://monde.generation-city.com/index.php" target="_blank" rel="nofollow">Le site du Monde G&eacute;n&eacute;ration City</a>. La carte &eacute;tant &agrave; la base du projet, nous avons d&eacute;velopp&eacute; une &quot;<a href="http://monde.generation-city.com/Page-carte.php" target="_blank" rel="nofollow">carte collaborative</a>&quot;  ou chacun peut placer les villes qu'il a construite dans les jeux,  nommer son pays, imaginer son personnage. Le principe est que chacun  puisse entrer dans une base de donn&eacute;es ces informations qui sont ensuite  affich&eacute;es sur la carte. La carte est donc mise &agrave; jour en temps r&eacute;el. </p>
    <p>&nbsp;</p>
    <h3>Quel rapport avec G&eacute;n&eacute;ration City et son forum&nbsp;?</h3>
    <p>Le  &quot;Monde de G&eacute;n&eacute;ration City&quot; a &eacute;t&eacute; construit et imagin&eacute;s par les membres  du forum. Il s'agit de l&rsquo;&oelig;uvre collective de notre communaut&eacute;. Nous  avons tous ensemble plac&eacute;s les villes que nous avons cr&eacute;es dans les jeux  sur une carte commune. Beaucoup portent ce projet dans leur c&oelig;ur. Il  repr&eacute;sente des ann&eacute;es d'&eacute;changes, de cr&eacute;ation, d'amusement... C'est la  somme de tout ce que nous avons construit, c'est toute notre histoire.  Le &quot;Monde de G&eacute;n&eacute;ration City&quot; est indissociable du forum. </p>
    <p>&nbsp;</p>
    <h3>Qui fait partie de l'&eacute;quipe du monde de GC&nbsp;?</h3>
    <p> Tous  les membres qui participent collaborent &agrave; la construction de ce monde  imaginaire. Les pays sont r&eacute;unis dans &quot;l'Organisation des Cit&eacute;  G&eacute;C&eacute;ennes&quot; (OCGC), une instance imaginaire inspir&eacute;e de l'ONU. <br />
      Certains  membres font partie du &quot;Haut-Conseil&quot;, le nom du groupe qui s'occupe  d'organiser et d'animer le projet au sein de l'OCGC. Chaque membre du  &quot;Haut-Conseil&quot; s'occupe d'un aspect diff&eacute;rent du Monde GC&nbsp;: </p>
    <ul>
      <li><em><a href="http://monde.generation-city.com/page-pays.php?ch_pay_id=30#diplomatie" target="_blank" rel="nofollow">L'OCGC</a></em> s'occupe de la carte et de ces mises &agrave; jour : il dirige <em><a href="http://monde.generation-city.com/patrimoine.php" target="_blank" rel="nofollow">l'Institut G&eacute;c&eacute;en de G&eacute;ographie</a></em>. <br />
      </li>
      <li><em><a href="http://monde.generation-city.com/page-pays.php?ch_pay_id=40#diplomatie" target="_blank" rel="nofollow">Galyie</a></em> s'occupe de recenser les plus belles constructions des villes qui font  partie du projet. On les appelle des &quot;monuments&quot;. Il a organise des concours pour &eacute;lire les plus beaux monuments. Il dirige <em><a href="http://monde.generation-city.com/geographie.php" target="_blank" rel="nofollow">l'Institut G&eacute;c&eacute;en du Patrimoine</a></em>.<br />
      </li>
      <li><em><a href="http://monde.generation-city.com/page-pays.php?ch_pay_id=31#diplomatie" target="_blank" rel="nofollow">Vinceinovich</a></em> et <em><a href="http://monde.generation-city.com/page-pays.php?ch_pay_id=58#diplomatie" target="_blank" rel="nofollow">d'autres membres</a></em> s'occupent d'&eacute;crire une histoire commune entre les diff&eacute;rents pays. C'est une mission tr&egrave;s difficile. Ils dirigent <em><a href="http://monde.generation-city.com/histoire.php" target="_blank" rel="nofollow">l'Institut G&eacute;c&eacute;en d'histoire</a></em>.<br />
      </li>
      <li><em><a href="http://monde.generation-city.com/page-pays.php?ch_pay_id=39#diplomatie" target="_blank" rel="nofollow">Sakuro</a></em> a mis en place le projet "temp&eacute;rance" destin&eacute; &agrave; r&eacute;guler l'&eacute;conomie. Il dirige <em><a href="http://monde.generation-city.com/economie.php" target="_blank" rel="nofollow">l'Institut Economique</a></em>.<br />
      </li>
      <li><em><a href="http://monde.generation-city.com/page-pays.php?ch_pay_id=29#diplomatie" target="_blank" rel="nofollow">Calimero</a></em> a cr&eacute;&eacute; le site internet avec l'aide de <em><a href="http://monde.generation-city.com/page-pays.php?ch_pay_id=38#diplomatie" target="_blank" rel="nofollow">Youcef et de Sakuro</a></em>.  </li>
    </ul>
    <p>Enfin, pendant quelques ann&eacute;es, il y avait la possibilit&eacute; d'&ecirc;tre  &quot;Haut-Commissaire&quot;. Tous les deux mois, des membres pouvaient proposer un grand projet qui devait &ecirc;tre r&eacute;alis&eacute; en commun. Le meilleur projet  &eacute;tait &eacute;lu et celui qui l'avait propos&eacute; devenait &quot;Haut-commissaire&quot; pour 2 mois avec des avantages comme la mod&eacute;ration de la partie consacr&eacute;e au  Monde GC. Il y a ainsi eu l'organisation <em>d'une exposition  universelle, de la coupe du monde de football, du bureau international  des villes, des City's Awards, de la GC race</em>. Le dernier projet, celui des <em>jeux olympiques</em> propos&eacute; par Vallamir est encore dans les cartons.</p>
    <p>&nbsp;</p>
    <h3>Comment int&eacute;grer le Monde de GC ?</h3>
    <p>Tous les membres du forum peuvent participer. Il y a deux possibilit&eacute;s&nbsp;:</p>
    <ul>
      <li>Soit tu as d&eacute;j&agrave; pr&eacute;sent&eacute; plusieurs villes sur le forum de G&eacute;n&eacute;ration City et tu veux cr&eacute;er un pays : il faut que tu recherches un emplacement libre en consultant <a href="http://monde.generation-city.com/participer.php" target="_blank" rel="nofollow">cette carte</a>. Ensuite, tu envoie un mp &agrave; l'un des membre du <a href="http://monde.generation-city.com/Haut-Conseil.php" target="_blank" rel="nofollow">Haut-Conseil</a> avec le num&eacute;ro d'emplacement que tu as choisit et ton adresse e-mail.  On va te cr&eacute;er un compte sur le nouveau site pour que tu puisse  commencer &agrave; construire ton pays. </li>
      <br />
      <li>Soit tu rejoins un pays  d&eacute;j&agrave; existant et tu y places tes villes. Un pays particulier &agrave; &eacute;t&eacute; mis  en place pour les joueurs qui ne voulaient pas cr&eacute;er leur propre pays  mais appara&icirc;tre dans un pays commun : La RFGC. <em>La R&eacute;publique F&eacute;d&eacute;rale de G&eacute;n&eacute;ration City</em> est le pays &quot;commun&quot; du forum. Il est d&eacute;coup&eacute; en 5 r&eacute;gions. Tu peux  aussi demander &agrave; un dirigeant de pays s'il veut bien accueillir tes  villes. Pour l'instant, le nouveau site ne permet pas d'avoir des  villes dans un pays sans en &ecirc;tre le dirigeant. Mais cela va bient&ocirc;t  changer. Cette possibilit&eacute; sera ajout&eacute;e prochainement et il pourra y  avoir plusieurs joueurs par pays.</li>
      <br />
    </ul>
    <h3>Où puis je trouver les r&egrave;gles ?</h3>
    <p>Tu peux trouver les r&egrave;gles <a href="http://monde.generation-city.com/page-communique.php?com_id=127" target="_blank" rel="nofollow">sur cette page</a>. </p>
    <p><br />
    </p>
    <h3>Que faire lorsque l'on est nouveau dans le Monde GC ?</h3>
    <p> Le  site va te guider pour cr&eacute;er ton pays ou tes villes. Il suffit d'une  bonne dose d'imagination et de cr&eacute;ativit&eacute;. Les formulaires consacr&eacute;s aux  villes te permettent de mettre des images de tes villes cr&eacute;es dans le  jeu. Le plus facile est de commencer par cr&eacute;er les pages qui vont  pr&eacute;senter tes villes pour ensuite imaginer un univers autour de  celles-ci.</p>
    <p>&nbsp;</p>
  </div>
  <div class="titre-vert anchor" id="charte">
    <h1>Charte</h1>
  </div>
  <div class="well">
    <div><a href="participer.php#charte1">Introduction</a> - <a href="participer.php#charte2">Adh&eacute;sion</a> - <a href="participer.php#charte3">Gestion des pays</a> - <a href="participer.php#charte4">L'OCGC</a> - <a href="participer.php#charte5">Sanctions</a></div>
    <br />
    <h3 id="charte1">1. Introduction :</h3>
     <br />
La présente charte définit les règles à respecter pour tous les membres du Monde GC. Elles sont mises en place pour modérer la vie de la communauté et offrir un cadre clair et apaisé aux membres qui participent à ce projet unique autour des citybuilders. Le Monde GC étant un projet inédit qui est né et s'est développé par l'investissement de sa communauté au fur et à mesure des années, la participation d’un membre aux différentes activités du monde est uniquement fondée sur le volontariat et la collaboration entre membres.
	<br />
	<br />
En participant au Monde GC, le membre s'engage à respecter les règles décrites ci dessous ainsi que les principes de base de la vie en communauté : respect mutuel des membres et de leur travail, l'interdiction des contenus inappropriés et illégaux.<br />
     <br />
    <h3 id="charte2">2. Adh&eacute;sion :</h3>
     <br />
Un pays peut demander un emplacement sur la carte du Monde GC après avoir rempli les conditions suivantes :
<br /><strong>- Deux villes minimum présentées sur le forum</strong> (Cities Skylines, Cities XL, SimCity...)
<br /><strong>- Une présentation détaillée du territoire</strong> à travers son histoire, son économie etc.</br>
	<br />
<strong>Le tout doit être en cohérence</strong> avec ce qui a déjà été fait au niveau du reste du Monde GC, <strong>particulièrement pour l'histoire.</strong>
	<br />
	<br />
Une fois ces conditions remplies, le membres peut poster sa demande sur le topic de recensement avec le numéro du territoire qu'il aimerait posséder (carte disponible ci-dessus hors territoire 20). La candidature sera examinée par les membres de l'OCGC, et si la réponse est positive le dirigeant de ce nouveau pays pourra s'inscrire sur le site du Monde GC en fournissant préalablement son adresse mail à l'administration.
     <br />
     <h3 id="charte3">3. Gestion des pays :</h3>
     <br />
     Le site internet du Monde GC met à la disposition des dirigeants des pays un petit nombre de formulaires dédiés aux informations globales (nom, habitants...), détaillés (histoire, culture, économie...) ou spécialisés (communiqués, carrousel d'images...), ainsi que les moyens de commenter le travail présenté sur cette plateforme. Pour développer vos statistiques économiques de Tempérance, vous pouvez construire des infrastructures ou appliquer du zoning à votre territoire.
Le Monde GC étant basé sur <strong>la coopération</strong>, nous vous invitons à participer aux événements mondiaux qui peuvent être organisés ainsi que de développer votre diplomatie. <strong>L'interaction</strong> avec le reste de la communauté participant au Monde GC est aussi une manière de développer son pays, sa place est même centrale. 
<br />
<br />
<strong>Les frontières ne sont pas modifiables</strong> (sauf cas exceptionnel décidé par l'OCGC) et <strong>vos réalisations (villes, monuments, zoning...) doivent se trouver sur votre territoire</strong> à moins que ce soit dans un cadre d'accord diplomatique avec consentement mutuel.
<br />
<br />
Il est évidemment bon de rappeler que <strong>le contenu offensant, insultant ou déplacé n'est pas admis</strong>. Les modérateurs du site se donnent le droit de les supprimer sans préavis. De multiples récidives peuvent mener à des rappels à l'ordre, des sanctions temporaires ou une suppression de la page pays du membre (dans le pire des cas).
<br>
<strong>Cette règle s'applique sur l'ensemble du site du Monde GC ainsi qu'à Squirrel.</strong>
</br>
<br />
<br />
Si un pays n'a plus d'activité depuis plus de 3 mois, <strong>l'OCGC se réserve le droit d'archiver ce pays</strong> en le classant comme faisant partie de l'histoire du Monde GC. Si un membre abandonne délibérément sa page pays en y supprimant tout son contenu, l'OCGC est en droit d'archiver sa page si aucune justification n'est donnée dans un délai d'une semaine. 
     <br />
    <h3 id="charte4">4. L'OCGC :</h3>
     <br />
     L'Organisation des Cités Gécéennes est la plus haute instance du Monde GC : <strong>c'est elle qui est chargée de veiller au bon fonctionnement de la communauté et valorisant ou en modernisant les outils mis à disposition</strong>. Le Conseil de l'OCGC est géré par 4 membres permanents dont un président, aidés par les administrateurs GC et tous les membres volontaires qui souhaitent participer. Composée des différents Comités et Directions, <strong>l'OCGC fixe les règles internationales nécessaires à la pérennité de la paix dans le monde</strong>. L'OCGC gère également les demandes de nouveaux pays ou de Territoires d'Outre-Mer (TOM). C'est également à elle que revient la responsabilité et la gestion de la RFGC, la vitrine des villes postées par les membres n'ayant aucun pays attitré.
     <br />
     <h3 id="charte7">5. Sanctions :</h3>
     <br />
     En cas de non-respect de la charte, des sanctions peuvent-être appliquées en fonction de la gravité de la situation. Cela peut être juste <strong>un rappel à l'ordre, une interdiction temporaire d'activité sur le site du Monde GC ou un bannissement du Monde GC</strong>.<br />
    <h3>&nbsp;</h3></div>
EOL;

$participer_cadre_content = <<<EOL
<h1>Carte des territoires libres</h1>
<h4>Jouer en tant que maire :</h4>
<p>Pour jouer, vous devez d'abord vous inscrire sur le forum de G&eacute;n&eacute;ration City.  Vous pouvez ensuite int&eacute;grer l'une des 4 r&eacute;gion de la RFGC ou demander &agrave; un dirigeant de rejoindre son pays. En tant que Maire vous pouvez avoir plusieurs villes, les r&eacute;unir au sein d'une r&eacute;gion et participer &agrave; la vie du pays. </p>
<p>&nbsp;</p>
<h4>Jouer en tant que dirigeant de pays :</h4>
<p> Pour demander un emplacement, vous devez d'abord vous inscrire sur le forum de G&eacute;n&eacute;ration City.  Vous devez avoir pr&eacute;sent&eacute;  au moins 2 villes et votre pays dans la section &quot;Monde GC&quot; avant de faire une demande officielle qui sera &eacute;xamin&eacute;e par le Haut-Conseil. Fa&icirc;tes l'&eacute;ffort de pr&eacute;senter votre projet, vous aurez toutes les chances d'&ecirc;tre int&eacute;gr&eacute;.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
EOL;

$participer = new \GenCity\Monde\Page('participer');
$participer_cadre = new \GenCity\Monde\Page('participer_cadre');

$participer->update($participer_content);
$participer_cadre->update($participer_cadre_content);