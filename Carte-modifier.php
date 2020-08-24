<?php

use App\Models\CustomUser;
use App\Models\Geometry;
use App\Models\Pays as EloquentPays;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

//Connexion et deconnexionw
include("php/log.php");

if(!isset($_SESSION['userObject'])) {
    // Redirection vers Haut Conseil
    header("Status: 301 Moved Permanently", false, 301);
    header(sprintf("Location: %s", legacyPage('connexion')));
    exit();
}

//Récupération variables
$colname_paysID = $_REQUEST['paysID'];

//Requete Pays

$query_InfoGenerale = sprintf("SELECT * FROM pays WHERE ch_pay_id = %s", GetSQLValueString($colname_paysID, "int"));
$InfoGenerale = mysql_query($query_InfoGenerale, $maconnexion) or die(mysql_error());
$row_InfoGenerale = mysql_fetch_assoc($InfoGenerale);
$totalRows_InfoGenerale = mysql_num_rows($InfoGenerale);

$user_has_perm = $_SESSION['userObject']->minStatus('OCGC');
$nonModifiableZones = ['terre', 'frontiere'];

// Vérifier permissions
$eloquentPays = EloquentPays::findOrFail($colname_paysID);
if( !auth()->user()->hasMinPermission('ocgc') &&
    !auth()->user()->ownsPays($eloquentPays) )
{
    throw new AccessDeniedHttpException();
}

// Init variables.
$surface = $tot_budget = $tot_industrie = $tot_commerce = $tot_agriculture = $tot_tourisme = $tot_recherche = $tot_environnement = $tot_education = $tot_emploi = 0;

/**
 * INSERER GEOMETRIE
 */
if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout_feature")) {

    $isSpecialZone = in_array($_POST['ch_geo_type'], $nonModifiableZones);
    if(!$user_has_perm && $isSpecialZone) {
        getErrorMessage('error', "Vous ne pouvez pas créer de zone de type "
            . __s($_POST['ch_geo_type']) . ".");
    }

    else {
        $insertSQL = sprintf("INSERT INTO geometries (ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_mesure, ch_geo_type, ch_geo_nom) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            GetSQLValueString($_POST['ch_geo_wkt'], "text"),
            GetSQLValueString($colname_paysID, "int"),
            GetSQLValueString($_POST['ch_geo_user'], "int"),
            GetSQLValueString($_POST['ch_geo_maj_user'], "int"),
            GetSQLValueString($_POST['ch_geo_date'], "date"),
            GetSQLValueString($_POST['ch_geo_mis_jour'], "date"),
            GetSQLValueString($_POST['ch_geo_geometries'], "text"),
            GetSQLValueString($_POST['ch_geo_mesure'], "int"),
            GetSQLValueString($_POST['ch_geo_type'], "text"),
            GetSQLValueString($_POST['ch_geo_nom'], "text"));

        $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

        getErrorMessage('success', "La zone " . __s($_POST['ch_geo_nom']) . ' a été ajoutée !');
    }

    //recherche des mesures des zones de la carte pour calcul ressources
    $query_geometries = sprintf("SELECT SUM(ch_geo_mesure) as mesure, ch_geo_type FROM geometries WHERE ch_geo_pay_id = %s GROUP BY ch_geo_type ORDER BY ch_geo_geometries", GetSQLValueString($colname_paysID, "int"));
    $geometries = mysql_query($query_geometries, $maconnexion) or die(mysql_error());

    //Calcul total des ressources de la carte.
    while($row_geometries = mysql_fetch_assoc($geometries)) {
        $surface = $row_geometries['mesure'];
        $typeZone = $row_geometries['ch_geo_type'];
        ressourcesGeometrie($surface, $typeZone, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population, $emploi);
        $tot_budget = $tot_budget + $budget;
        $tot_industrie = $tot_industrie + $industrie;
        $tot_commerce = $tot_commerce + $commerce;
        $tot_agriculture = $tot_agriculture + $agriculture;
        $tot_tourisme = $tot_tourisme + $tourisme;
        $tot_recherche = $tot_recherche + $recherche;
        $tot_environnement = $tot_environnement + $environnement;
        $tot_education = $tot_education + $education;
        $tot_population = $tot_population + $population;
        $tot_emploi = $tot_emploi + $emploi;
    }

    //Enregistrement du total des ressources de la carte.
    $updateSQL = sprintf("UPDATE pays SET ch_pay_budget_carte=%s, ch_pay_industrie_carte=%s, ch_pay_commerce_carte=%s, ch_pay_agriculture_carte=%s, ch_pay_tourisme_carte=%s, ch_pay_recherche_carte=%s, ch_pay_environnement_carte=%s, ch_pay_education_carte=%s, ch_pay_population_carte=%s, ch_pay_emploi_carte=%s WHERE ch_pay_id=%s",
        GetSQLValueString($tot_budget, "int"),
        GetSQLValueString($tot_industrie, "int"),
        GetSQLValueString($tot_commerce, "int"),
        GetSQLValueString($tot_agriculture, "int"),
        GetSQLValueString($tot_tourisme, "int"),
        GetSQLValueString($tot_recherche, "int"),
        GetSQLValueString($tot_environnement, "int"),
        GetSQLValueString($tot_education, "int"),
        GetSQLValueString($tot_population, "int"),
        GetSQLValueString($tot_emploi, "int"),
        GetSQLValueString($colname_paysID, "int"));

    $Result2 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
    mysql_free_result($geometries);
}


