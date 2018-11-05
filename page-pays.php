<?php session_start();
include('Connections/maconnexion.php');

//Connexion et deconnexion
include('php/log.php');

$colname_Pays = "-1";
if (isset($_GET['ch_pay_id'])) {
  $colname_Pays = $_GET['ch_pay_id'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_Pays = sprintf("SELECT * FROM pays WHERE ch_pay_id = %s", GetSQLValueString($colname_Pays, "int"));
$Pays = mysql_query($query_Pays, $maconnexion) or die(mysql_error());
$row_Pays = mysql_fetch_assoc($Pays);

//Recherche des villes du pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_villes = sprintf("SELECT ch_vil_ID, ch_vil_paysID, ch_vil_user, ch_vil_date_enregistrement, ch_vil_mis_jour, ch_vil_nom, ch_vil_capitale, ch_vil_population, ch_vil_specialite, ch_vil_lien_img1, ch_use_login FROM villes INNER JOIN users ON ch_vil_user = ch_use_id WHERE ch_vil_capitale <> 3 AND villes.ch_vil_paysID = %s ORDER BY ch_vil_mis_jour DESC", GetSQLValueString($colname_Pays, "int"));
$villes = mysql_query($query_villes, $maconnexion) or die(mysql_error());
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);


//Addition des populations des villes
mysql_select_db($database_maconnexion, $maconnexion);
$query_population = sprintf("SELECT Sum(ch_vil_population) AS population_pays FROM villes WHERE villes.ch_vil_capitale != 3 AND villes.ch_vil_paysID = %s", GetSQLValueString($colname_Pays, "int"));
$population = mysql_query($query_population, $maconnexion) or die(mysql_error());
$row_population = mysql_fetch_assoc($population);
$totalRows_population = mysql_num_rows($population);
$population_pays = $row_population['population_pays'];

//Connexion base de données utilisateur pour info personnage
mysql_select_db($database_maconnexion, $maconnexion);
$query_User = sprintf("SELECT ch_use_id, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_biographie_dirigeant, ch_use_login, (SELECT GROUP_CONCAT(ch_disp_group_id) FROM dispatch_mem_group WHERE ch_use_id = ch_disp_mem_id AND ch_disp_mem_statut != 3) AS listgroup FROM users WHERE ch_use_paysID = %s AND ch_use_statut >= 10", GetSQLValueString($colname_Pays, "int"));
$User = mysql_query($query_User, $maconnexion) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

//Recherche des monuments du pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_monument = sprintf("SELECT ch_pat_ID, ch_pat_paysID, ch_pat_date, ch_pat_mis_jour, ch_pat_nom, ch_pat_statut, ch_pat_lien_img1, ch_pat_description, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_ID = ch_disp_mon_id) AS listcat FROM patrimoine WHERE ch_pat_statut = 1 AND ch_pat_paysID = %s ORDER BY ch_pat_mis_jour DESC", GetSQLValueString($colname_Pays, "int"));
$monument = mysql_query($query_monument, $maconnexion) or die(mysql_error());
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);

//Recherche des faits historiques du pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_fait_his = sprintf("SELECT ch_his_id, ch_his_paysID, ch_his_date, ch_his_mis_jour, ch_his_nom, ch_his_statut, ch_his_personnage, ch_his_lien_img1, ch_his_date_fait, ch_his_date_fait2, ch_his_profession, ch_his_description, (SELECT GROUP_CONCAT(ch_disp_fait_hist_cat_id) FROM dispatch_fait_his_cat WHERE ch_his_ID = ch_disp_fait_hist_id) AS listcat FROM histoire WHERE ch_his_statut = 1 AND ch_his_paysID = %s ORDER BY ch_his_date_fait ASC", GetSQLValueString($colname_Pays, "int"));
$fait_his = mysql_query($query_fait_his, $maconnexion) or die(mysql_error());
$row_fait_his = mysql_fetch_assoc($fait_his);
$totalRows_fait_his = mysql_num_rows($fait_his);

//Recherche de la balance des ressources de la ville
if (isset($colname_Pays)) {
mysql_select_db($database_maconnexion, $maconnexion);
$query_somme_ressources = sprintf("SELECT SUM(ch_inf_off_budget) AS budget,SUM(ch_inf_off_Industrie) AS industrie, SUM(ch_inf_off_Commerce) AS commerce, SUM(ch_inf_off_Agriculture) AS agriculture, SUM(ch_inf_off_Tourisme) AS tourisme, SUM(ch_inf_off_Recherche) AS recherche, SUM(ch_inf_off_Environnement) AS environnement, SUM(ch_inf_off_Education) AS education FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID INNER JOIN pays ON ch_vil_paysID = ch_pay_id WHERE ch_pay_id = %s AND ch_vil_capitale != 3 AND ch_inf_statut = 2", GetSQLValueString($colname_Pays, "int"));
$somme_ressources = mysql_query($query_somme_ressources, $maconnexion) or die(mysql_error());
$row_somme_ressources = mysql_fetch_assoc($somme_ressources);
}


$listgroup = "-1";
if (isset($row_User['listgroup'])) {
$listgroup = $row_User['listgroup'];

//recherche des groupes du membre
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_group = "SELECT * FROM membres_groupes WHERE ch_mem_group_ID In ($listgroup) AND ch_mem_group_statut = 1";
$liste_group = mysql_query($query_liste_group, $maconnexion) or die(mysql_error());
$row_liste_group = mysql_fetch_assoc($liste_group);
$totalRows_liste_group = mysql_num_rows($liste_group);
}

//recherche de la liste des jeux
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_jeux = sprintf("SELECT ch_vil_type_jeu FROM villes WHERE ch_vil_paysID = %s GROUP BY ch_vil_type_jeu ", GetSQLValueString($colname_Pays, "int"));
$liste_jeux = mysql_query($query_liste_jeux, $maconnexion) or die(mysql_error());
$row_liste_jeux = mysql_fetch_assoc($liste_jeux);
$totalRows_liste_jeux = mysql_num_rows($liste_jeux);


