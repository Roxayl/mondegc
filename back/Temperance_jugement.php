<?php

require_once('../Connections/maconnexion.php');
//deconnexion
include('../php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=15))
{
} else {
	// Redirection vers page connexion
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ../Juges-temperants.php');
    exit();
}

//requete liste temperances
$maxRows_liste_temperance = 10;
$pageNum_liste_temperance = 0;
if (isset($_GET['pageNum_liste_temperance'])) {
  $pageNum_liste_temperance = $_GET['pageNum_liste_temperance'];
}
$startRow_liste_temperance = $pageNum_liste_temperance * $maxRows_liste_temperance;

mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_temperance = sprintf("SELECT ch_temp_id as id, ch_pay_nom as nom, ch_temp_element as element, ch_temp_date as date, ch_temp_mis_jour as mis_jour, ch_temp_statut as statut FROM temperance INNER JOIN pays ON ch_temp_element_id = ch_pay_id WHERE ch_temp_id NOT IN (SELECT ch_not_temp_temperance_id FROM notation_temperance WHERE ch_not_temp_juge=%s) AND ch_temp_element='pays' AND ch_temp_statut = 2
UNION
SELECT ch_temp_id as id, ch_vil_nom as nom, ch_temp_element as element, ch_temp_date as date, ch_temp_mis_jour as mis_jour, ch_temp_statut as statut FROM temperance INNER JOIN villes ON ch_temp_element_id = ch_vil_ID WHERE ch_temp_id NOT IN (SELECT ch_not_temp_temperance_id FROM notation_temperance WHERE ch_not_temp_juge=%s) AND ch_temp_element='ville' AND ch_temp_statut = 2
 ORDER BY date desc", GetSQLValueString($juge, "text"), GetSQLValueString($juge, "text"));
$query_limit_liste_temperance = sprintf("%s LIMIT %d, %d", $query_liste_temperance, $startRow_liste_temperance, $maxRows_liste_temperance);
$liste_temperance = mysql_query($query_limit_liste_temperance, $maconnexion) or die(mysql_error());
$row_liste_temperance = mysql_fetch_assoc($liste_temperance);

if (isset($_GET['totalRows_liste_temperance'])) {
  $totalRows_liste_temperance = $_GET['totalRows_liste_temperance'];
} else {
  $all_liste_temperance = mysql_query($query_liste_temperance);
  $totalRows_liste_temperance = mysql_num_rows($all_liste_temperance);
}
$totalPages_liste_temperance = ceil($totalRows_liste_temperance/$maxRows_liste_temperance)-1;

$queryString_liste_temperance = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_liste_temperance") == false && 
        stristr($param, "totalRows_liste_temperance") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_liste_temperance = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_liste_temperance = sprintf("&totalRows_liste_temperance=%d%s", $totalRows_liste_temperance, $queryString_liste_temperance);


//choix tri des infrastructures
$colname_type_jugement = "1";
if (isset($_GET['inf'])) {
	$colname_type_jugement = $_GET['inf'];
} 

if ($colname_type_jugement ==1) {
	$ordre_classement = "ch_inf_date";
} else {
	$ordre_classement = "ch_pay_nom, ch_inf_date";
} 


$juge = $_SESSION['login_user'];



//requete liste infrastructures en attente
$maxRows_liste_infrastructures = 10;
$pageNum_liste_infrastructures = 0;
if (isset($_GET['pageNum_liste_infrastructures'])) {
  $pageNum_liste_infrastructures = $_GET['pageNum_liste_infrastructures'];
}
$startRow_liste_infrastructures = $pageNum_liste_infrastructures * $maxRows_liste_infrastructures;

mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_infrastructures = sprintf("SELECT infrastructures.*, infrastructures_officielles.*, ch_vil_nom, ch_pay_id, ch_pay_nom FROM infrastructures INNER JOIN infrastructures_officielles ON infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID INNER JOIN pays ON ch_vil_paysID=ch_pay_id WHERE ch_inf_statut=%s GROUP BY ch_inf_id ORDER BY $ordre_classement", GetSQLValueString($colname_type_jugement, "text"));
$query_limit_liste_infrastructures = sprintf("%s LIMIT %d, %d", $query_liste_infrastructures, $startRow_liste_infrastructures, $maxRows_liste_infrastructures);
$liste_infrastructures = mysql_query($query_limit_liste_infrastructures, $maconnexion) or die(mysql_error());
$row_liste_infrastructures = mysql_fetch_assoc($liste_infrastructures);

if (isset($_GET['totalRows_liste_infrastructures'])) {
  $totalRows_liste_infrastructures = $_GET['totalRows_liste_infrastructures'];
} else {
  $all_liste_infrastructures = mysql_query($query_liste_infrastructures);
  $totalRows_liste_infrastructures = mysql_num_rows($all_liste_infrastructures);
}
$totalPages_liste_infrastructures = ceil($totalRows_liste_infrastructures/$maxRows_liste_infrastructures)-1;

$queryString_liste_infrastructures = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_liste_infrastructures") == false && 
        stristr($param, "totalRows_liste_infrastructures") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_liste_infrastructures = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_liste_infrastructures = sprintf("&totalRows_liste_infrastructures=%d%s", $totalRows_liste_infrastructures, $queryString_liste_infrastructures);



$_SESSION['last_work'] = "institut_economie.php";
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Projet Tempérance : salle de jugement</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="../assets/css/colorpicker.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('');
}
</style>
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
    </script>
<!-- Color Picker  -->
<script src="../assets/js/bootstrap-colorpicker.js" type="text/javascript"></script>
<!-- MODAL -->
<script src="../assets/js/bootstrap-modalmanager.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>
<!-- Subhead
================================================== -->
<div class="container" id="overview"> 
  
  <!-- Page CONTENT
    ================================================== -->
  <section class="corps-page"> 
    <!-- Titre page
        ================================================== -->
    <div id="titre_institut" class="titre-bleu anchor">
      <h1>Projet Tempérance : salle de jugement</h1>
    </div>

    <ul class="breadcrumb">
      <li><a href="../OCGC.php">OCGC</a> <span class="divider">/</span></li>
      <li><a href="../economie.php">Économie</a> <span class="divider">/</span></li>
      <li class="active">Projet Tempérance : salle de jugement</li>
    </ul>
    
    <!-- Liste des infrstructures officielles
     ================================================== -->
    <div class="row-fluid">
    <div class="span12">
    <div class="titre-gris anchor" id="liste-infrastructures">
      <?php if ($colname_type_jugement == '2') {?>
      <h3>Liste des infrastructures accept&eacute;es</h3>
      <?php } elseif ($colname_type_jugement == '3') { ?>
      <h3>Liste des infrastructures refus&eacute;es</h3>
      <?php } else  { ?>
      <h3>Liste des infrastructures en attente de jugement</h3>
      <?php } ?>
    </div>
    <!-- choix ressources  -->
    <form action="Temperance_jugement.php#liste-infrastructures" method="GET">
      <select name="inf" id="inf" onchange="this.form.submit()" class="span4">
        <option value="1" <?php if ($colname_type_jugement == '1') {?>selected<?php } ?>>Infrastructures non jug&eacute;es</option>
        <option value="2" <?php if ($colname_type_jugement == '2') {?>selected<?php } ?>>Modifier les infrastructures d&eacute;j&agrave; accept&eacute;es</option>
        <option value="3" <?php if ($colname_type_jugement == '3') {?>selected<?php } ?>>Modifier les infrastructures d&eacute;j&agrave; refus&eacute;es</option>
      </select>
    </form>
    <?php if ($row_liste_infrastructures) { ?>
    <ul class="listes">
      <?php do { ?>
        <li class="row-fluid"> 
          <!-- ICONE infrastructures -->
          <div class="span2">
              <img src="<?= __s($row_liste_infrastructures['ch_inf_lien_image']) ?>"
                   alt="icone <?= __s($row_liste_infrastructures['ch_inf_off_nom']) ?>">
          </div>
          <!-- contenu categorie -->
          <div class="span10 info-listes"> 
            <!-- Boutons modifier / supprimer --> 
            <a class="pull-right btn btn-primary" href="../php/infrastructure-juger-modal.php?ch_inf_id=<?= __s($row_liste_infrastructures['ch_inf_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="juger cette infrastructure"><i class="icon-jugement"></i> Juger</a>
            <!-- Desc categorie -->
            <div class="icone-categorie pull-left"><img src="<?= __s($row_liste_infrastructures['ch_inf_off_icone']) ?>" alt="icone <?= __s($row_liste_infrastructures['ch_inf_off_nom']) ?>"></div>
            <h4><?= __s($row_liste_infrastructures['ch_inf_off_nom']) ?></h4>

            <small><?= __s($row_liste_infrastructures['ch_inf_off_nom']) ?></small>
            <div class="clearfix"></div>
            <p>La ville <a href="../page-ville.php?ch_pay_id=<?= __s($row_liste_infrastructures['ch_pay_id']) ?>&ch_ville_id=<?= __s($row_liste_infrastructures['ch_inf_villeid']) ?>"><?= __s($row_liste_infrastructures['ch_vil_nom']) ?></a> appartenant au pays <a href="../page-pays.php?ch_pay_id=<?= __s($row_liste_infrastructures['ch_pay_id']) ?>"><?= __s($row_liste_infrastructures['ch_pay_nom']) ?></a> souhaite ajouter cette infrastructure &agrave; son &eacute;conomie.</p>
          </div>
        </li>
        <?php } while ($row_liste_infrastructures = mysql_fetch_assoc($liste_infrastructures)); ?>
    </ul>
    <?php } else { ?>
    <div class="well">
      <div class="alert alert-tips">Il n'y a plus de nouvelles infrastructures &agrave; juger.</div>
    </div>
    <?php } ?>
    <!-- Pagination de la liste -->
    <p>&nbsp;</p>
    <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_liste_infrastructures + 1) ?> &agrave; <?php echo min($startRow_liste_infrastructures + $maxRows_liste_infrastructures, $totalRows_liste_infrastructures) ?> sur <?php echo $totalRows_liste_infrastructures ?>
      <?php if ($pageNum_liste_infrastructures > 0) { // Show if not first page ?>
        <a class="btn" href="<?php printf("%s?pageNum_liste_infrastructures=%d%s#liste-categories", $currentPage, max(0, $pageNum_liste_infrastructures - 1), $queryString_liste_infrastructures); ?>"><i class=" icon-backward"></i></a>
        <?php } // Show if not first page ?>
      <?php if ($pageNum_liste_infrastructures < $totalPages_liste_infrastructures) { // Show if not last page ?>
        <a class="btn" href="<?php printf("%s?pageNum_liste_infrastructures=%d%s#liste-categories", $currentPage, min($totalPages_liste_infrastructures, $pageNum_liste_infrastructures + 1), $queryString_liste_infrastructures); ?>"> <i class="icon-forward"></i></a>
        <?php } // Show if not last page ?>
      </small> </p>
    <!-- Modal et script -->
    <div class="modal hide fade" id="Modal-Monument" data-width="760" tabindex="-1" data-focus-on="input:first"></div>
    <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script> 
    <!-- Liste des temperances
     ================================================== -->
    <div class="row-fluid">
    <div class="span12">
    <div class="titre-gris anchor" id="liste-temperance">
      <h3>Liste des temp&eacute;rances en cours</h3>
    </div>
    <?php
    if(!$row_liste_temperance) {
        ?>
        <div class="alert alert-tips">Aucun pays ou ville en cours de jugement.</div>
        <?php
    } else { ?>

        <ul class="listes">
        <?php
        while ($row_liste_temperance = mysql_fetch_assoc($liste_temperance)) { ?>
            <li class="row-fluid liste-temp">
                <!-- affichage du statut -->
                <?php if ($row_liste_temperance['statut'] == 1) {?>
                    <div class="span2 liste-statut-temp" style="background-color: rgba(255,102,0,1);">
                        <p>Phase de lancement</p>
                    </div>
                <?php   } elseif ($row_liste_temperance['statut'] == 2) {?>
                    <div class="span2 liste-statut-temp" style="background-color: rgba(0,204,51,1);">
                        <p>Ouverture notations</p>
                    </div>
                <?php } elseif ($row_liste_temperance['statut'] == 3) {?>
                    <div class="span2 liste-statut-temp" style="background-color: rgba(204,0,0,1);">
                        <p>Cl&ocirc;ture notations</p>
                    </div>
                <?php } elseif ($row_liste_temperance['statut'] == 4) {?>
                    <div class="span2 liste-statut-temp" style="background-color: rgba(153,153,102,1);">
                        <p>R&eacute;diger rapport</p>
                    </div>
                <?php } elseif ($row_liste_temperance['statut'] == 5) {?>
                    <div class="span2 liste-statut-temp" style="background-color: rgba(0,0,255,1);"> <p)>Publi&eacute;e
                        </p>
                    </div>
                <?php  } else {?>
                    <div class="span2 liste-statut-temp" style="background-color: rgba(204,0,0,1);">
                        <p>Statut inconnu</p>
                    </div>
                <?php } ?>
                <!-- Nom element tempere -->
                <div class="span3">
                    <h5><?php echo $row_liste_temperance['element']; ?>&nbsp;: <?php echo $row_liste_temperance['nom']; ?></h5>
                </div>
                <!-- contenu categorie -->
                <div class="span4"><em>Lanc&eacute;e il y a
                        <?php
                        $now= date("Y-m-d G:i:s");
                        $d1 = new DateTime($row_liste_temperance['date']);
                        $d2 = new DateTime($now);
                        $diff = get_timespan_string_hour($d1, $d2);
                        echo $diff;?>
                    </em></div>
                <!-- Boutons modifier -->
                <div class="span2">
                    <?php if ($row_liste_temperance['element'] == "pays") { // visible si pays ?>
                        <a class="btn btn-primary" href="../php/temperance-questionnaire-pays.php?ch_temp_id=<?php echo $row_liste_temperance['id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="remplir le questionnaire de notation temperance">Noter</a>
                    <?php } else { // visible si ville ?>
                        <a class="btn btn-primary" href="../php/temperance-questionnaire-ville.php?ch_temp_id=<?php echo $row_liste_temperance['id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="remplir le questionnaire de notation temperance">Noter</a>
                    <?php } ?>
                </div>
            </li>
        <?php } ?>
        </ul>

        <!-- Pagination de la liste -->
        <p>&nbsp;</p>
        <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_liste_temperance + 1) ?> &agrave; <?php echo min($startRow_liste_temperance + $maxRows_liste_temperance, $totalRows_liste_temperance) ?> sur <?php echo $totalRows_liste_temperance ?>
          <?php if ($pageNum_liste_temperance > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_temperance=%d%s#liste-categories", $currentPage, max(0, $pageNum_liste_temperance - 1), $queryString_liste_temperance); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
          <?php if ($pageNum_liste_temperance < $totalPages_liste_temperance) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_temperance=%d%s#liste-categories", $currentPage, min($totalPages_liste_temperance, $pageNum_liste_temperance + 1), $queryString_liste_temperance); ?>"> <i class="icon-forward"></i></a>
            <?php } // Show if not last page ?>
          </small> </p>

    <?php } ?>

    <!-- Modal et script -->
    <div class="modal container fade" id="Modal-Monument" data-width="760"></div>
    <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script> 
  </section>
</div>
<!-- END CONTENT
    ================================================== --> 

<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>
</body>
</html>
<?php
mysql_free_result($liste_infrastructures);
?>