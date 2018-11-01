<?php session_start();
require_once('Connections/maconnexion.php');

//Connexion et deconnexion
include('php/log.php');

// Calcul des statistiques
mysql_select_db($database_maconnexion, $maconnexion);
$query_stat_pays = "SELECT ch_pay_id, ch_pay_continent, ch_pay_population_carte FROM pays WHERE ch_pay_publication = 1";
$stat_pays = mysql_query($query_stat_pays, $maconnexion) or die(mysql_error());
$row_stat_pays = mysql_fetch_assoc($stat_pays);
$totalRows_stat_pays = mysql_num_rows($stat_pays);

mysql_select_db($database_maconnexion, $maconnexion);
$query_stat_ville = "SELECT COUNT(ch_vil_ID) AS nbville, ch_pay_continent, SUM(ch_vil_population) AS nbhabitant FROM villes INNER JOIN pays ON ch_pay_id = ch_vil_paysID WHERE ch_vil_capitale != 3 AND ch_pay_publication = 1 GROUP BY ch_pay_id";
$stat_ville = mysql_query($query_stat_ville, $maconnexion) or die(mysql_error());
$row_stat_ville = mysql_fetch_assoc($stat_ville);
$totalRows_stat_ville = mysql_num_rows($stat_ville);

do {
switch ($row_stat_pays['ch_pay_continent']) {
		case "Aurinea" :
		$nbhabitants_Aurinea = $nbhabitants_Aurinea + $row_stat_pays['ch_pay_population_carte'];
		$nbpays_Aurinea = $nbpays_Aurinea +1; break;
		case "RFGC" :
		$nbhabitants_RFGC = $nbhabitants_RFGC + $row_stat_pays['ch_pay_population_carte'];
		$nbpays_RFGC = $nbpays_RFGC +1; break;
		case "Volcania" :
		$nbhabitants_Volcania = $nbhabitants_Volcania + $row_stat_pays['ch_pay_population_carte'];
		$nbpays_Volcania = $nbpays_Volcania +1; break;
		case "Aldesyl" :
		$nbhabitants_Aldesyl = $nbhabitants_Aldesyl + $row_stat_pays['ch_pay_population_carte'];
		$nbpays_Aldesyl = $nbpays_Aldesyl +1; break;
		case "Oceania" :
		$nbhabitants_Oceania = $nbhabitants_Oceania + $row_stat_pays['ch_pay_population_carte'];
		$nbpays_Oceania = $nbpays_Oceania+1; break;
		case "Philicie" :
		$nbhabitants_Philicie = $nbhabitants_Philicie + $row_stat_pays['ch_pay_population_cartes'];
		$nbpays_Philicie = $nbpays_Philicie+1; break;
		default:
		break;
		}
} while ($row_stat_pays = mysql_fetch_assoc($stat_pays));
	  mysql_data_seek($stat_pays,0); 

do {
switch ($row_stat_ville['ch_pay_continent']) {
		case "Aurinea" :
		$nbvilles_Aurinea = $nbvilles_Aurinea + $row_stat_ville['nbville'];
		$nbhabitants_Aurinea = $nbhabitants_Aurinea + $row_stat_ville['nbhabitant']; break;
		case "RFGC" :
		$nbvilles_RFGC = $nbvilles_RFGC + $row_stat_ville['nbville'];
		$nbhabitants_RFGC = $nbhabitants_RFGC + $row_stat_ville['nbhabitant']; break;
		case "Volcania" :
		$nbvilles_Volcania = $nbvilles_Volcania + $row_stat_ville['nbville'];
		$nbhabitants_Volcania = $nbhabitants_Volcania + $row_stat_ville['nbhabitant']; break;
		case "Aldesyl" :
		$nbvilles_Aldesyl = $nbvilles_Aldesyl + $row_stat_ville['nbville'];
		$nbhabitants_Aldesyl = $nbhabitants_Aldesyl + $row_stat_ville['nbhabitant']; break;
		case "Oceania" :
		$nbvilles_Oceania = $nbvilles_Oceania + $row_stat_ville['nbville'];
		$nbhabitants_Oceania = $nbhabitants_Oceania + $row_stat_ville['nbhabitant']; break;
		case "Philicie" :
		$nbvilles_Philicie = $nbvilles_Philicie + $row_stat_ville['nbville'];
		$nbhabitants_Philicie = $nbhabitants_Philicie + $row_stat_ville['nbhabitant']; break;
		default:
		break;
		}
} while ($row_stat_ville = mysql_fetch_assoc($stat_ville));
	  mysql_data_seek($stat_ville,0); 