//recherche de la note temperance
mysql_select_db($database_maconnexion, $maconnexion);
$query_temperance = sprintf("SELECT * FROM temperance WHERE ch_temp_element_id = %s AND ch_temp_element = 'pays' AND ch_temp_statut='3' ORDER BY ch_temp_date DESC", GetSQLValueString($colname_Pays, "int"));
$temperance = mysql_query($query_temperance, $maconnexion) or die(mysql_error());
$row_temperance = mysql_fetch_assoc($temperance);

//recherche des mesures des zones de la carte pour calcul ressources
mysql_select_db($database_maconnexion, $maconnexion);
$query_geometries = sprintf("SELECT SUM(ch_geo_mesure) as mesure, ch_geo_type FROM geometries WHERE ch_geo_pay_id = %s AND ch_geo_type != 'maritime' AND ch_geo_type != 'region' GROUP BY ch_geo_type ORDER BY ch_geo_geometries", GetSQLValueString($colname_Pays, "int"));
$geometries = mysql_query($query_geometries, $maconnexion) or die(mysql_error());
$row_geometries = mysql_fetch_assoc($geometries);

$_SESSION['last_work'] = 'page-pays.php?ch_pay_id='.$row_Pays['ch_pay_id'];
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC-<?php echo $row_Pays['ch_pay_nom']; ?></title>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="Carto/OLdefault.css" rel="stylesheet">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
 background-image: url(<?php echo $row_Pays['ch_pay_lien_imgheader'];
?>);
	background-position: center;
}
#map {
	height: 500px;
	background-color: #fff;
}
 @media (max-width: 480px) {
#map {
	height: 260px;
}
}
</style>
<!-- Le javascript
    ================================================== -->
<!-- CARTE -->
<script src="assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="assets/js/OpenLayers.js" type="text/javascript"></script>
<?php $menupays=true; include('php/cartepays.php'); ?>
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
<script src="assets/js/application.js"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
</script>
<script> 
 $( document ).ready(function() {
init();
});
</script>
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
<!-- EDITEUR -->
<script type="text/javascript" src="assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" <?php if ($row_Pays['ch_pay_publication']== 2) { echo 'class="sepia"';} ?>>

<!-- Navbar
    ================================================== -->
<?php $pays=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron subhead anchor" id="pays_stats">
  <div class="container">
    <h1><?php echo $row_Pays['ch_pay_nom']; ?></h1>
    <p><?php echo $row_Pays['ch_pay_devise']; ?></p>
  </div>
