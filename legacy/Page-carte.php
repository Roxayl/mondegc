<?php

//Connexion et deconnexion
include('php/log.php');

// On essaye de ne pas afficher les messages d'erreur, le temps de les corriger.
if($_SERVER["REMOTE_ADDR"] === '127.0.0.1') {
    ob_start();
}

// Calcul des statistiques

$query_stat_pays = "SELECT ch_pay_id, ch_pay_continent, ch_pay_population_carte FROM pays WHERE ch_pay_publication = 1";
$stat_pays = mysql_query($query_stat_pays, $maconnexion) or die(mysql_error());
$row_stat_pays = mysql_fetch_assoc($stat_pays);
$totalRows_stat_pays = mysql_num_rows($stat_pays);

$query_stat_ville = "SELECT COUNT(ch_vil_ID) AS nbville, ch_pay_continent, SUM(ch_vil_population) AS nbhabitant FROM villes INNER JOIN pays ON ch_pay_id = ch_vil_paysID WHERE ch_vil_capitale != 3 AND ch_pay_publication = 1 GROUP BY ch_pay_id";
$stat_ville = mysql_query($query_stat_ville, $maconnexion) or die(mysql_error());
$row_stat_ville = mysql_fetch_assoc($stat_ville);
$totalRows_stat_ville = mysql_num_rows($stat_ville);

//Aurinea
$nbhabitants_Aurinea = 0;
$nbpays_Aurinea = 0;
$nbvilles_Aurinea = 0;
//RFGC
$nbhabitants_RFGC = 0;
$nbpays_RFGC = 0;
$nbvilles_RFGC = 0;
//Volcania
$nbhabitants_Volcania = 0;
$nbpays_Volcania = 0;
$nbvilles_Volcania = 0;
//Oceania
$nbhabitants_Oceania = 0;
$nbpays_Oceania = 0;
$nbvilles_Oceania = 0;
//Philicie
$nbhabitants_Philicie = 0;
$nbpays_Philicie = 0;
$nbvilles_Philicie = 0;
// Aldesyl
$nbhabitants_Aldesyl = 0;
$nbpays_Aldesyl = 0;
$nbvilles_Aldesyl = 0;

//Récupération des statistiques issues de la carte
do {
    switch($row_stat_pays['ch_pay_continent']) {
        case "Aurinea" :
            $nbhabitants_Aurinea += $row_stat_pays['ch_pay_population_carte'];
            $nbpays_Aurinea++;
            break;
        case "RFGC" :
            $nbhabitants_RFGC += $row_stat_pays['ch_pay_population_carte'];
            $nbpays_RFGC++;
            break;
        case "Volcania" :
            $nbhabitants_Volcania += $row_stat_pays['ch_pay_population_carte'];
            $nbpays_Volcania++;
            break;
        case "Aldesyl" :
            $nbhabitants_Aldesyl += $row_stat_pays['ch_pay_population_carte'];
            $nbpays_Aldesyl++;
            break;
        case "Oceania" :
            $nbhabitants_Oceania += $row_stat_pays['ch_pay_population_carte'];
            $nbpays_Oceania++;
            break;
        case "Philicie" :
            $nbhabitants_Philicie += $row_stat_pays['ch_pay_population_cartes'];
            $nbpays_Philicie++;
            break;
        default:
            break;
    }
} while($row_stat_pays = mysql_fetch_assoc($stat_pays));
mysql_data_seek($stat_pays, 0);

//Récupération des statistiques issues des villes
do {
    switch($row_stat_ville['ch_pay_continent']) {
        case "Aurinea" :
            $nbvilles_Aurinea += $row_stat_ville['nbville'];
            $nbhabitants_Aurinea += $row_stat_ville['nbhabitant'];
            break;
        case "RFGC" :
            $nbvilles_RFGC += $row_stat_ville['nbville'];
            $nbhabitants_RFGC += $row_stat_ville['nbhabitant'];
            break;
        case "Volcania" :
            $nbvilles_Volcania += $row_stat_ville['nbville'];
            $nbhabitants_Volcania += $row_stat_ville['nbhabitant'];
            break;
        case "Aldesyl" :
            $nbvilles_Aldesyl += $row_stat_ville['nbville'];
            $nbhabitants_Aldesyl += $row_stat_ville['nbhabitant'];
            break;
        case "Oceania" :
            $nbvilles_Oceania += $row_stat_ville['nbville'];
            $nbhabitants_Oceania += $row_stat_ville['nbhabitant'];
            break;
        case "Philicie" :
            $nbvilles_Philicie += $row_stat_ville['nbville'];
            $nbhabitants_Philicie += $row_stat_ville['nbhabitant'];
            break;
        default:
            break;
    }
} while($row_stat_ville = mysql_fetch_assoc($stat_ville));
mysql_data_seek($stat_ville, 0);

$nbpays_Aurinea = $nbpays_Aurinea + 1;
$nbvilles_Aurinea = $nbvilles_Aurinea + $nbvilles_RFGC;
$nbhabitants_Aurinea = $nbhabitants_Aurinea + $nbhabitants_RFGC;


// Liste des pays par continent