/**
 * MODIFIER GEOMETRIE
 */
if((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "modifier_feature")) {
    $paysid = $_POST['ch_geo_pay_id'];

    // Obtenir l'ancienne version de l'élément.
    $eloquentGeometry = Geometry::findOrFail($_POST['ch_geo_id']);
    $isSpecialZone = in_array($eloquentGeometry->ch_geo_type, $nonModifiableZones);
    if(!$user_has_perm && $isSpecialZone) {
        getErrorMessage('error', "Vous ne pouvez pas modifier de zone de type "
            . __s($eloquentGeometry->ch_geo_type) . ". Vous ne disposez pas des permissions "
            . "nécessaires.");
    }

    else {
        $updateSQL = sprintf("UPDATE geometries SET ch_geo_wkt=%s, ch_geo_pay_id=%s, ch_geo_user=%s, ch_geo_maj_user=%s, ch_geo_date=%s, ch_geo_mis_jour=%s, ch_geo_geometries=%s, ch_geo_mesure=%s, ch_geo_type=%s, ch_geo_nom=%s WHERE ch_geo_id=%s",
            GetSQLValueString($_POST['ch_geo_wkt'], "text"),
            GetSQLValueString($_POST['ch_geo_pay_id'], "int"),
            GetSQLValueString($_POST['ch_geo_user'], "int"),
            GetSQLValueString($_POST['ch_geo_maj_user'], "int"),
            GetSQLValueString($_POST['ch_geo_date'], "date"),
            GetSQLValueString($_POST['ch_geo_mis_jour'], "date"),
            GetSQLValueString($_POST['ch_geo_geometries'], "text"),
            GetSQLValueString($_POST['ch_geo_mesure'], "decimal"),
            GetSQLValueString($_POST['ch_geo_type'], "text"),
            GetSQLValueString($_POST['ch_geo_nom'], "text"),
            GetSQLValueString($_POST['ch_geo_id'], "int"));

        $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
    }

    //recherche des mesures des zones de la carte pour calcul ressources

    $query_geometries = sprintf("SELECT SUM(ch_geo_mesure) as mesure, ch_geo_type FROM geometries WHERE ch_geo_pay_id = %s AND ch_geo_type != 'maritime' AND ch_geo_type != 'region' GROUP BY ch_geo_type ORDER BY ch_geo_geometries", GetSQLValueString($paysid, "int"));
    $geometries = mysql_query($query_geometries, $maconnexion) or die(mysql_error());

    //Calcul total des ressources de la carte.
    while($row_geometries = mysql_fetch_assoc($geometries)) {
        $surface = $row_geometries['mesure'];
        $typeZone = $row_geometries['ch_geo_type'];
        ressourcesGeometrie($surface, $typeZone, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population, $emploi);
        $tot_budget = $tot_budget + $budget;
        $tot_industrie = $tot_industrie + $industrie;
        $tot_commerce = $tot_commerce + $commerce;
        $tot_agriculture = $tot_agriculture + $agriculture;
        $tot_tourisme = $tot_tourisme + $tourisme;
        $tot_recherche = $tot_recherche + $recherche;
        $tot_environnement = $tot_environnement + $environnement;
        $tot_education = $tot_education + $education;
        $tot_population = $tot_population + $population;
        $tot_emploi = $tot_emploi + $emploi;
    }

    //Enregistrement du total des ressources de la carte.
    $updateSQL = sprintf("UPDATE pays SET ch_pay_budget_carte=%s, ch_pay_industrie_carte=%s, ch_pay_commerce_carte=%s, ch_pay_agriculture_carte=%s, ch_pay_tourisme_carte=%s, ch_pay_recherche_carte=%s, ch_pay_environnement_carte=%s, ch_pay_education_carte=%s, ch_pay_population_carte=%s, ch_pay_emploi_carte=%s WHERE ch_pay_id=%s",
        GetSQLValueString($tot_budget, "int"),
        GetSQLValueString($tot_industrie, "int"),
        GetSQLValueString($tot_commerce, "int"),
        GetSQLValueString($tot_agriculture, "int"),
        GetSQLValueString($tot_tourisme, "int"),
        GetSQLValueString($tot_recherche, "int"),
        GetSQLValueString($tot_environnement, "int"),
        GetSQLValueString($tot_education, "int"),
        GetSQLValueString($tot_population, "int"),
        GetSQLValueString($tot_emploi, "int"),
        GetSQLValueString($paysid, "int"));

    $Result2 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
    mysql_free_result($geometries);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Monde GC - Modifier la carte</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="Carto/OLdefault.css" rel="stylesheet">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Merriweather+Sans:400,700|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
<style>
    .jumbotron {
        background-image: url("assets/img/ImgIntroheader.jpg");
    }
    #map {
        height: 500px;
        background: #FFFFFF;
        color: rgba(0, 0, 0, 1)
    }
    img.olTileImage {
        max-width: none;
    }
    @media (max-width: 480px) {
        #map {
            height: 360px;
        }
    }
    div.olControlPanel {
        top: 0px;
        left: 50px;
        position: absolute;
    }
    .olControlPanel div {
        display: block;
        width: 22px;
        height: 22px;
        border: thin solid black;
        margin-top: 10px;
        background-color: white
    }
    div.editPanel {
        top: 77px;
        right: 0px;
        position: absolute;
    }
    .editPanel div {
        background-image: url("Carto/images/edit_sprite.png");
        background-repeat: no-repeat;
        width: 40px;
        height: 30px;
        border: none;
        margin-top: 5px;
        cursor: pointer;
    }
    .lineButtonItemInactive {
        background-position: 0px 0px;
    }
    .lineButtonItemActive {
        background-position: 0px -30px;
    }
    .polygonButtonItemInactive {
        background-position: 0px -60px;
    }
    .polygonButtonItemActive {
        background-position: 0px -90px;
    }
    .olControlNavigationItemInactive {
        background-position: 0px -120px;
    }
    .olControlNavigationItemActive {
        background-position: 0px -150px;
    }
    .ModifyLineButtonItemInactive {
        background-position: 0px -180px;
    }
    .ModifyLineButtonItemActive {
        background-position: 0px -210px;
    }
    .ModifyPolygonButtonItemInactive {
        background-position: 0px -240px;
    }
    .ModifyPolygonButtonItemActive {
        background-position: 0px -270px;
    }
    .ModifyAdministrativeButtonItemInactive {
        background-position: 0px -300px;
    }
    .ModifyAdministrativeButtonItemActive {
        background-position: 0px -330px;
    }