</header>
<div class="container"> 
  
  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <li class="row-fluid"><a href="#pays_stats"><img src="<?php echo $row_Pays['ch_pay_lien_imgdrapeau']; ?>">
          <p><strong><?php echo $row_Pays['ch_pay_nom']; ?></strong></p>
          <p><em>Cr&eacute;&eacute; par <?php echo $row_User['ch_use_login']; ?></em></p>
          </a></li>
        <li><a href="#diplomatie">Diplomatie</a></li>
        <li><a href="#communiques">Communiqu&eacute;s</a></li>
        <?php if ($row_Pays['ch_pay_header_presentation'] OR $row_Pays['ch_pay_text_presentation']) { ?>
        <li><a href="#presentation">Pr&eacute;sentation</a></li>
        <?php } ?>
        <?php if ($row_villes) { ?>
        <li><a href="#villes">Villes</a></li>
        <?php } ?>
        <li><a href="#geographie">G&eacute;ographie</a></li>
        <?php if ($row_Pays['ch_pay_header_politique'] OR $row_Pays['ch_pay_text_politique']) { ?>
        <li><a href="#politique">Politique</a></li>
        <?php } ?>
        <?php if ($row_Pays['ch_pay_header_histoire'] OR $row_Pays['ch_pay_text_histoire'] OR $row_fait_his) { ?>
        <li><a href="#histoire">Histoire</a></li>
        <?php } ?>
        <li><a href="#economie">Economie</a></li>
        <?php if ($row_Pays['ch_pay_header_transport'] OR $row_Pays['ch_pay_text_transport']) { ?>
        <li><a href="#transport">Transport</a></li>
        <?php } ?>
        <?php if ($row_Pays['ch_pay_header_sport'] OR $row_Pays['ch_pay_text_sport']) { ?>
        <li><a href="#sport">Sport</a></li>
        <?php } ?>
        <?php if ($row_Pays['ch_pay_header_culture'] OR $row_Pays['ch_pay_text_culture']) { ?>
        <li><a href="#culture">Culture</a></li>
        <?php } ?>
        <?php if ($row_Pays['ch_pay_header_patrimoine'] OR $row_Pays['ch_pay_text_patrimoine'] OR $row_monument) { ?>
        <li><a href="#patrimoine">Patrimoine</a></li>
        <?php } ?>
		<?php if ($row_Pays['ch_pay_header_culture'] OR $row_Pays['ch_pay_text_culture'] OR $row_monument) { ?>
        <li><a href="#culture">Projets secrets</a></li>
        <?php } ?>
        <li><a href="#commentaires">Visites</a></li>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== -->
    <div class="span9 corps-page">
      <?php if ($row_temperance) { ?>
      <a class="btn btn-primary" href="php/temperance-rapport-pays.php?ch_temp_id=<?php echo $row_temperance['ch_temp_id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="voir le d&eacute;tail de cette note">Note des juges&nbsp;: <?php echo get_note_finale($row_temperance['ch_temp_note']); ?>
      <?php	if ($row_temperance['ch_temp_tendance'] == "sup") { ?>
      <i class="icon-arrow-up icon-white"></i>
      <?php } elseif ($row_temperance['ch_temp_tendance'] == "inf") { ?>
      <i class="icon-arrow-down icon-white"></i>
      <?php } else { ?>
      <i class=" icon-arrow-right icon-white"></i>
      <?php } ?>
      </a>
      <?php } ?>
      <!-- Si c'est un pays archive
    ================================================== -->
      <?php if ($row_Pays['ch_pay_publication'] == 2) { ?>
      <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4>Ce pays n'est plus actif, il fait partie de l'histoire du Monde GC</h4>
      </div>
      <?php } ?>
      <!-- Moderation
     ================================================== -->
      <?php if ($_SESSION['statut'] >= 30) { ?>
      <form class="pull-right" action="back/page_pays_confirmer_supprimer.php" method="post">
        <input name="paysID" type="hidden" value="<?php echo $row_Pays['ch_pay_id']; ?>">
        <button class="btn btn-danger" type="submit" title="supprimer ce pays"><i class="icon-trash icon-white"></i></button>
      </form>
      <?php } ?>
      <?php if (($_SESSION['statut'] >= 20) OR ($row_User['ch_use_id'] == $_SESSION['user_ID'])) { ?>
      <form class="pull-right" action="back/page_pays_back.php" method="post">
        <input name="paysID" type="hidden" value="<?php echo $row_Pays['ch_pay_id']; ?>">
        <input name="userID" type="hidden" value="<?php echo $row_User['ch_use_id']; ?>">
        <button class="btn btn-danger" type="submit" title="modifier la page de ce pays"><i class="icon-pencil icon-white"></i></button>
      </form>
      <?php } ?>
      <?php if ($row_User['ch_use_id'] == $_SESSION['user_ID']) { ?>
      <a class="btn btn-primary pull-right" href="php/partage-pays.php?ch_pay_id=<?php echo $row_Pays['ch_pay_id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i> Forum</a>
      <?php } ?>
      <div class="clearfix"></div>
      <!-- INfo Generales
     ================================================== -->
      <section>
        <div class="row-fluid">
          <div class="span4 thumb"> <img src="<?php echo $row_Pays['ch_pay_lien_imgdrapeau']; ?>" alt="Drapeau du pays n°<?php echo $row_Pays['ch_pay_id']; ?>" title="drapeau <?php echo $row_Pays['ch_pay_nom']; ?>"> </div>
          <div class="span8">
            <h3>Informations&nbsp;:&nbsp; <?php  echo $currentPage; ?></h3>
            <div class="well">
              <p><strong>Date de cr&eacute;ation&nbsp;:&nbsp;</strong> le
                <?php  echo date("d/m/Y", strtotime($row_Pays['ch_pay_date'])); ?>
              </p>
              <p><strong>Derni&egrave;re mise &agrave; jour&nbsp;:&nbsp;</strong> le
                <?php  echo date("d/m/Y à G:i:s", strtotime($row_Pays['ch_pay_mis_jour'])); ?>
              </p>
              <p><strong>Nombre de villes&nbsp;:&nbsp;</strong><?php echo $totalRows_villes ?></p>
              <p><strong>Population urbaine <a href="#" rel="clickover" title="Population urbaine" data-content="La population urbaine est la somme des populations issues des villes cr&eacute;es dans les jeux"><i class="icon-info-sign"></i></a> &nbsp;:&nbsp;</strong><?php $population_pays_francais = number_format($population_pays, 0, ',', ' '); echo $population_pays_francais ?> habitants</p>
              <p><strong>Population rurale <a href="#" rel="clickover" title="Population rurale" data-content="La population rurale prend en compte la population issue de zones dessin&eacute;es sur la carte"><i class="icon-info-sign"></i></a>&nbsp;:&nbsp;</strong><?php $population_pays_francais = number_format($row_Pays['ch_pay_population_carte'], 0, ',', ' '); echo $population_pays_francais ?> habitants</p>
              <p><strong>Population totale&nbsp;:&nbsp;</strong><?php $population_pays_francais = number_format($population_pays + $row_Pays['ch_pay_population_carte'], 0, ',', ' '); echo $population_pays_francais ?> habitants</p>
              <p><strong>Population employée&nbsp;:&nbsp;</strong><?php $population_pays_francais = number_format($row_Pays['ch_pay_emploi_carte'], 0, ',', ' '); echo $population_pays_francais ?> habitants</p>
              <p><strong>R&eacute;gime&nbsp;:&nbsp;</strong><?php echo $row_Pays['ch_pay_forme_etat']; ?></p>
            </div>
            <!-- type de jeu
     ================================================== -->
            <h3>R&eacute;alis&eacute; avec&nbsp;:&nbsp;</h3>
            <div class="well">
              <?php do { ?>
                <?php if($row_liste_jeux['ch_vil_type_jeu'] == 'CL') { ?>
                <img src="assets/img/jeux-ico/cl.png" class="img-jeu">
                <?php } elseif ($row_liste_jeux['ch_vil_type_jeu'] == 'CXL'){ ?>
                <img src="assets/img/jeux-ico/cxl.png" class="img-jeu">
                <?php } elseif ($row_liste_jeux['ch_vil_type_jeu'] =='SC5'){ ?>
                <img src="assets/img/jeux-ico/sc5.png" class="img-jeu">
                <?php } elseif ($row_liste_jeux['ch_vil_type_jeu'] =='SC4'){ ?>
                <img src="assets/img/jeux-ico/sc4.png" class="img-jeu">
                <?php } elseif ($row_liste_jeux['ch_vil_type_jeu'] =='SIM'){ ?>
                <img src="assets/img/jeux-ico/sims.png" class="img-jeu">
                <?php } elseif ($row_liste_jeux['ch_vil_type_jeu'] =='SKY'){ ?>
                <img src="assets/img/jeux-ico/skylines.png" class="img-jeu">
                <?php } else { ?>
                <?php } ?>
                <?php } while ($row_liste_jeux = mysql_fetch_assoc($liste_jeux)); ?>
            </div>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span4 thumb"> <img src="Carto/Emplacements/emplacement<?php echo $row_Pays['ch_pay_emplacement']; ?>.jpg" alt="situation du pays <?php echo $row_Pays['ch_pay_nom']; ?>" title="situation pays <?php echo $row_Pays['ch_pay_nom']; ?>"> </div>
          <div class="span8">
            <h3>Situation&nbsp;:&nbsp;</h3>
            <div class="well">
              <p><strong>Capitale&nbsp;:&nbsp;</strong><?php echo $row_Pays['ch_pay_capitale']; ?></p>
              <p><strong>Langues officielles&nbsp;:&nbsp;</strong><?php echo $row_Pays['ch_pay_langue_officielle']; ?></p>
              <p><strong>Monnaie&nbsp;:&nbsp;</strong><?php echo $row_Pays['ch_pay_monnaie']; ?></p>
            </div>
          </div>
        </div>
      </section>
      <!-- Diplomatie
     ================================================== -->
      <section>
        <div id="diplomatie" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Membre1.png">
          <h1>Diplomatie</h1>
        </div>
        <div class="row-fluid">
          <div class="span4 thumb">
            <?php if ($row_User['ch_use_lien_imgpersonnage']) {?>
            <img src="<?php echo $row_User['ch_use_lien_imgpersonnage']; ?>" alt="<?php echo $row_User['ch_use_nom_dirigeant']; ?> <?php echo $row_User['ch_use_prenom_dirigeant']; ?>" title="<?php echo $row_User['ch_use_nom_dirigeant']; ?> <?php echo $row_User['ch_use_prenom_dirigeant']; ?>">
            <?php } else { ?>
            <img src="assets/img/imagesdefaut/personnage.jpg" alt="personnage par default">
            <?php } ?>
            <div class="titre-gris">
              <?php if ($row_User['ch_use_nom_dirigeant'] OR $row_User['ch_use_prenom_dirigeant']) {?>
              <h3><?php echo $row_User['ch_use_nom_dirigeant']; ?> <?php echo $row_User['ch_use_prenom_dirigeant']; ?></h3>
              <?php } else { ?>
              <h3>Pas de dirigeant</h3>
              <?php } ?>
            </div>
          </div>
          <div class="span8">
            <h3>Dirigeant du pays&nbsp;:</h3>
            <div class="well">
              <p><i><?php echo $row_User['ch_use_predicat_dirigeant']; ?></i></p>
              <p><i><?php echo $row_User['ch_use_titre_dirigeant']; ?></i></p>
            </div>
            <?php if ($row_User['listgroup']) { ?>
            <h3>Groupes politiques</h3>
            <div class="row-fluid">
              <?php do { ?>
                <!-- Icone et popover de la categorie -->
                <div class="span2 icone-categorie"><a href="#" rel="clickover" title="<?php echo $row_liste_group['ch_mem_group_nom']; ?>" data-placement="top" data-content="<?php echo $row_liste_group['ch_mem_group_desc']; ?>"><img src="<?php echo $row_liste_group['ch_mem_group_icon']; ?>" alt="icone <?php echo $row_liste_group['ch_mem_group_nom']; ?>" style="background-color:<?php echo $row_liste_group['ch_mem_group_couleur']; ?>;"></a></div>
                <?php } while ($row_liste_group = mysql_fetch_assoc($liste_group)); ?>
            </div>
            <?php } ?>
            <?php if ($row_User['ch_use_biographie_dirigeant']) { ?>
            <h3>Biographie&nbsp;:</h3>
            <div class="well">
              <p><?php echo $row_User['ch_use_biographie_dirigeant']; ?></p>
            </div>
          </div>
          <?php } ?>
        </div>
      </section>
      <!-- Communiqués
        ================================================== -->
      <section>
        <div id="communiques" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Communique.png">
          <h1>Communiqu&eacute;s</h1>
        </div>
        <?php 
	 $ch_com_categorie = 'pays';
	  $ch_com_element_id = $colname_Pays;
	  include('php/communiques.php'); ?>
      </section>
      <!-- Présentation
        ================================================== -->
      <?php if ($row_Pays['ch_pay_header_presentation'] OR $row_Pays['ch_pay_text_presentation']) { ?>
      <section>
        <div id="presentation" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Pays1.png">
          <h1>Pr&eacute;sentation</h1>
        </div>
        <div class="well">
          <h5><strong><?php echo $row_Pays['ch_pay_header_presentation']; ?></strong></h5>
          <?php echo $row_Pays['ch_pay_text_presentation']; ?></div>
      </section>
      <?php } ?>
      
      <!-- Liste des villes
        ================================================== -->
      <?php if ($row_villes) { ?>
      <section>
        <div id="villes" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Ville1.png">
          <h1>Liste des villes</h1>
        </div>
        <div id="liste-villes">
          <ul class="listes">
            <?php do { ?>
              <li class="row-fluid">
                <div class="span5 img-listes"> <a href="page-ville.php?ch_pay_id=<?php echo $row_Pays['ch_pay_id']; ?>&ch_ville_id=<?php echo $row_villes['ch_vil_ID']; ?>">
                  <?php if ($row_villes['ch_vil_lien_img1']) {?>
                  <img src="<?php echo $row_villes['ch_vil_lien_img1']; ?>" alt="<?php echo $row_villes['ch_vil_nom']; ?>">
                  <?php } else { ?>
                  <img src="assets/img/imagesdefaut/ville.jpg" alt="ville">
                  <?php } ?>
                  </a> </div>
                <div class="span6 info-listes">
                  <h4><?php echo $row_villes['ch_vil_nom']; ?></h4>
                  <p><strong>Derni&egrave;re mise &agrave; jour&nbsp;: </strong>le
                    <?php  echo date("d/m/Y", strtotime($row_villes['ch_vil_mis_jour'])); ?>
                  </p>
                  <p><strong>Population&nbsp;: </strong>
                    <?php 
					  $population_ville_francais = number_format($row_villes['ch_vil_population'], 0, ',', ' ');
					  echo $population_ville_francais; ?>
                    habitants</p>
                  <p><strong>Sp&eacute;cialit&eacute;&nbsp;: </strong> <?php echo $row_villes['ch_vil_specialite']; ?></p>
                  <?php if ($row_villes['ch_vil_user'] != $row_User['ch_use_id']) { ?>
                  <p><em><b>Ville créée par <a href="page-ville.php?ch_pay_id=<?php echo $row_Pays['ch_pay_id']; ?>&ch_ville_id=<?php echo $row_villes['ch_vil_ID']; ?>#diplomatie"><?php echo $row_villes['ch_use_login']; ?></a></b></em></p>
                  <?php } ?>
                  <a href="page-ville.php?ch_pay_id=<?php echo $row_Pays['ch_pay_id']; ?>&ch_ville_id=<?php echo $row_villes['ch_vil_ID']; ?>" class="btn btn-primary">Visiter</a> </div>
              </li>
              <?php } while ($row_villes = mysql_fetch_assoc($villes)); ?>
          </ul>
        </div>
      </section>
      <?php } ?>
      <!-- Géographie - Carte INTERACTIVE
    ================================================== -->
      
      <section>
        <div id="geographie" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/carte.png">
          <h1>G&eacute;ographie</h1>
        </div>
        <div id="map" class="well"></div>
        <?php if ($row_Pays['ch_pay_header_geographie'] OR $row_Pays['ch_pay_text_geographie']) { ?>
        <div class="well">
          <h5><strong><?php echo $row_Pays['ch_pay_header_geographie']; ?></strong></h5>
          <?php echo $row_Pays['ch_pay_text_geographie']; ?></div>
        <?php } ?>
      </section>
      
      <!-- Politique
        ================================================== -->
      <?php if ($row_Pays['ch_pay_header_politique'] OR $row_Pays['ch_pay_text_politique']) { ?>
      <section>
        <div id="politique" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Membre1.png">
          <h1>Politique</h1>
        </div>
        <div class="well">
          <h5><strong><?php echo $row_Pays['ch_pay_header_politique']; ?></strong></h5>
          <?php echo $row_Pays['ch_pay_text_politique']; ?></div>
      </section>
      <?php } ?>
      <!-- Histoire
        ================================================== -->
      <?php if ($row_Pays['ch_pay_header_histoire'] OR $row_Pays['ch_pay_text_histoire'] OR $row_fait_his) { ?>
      <section>
        <div id="histoire" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/histoire.png">
          <h1>Histoire</h1>
        </div>
        <?php if ($row_fait_his) { ?>
        <!-- Liste des faits historiques
        ================================================== -->
        <div id="liste-faits">
          <ul class="listes">
            <?php do { 
			$listcategories = ($row_fait_his['listcat']);
			if ($row_fait_his['listcat']) {
          
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_fai_cat3 = "SELECT * FROM faithist_categories WHERE ch_fai_cat_ID In ($listcategories) AND ch_fai_cat_statut = 1";
$liste_fai_cat3 = mysql_query($query_liste_fai_cat3, $maconnexion) or die(mysql_error());
$row_liste_fai_cat3 = mysql_fetch_assoc($liste_fai_cat3);
$totalRows_liste_fai_cat3 = mysql_num_rows($liste_fai_cat3);
			 } ?>
            <li class="row-fluid">
              <div class="span5 img-listes">
                <?php if ($row_fait_his['ch_his_lien_img1']) {?>
                <img src="<?php echo $row_fait_his['ch_his_lien_img1']; ?>" alt="illustration <?php echo $row_fait_his['ch_his_nom']; ?>">
                <?php } else { ?>
                <img src="assets/img/imagesdefaut/ville.jpg" alt="illustration">
                <?php } ?>
              </div>
              <div class="span6 info-listes">
                <?php if (($row_fait_his['ch_his_date_fait2'] != NULL) AND ($row_fait_his['ch_his_personnage'] == 1)) { ?>
                <!-- si periode historique -->
                <h5>Période du <?php echo affDate($row_fait_his['ch_his_date_fait']); ?> au <?php echo affDate($row_fait_his['ch_his_date_fait2']); ?>&nbsp;:</h5>
                <h4><?php echo $row_fait_his['ch_his_nom']; ?></h4>
                <?php } elseif (($row_fait_his['ch_his_date_fait2'] != NULL) AND ($row_fait_his['ch_his_personnage'] == 2)) { ?>
                <!-- si pers historique -->
                <h4><?php echo $row_fait_his['ch_his_nom']; ?></h4>
                <p><?php echo $row_fait_his['ch_his_profession']; ?> (<?php echo affDate($row_fait_his['ch_his_date_fait']); ?> - <?php echo affDate($row_fait_his['ch_his_date_fait2']); ?>)&nbsp; <em>
                  <?php 
	  $d1 = new DateTime($row_fait_his['ch_his_date_fait']);
	  $d2 = new DateTime($row_fait_his['ch_his_date_fait2']);
	  $diff = get_timespan_string($d1, $d2);
	  echo "mort &agrave; ".$diff;?>
                  </em></p>
                <?php } elseif (($row_fait_his['ch_his_date_fait2'] == NULL) AND ($row_fait_his['ch_his_personnage'] == 2)) { ?>
                <!-- si pers vivant -->
                <h4><?php echo $row_fait_his['ch_his_nom']; ?></h4>
                <p><?php echo $row_fait_his['ch_his_profession']; ?> (<?php echo affDate($row_fait_his['ch_his_date_fait']); ?>-&nbsp;&nbsp;)&nbsp; <em>
                  <?php 
	  $d1 = new DateTime($row_fait_his['ch_his_date_fait']);
	  $d2 = new DateTime('NOW');
	  $diff = get_timespan_string($d1, $d2);
	  echo $diff;?>
                  </em></p>
                <?php } else { ?>
                <!-- si fait historique -->
                <h5>&Eacute;v&eacute;nement du <?php echo affDate($row_fait_his['ch_his_date_fait']); ?>&nbsp;:</h5>
                <h4><?php echo $row_fait_his['ch_his_nom']; ?></h4>
                <?php } ?>
                <?php if ($row_liste_fai_cat3) { ?>
                <div class="row-fluid icone-categorie">
                  <?php do { ?>
                    <!-- Icone et popover de la categorie -->
                    <div class=""><a href="#" rel="clickover" title="<?php echo $row_liste_fai_cat3['ch_fai_cat_nom']; ?>" data-placement="top" data-content="<?php echo $row_liste_fai_cat3['ch_fai_cat_desc']; ?>"><img src="<?php echo $row_liste_fai_cat3['ch_fai_cat_icon']; ?>" alt="icone <?php echo $row_liste_fai_cat3['ch_fai_cat_nom']; ?>" style="background-color:<?php echo $row_liste_fai_cat3['ch_fai_cat_couleur']; ?>;"></a></div>
                    <?php } while ($row_liste_fai_cat3 = mysql_fetch_assoc($liste_fai_cat3)); ?>
                  <?php mysql_free_result($liste_fai_cat3); ?>
                </div>
                <?php } else  {  ?>
                <p>&nbsp;</p>
                <?php }?>
                <p><strong>Description&nbsp;: </strong> <?php echo $row_fait_his['ch_his_description']; ?></p>
                <a class="btn btn-primary" href="php/fait-his-modal.php?ch_his_id=<?php echo $row_fait_his['ch_his_id']; ?>" data-toggle="modal" data-target="#Modal-Monument">Consulter</a>
                <?php if ($row_User['ch_use_id'] == $_SESSION['user_ID']) { ?>
                <a class="btn btn-primary" href="php/partage-fait-hist.php?ch_his_id=<?php echo $row_fait_his['ch_his_id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i></a>
                <?php }?>
              </div>
            </li>
            <?php } while ($row_fait_his = mysql_fetch_assoc($fait_his)); ?>
          </ul>
        </div>
        <div class="modal container fade" id="Modal-Monument"></div>
        <p>&nbsp;</p>
        <?php } ?>
        <?php if ($row_Pays['ch_pay_header_histoire'] OR $row_Pays['ch_pay_text_histoire']) { ?>
        <div class="well">
          <h5><strong><?php echo $row_Pays['ch_pay_header_histoire']; ?></strong></h5>
          <?php echo $row_Pays['ch_pay_text_histoire']; ?> </div>
        <?php } ?>
      </section>
      <?php } ?>
      <!-- Econonomie
        ================================================== -->
      <section>
        <div id="economie" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/eco.png">
          <h1>Economie</h1>
        </div>
        <p>&nbsp;</p>
        <div class="row-fluid">
          <h3>Balance des ressources issues des villes du pays </h3>
          <ul class="token">
            <li class="span1"><a title="Budget"><img src="assets/img/ressources/Budget.png" alt="icone Budget"></a>
              <p>
                <?php $chiffre_francais = number_format($row_somme_ressources['budget'], 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Industrie"><img src="assets/img/ressources/Industrie.png" alt="icone Industrie"></a>
              <p>
                <?php $chiffre_francais = number_format($row_somme_ressources['industrie'], 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Commerce"><img src="assets/img/ressources/Bureau.png" alt="icone Commerce"></a>
              <p>
                <?php $chiffre_francais = number_format($row_somme_ressources['commerce'], 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Agriculture"><img src="assets/img/ressources/Agriculture.png" alt="icone Agriculture"></a>
              <p>
                <?php $chiffre_francais = number_format($row_somme_ressources['agriculture'], 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Tourisme"><img src="assets/img/ressources/tourisme.png" alt="icone Tourisme"></a>
              <p>
                <?php $chiffre_francais = number_format($row_somme_ressources['tourisme'], 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Recherche"><img src="assets/img/ressources/Recherche.png" alt="icone Recherche"></a>
              <p>
                <?php $chiffre_francais = number_format($row_somme_ressources['recherche'], 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Environnement"><img src="assets/img/ressources/Environnement.png" alt="icone Environnement"></a>
              <p>
                <?php $chiffre_francais = number_format($row_somme_ressources['environnement'], 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Education"><img src="assets/img/ressources/Education.png" alt="icone Education"></a>
              <p>
                <?php $chiffre_francais = number_format($row_somme_ressources['education'], 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
          </ul>
        </div>
        <div class="row-fluid"> 
          <h3>Balance des ressources issues de la carte</h3>
          <ul class="token">
            <li class="span1"><a title="Budget"><img src="assets/img/ressources/Budget.png" alt="icone Budget"></a>
              <p> <strong>
                <?php $chiffre_francais = number_format($row_Pays['ch_pay_budget_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
                </strong> </p>
            </li>
            <li class="span1"><a title="Industrie"><img src="assets/img/ressources/Industrie.png" alt="icone Industrie"></a>
              <p> <strong>
                <?php $chiffre_francais = number_format($row_Pays['ch_pay_industrie_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
                </strong> </p>
            </li>
            <li class="span1"><a title="Commerce"><img src="assets/img/ressources/Bureau.png" alt="icone Commerce"></a>
              <p> <strong>
                <?php $chiffre_francais = number_format($row_Pays['ch_pay_commerce_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
                </strong> </p>
            </li>
            <li class="span1"><a title="Agriculture"><img src="assets/img/ressources/Agriculture.png" alt="icone Agriculture"></a>
              <p> <strong>
                <?php $chiffre_francais = number_format($row_Pays['ch_pay_agriculture_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
                </strong> </p>
            </li>
            <li class="span1"><a title="Tourisme"><img src="assets/img/ressources/tourisme.png" alt="icone Tourisme"></a>
              <p> <strong>
                <?php $chiffre_francais = number_format($row_Pays['ch_pay_tourisme_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
                </strong> </p>
            </li>
            <li class="span1"><a title="Recherche"><img src="assets/img/ressources/Recherche.png" alt="icone Recherche"></a>
              <p> <strong>
                <?php $chiffre_francais = number_format($row_Pays['ch_pay_recherche_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
                </strong> </p>
            </li>
            <li class="span1"><a title="Environnement"><img src="assets/img/ressources/Environnement.png" alt="icone Environnement"></a>
              <p> <strong>
                <?php $chiffre_francais = number_format($row_Pays['ch_pay_environnement_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
                </strong> </p>
            </li>
            <li class="span1"><a title="Education"><img src="assets/img/ressources/Education.png" alt="icone Education"></a>
              <p> <strong>
                <?php $chiffre_francais = number_format($row_Pays['ch_pay_education_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
                </strong> </p>
            </li>
            <li class="span3">
            <a class="btn btn-primary pull-right" href="php/ressource-rapport-carte.php?ch_pay_id=<?php echo $colname_Pays ?>" data-toggle="modal" data-target="#Modal-Monument" title="voir le d&eacute;tail des ressources par type de zone">D&eacute;tail par zone</a>
            </li>
          </ul>
        </div>
        <div class="row-fluid">
          <h3>Balance totale des ressources</h3>
          <ul class="token">
            <li class="span1"><a title="Budget"><img src="assets/img/ressources/Budget.png" alt="icone Budget"></a>
              <p>
                <?php $chiffre_francais = number_format(($row_somme_ressources['budget']+$row_Pays['ch_pay_budget_carte']), 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Industrie"><img src="assets/img/ressources/Industrie.png" alt="icone Industrie"></a>
              <p>
                <?php $chiffre_francais = number_format(($row_somme_ressources['industrie']+$row_Pays['ch_pay_industrie_carte']), 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Commerce"><img src="assets/img/ressources/Bureau.png" alt="icone Commerce"></a>
              <p>
                <?php $chiffre_francais = number_format(($row_somme_ressources['commerce']+$row_Pays['ch_pay_commerce_carte']), 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Agriculture"><img src="assets/img/ressources/Agriculture.png" alt="icone Agriculture"></a>
              <p>
                <?php $chiffre_francais = number_format(($row_somme_ressources['agriculture']+$row_Pays['ch_pay_agriculture_carte']), 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Tourisme"><img src="assets/img/ressources/tourisme.png" alt="icone Tourisme"></a>
              <p>
                <?php $chiffre_francais = number_format(($row_somme_ressources['tourisme']+$row_Pays['ch_pay_tourisme_carte']), 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Recherche"><img src="assets/img/ressources/Recherche.png" alt="icone Recherche"></a>
              <p>
                <?php $chiffre_francais = number_format(($row_somme_ressources['recherche']+$row_Pays['ch_pay_recherche_carte']), 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Environnement"><img src="assets/img/ressources/Environnement.png" alt="icone Environnement"></a>
              <p>
                <?php $chiffre_francais = number_format(($row_somme_ressources['environnement']+$row_Pays['ch_pay_environnement_carte']), 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
            <li class="span1"><a title="Education"><img src="assets/img/ressources/Education.png" alt="icone Education"></a>
              <p>
                <?php $chiffre_francais = number_format(($row_somme_ressources['education']+$row_Pays['ch_pay_education_carte']), 0, ',', ' '); echo $chiffre_francais; ?>
              </p>
            </li>
          </ul>
        </div>
        <div class="clearfix"></div>
        <?php if ($row_Pays['ch_pay_header_economie'] OR $row_Pays['ch_pay_text_economie']) { ?>
        <p>&nbsp;</p>
        <div class="well">
          <h5><strong><?php echo $row_Pays['ch_pay_header_economie']; ?></strong></h5>
          <?php echo $row_Pays['ch_pay_text_economie']; ?></div>
        <?php } ?>
      </section>
      <!-- Transport
        ================================================== -->
      <?php if ($row_Pays['ch_pay_header_transport'] OR $row_Pays['ch_pay_text_transport']) { ?>
      <section>
        <div id="transport" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Transport.png">
          <h1>Transport</h1>
        </div>
        <div class="well">
          <h5><strong><?php echo $row_Pays['ch_pay_header_transport']; ?></strong></h5>
          <?php echo $row_Pays['ch_pay_text_transport']; ?></div>
      </section>
      <?php } ?>
      <!-- Sport
        ================================================== -->
      <?php if ($row_Pays['ch_pay_header_sport'] OR $row_Pays['ch_pay_text_sport']) { ?>
      <section>
        <div id="sport" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/sport.png">
          <h1>Sport</h1>
        </div>
        <div class="well">
          <h5><strong><?php echo $row_Pays['ch_pay_header_sport']; ?></strong></h5>
          <?php echo $row_Pays['ch_pay_text_sport']; ?></div>
      </section>
      <?php } ?>
      <?php if ($row_Pays['ch_pay_header_culture'] OR $row_Pays['ch_pay_text_culture']) { ?>
      <!-- Culture
        ================================================== -->
      <section>
        <div id="culture" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/culture.png">
          <h1>Culture</h1>
        </div>
        <div class="well">
          <h5><?php echo $row_Pays['ch_pay_header_culture']; ?></h5>
          <?php echo $row_Pays['ch_pay_text_culture']; ?></div>
      </section>
      <?php } ?>
      <!-- Patrimoine
        ================================================== -->
      <?php if ($row_Pays['ch_pay_header_patrimoine'] OR $row_Pays['ch_pay_text_patrimoine'] OR $row_monument) { ?>
      <section>
        <div id="patrimoine" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/monument1.png">
          <h1>Patrimoine</h1>
        </div>
        <?php if ($row_monument) { ?>
        <!-- Liste des monuments
        ================================================== -->
        <h3>Liste des monuments</h3>
        <div id="liste-monuments">
          <ul class="listes">
            <?php do { 
			$listcategories = ($row_monument['listcat']);
			if ($row_monument['listcat']) {
          
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) AND ch_mon_cat_statut =1";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion) or die(mysql_error());
$row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
			 } ?>
            <li class="row-fluid">
              <div class="span5 img-listes">
                <?php if ($row_monument['ch_pat_lien_img1']) {?>
                <img src="<?php echo $row_monument['ch_pat_lien_img1']; ?>" alt="image <?php echo $row_monument['ch_pat_nom']; ?>">
                <?php } else { ?>
                <img src="assets/img/imagesdefaut/ville.jpg" alt="monument">
                <?php } ?>
              </div>
              <div class="span6 info-listes">
                <h4><?php echo $row_monument['ch_pat_nom']; ?></h4>
                <p><strong>Derni&egrave;re mise &agrave; jour&nbsp;: </strong>le
                  <?php  echo date("d/m/Y", strtotime($row_monument['ch_pat_mis_jour'])); ?>
                  &agrave; <?php echo date("G:i:s", strtotime($row_monument['ch_pat_mis_jour'])); ?> </p>
                <?php if ($row_liste_mon_cat3) { ?>
                <div class="row-fluid icone-categorie">
                  <?php do { ?>
                    <!-- Icone et popover de la categorie -->
                    <div class=""><a href="#" rel="clickover" title="<?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>" data-placement="top" data-content="<?php echo $row_liste_mon_cat3['ch_mon_cat_desc']; ?>"><img src="<?php echo $row_liste_mon_cat3['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>" style="background-color:<?php echo $row_liste_mon_cat3['ch_mon_cat_couleur']; ?>;"></a></div>
                    <?php } while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)); ?>
                  <?php mysql_free_result($liste_mon_cat3); ?>
                </div>
                <?php }?>
                <p><strong>Description&nbsp;: </strong> <?php echo $row_monument['ch_pat_description']; ?></p>
                <a class="btn btn-primary" href="php/patrimoine-modal.php?ch_pat_id=<?php echo $row_monument['ch_pat_ID']; ?>" data-toggle="modal" data-target="#Modal-Monument">Visiter</a>
                <?php if ($row_User['ch_use_id'] == $_SESSION['user_ID']) { ?>
                <a class="btn btn-primary" href="php/partage-monument.php?ch_pat_id=<?php echo $row_monument['ch_pat_ID']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i></a>
                <?php } ?>
              </div>
            </li>
            <?php } while ($row_monument = mysql_fetch_assoc($monument)); ?>
          </ul>
        </div>
        <div class="modal container fade" id="Modal-Monument"></div>
        <p>&nbsp;</p>
        <?php } ?>
        <?php if ($row_Pays['ch_pay_header_patrimoine'] OR $row_Pays['ch_pay_text_patrimoine']) { ?>
        <div class="well">
          <h5><strong><?php echo $row_Pays['ch_pay_header_patrimoine']; ?></strong></h5>
          <?php echo $row_Pays['ch_pay_text_patrimoine']; ?> </div>
        <?php } ?>
      </section>
      <?php } ?>
	  <!-- Projets intercontinentaux
        ================================================== -->
      <?php if ($row_Pays['ch_pay_header_culture'] OR $row_Pays['ch_pay_text_culture'] OR $row_monument) { ?>
      <section>
        <div id="culture" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/monument1.png">
          <h1>Projets intercontinentaux</h1>
        </div>
        <?php if ($row_monument) { ?>
        <!-- Liste des projets
        ================================================== -->
        <h3>Liste des projets</h3>
        <div id="liste-monuments">
          <ul class="listes">
            <?php do { 
			$listcategories = ($row_monument['listcat']);
			if ($row_monument['listcat']) {
          
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) AND ch_mon_cat_statut =1";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion) or die(mysql_error());
$row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
			 } ?>
            <li class="row-fluid">
              <div class="span5 img-listes">
                <?php if ($row_monument['ch_pat_lien_img1']) {?>
                <img src="<?php echo $row_monument['ch_pat_lien_img1']; ?>" alt="image <?php echo $row_monument['ch_pat_nom']; ?>">
                <?php } else { ?>
                <img src="assets/img/imagesdefaut/ville.jpg" alt="monument">
                <?php } ?>
              </div>
              <div class="span6 info-listes">
                <h4><?php echo $row_monument['ch_pat_nom']; ?></h4>
                <p><strong>Derni&egrave;re mise &agrave; jour&nbsp;: </strong>le
                  <?php  echo date("d/m/Y", strtotime($row_monument['ch_pat_mis_jour'])); ?>
                  &agrave; <?php echo date("G:i:s", strtotime($row_monument['ch_pat_mis_jour'])); ?> </p>
                <?php if ($row_liste_mon_cat3) { ?>
                <div class="row-fluid icone-categorie">
                  <?php do { ?>
                    <!-- Icone et popover de la categorie -->
                    <div class=""><a href="#" rel="clickover" title="<?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>" data-placement="top" data-content="<?php echo $row_liste_mon_cat3['ch_mon_cat_desc']; ?>"><img src="<?php echo $row_liste_mon_cat3['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>" style="background-color:<?php echo $row_liste_mon_cat3['ch_mon_cat_couleur']; ?>;"></a></div>
                    <?php } while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)); ?>
                  <?php mysql_free_result($liste_mon_cat3); ?>
                </div>
                <?php }?>
                <p><strong>Description&nbsp;: </strong> <?php echo $row_monument['ch_pat_description']; ?></p>
                <a class="btn btn-primary" href="php/patrimoine-modal.php?ch_pat_id=<?php echo $row_monument['ch_pat_ID']; ?>" data-toggle="modal" data-target="#Modal-Monument">Visiter</a>
                <?php if ($row_User['ch_use_id'] == $_SESSION['user_ID']) { ?>
                <a class="btn btn-primary" href="php/partage-monument.php?ch_pat_id=<?php echo $row_monument['ch_pat_ID']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i></a>
                <?php } ?>
              </div>
            </li>
            <?php } while ($row_monument = mysql_fetch_assoc($monument)); ?>
          </ul>
        </div>
        <div class="modal container fade" id="Modal-Monument"></div>
        <p>&nbsp;</p>
        <?php } ?>
        <?php if ($row_Pays['ch_pay_header_patrimoine'] OR $row_Pays['ch_pay_text_patrimoine']) { ?>
        <div class="well">
          <h5><strong><?php echo $row_Pays['ch_pay_header_patrimoine']; ?></strong></h5>
          <?php echo $row_Pays['ch_pay_text_patrimoine']; ?> </div>
        <?php } ?>
      </section>
      <?php } ?>
      <!-- Commentaire
        ================================================== -->
      <section>
        <div id="commentaires" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Membre1.png" alt="visites">
          <h1>Visites</h1>
        </div>
        <?php 
	  $ch_com_categorie = "com_pays";
	  $ch_com_element_id = $colname_Pays;
	  include('php/commentaire.php'); ?>
      </section>
    </div>
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>

<!-- Footer
    ================================================== -->
</body>
</html>
<script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});

$('.popover-html').popover({ html : true});
</script>
<?php
mysql_free_result($Pays);

mysql_free_result($villes);

mysql_free_result($User);

mysql_free_result($monument);

mysql_free_result($fait_his);

mysql_free_result($somme_ressources);

mysql_free_result($temperance);

mysql_free_result($liste_jeux);

if (isset($row_User['listgroup'])) {
mysql_free_result($liste_group);
}
?>