$query_listePays = "SELECT ch_pay_id, ch_pay_continent, ch_pay_nom, ch_pay_lien_imgdrapeau, (SELECT COUNT(ch_vil_ID) FROM villes WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3) AS nbville, (SELECT SUM(ch_vil_population) FROM villes WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3) + ch_pay_population_carte AS nbhabitant FROM pays WHERE ch_pay_publication = 1 Group By ch_pay_id ORDER BY ch_pay_nom ASC";
$listePays = mysql_query($query_listePays, $maconnexion) or die(mysql_error());
$row_listePays = mysql_fetch_assoc($listePays);
$totalRows_listePays = mysql_num_rows($listePays);

// Fin de la temporisation de sortie
if($_SERVER["REMOTE_ADDR"] === '127.0.0.1') {
    ob_end_clean();
}

// Ressources
$paysResources = \App\Services\EconomyService::getPaysResources();

?>
<!DOCTYPE html>
<!-- head Html -->
<html lang="fr">
<head>
<title>Monde GC - La carte</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="Carto/OLdefault.css" rel="stylesheet">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
    $(document).ready(function () {
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
        <h1>Carte du Monde</h1>
        <p>&nbsp;</p>
        <h4>Cliquez sur un élément de la carte pour en savoir plus.</h4>
        <div class="well">
          <a href="<?= route('map') ?>">Afficher en plein écran</a>
        </div>
      </div>
    </div>
  </div>
</header>
<div class="container corps-page">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <!-- Liste pays
    ================================================== -->

  <!-- Continent RFGC
    ================================================== -->
  <div class="titre-gris anchor" id="RFGC">
    <h2>Pays : République fédérale de Gécée</h2>
  </div>
  <div class="stat-continent pull-center">
    <p><span class="stat"><span class="chiffre"><?php echo $nbpays_RFGC; ?></span> provinces</span> <span class="stat"><span class="chiffre"><?php echo $nbvilles_RFGC; ?></span> villes</span> <span class="stat"><span class="chiffre">
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
            renderElement('pays/liste_pays', [
                    'pays' => $row_listePays,
                    'temperance' => $paysResources[$row_listePays['ch_pay_id']]['resources'],
                ]);
			}
        } while ($row_listePays = mysql_fetch_assoc($listePays));
		  mysql_data_seek($listePays,0); ?>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>

  <!-- Continent Aurinea
    ================================================== -->
  <div class="titre-gris anchor" id="liste-pays">
    <h2>Continent Aurin&eacute;a</h2>
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
            renderElement('pays/liste_pays', [
                    'pays' => $row_listePays,
                    'temperance' => $paysResources[$row_listePays['ch_pay_id']]['resources'],
                ]);
			}
        } while ($row_listePays = mysql_fetch_assoc($listePays));
        mysql_data_seek($listePays,0); ?>

        <!-- RFGC -->
        <li>
          <div class="row-fluid">
            <div class="span2"> <a href="#RFGC"><img src="assets/img/Drapeau-RFGC.jpg" width="100" alt="drapeau"></a> </div>
            <div class="span4">
              <h3>R&eacute;publique F&eacute;d&eacute;rale de G&eacute;n&eacute;ration City</h3>
            </div>
            <div class="span4">
              <p>La RFGC est un pays communautaire ouvert à tous, il est divisé en deux provinces.</p>
              <p><strong>
                <?= number_format($nbhabitants_RFGC, 0, ',', ' '); ?>
                </strong> habitants</p>
            </div>
            <div class="span2"> <a href="#RFGC" class="btn btn-primary">Voir les provinces</a> </div>
          </div>
        </li>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>

  <!-- Continent Volcania
    ================================================== -->
  <div class="titre-gris anchor" id="Volcania">
    <h2>Continent Volcania</h2>
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
            renderElement('pays/liste_pays', [
                    'pays' => $row_listePays,
                    'temperance' => $paysResources[$row_listePays['ch_pay_id']]['resources'],
                ]);
			}
        } while ($row_listePays = mysql_fetch_assoc($listePays));
        mysql_data_seek($listePays,0); ?>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>

  <!-- Continent Aldesyl
    ================================================== -->
  <div class="titre-gris anchor" id="Aldesyl">
    <h2>Continent Aldesyl</h2>
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
            renderElement('pays/liste_pays', [
                    'pays' => $row_listePays,
                    'temperance' => $paysResources[$row_listePays['ch_pay_id']]['resources'],
                ]);
			}
        } while ($row_listePays = mysql_fetch_assoc($listePays));
        mysql_data_seek($listePays, 0); ?>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>

  <!-- Continent Oceania
    ================================================== -->
  <div class="titre-gris anchor" id="Oceania">
    <h2>Continent Oceania</h2>
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
		renderElement('pays/liste_pays', [
                'pays' => $row_listePays,
                'temperance' => $paysResources[$row_listePays['ch_pay_id']]['resources'],
            ]);
            }
        } while ($row_listePays = mysql_fetch_assoc($listePays));
        mysql_data_seek($listePays,0); ?>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>

  <!-- Continent Philicie
    ================================================== -->
  <div class="titre-gris anchor" id="Philicie">
    <h2>Continent Philicie</h2>
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
		    renderElement('pays/liste_pays', [
                'pays' => $row_listePays,
                'temperance' => $paysResources[$row_listePays['ch_pay_id']]['resources'],
            ]);
            }
        } while ($row_listePays = mysql_fetch_assoc($listePays));
        mysql_data_seek($listePays,0); ?>
        <p>&nbsp;</p>
      </ul>
    </div>
  </div>
</div>

<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
</body>
</html>