$nbpays_Aurinea = $nbpays_Aurinea +1;
$nbvilles_Aurinea = $nbvilles_Aurinea + $nbvilles_RFGC;
$nbhabitants_Aurinea = $nbhabitants_Aurinea + $nbhabitants_RFGC;


// Liste des pays par continent

mysql_select_db($database_maconnexion, $maconnexion);
$query_listePays = "SELECT ch_pay_id, ch_pay_continent, ch_pay_nom, ch_pay_lien_imgdrapeau, ch_use_login, (SELECT COUNT(ch_vil_ID) FROM villes WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3) AS nbville, (SELECT SUM(ch_vil_population) FROM villes WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3) + ch_pay_population_carte AS nbhabitant FROM pays INNER JOIN users ON ch_use_paysID = ch_pay_id AND ch_use_statut >=10 WHERE ch_pay_publication = 1 Group By ch_pay_id ORDER BY ch_pay_nom ASC";
$listePays = mysql_query($query_listePays, $maconnexion) or die(mysql_error());
$row_listePays = mysql_fetch_assoc($listePays);
$totalRows_listePays = mysql_num_rows($listePays);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml>">
<html lang="fr">
<!-- head Html -->
<html lang="fr">
<head>
<meta charset="iso-8859-1">
<title>Le Monde GC - La carte</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="Carto/OLdefault.css" rel="stylesheet">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
<!-- TemplateEndEditable -->
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

<!-- CARTE -->
<script src="assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="assets/js/OpenLayers.js" type="text/javascript"></script>
<?php include('php/cartegenerale.php'); ?>
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script> 
 $( document ).ready(function() {
init();
});
</script>


<style>
.jumbotron {
	background-image: url('assets/img/ImgIntroheader.jpg');
}
#map {
	width: 100%;
	height: 500px;
	background: #FFFFFF;
	color: rgba(0,0,0,1);
}
img.olTileImage {
	max-width: none;
}
}
@media (max-width: 480px) {
#map {
	height: 360px;
}
}
div.olControlPanel {
	top: 65px;
	left: 10px;
	position: absolute;
	background: none repeat scroll 0 0 rgba(255, 255, 255, 0.4);
	border-radius: 4px;
	left: 8px;
	padding: 3px;
	margin-left: 1px;
}
.olControlPanel div {
	display: block;
	width: 21px;
	height: 21px;
	border-radius: 4px;
	cursor: pointer;
	background-repeat: no-repeat
}
.helpButtonItemInactive {
	background-image: url("Carto/images/icon_legend.png");
}
.helpButtonItemActive {
	background-image: url("Carto/images/icon_legend_active.png");
}
</style>
</head>

<body>
<!-- Navbar
    ================================================== -->
<?php $carte=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron subhead anchor" id="carte-generale">
  <div class="container-fluid container-carte"> 
    <!-- Carte desktop
    ================================================== -->
    <div class="row-fluid">
      <div class="span9">
        <div id="map"></div>
      </div>
      <div class="" id="info">
        <h1>Carte du Monde de G&eacute;n&eacute;ration City</h1>
        <p>&nbsp;</p>
        <h4>cliquez sur la carte</h4>
      </div>
    </div>
  </div>