</style>
</head>
<body>
<!-- Navbar
    ================================================== -->
<?php $carte=true; include("php/navbar.php"); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron anchor" id="carte-generale">
  <div class="container-fluid container-carte"> 
    <!-- Carte desktop
    ================================================== -->
    <div class="row-fluid">
      <div class="span9">
        <div id="map"></div>
      </div>
      <div id="info">
        <h1>Modifier la carte</h1>
          <?php renderElement('errormsgs'); ?>
        <p>Cliquez sur les outils &agrave; droite de la carte pour ajouter ou modifier des &eacute;l&eacute;ments.</p>
        <p>Utilsez le premier outil pour ajouter votre premi&egrave;re route ou le troisi&egrave;me outil lors &agrave; sauvegarder votre trac&eacute;.</p>
        <p>Utilisez Ctrl-Z ou Cmd-Z pour annuler les derniers points trac&eacute;s.</p>
        <p>Utilisez Ctrl-Y ou Cmd-Y pour r&eacute;tablir les derniers points annul&eacute;s.</p>
        <p>Utilisez Shift et le cliquer-glisser pour d&eacute;ssiner directement avec la souris.</p>
      </div>
    </div>
  </div>
</header>
<div class="container corps-page">

    <ul class="breadcrumb">
      <li><a href="<?= DEF_URI_PATH ?>back/page_pays_back.php?paysID=<?= e($eloquentPays->ch_pay_id) ?>">Gestion du pays : <?= e($eloquentPays->ch_pay_nom) ?></a> <span class="divider">/</span></li>
      <li class="active">Modifier la carte</a></li>
    </ul>

  <!-- Balance des ressources  -->
  <div class="well" style="margin-top: -15px;">
    <a class="btn btn-primary pull-right" title="Voir le détail des ressources"
       href="php/ressource-rapport-carte.php?ch_pay_id=<?= e($colname_paysID) ?>"
       data-toggle="modal" data-target="#Modal-Monument">
        Détail par type de géométrie</a>
    <h4>
        Balance des ressources issues de la carte du pays
        <?= e($row_InfoGenerale['ch_pay_nom']) ?>
    </h4>

    <div class="row-fluid">
      <div class="span8">
          <?php
          $resources = [];
          foreach(['budget', 'agriculture', 'commerce', 'education',
             'environnement', 'industrie', 'recherche', 'tourisme'] as $resource)
          {
              $resources[$resource] = $row_InfoGenerale["ch_pay_{$resource}_carte"];
          }
          renderElement('temperance/resources_small', [
                  'resources' => $resources
          ]); ?>
      </div>
      <div class="span4">
        <p>Population rurale&nbsp;: <?= number_format($row_InfoGenerale['ch_pay_population_carte'], 0, ',', ' '); ?> habitants</p>
      </div>
    </ul>
    </div>
  </div>

  <!-- Explication  -->
  <div id="titre" class="titre-vert anchor">
    <h1>Modifier la carte du monde GC</h1>
  </div>
  <div class="well">
    <p><strong>Chaque zone ajout&eacute;e sur votre emplacement va avoir une influence sur l'&eacute;conomie de votre pays et modifier vos ressources.</strong> Vous pouvez consulter sur cette page l'influence de chaque zone. N'oubliez pas d'enregistrer syst&eacute;matiquement &agrave; chaque fois que vous modifiez ou ajoutez une zone. Vous ne pouvez modifier ou ajouter qu'une seule zone &agrave; la fois.</p>
    <ol>
      <li>Vous devez dessiner des zones et des routes uniquement dans votre emplacement. Les routes doivent se terminer &agrave; la fronti&egrave;re. A vous de solliciter vos voisins pour qu'ils continuent les trac&eacute;s de leurs c&ocirc;t&eacute;s.</li>
      <li>Les zones qui se chevauchent sont interdites.</li>
      <li>Les zones dont les c&ocirc;t&eacute;s se croisent sont interdites.</li>
      <li>Les zones doivent &ecirc;tre trac&eacute;es sur les terres. Il n'existe pas de zone maritimes. Seule exeption : les r&eacute;gions qui peuvent &ecirc;tre trac&eacute;es sur les territoires maritimes en respectant la forme de l'emplacement au large des c&ocirc;tes dans des proportions raisonnables</li>
      <li>Il est possible de tracer des routes maritimes &agrave; l'int&eacute;rieur de votre pays ou pour des itin&eacute;raires internationaux. Les routes maritimes sont trac&eacute;es par l'Institut de G&eacute;c&eacute;en de G&eacute;ographie. Pour avoir une route maritime, vous devez en faire la demande sur le forum. Pour des itini&eacute;raires internationaux, les deux pays doivent faire cette demande.</li>
      <li>En aucun cas, le trac&eacute; d'une zone peut &ecirc;tre le pr&eacute;texte &agrave; la revendication d'un territoire. Le joueur doit d'abord s'assurer qu'il poss&egrave;de bien les terres avant d'y tracer des &eacute;l&eacute;ments.</li>
      <li>Tout manquement &agrave; ces r&egrave;gles peut faire l'objet d'une mod&eacute;ration de la part du Comité de G&eacute;ographie ou du Conseil de l'OCGC.</li>
    </ol>
  </div>
  <!-- Repartition des ressources  -->
  <div id="titre" class="titre-vert anchor">
    <h1>R&eacute;partition des ressources</h1>
  </div>

  <div class="well">
  <div class="accordion-group">
    <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" href="#repartition-ressources">
            Voir la répartition des ressources
        </a>
    </div>
    <div id="repartition-ressources" class="accordion-body collapse">
        <ul class="listes">
        <li class="info-listes">
           <!-- Desc categorie -->
          <h4>Zone maritime protégée</h4>
          <p>Population&nbsp;: 0 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*-0.04</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*-0.00025</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*-0.00025</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*0.001</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*0.002</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0.00125</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*0.004</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0.0015</strong></p>
              </div>
          </div>
        </li>
        <li class="info-listes">
           <!-- Desc categorie -->
          <h4>Zone de pêche traditionnelle</h4>
          <p>Population&nbsp;: 0 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*0.014</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*0.00025</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*0.00025</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*0.025</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*0</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*-0.001</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0</strong></p>
              </div>
          </div>
        </li>
        <li class="info-listes">
           <!-- Desc categorie -->
          <h4>Zone de pêche intensive</h4>
          <p>Population&nbsp;: 0 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*0.020</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*0.0004</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*0.0003</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*0.04</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*-0.0015</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*-0.003</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0</strong></p>
              </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Zone megapole</h4>
          <p>Population&nbsp;: 500 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*-5</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*0.02</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*0.10</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*-2</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*0.04</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0.04</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*-0.03</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0.04</strong></p>
              </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Zone urbaine</h4>
          <p>Population&nbsp;: 75 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*-1</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*0.04</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*-0.3</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*0</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*-0.08</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0.02</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Zone periurbaine</h4>
          <p>Population&nbsp;: 25 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*-0.25</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*0.005</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*0.005</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*-0.01</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*0</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*-0.0075</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Zone industrielle</h4>
          <p>Population&nbsp;: 50 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*-3.5</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*0.16</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*0</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*-0.6</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*-0.1</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0.12</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*-0.4</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Zone mara&icirc;chere, Zone c&eacute;reali&egrave;re, Zone d'&eacute;levage</h4>
          <p>Population&nbsp;: 2 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*0.028</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*0.0005</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*0.0005</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*0.05</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*0</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*-0.002</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Prairies</h4>
          <p>Population&nbsp;: 0.5 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*-0.001</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*0</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*0.001</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*0.001</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0.0005</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*0.00625</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0.0005</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Zone foresti&egrave;re</h4>
          <p>Population&nbsp;: 0.1 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*0.001</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*-0.00025</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*-0.00025</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*0.001</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*0.001</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0.0005</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*0.002</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0.0007</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Zone foresti&egrave;re prot&eacute;g&eacute;e</h4>
          <p>Population&nbsp;: 0.01 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*-0.04</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*-0.00025</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*-0.00025</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*0.001</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*0.002</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0.00125</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*0.004</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0.0015</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Zone mar&eacute;cageuse</h4>
          <p>Population&nbsp;: 0.01 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*0</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*-0.001</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*-0.001</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*-0.001</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0.001</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*0.002</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0.001</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Zone lagunaire</h4>
          <p>Population&nbsp;: 2 habitants/km<sup>2</sup></p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>surface*0</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>surface*0.001</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>surface*0.001</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>surface*-0.001</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>surface*0.0125</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>surface*0.001</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>surface*0.005</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>surface*0.001</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Lignes &agrave; Grande Vitesse</h4>
          <p>Population&nbsp;: 0 habitants/km</p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>longueur*-1,5</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>longueur*0</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>longueur*0.025</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>longueur*0.025</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>longueur*-0.02</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>longueur*0.025</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Voie Express</h4>
          <p>Population&nbsp;: 0 habitants/km</p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>longueur*-0.25</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>longueur*0.005</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>longueur*0.005</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>longueur*0.005</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>longueur*-0.01</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>longueur*0</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Routes nationales</h4>
          <p>Population&nbsp;: 0 habitants/km</p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>longueur*-0.01</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>longueur*0.002</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>longueur*0.002</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>longueur*0.002</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>longueur*-0.004</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>longueur*0</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Autoroute</h4>
          <p>Population&nbsp;: 0 habitants/km</p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>longueur*-0.5</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>longueur*0.01</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>longueur*0.01</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>longueur*0.005</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>longueur*-0.04</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>longueur*0</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Chemin de Fer</h4>
          <p>Population&nbsp;: 0 habitants/km</p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>longueur*-0.75</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>longueur*0.015</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>longueur*0.015</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>longueur*0.015</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>longueur*-0.01</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>longueur*0</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Canal</h4>
          <p>Population&nbsp;: 0 habitants/km</p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>longueur*-1</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>longueur*0.03</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>longueur*0</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>longueur*0.03</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>longueur*0.03</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>longueur*0</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Ferry</h4>
          <p>Population&nbsp;: 0 habitants/km</p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>longueur*-0.75</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>longueur*0.015</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>longueur*0.015</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>longueur*0.015</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>longueur*-0.005</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>longueur*0</strong></p>
            </div>
          </div>
        </li>
        <li class="info-listes">
          <!-- Desc categorie -->
          <h4>Route maritime</h4>
          <p>Population&nbsp;: 0 habitants/km</p>
          <div class="row-fluid">
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/budget.png" alt="icone Budget">
              <p>Budget&nbsp;: <strong>longueur*-0.25</strong></p>
              <img src="assets/img/ressources/industrie.png" alt="icone Industrie">
              <p>Industrie&nbsp;: <strong>longueur*0.005</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/bureau.png" alt="icone Commerce">
              <p>Commerce&nbsp;: <strong>longueur*0.01</strong></p>
              <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture">
              <p>Agriculture&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme">
              <p>Tourisme&nbsp;: <strong>longueur*0</strong></p>
              <img src="assets/img/ressources/recherche.png" alt="icone Recherche">
              <p>Recherche&nbsp;: <strong>longueur*0</strong></p>
            </div>
            <div class="span3 icone-ressources"> <img src="assets/img/ressources/environnement.png" alt="icone Evironnement">
              <p>Environnement&nbsp;: <strong>longueur*-0.01</strong></p>
              <img src="assets/img/ressources/education.png" alt="icone Education">
              <p>Education&nbsp;: <strong>longueur*0</strong></p>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
  </div>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <div class="modal container fade" id="Modal-Monument"></div>
</div>

<!-- Footer
    ================================================== -->
<?php include("php/footer.php"); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- CARTE -->
<script src="assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="assets/js/OpenLayers.js" type="text/javascript"></script>
<?php include("php/carte-modifier-zone.php"); ?>
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
    $(function () {
        $('[rel="clickover"]').clickover();
    })
</script>
<script>
    $(document).ready(function () {
        init();
    });
</script>
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>

<script>
    $("a[data-toggle=modal]").click(function (e) {
        lv_target = $(this).attr('data-target')
        lv_url = $(this).attr('href')
        $(lv_target).load(lv_url)
    })

    $('#closemodal').click(function () {
        $('#Modal-Monument').modal('hide');
    });
</script>
</body>
</html>