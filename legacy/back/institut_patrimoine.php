<?php

//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=20))
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ' . legacyPage('connexion'));
exit();
	   }

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-categorie")) {
$insertSQL = sprintf("INSERT INTO monument_categories (ch_mon_cat_label, ch_mon_cat_statut, ch_mon_cat_date, ch_mon_cat_mis_jour, ch_mon_cat_nb_update, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_mon_cat_label'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_statut'], "int"),
                       GetSQLValueString($_POST['ch_mon_cat_date'], "date"),
                       GetSQLValueString($_POST['ch_mon_cat_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_mon_cat_nb_update'], "int"),
                       GetSQLValueString($_POST['ch_mon_cat_nom'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_desc'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_icon'], "text"),
					             GetSQLValueString($_POST['ch_mon_cat_couleur'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_quete'], "text"));
					   

  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = DEF_URI_PATH . "back/institut_patrimoine.php";
  appendQueryString($insertGoTo);
  header(sprintf("Location: %s", $insertGoTo));
 exit;
}
					   
//requete instituts
$institut_id = 3;

$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

//requete liste categories monuments
$maxRows_liste_mon_cat = 10;
$pageNum_liste_mon_cat = 0;
if (isset($_GET['pageNum_liste_mon_cat'])) {
  $pageNum_liste_mon_cat = $_GET['pageNum_liste_mon_cat'];
}
$startRow_liste_mon_cat = $pageNum_liste_mon_cat * $maxRows_liste_mon_cat;


$query_liste_mon_cat = "SELECT * FROM monument_categories ORDER BY ch_mon_cat_mis_jour DESC";
$query_limit_liste_mon_cat = sprintf("%s LIMIT %d, %d", $query_liste_mon_cat, $startRow_liste_mon_cat, $maxRows_liste_mon_cat);
$liste_mon_cat = mysql_query($query_limit_liste_mon_cat, $maconnexion) or die(mysql_error());
$row_liste_mon_cat = mysql_fetch_assoc($liste_mon_cat);

if (isset($_GET['totalRows_liste_mon_cat'])) {
  $totalRows_liste_mon_cat = $_GET['totalRows_liste_mon_cat'];
} else {
  $all_liste_mon_cat = mysql_query($query_liste_mon_cat);
  $totalRows_liste_mon_cat = mysql_num_rows($all_liste_mon_cat);
}
$totalPages_liste_mon_cat = ceil($totalRows_liste_mon_cat/$maxRows_liste_mon_cat)-1;

$queryString_liste_mon_cat = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_liste_mon_cat") == false && 
        stristr($param, "totalRows_liste_mon_cat") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_liste_mon_cat = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_liste_mon_cat = sprintf("&totalRows_liste_mon_cat=%d%s", $totalRows_liste_mon_cat, $queryString_liste_mon_cat);

//requete liste categories monuments pour pouvoir selectionner la categorie 

$query_liste_mon_cat2 = "SELECT * FROM monument_categories ORDER BY ch_mon_cat_mis_jour DESC";
$liste_mon_cat2 = mysql_query($query_liste_mon_cat2, $maconnexion) or die(mysql_error());
$row_liste_mon_cat2 = mysql_fetch_assoc($liste_mon_cat2);
$totalRows_liste_mon_cat2 = mysql_num_rows($liste_mon_cat2);


//requete liste  monuments d'une catégorie 
$maxRows_classer_mon = 10;
$pageNum_classer_mon = 0;
if (isset($_GET['pageNum_classer_mon'])) {
  $pageNum_classer_mon = $_GET['pageNum_classer_mon'];
}
$startRow_classer_mon = $pageNum_classer_mon * $maxRows_classer_mon;

$colname_classer_mon = "-1";
if (isset($_GET['mon_cat_ID'])) {
	if ($_GET['mon_cat_ID'] == "") {
	$colname_classer_mon = NULL;
} else {
  $colname_classer_mon = $_GET['mon_cat_ID'];
} } else {
  $colname_classer_mon = NULL;
} 

$query_classer_mon = sprintf("SELECT monument.ch_disp_id as id, monument.ch_disp_mon_id, ch_pat_nom, ch_pat_mis_jour, ch_pat_statut, ch_pat_lien_img1, (SELECT GROUP_CONCAT(categories.ch_disp_cat_id) FROM dispatch_mon_cat as categories WHERE monument.ch_disp_mon_id = categories.ch_disp_mon_id) AS listcat
FROM dispatch_mon_cat as monument 
INNER JOIN patrimoine ON monument.ch_disp_mon_id = ch_pat_id 
WHERE monument.ch_disp_cat_id = %s OR %s IS NULL AND ch_pat_statut = 1 
GROUP BY monument.ch_disp_mon_id
ORDER BY monument.ch_disp_date DESC", GetSQLValueString($colname_classer_mon, "int"), GetSQLValueString($colname_classer_mon, "int"));
$query_limit_classer_mon = sprintf("%s LIMIT %d, %d", $query_classer_mon, $startRow_classer_mon, $maxRows_classer_mon);
$classer_mon = mysql_query($query_limit_classer_mon, $maconnexion) or die(mysql_error());
$row_classer_mon = mysql_fetch_assoc($classer_mon);

if (isset($_GET['totalRows_classer_mon'])) {
  $totalRows_classer_mon = $_GET['totalRows_classer_mon'];
} else {
  $all_classer_mon = mysql_query($query_classer_mon);
  $totalRows_classer_mon = mysql_num_rows($all_classer_mon);
}
$totalPages_classer_mon = ceil($totalRows_classer_mon/$maxRows_classer_mon)-1;

$queryString_classer_mon = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_classer_mon") == false && 
        stristr($param, "totalRows_classer_mon") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_classer_mon = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_classer_mon = sprintf("&totalRows_classer_mon=%d%s", $totalRows_classer_mon, $queryString_classer_mon);



//requete listes monuments restants

$query_liste_mon_restants = sprintf("SELECT ch_pat_id AS nb_mon_restants FROM patrimoine WHERE ch_pat_id NOT IN (SELECT ch_disp_mon_id FROM dispatch_mon_cat WHERE ch_disp_cat_id = %s OR %s IS NULL)", GetSQLValueString($colname_classer_mon, "int"), GetSQLValueString($colname_classer_mon, "int"));
$liste_mon_restants = mysql_query($query_liste_mon_restants, $maconnexion) or die(mysql_error());
$row_liste_mon_restants = mysql_fetch_assoc($liste_mon_restants);
$totalRows_liste_mon_restants = mysql_num_rows($liste_mon_restants);

//requete listes monuments non classés

$query_new_mon = "SELECT ch_pat_id, ch_pat_lien_img1, ch_pat_nom, ch_pat_mis_jour FROM patrimoine INNER JOIN pays ON ch_pat_paysID = ch_pay_id WHERE ch_pat_id NOT IN (
        SELECT ch_disp_mon_id FROM dispatch_mon_cat ) AND ch_pay_publication = 1 ORDER BY ch_pat_nom ASC";
$new_mon = mysql_query($query_new_mon, $maconnexion) or die(mysql_error());
$row_new_mon = mysql_fetch_assoc($new_mon);
$totalRows_new_mon = mysql_num_rows($new_mon);


$_SESSION['last_work'] = "institut_patrimoine.php";
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Gérer le <?= __s($row_institut['ch_ins_nom']) ?></title>
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
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
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
<script>
		$(function(){
			window.prettyPrint && prettyPrint()
			$('#cp3').colorpicker({
format: 'hex'});
$('#cp4').colorpicker({
format: 'hex'});
		});
	</script>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div class="container" id="overview"> 
  
  <!-- Page CONTENT
    ================================================== -->
  <section class="corps-page">
  <?php include(DEF_ROOTPATH . 'php/menu-haut-conseil.php'); ?>
  
  <!-- Liste des Communiqués
        ================================================== -->
  <!-- formulaire de modification instituts
     ================================================== -->
  <form class="pull-right-cta cta-title" action="<?= DEF_URI_PATH ?>back/insitut_modifier.php" method="post">
    <input name="institut_id" type="hidden" value="<?= e($row_institut['ch_ins_ID']) ?>">
    <button class="btn btn-primary btn-cta" type="submit" title="modifier les informations sur l'institut"><i class="icon-edit icon-white"></i> Modifier la description</button>
  </form>
  <div id="titre_institut" class="titre-bleu anchor">
    <h1>G&eacute;rer le <?= e($row_institut['ch_ins_nom']) ?></h1>
  </div>
  <div class="clearfix"></div>

      <?php renderElement('errormsgs'); ?>

  <!-- liste communique de l'institut
     ================================================== -->
  <div class="row-fluid">
    <div class="span6">
      <div class="titre-gris" id="mes-communiques" class="anchor">
      <h3>Communiqu&eacute;s</h3>
    </div>
    <div class="alert alert-tips">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      Les communiqu&eacute;s post&eacute;s &agrave; partir de cette page seront consid&eacute;r&eacute;s comme des annonces officielles &eacute;manant de cette institution. Ils seront publiés sur la page de l'institut et dans la partie événement du site. Utilisez les communiqu&eacute;s pour animer le site</div>
    <?php 
$com_cat = "institut";
$userID = $_SESSION['user_ID'];
$com_element_id = 3;
include(DEF_ROOTPATH . 'php/communiques-back.php'); ?>
  </div>
  <!-- Categorie monuments
     ================================================== -->
  <div class="span6">
    <div class="titre-gris">
      <h3>Catégories de Monuments</h3>
    </div>
    <div id="liste-categories" class="anchor">
      <ul class="listes">
        <?php do { ?>
          <li class="row-fluid"> 
            <!-- ICONE categories -->
            <div class="span2 icone-categorie"><img src="<?= e($row_liste_mon_cat['ch_mon_cat_icon']) ?>" alt="icone <?= e($row_liste_mon_cat['ch_mon_cat_nom']) ?>;"></div>
            <!-- contenu categorie -->
            <div class="span10 info-listes"> 
              <!-- Boutons modifier / supprimer --> 
              <a class="pull-right" href="../php/patrimoine-supprimmer-categorie-modal.php?mon_cat_id=<?= e($row_liste_mon_cat['ch_mon_cat_ID']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="supprimer cette cat&eacute;gorie"><i class="icon-remove"></i></a> <a class="pull-right" href="../php/patrimoine-modifier-categorie-modal.php?mon_cat_id=<?= e($row_liste_mon_cat['ch_mon_cat_ID']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="modifier cette cat&eacute;gorie"><i class="icon-pencil"></i></a> 
              <!-- Desc categorie -->
              <h4><?= e($row_liste_mon_cat['ch_mon_cat_nom']) ?></h4>
              <p><?= e($row_liste_mon_cat['ch_mon_cat_desc']) ?></p>
                <div class="row-fluid">
                    <img src="../assets/img/ressources/budget.png" alt="icone Budget" style="max-width: 15px"> <strong><?= e($row_liste_mon_cat['ch_mon_cat_budget']) ?></strong>  <img src="../assets/img/ressources/industrie.png" alt="icone Industrie" style="max-width: 15px"> <strong><?= e($row_liste_mon_cat['ch_mon_cat_industrie']) ?></strong>  <img src="../assets/img/ressources/bureau.png" alt="icone Commerce" style="max-width: 15px"> <strong><?= e($row_liste_mon_cat['ch_mon_cat_commerce']) ?></strong>  <img src="../assets/img/ressources/agriculture.png" alt="icone Agriculture" style="max-width: 15px"> <strong><?= e($row_liste_mon_cat['ch_mon_cat_agriculture']) ?></strong>  <img src="../assets/img/ressources/tourisme.png" alt="icone Tourisme" style="max-width: 15px"> <strong><?= e($row_liste_mon_cat['ch_mon_cat_tourisme']) ?></strong>  <img src="../assets/img/ressources/recherche.png" alt="icone Recherche" style="max-width: 15px"> <strong><?= e($row_liste_mon_cat['ch_mon_cat_recherche']) ?></strong>  <img src="../assets/img/ressources/environnement.png" alt="icone Evironnement" style="max-width: 15px"> <strong><?= e($row_liste_mon_cat['ch_mon_cat_environnement']) ?></strong>  <img src="../assets/img/ressources/education.png" alt="icone Education" style="max-width: 15px"> <strong><?= e($row_liste_mon_cat['ch_mon_cat_education']) ?></strong>
            </div>
          </li>
          <?php } while ($row_liste_mon_cat = mysql_fetch_assoc($liste_mon_cat)); ?>
      </ul>
      <!-- Pagination de la liste -->
      <p>&nbsp;</p>
        <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_liste_mon_cat + 1) ?> &agrave; <?php echo min($startRow_liste_mon_cat + $maxRows_liste_mon_cat, $totalRows_liste_mon_cat) ?> sur <?php echo $totalRows_liste_mon_cat ?>
            <?php if ($pageNum_liste_mon_cat > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_mon_cat=%d%s#liste-categories", $currentPage, max(0, $pageNum_liste_mon_cat - 1), $queryString_liste_mon_cat); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_liste_mon_cat < $totalPages_liste_mon_cat) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_mon_cat=%d%s#liste-categories", $currentPage, min($totalPages_liste_mon_cat, $pageNum_liste_mon_cat + 1), $queryString_liste_mon_cat); ?>"> <i class="icon-forward"></i></a>
          <?php } // Show if not last page ?></small>
  </p>
    </div>
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
    <!-- Ajouter une categorie
        ================================================== --> 
    <!-- Button to trigger modal --> 
    <a href="#ajouter-cat" role="button" class="btn btn-primary" title="Ajouter une cat&eacute;gorie" data-toggle="modal">Ajouter une cat&eacute;gorie</a> 
    <!-- Modal -->
    <div id="ajouter-cat" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-width="760">
      <form action="<?php echo $editFormAction; ?>" name="ajout-categorie" method="POST" class="form-horizontal" id="ajout-categorie">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="myModalLabel">Ajouter une nouvelle cat&eacute;gorie de monuments</h3>
        </div>
        <div class="modal-body"> 
          <!-- Boutons cachés -->
          <?php 
				  $now= date("Y-m-d G:i:s");?>
          <input name="ch_mon_cat_label" type="hidden" value="mon_cat">
          <input name="ch_mon_cat_date" type="hidden" value="<?php echo $now; ?>">
          <input name="ch_mon_cat_mis_jour" type="hidden" value="<?php echo $now; ?>">
          <input name="ch_mon_cat_nb_update" type="hidden" value=0 >
        <!-- Statut -->
        <div id="spryradio20" class="control-group">
          <div class="control-label">Catégorie</div>
          <div class="controls">
            <label>
              <input <?php if (!(strcmp($row_liste_mon_cat['ch_mon_cat_statut'],"1"))) { echo "checked"; } ?> name="ch_mon_cat_statut" type="radio" id="ch_mon_cat_statut_1" value="1">
              Entreprise</label>
            <label>
              <input <?php if (!(strcmp($row_liste_mon_cat['ch_mon_cat_statut'],"2"))) { echo "checked"; } ?> name="ch_mon_cat_statut" type="radio" id="ch_mon_cat_statut_2" value="2">
              Ville</label>
            <label>
              <input <?php if (!(strcmp($row_liste_mon_cat['ch_mon_cat_statut'],"3"))) { echo "checked"; } ?> name="ch_mon_cat_statut" type="radio" id="ch_mon_cat_statut_3" value="3">
              Pays</label>
            <span class="radioRequiredMsg">Choisissez une catégorie pour votre Quête</span></div>
        </div>

          <!-- Nom-->
          <div id="sprytextfield2" class="control-group">
            <label class="control-label" for="ch_mon_cat_nom">Nom de la cat&eacute;gorie <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="input-xlarge" type="text" id="ch_mon_cat_nom" name="ch_mon_cat_nom">
              <br>
              <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">30 caract&egrave;res max.</span></div>
          </div>
          <!-- Icone -->
          <div id="sprytextfield3" class="control-group">
            <label class="control-label" for="ch_mon_cat_icon">Ic&ocirc;ne <a href="#" rel="clickover" title="Ic&ocirc;ne" data-content="L'ic&ocirc;ne sert &agrave; repr&eacute;senter la cat&eacute;gorie dans l'ensemble du site. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="input-xlarge" type="text" name="ch_mon_cat_icon" id="ch_mon_cat_icon" value="">
              <br>
              <span class="textfieldRequiredMsg">une ic&ocirc;ne est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
          </div>
          <!-- Description -->
          <div id="sprytextarea1" class="control-group">
            <label class="control-label" for="ch_mon_cat_desc">Description <a href="#" rel="clickover" title="Description" data-content="Donnez en quelques lignes des informations qui permettrons de comprendre l'objet de cette cat&eacute;gorie. 400 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <textarea rows="6" name="ch_mon_cat_desc" class="input-xlarge" id="ch_mon_cat_desc"></textarea>
              <br>
              <span class="textareaMaxCharsMsg">400 caract&egrave;res max.</span></div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
          <button type="submit" class="btn btn-primary">Enregistrer</button>
          <input type="hidden" name="MM_insert" value="ajout-categorie">
        </div>
      </form>
    </div>
  </div>
</div>
<p>&nbsp;</p>

<!-- Liste des monuments non-classés
        ================================================== -->
        <?php if ($row_new_mon) { ?>
<div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h3>Nouveaux monuments</h3>
        <p>Les monuments suivants ne sont toujours pas index&eacute;s&nbsp;:</p>
       <ul class="listes">
    <!-- Requetes pour infos et icones des catégories du monuments -->
    <?php do {  ?>
    <!-- Item monument -->
    <li class="row-fluid"> 
      <!-- Image du monument -->
      <div class="span2 img-listes">
        <?php if ($row_new_mon['ch_pat_lien_img1']) {?>
        <img src="<?php echo $row_new_mon['ch_pat_lien_img1']; ?>" alt="image <?= e($row_new_mon['ch_pat_nom']) ?>">
        <?php } else { ?>
        <img src="../assets/img/imagesdefaut/ville.jpg" alt="monument">
        <?php } ?>
      </div>
      <!-- Nom, date et lien vers la page du monument -->
      <div class="span6 info-listes">
        <h4><?= e($row_new_mon['ch_pat_nom']) ?></h4>
        <p><strong><?php if ($row_classer_mon['ch_pat_statut']==1) { ?>Entreprise<?php } if ($row_classer_mon['ch_pat_statut']==2) { ?>Ville<?php } if ($row_classer_mon['ch_pat_statut']==3) { ?>Pays / organisation<?php } else { ?><?php }?></strong> • Dernière MAJ le
          <?php  echo date("d/m/Y", strtotime($row_new_mon['ch_pat_mis_jour'])); ?>
          &agrave; <?php echo date("G:i:s", strtotime($row_new_mon['ch_pat_mis_jour'])); ?> </p>
        <div class="btn-group form-button-inline">
          <a class="btn btn-primary" href="../php/patrimoine-modal.php?ch_pat_id=<?= e($row_new_mon['ch_pat_id']) ?>" data-toggle="modal" data-target="#Modal-Monument">Visiter (dans une fenêtre contextuelle)</a>
          <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../page-monument.php?ch_pat_id=<?= e($row_new_mon['ch_pat_id']) ?>"> Visiter (lien direct)</a></li>
          </ul>
        </div>

        <a class="btn btn-primary btn-margin-left" href="../php/patrimoine-ajouter-monument-a-categorie-direct-modal.php?mon_id=<?= e($row_new_mon['ch_pat_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="Ajouter à une catégorie">Ajouter à une catégorie</a>
      </div>
      <!-- Affichage des categories du monument -->
    </li>
    <?php } while ($row_new_mon = mysql_fetch_assoc($new_mon)); ?>
       
       </ul>
       </div>
<?php }?>




<!-- Classer des monuments
        ================================================== -->
<div class="titre-gris" id="classer-monument" class="anchor">
<h3>Classer des monuments dans des catégories</h3>
</div>
<div class="row-fluid"> 
  <!-- Liste pour choix de la categories -->
  <div id="select-categorie">
    <form action="<?= DEF_URI_PATH ?>back/institut_patrimoine.php#classer-monument" method="GET">
      <select name="mon_cat_ID" id="mon_cat_ID" onchange="this.form.submit()">
        <option value="" <?php if ($colname_classer_mon == NULL) {?>selected<?php } ?>>S&eacute;lectionnez une cat&eacute;gorie&nbsp;</option>
        <?php do { ?>
        <option value="<?php echo $row_liste_mon_cat2['ch_mon_cat_ID']; ?>" <?php if ($colname_classer_mon == $row_liste_mon_cat2['ch_mon_cat_ID']) {?>selected<?php } ?>><?php echo $row_liste_mon_cat2['ch_mon_cat_nom']; ?></option>
        <?php } while ($row_liste_mon_cat2 = mysql_fetch_assoc($liste_mon_cat2)); ?>
      </select>
    </form>
  </div>
  <!-- Liste des monuments de la categorie -->
  <ul class="listes">
    <!-- Requetes pour infos et icones des catégories du monuments -->
    <?php do { 
	  
			$listcategories = $row_classer_mon['listcat'];
			if ($row_classer_mon['listcat']) {
          

$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories)";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion) or die(mysql_error());
$row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
			 } ?>
    <?php if ($row_classer_mon) {?>
    <!-- Item monument -->
    <li class="row-fluid"> 
      <!-- Image du monument -->
      <div class="span2 img-listes">
        <?php if ($row_classer_mon['ch_pat_lien_img1']) {?>
        <img src="<?php echo $row_classer_mon['ch_pat_lien_img1']; ?>" alt="image <?= e($row_classer_mon['ch_pat_nom']) ?>">
        <?php } else { ?>
        <img src="../assets/img/imagesdefaut/ville.jpg" alt="monument">
        <?php } ?>
      </div>
      <!-- Nom, date et lien vers la page du monument -->
      <div class="span6 info-listes">
        <h4><?= e($row_classer_mon['ch_pat_nom']) ?></h4>
        <p><strong><?php if ($row_classer_mon['ch_pat_statut']==1) { ?>Entreprise<?php } if ($row_classer_mon['ch_pat_statut']==2) { ?>Ville<?php } if ($row_classer_mon['ch_pat_statut']==3) { ?>Pays / organisation<?php } else { ?><?php }?></strong> • Dernière MAJ le
          <?php  echo date("d/m/Y", strtotime($row_classer_mon['ch_pat_mis_jour'])); ?>
          &agrave; <?php echo date("G:i:s", strtotime($row_classer_mon['ch_pat_mis_jour'])); ?> </p>
         <div class="btn-group form-button-inline">
          <a class="btn btn-primary" href="../php/patrimoine-modal.php?ch_pat_id=<?= e($row_classer_mon['ch_disp_mon_id']) ?>" data-toggle="modal" data-target="#Modal-Monument">Visiter (dans une fenêtre contextuelle)</a>
          <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../page-monument.php?ch_pat_id=<?= e($row_classer_mon['ch_disp_mon_id']) ?>"> Visiter (lien direct)</a></li>
          </ul>
        </div>
        <a class="btn btn-primary btn-margin-left" href="../php/patrimoine-ajouter-monument-a-categorie-direct-modal.php?mon_id=<?= e($row_classer_mon['ch_disp_mon_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="Modifier les catégories">Modifier les catégories</a></div>
      <!-- Affichage des categories du monument -->
      <div class="span4">
        <?php if (($colname_classer_mon != NULL) AND ($colname_classer_mon != "")) { // affiche bouton ajouter si une categorie est choisie ?>
        <!-- Boutons supprimer monument de la catégorie --> 
        <a class="pull-right" href="../php/patrimoine-supprimmer-monument-categorie-modal.php?ch_disp_id=<?= e($row_classer_mon['ch_pat_nom']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="supprimer ce monument de cette cat&eacute;gorie"><i class="icon-remove"></i></a>
        <?php } ?>
        <?php if ($row_liste_mon_cat3) {?>
        <?php do { ?>
          <!-- Icone et popover de la categorie -->
          <div class="span2 icone-categorie"><a href="#" rel="clickover" title="<?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>" data-placement="left" data-content="<?php echo $row_liste_mon_cat3['ch_mon_cat_desc']; ?>"><img src="<?php echo $row_liste_mon_cat3['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>"></a></div>
          <?php } while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)); ?>
        <?php } ?>
      </div>
    </li>
    <?php } ?>
    <?php } while ($row_classer_mon = mysql_fetch_assoc($classer_mon)); ?>
  </ul>
  <p>&nbsp;</p>
  <!-- Boutons Ajouter monument dans la catégorie -->
  <?php if (($colname_classer_mon != NULL) AND ($colname_classer_mon != "") AND ($row_liste_mon_restants['nb_mon_restants'])) { // affiche bouton ajouter si une categorie est choisie ?>
  <a class="btn btn-primary btn-margin-left" href="../php/patrimoine-ajouter-monument-dans-categorie-modal.php?mon_cat_ID=<?php echo $colname_classer_mon; ?>" data-toggle="modal" data-target="#Modal-Monument" title="Ajouter un monument dans cette cat&eacute;gorie">Ajouter un monument dans cette cat&eacute;gorie</a>
  <?php } else { ?>
  <a class="btn btn-primary btn-margin-left disabled" title="Choisissez une cat&eacute;gorie avant d'ajouter un monument">Ajouter un monument dans cette cat&eacute;gorie</a>
  <?php }?>
  <!-- Modal et script -->
  <div class="modal container fade" id="#Modal-Monument" data-width="760"></div>
  <div class="modal container fade" id="#Modal-General" data-width="760"></div>
  <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
    $('#Modal-General').modal('hide');
});
</script>
  <p>&nbsp;</p>
  <!-- Pagination liste des monuments de la categorie -->
   <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_classer_mon + 1) ?> &agrave; <?php echo min($startRow_classer_mon + $maxRows_classer_mon, $totalRows_classer_mon) ?> sur <?php echo $totalRows_classer_mon ?>
            <?php if ($pageNum_classer_mon > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_classer_mon=%d%s#classer-monument", $currentPage, max(0, $pageNum_classer_mon - 1), $queryString_classer_mon); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_classer_mon < $totalPages_classer_mon) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_classer_mon=%d%s#classer-monument", $currentPage, min($totalPages_classer_mon, $pageNum_classer_mon + 1), $queryString_classer_mon); ?>"> <i class="icon-forward"></i></a>
          <?php } // Show if not last page ?></small>
  </p>
</div>
</section>
</div>
<!-- END CONTENT
    ================================================== --> 

<!-- Footer
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/footerback.php'); ?>

<script type="text/javascript">
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {minChars:2, validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "url", {minChars:2, maxChars:250, validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {maxChars:400, validateOn:["change"], isRequired:false, useCharacterMasking:false});
</script>
</body>
</html>