</header>
<div class="container corps-page">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <!-- Liste pays
    ================================================== --> 
  <!-- Continent Aurinea
    ================================================== -->
  <div class="titre-gris anchor" id="liste-pays">
    <h3>Continent Aurin&eacute;a</h3>
  </div>
  <div class="stat-continent pull-center">
    <p><span class="stat"><span class="chiffre"> <?php echo $nbpays_Aurinea; ?> </span> pays</span> <span class="stat"><span class="chiffre"> <?php echo $nbvilles_Aurinea; ?> </span> villes</span> <span class="stat"><span class="chiffre">
      <?php $nbhabitant_francais = number_format($nbhabitants_Aurinea, 0, ',', ' '); echo $nbhabitant_francais; ?>
      </span> habitants</span></p>
  </div>
  <div class="row-fluid">
    <div class="span4"> <img src="assets/img/continents/aurinea.png" alt="img-continent-Aurin&eacute;a"> </div>
    <div class="span8">
      <ul class="listes liste-pays">
        <?php do { 
			if ($row_listePays['ch_pay_continent'] == 'Aurinea') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_listePays['ch_pay_lien_imgdrapeau']))
					{
					$row_listePays['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_listePays['ch_pay_lien_imgdrapeau']);
					}
		?>
        <li class="row-fluid">
          <div class="">
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>"><img src="<?php echo $row_listePays['ch_pay_lien_imgdrapeau']; ?>" alt="drapeau"></a> </div>
            <div class="span4">
              <h3><?php echo $row_listePays['ch_pay_nom']; ?></h3>
            </div>
            <div class="span4">
              <p>cr&eacute;&eacute; par&nbsp;: <strong><?php echo $row_listePays['ch_use_login']; ?></strong></p>
              <p>Nombre de villes&nbsp;: <strong><?php echo $row_listePays['nbville']; ?></strong></p>
              <p>Population&nbsp;: <strong>
                <?php 
	$nbhabitant_francais = number_format($row_listePays['nbhabitant'], 0, ',', ' ');
	echo $nbhabitant_francais; ?>
                habitants</strong></p>
            </div>
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>" class="btn btn-primary">Visiter</a> </div>
          </div>
        </li>
        <?php } } while ($row_listePays = mysql_fetch_assoc($listePays));
		  mysql_data_seek($listePays,0); ?>
        <li class="row-fluid">
          <div class="">
            <div class="span2"> <a href="#RFGC"><img src="assets/img/Drapeau-RFGC.jpg" width="100" alt="drapeau"></a> </div>
            <div class="span4">
              <h3>R&eacute;publique F&eacute;d&eacute;rale de G&eacute;n&eacute;ration City</h3>
            </div>
            <div class="span4">
              <p>La RFGC est un pays communautaire ouvert � tous, il est divis� en r�gions</p>
              <p>Nombre de villes&nbsp;: <strong> <?php echo $nbvilles_RFGC; ?> </strong></p>
              <p>Population&nbsp;: <strong>
                <?php $nbhabitant_francais = number_format($nbhabitants_RFGC, 0, ',', ' '); echo $nbhabitant_francais; ?>
                habitants</strong></p>
            </div>
            <div class="span2"> <a href="#RFGC" class="btn btn-primary">Voir les r&eacute;gions</a> </div>
          </div>
        </li>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>
  <!-- Continent Volcania
    ================================================== -->
  <div class="titre-gris anchor" id="Volcania">
    <h3>Continent Volcania</h3>
  </div>
  <div class="stat-continent pull-center">
    <p><span class="stat"><span class="chiffre"><?php echo $nbpays_Volcania; ?></span> pays</span> <span class="stat"><span class="chiffre"><?php echo $nbvilles_Volcania; ?></span> villes</span> <span class="stat"><span class="chiffre">
      <?php 
	$nbhabitant_francais = number_format($nbhabitants_Volcania, 0, ',', ' '); echo $nbhabitant_francais; ?>
      </span> habitants</span></p>
  </div>
  <div class="row-fluid">
    <div class="span4"> <img src="assets/img/continents/volcania.png" alt="img-continent-Volcania"> </div>
    <div class="span8">
      <ul class="listes liste-pays">
        <?php do { 
			if ($row_listePays['ch_pay_continent'] == 'Volcania') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_listePays['ch_pay_lien_imgdrapeau']))
					{
					$row_listePays['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_listePays['ch_pay_lien_imgdrapeau']);
					}
		?>
        <li class="row-fluid">
          <div class="">
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>"><img src="<?php echo $row_listePays['ch_pay_lien_imgdrapeau']; ?>" alt="drapeau"></a> </div>
            <div class="span4">
              <h3><?php echo $row_listePays['ch_pay_nom']; ?></h3>
            </div>
            <div class="span4">
              <p>cr&eacute;&eacute; par&nbsp;: <strong><?php echo $row_listePays['ch_use_login']; ?></strong></p>
              <p>Nombre de villes&nbsp;: <strong><?php echo $row_listePays['nbville']; ?></strong></p>
              <p>Population&nbsp;: <strong>
                <?php $nbhabitant_francais = number_format($row_listePays['nbhabitant'], 0, ',', ' '); echo $nbhabitant_francais; ?>
                habitants</strong></p>
            </div>
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>" class="btn btn-primary">Visiter</a> </div>
          </div>
        </li>
        <?php } } while ($row_listePays = mysql_fetch_assoc($listePays));
		  mysql_data_seek($listePays,0); ?>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>
  <!-- Continent Aldesyl
    ================================================== -->
  <div class="titre-gris anchor" id="Aldesyl">
    <h3>Continent Aldesyl</h3>
  </div>
  <div class="stat-continent pull-center">
    <p><span class="stat"><span class="chiffre"><?php echo $nbpays_Aldesyl; ?></span> pays</span> <span class="stat"><span class="chiffre"><?php echo $nbvilles_Aldesyl; ?></span> villes</span> <span class="stat"><span class="chiffre">
      <?php 
	$nbhabitant_francais = number_format($nbhabitants_Aldesyl, 0, ',', ' ');
	echo $nbhabitant_francais; ?>
      </span> habitants</span></p>
  </div>
  <div class="row-fluid">
    <div class="span4"> <img src="assets/img/continents/aldesyl.png" alt="img-continent-Aldesyl"> </div>
    <div class="span8">
      <ul class="listes liste-pays">
        <?php do { 
			if ($row_listePays['ch_pay_continent'] == 'Aldesyl') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_listePays['ch_pay_lien_imgdrapeau']))
					{
					$row_listePays['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_listePays['ch_pay_lien_imgdrapeau']);
					}
		?>
        <li class="row-fluid">
          <div class="">
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>"><img src="<?php echo $row_listePays['ch_pay_lien_imgdrapeau']; ?>" alt="drapeau"></a> </div>
            <div class="span4">
              <h3><?php echo $row_listePays['ch_pay_nom']; ?></h3>
            </div>
            <div class="span4">
              <p>cr&eacute;&eacute; par&nbsp;: <strong><?php echo $row_listePays['ch_use_login']; ?></strong></p>
              <p>Nombre de villes&nbsp;: <strong><?php echo $row_listePays['nbville']; ?></strong></p>
              <p>Population&nbsp;: <strong>
                <?php $nbhabitant_francais = number_format($row_listePays['nbhabitant'], 0, ',', ' '); echo $nbhabitant_francais; ?>
                habitants</strong></p>
            </div>
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>" class="btn btn-primary">Visiter</a> </div>
          </div>
        </li>
        <?php } } while ($row_listePays = mysql_fetch_assoc($listePays));
		  mysql_data_seek($listePays,0); ?>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>
  <!-- Continent Oceania
    ================================================== -->
  <div class="titre-gris anchor" id="Oceania">
    <h3>Continent Oceania</h3>
  </div>
  <div class="stat-continent pull-center">
    <p><span class="stat"><span class="chiffre"><?php echo $nbpays_Oceania; ?></span> pays</span> <span class="stat"><span class="chiffre"><?php echo $nbvilles_Oceania; ?></span> villes</span> <span class="stat"><span class="chiffre">
      <?php 
	$nbhabitant_francais = number_format($nbhabitants_Oceania, 0, ',', ' ');
	echo $nbhabitant_francais; ?>
      </span> habitants</span></p>
  </div>
  <div class="row-fluid">
    <div class="span4"> <img src="assets/img/continents/oceania.png" alt="img-continent-Oceania"> </div>
    <div class="span8">
      <ul class="listes liste-pays">
        <?php do { 
			if ($row_listePays['ch_pay_continent'] == 'Oceania') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_listePays['ch_pay_lien_imgdrapeau']))
					{
					$row_listePays['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_listePays['ch_pay_lien_imgdrapeau']);
					}
		?>
        <li class="row-fluid">
          <div class="">
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>"><img src="<?php echo $row_listePays['ch_pay_lien_imgdrapeau']; ?>" alt="drapeau"></a> </div>
            <div class="span4">
              <h3><?php echo $row_listePays['ch_pay_nom']; ?></h3>
            </div>
            <div class="span4">
              <p>cr&eacute;&eacute; par&nbsp;: <strong><?php echo $row_listePays['ch_use_login']; ?></strong></p>
              <p>Nombre de villes&nbsp;: <strong><?php echo $row_listePays['nbville']; ?></strong></p>
              <p>Population&nbsp;: <strong>
                <?php 
	$nbhabitant_francais = number_format($row_listePays['nbhabitant'], 0, ',', ' ');
	echo $nbhabitant_francais; ?>
                habitants</strong></p>
            </div>
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>" class="btn btn-primary">Visiter</a> </div>
          </div>
        </li>
        <?php } } while ($row_listePays = mysql_fetch_assoc($listePays));
		  mysql_data_seek($listePays,0); ?>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>
  <!-- Continent Philicie
    ================================================== -->
  <div class="titre-gris anchor" id="Philicie">
    <h3>Continent Philicie</h3>
  </div>
  <div class="stat-continent pull-center">
    <p><span class="stat"><span class="chiffre"><?php echo $nbpays_Philicie; ?></span> pays</span> <span class="stat"><span class="chiffre"><?php echo $nbvilles_Philicie; ?></span> villes</span> <span class="stat"><span class="chiffre">
      <?php 
	$nbhabitant_francais = number_format($nbhabitants_Philicie, 0, ',', ' ');
	echo $nbhabitant_francais; ?>
      </span> habitants</span></p>
  </div>
  <div class="row-fluid">
    <div class="span4"> <img src="assets/img/continents/philicie.png" alt="img-continent-Philicie"> </div>
    <div class="span8">
      <ul class="listes liste-pays">
        <?php do { 
			if ($row_listePays['ch_pay_continent'] == 'Philicie') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_listePays['ch_pay_lien_imgdrapeau']))
					{
					$row_listePays['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_listePays['ch_pay_lien_imgdrapeau']);
					}
		?>
        <li class="row-fluid">
          <div class="">
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>"><img src="<?php echo $row_listePays['ch_pay_lien_imgdrapeau']; ?>" alt="drapeau"></a> </div>
            <div class="span4">
              <h3><?php echo $row_listePays['ch_pay_nom']; ?></h3>
            </div>
            <div class="span4">
              <p>cr&eacute;&eacute; par&nbsp;: <strong><?php echo $row_listePays['ch_use_login']; ?></strong></p>
              <p>Nombre de villes&nbsp;: <strong><?php echo $row_listePays['nbville']; ?></strong></p>
              <p>Population&nbsp;: <strong>
                <?php 
	$nbhabitant_francais = number_format($row_listePays['nbhabitant'], 0, ',', ' ');
	echo $nbhabitant_francais; ?>
                habitants</strong></p>
            </div>
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>" class="btn btn-primary">Visiter</a> </div>
          </div>
        </li>
        <?php } } while ($row_listePays = mysql_fetch_assoc($listePays));
		  mysql_data_seek($listePays,0); ?>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>
  <!-- Continent RFGC
    ================================================== -->
  <div class="titre-gris anchor" id="RFGC">
    <h3>Pays RFGC</h3>
  </div>
  <div class="stat-continent pull-center">
    <p><span class="stat"><span class="chiffre"><?php echo $nbpays_RFGC; ?></span> r&eacute;gions</span> <span class="stat"><span class="chiffre"><?php echo $nbvilles_RFGC; ?></span> villes</span> <span class="stat"><span class="chiffre">
      <?php 
	$nbhabitant_francais = number_format($nbhabitants_RFGC, 0, ',', ' ');
	echo $nbhabitant_francais; ?>
      </span> habitants</span></p>
  </div>
  <div class="row-fluid">
    <div class="span4"> <img src="assets/img/continents/rfgc.png" alt="img-RFGC"> </div>
    <div class="span8">
      <ul class="listes liste-pays">
        <?php do { 
			if ($row_listePays['ch_pay_continent'] == 'RFGC') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_listePays['ch_pay_lien_imgdrapeau']))
					{
					$row_listePays['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_listePays['ch_pay_lien_imgdrapeau']);
					}
		?>
        <li class="row-fluid">
          <div class="">
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>"><img src="<?php echo $row_listePays['ch_pay_lien_imgdrapeau']; ?>" alt="drapeau"></a> </div>
            <div class="span4">
              <h3><?php echo $row_listePays['ch_pay_nom']; ?></h3>
            </div>
            <div class="span4">
              <p>cr&eacute;&eacute; par&nbsp;: <strong><?php echo $row_listePays['ch_use_login']; ?></strong></p>
              <p>Nombre de villes&nbsp;: <strong><?php echo $row_listePays['nbville']; ?></strong></p>
              <p>Population&nbsp;: <strong>
                <?php 
	$nbhabitant_francais = number_format($row_listePays['nbhabitant'], 0, ',', ' ');
	echo $nbhabitant_francais; ?>
                habitants</strong></p>
            </div>
            <div class="span2"> <a href="page-pays.php?ch_pay_id=<?php echo $row_listePays['ch_pay_id']; ?>" class="btn btn-primary">Visiter</a> </div>
          </div>
        </li>
        <?php } } while ($row_listePays = mysql_fetch_assoc($listePays));
		  mysql_data_seek($listePays,0); ?>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->