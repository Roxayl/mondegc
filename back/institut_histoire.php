<?php


if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=20))
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
	}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-categorie")) {
  $insertSQL = sprintf("INSERT INTO faithist_categories (ch_fai_cat_label, ch_fai_cat_statut, ch_fai_cat_date, ch_fai_cat_mis_jour, ch_fai_cat_nb_update, ch_fai_cat_nom, ch_fai_cat_desc, ch_fai_cat_icon, ch_fai_cat_couleur) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_fai_cat_label'], "text"),
                       GetSQLValueString($_POST['ch_fai_cat_statut'], "int"),
                       GetSQLValueString($_POST['ch_fai_cat_date'], "date"),
                       GetSQLValueString($_POST['ch_fai_cat_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_fai_cat_nb_update'], "int"),
                       GetSQLValueString($_POST['ch_fai_cat_nom'], "text"),
                       GetSQLValueString($_POST['ch_fai_cat_desc'], "text"),
                       GetSQLValueString($_POST['ch_fai_cat_icon'], "text"),
					   GetSQLValueString($_POST['ch_fai_cat_couleur'], "text"));


  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = "institut_histoire.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

//requete instituts
$institut_id = 4;

$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

//requete liste categories faits hist
$maxRows_liste_fait_cat = 10;
$pageNum_liste_fait_cat = 0;
if (isset($_GET['pageNum_liste_fait_cat'])) {
  $pageNum_liste_fait_cat = $_GET['pageNum_liste_fait_cat'];
}
$startRow_liste_fait_cat = $pageNum_liste_fait_cat * $maxRows_liste_fait_cat;


$query_liste_fait_cat = "SELECT * FROM faithist_categories ORDER BY ch_fai_cat_mis_jour DESC";
$query_limit_liste_fait_cat = sprintf("%s LIMIT %d, %d", $query_liste_fait_cat, $startRow_liste_fait_cat, $maxRows_liste_fait_cat);
$liste_fait_cat = mysql_query($query_limit_liste_fait_cat, $maconnexion) or die(mysql_error());
$row_liste_fait_cat = mysql_fetch_assoc($liste_fait_cat);

if (isset($_GET['totalRows_liste_fait_cat'])) {
  $totalRows_liste_fait_cat = $_GET['totalRows_liste_fait_cat'];
} else {
  $all_liste_fait_cat = mysql_query($query_liste_fait_cat);
  $totalRows_liste_fait_cat = mysql_num_rows($all_liste_fait_cat);
}
$totalPages_liste_fait_cat = ceil($totalRows_liste_fait_cat/$maxRows_liste_fait_cat)-1;

$queryString_liste_fait_cat = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_liste_fait_cat") == false && 
        stristr($param, "totalRows_liste_fait_cat") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_liste_fait_cat = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_liste_fait_cat = sprintf("&totalRows_liste_fait_cat=%d%s", $totalRows_liste_fait_cat, $queryString_liste_fait_cat);


//requete liste categories faits hist pour pouvoir selectionner la categorie 

$query_liste_fait_cat2 = "SELECT * FROM faithist_categories ORDER BY ch_fai_cat_mis_jour DESC";
$liste_fait_cat2 = mysql_query($query_liste_fait_cat2, $maconnexion) or die(mysql_error());
$row_liste_fait_cat2 = mysql_fetch_assoc($liste_fait_cat2);
$totalRows_liste_fait_cat2 = mysql_num_rows($liste_fait_cat2);


//requete liste  faits  d'une catégorie 
$maxRows_classer_fait_his = 10;
$pageNum_classer_fait_his = 0;
if (isset($_GET['pageNum_classer_fait_his'])) {
  $pageNum_classer_fait_his = $_GET['pageNum_classer_fait_his'];
}
$startRow_classer_fait_his = $pageNum_classer_fait_his * $maxRows_classer_fait_his;

$colname_classer_fait_his = "-1";
if (isset($_GET['fai_catID'])) {
	if ($_GET['fai_catID'] == "") {
	$colname_classer_fait_his = NULL;
} else {
  $colname_classer_fait_his = $_GET['fai_catID'];
} } else {
  $colname_classer_fait_his = NULL;
} 

$query_classer_fait_his = sprintf("SELECT fait.ch_disp_FH_id as id, fait.ch_disp_fait_hist_id, ch_his_nom, ch_his_mis_jour, ch_his_lien_img1, (SELECT GROUP_CONCAT(categories.ch_disp_fait_hist_cat_id) FROM dispatch_fait_his_cat as categories WHERE fait.ch_disp_fait_hist_id = categories.ch_disp_fait_hist_id) AS listcat
FROM dispatch_fait_his_cat as fait 
INNER JOIN histoire ON fait.ch_disp_fait_hist_id = ch_his_id 
WHERE fait.ch_disp_fait_hist_cat_id = %s OR %s IS NULL AND ch_his_statut = 1 
GROUP BY fait.ch_disp_fait_hist_id
ORDER BY fait.ch_disp_FH_date DESC", GetSQLValueString($colname_classer_fait_his, "int"), GetSQLValueString($colname_classer_fait_his, "int"));
$query_limit_classer_fait_his = sprintf("%s LIMIT %d, %d", $query_classer_fait_his, $startRow_classer_fait_his, $maxRows_classer_fait_his);
$classer_fait_his = mysql_query($query_limit_classer_fait_his, $maconnexion) or die(mysql_error());
$row_classer_fait_his = mysql_fetch_assoc($classer_fait_his);

if (isset($_GET['totalRows_classer_fait_his'])) {
  $totalRows_classer_fait_his = $_GET['totalRows_classer_fait_his'];
} else {
  $all_classer_fait_his = mysql_query($query_classer_fait_his);
  $totalRows_classer_fait_his = mysql_num_rows($all_classer_fait_his);
}
$totalPages_classer_fait_his = ceil($totalRows_classer_fait_his/$maxRows_classer_fait_his)-1;

$queryString_classer_fait_his = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_classer_fait_his") == false && 
        stristr($param, "totalRows_classer_fait_his") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_classer_fait_his = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_classer_fait_his = sprintf("&totalRows_classer_fait_his=%d%s", $totalRows_classer_fait_his, $queryString_classer_fait_his);


//requete listes faits restants

$query_liste_fait_restants = sprintf("SELECT ch_his_id AS nb_faits_restants FROM histoire WHERE ch_his_id NOT IN (SELECT ch_disp_fait_hist_id FROM dispatch_fait_his_cat WHERE ch_disp_fait_hist_cat_id = %s OR %s IS NULL)", GetSQLValueString($colname_classer_fait_his, "int"), GetSQLValueString($colname_classer_fait_his, "int"));
$liste_fait_restants = mysql_query($query_liste_fait_restants, $maconnexion) or die(mysql_error());
$row_liste_fait_restants = mysql_fetch_assoc($liste_fait_restants);
$totalRows_liste_fait_restants = mysql_num_rows($liste_fait_restants);


//requete listes faits hist non classés

$query_new_fait = "SELECT ch_his_id, ch_his_lien_img1, ch_his_nom, ch_his_mis_jour FROM histoire INNER JOIN pays ON ch_his_paysID = ch_pay_id WHERE ch_his_id NOT IN (
        SELECT ch_disp_fait_hist_id FROM dispatch_fait_his_cat ) AND ch_pay_publication = 1 ORDER BY ch_his_mis_jour DESC";
$new_fait = mysql_query($query_new_fait, $maconnexion) or die(mysql_error());
$row_new_fait = mysql_fetch_assoc($new_fait);
$totalRows_new_fait = mysql_num_rows($new_fait);



$_SESSION['last_work'] = "institut_histoire.php";
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
<?php include(DEF_ROOTPATH . 'php/navbarback.php'); ?>
<!-- Subhead
================================================== -->
<div class="container" id="overview"> 
  
  <!-- Page CONTENT
    ================================================== -->
  <section class="corps-page">
  <?php include(DEF_ROOTPATH . 'php/menu-haut-conseil.php'); ?>

  <!-- formulaire de modification instituts
     ================================================== -->
  <form class="pull-right-cta" action="insitut_modifier.php" method="post" style="margin-top: 30px;">
    <input name="institut_id" type="hidden" value="<?php echo $row_institut['ch_ins_ID']; ?>">
    <button class="btn btn-primary btn-cta" type="submit" title="modifier les informations sur l'institut"><i class="icon-edit icon-white"></i> Modifier la description</button>
  </form>
  <!-- Liste des Communiqués
        ================================================== -->
  <div id="titre_institut" class="titre-bleu anchor">
    <h1>G&eacute;rer le <?php echo $row_institut['ch_ins_nom']; ?></h1>
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
$com_element_id = 4;
include(DEF_ROOTPATH . 'php/communiques-back.php'); ?>
  </div>
  <!-- Categorie monuments
     ================================================== -->
  <div class="span6">
    <div class="titre-gris">
      <h3>Catégories historiques</h3>
    </div>
    <div id="liste-categories" class="anchor">
      <ul class="listes">
        <?php do { ?>
          <li class="row-fluid"> 
            <!-- ICONE categories -->
            <div class="span2 icone-categorie"><img src="<?php echo $row_liste_fait_cat['ch_fai_cat_icon']; ?>" alt="icone <?php echo $row_liste_fait_cat['ch_fai_cat_nom']; ?>" style="background-color:<?php echo $row_liste_fait_cat['ch_fai_cat_couleur']; ?>; <?php if ($row_liste_fait_cat['ch_fai_cat_statut'] == 2 ) {?>opacity:0.5;<?php }?>"></div>
            <!-- contenu categorie -->
            <div class="span10 info-listes"> 
              <!-- Boutons modifier / supprimer --> 
              <a class="pull-right" href="../php/histoire-supprimmer-categorie-modal.php?fai_cat_id=<?php echo $row_liste_fait_cat['ch_fai_cat_ID']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="supprimer cette cat&eacute;gorie"><i class="icon-remove"></i></a> <a class="pull-right" href="../php/histoire-modifier-categorie-modal.php?fai_cat_id=<?php echo $row_liste_fait_cat['ch_fai_cat_ID']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="modifier cette cat&eacute;gorie"><i class="icon-pencil"></i></a> 
              <!-- Desc categorie -->
              <h4><?php echo $row_liste_fait_cat['ch_fai_cat_nom']; ?></h4>
              <p><?php echo $row_liste_fait_cat['ch_fai_cat_desc']; ?></p>
            </div>
          </li>
          <?php } while ($row_liste_fait_cat = mysql_fetch_assoc($liste_fait_cat)); ?>
      </ul>
      <!-- Pagination de la liste -->
      <p>&nbsp;</p>
      <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_liste_fait_cat + 1) ?> &agrave; <?php echo min($startRow_liste_fait_cat + $maxRows_liste_fait_cat, $totalRows_liste_fait_cat) ?> sur <?php echo $totalRows_liste_fait_cat ?>
        <?php if ($pageNum_liste_fait_cat > 0) { // Show if not first page ?>
          <a class="btn" href="<?php printf("%s?pageNum_liste_fait_cat=%d%s#liste-categories", $currentPage, max(0, $pageNum_liste_fait_cat - 1), $queryString_liste_fait_cat); ?>"><i class=" icon-backward"></i></a>
          <?php } // Show if not first page ?>
        <?php if ($pageNum_liste_fait_cat < $totalPages_liste_fait_cat) { // Show if not last page ?>
          <a class="btn" href="<?php printf("%s?pageNum_liste_fait_cat=%d%s#liste-categories", $currentPage, min($totalPages_liste_fait_cat, $pageNum_liste_fait_cat + 1), $queryString_liste_fait_cat); ?>"> <i class="icon-forward"></i></a>
          <?php } // Show if not last page ?>
        </small> </p>
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
          <h3 id="myModalLabel">Ajouter une nouvelle cat&eacute;gorie historique</h3>
        </div>
        <div class="modal-body"> 
          <!-- Boutons cachés -->
          <?php 
				  $now= date("Y-m-d G:i:s");?>
          <input name="ch_fai_cat_label" type="hidden" value="fai_cat">
          <input name="ch_fai_cat_date" type="hidden" value="<?php echo $now; ?>">
          <input name="ch_fai_cat_mis_jour" type="hidden" value="<?php echo $now; ?>">
          <input name="ch_fai_cat_nb_update" type="hidden" value=0 >
          <!-- Statut -->
          <div id="spryradio1" class="control-group">
            <div class="control-label">Statut <a href="#" rel="clickover" title="Statut de la cat&eacute;gorie" data-content="
    Visible : cette cat&eacute;gorie sera visible sur la page de l'institut d'histoire.
    Invisible : cette cat&eacute;gorie sera cach&eacute;e sur la page de l'institut d'histoire."><i class="icon-info-sign"></i></a></div>
            <div class="controls">
              <label>
                <input type="radio" name="ch_fai_cat_statut" value="1" id="ch_fai_cat_statut_1" checked="CHECKED">
                visible</label>
              <label>
                <input name="ch_fai_cat_statut" type="radio" id="ch_fai_cat_statut_2" value="2">
                invisible</label>
              <span class="radioRequiredMsg">Choisissez un statut pour cette cat&eacute;gorie</span></div>
          </div>
          <!-- Nom-->
          <div id="sprytextfield2" class="control-group">
            <label class="control-label" for="ch_fai_cat_nom">Nom de la cat&eacute;gorie <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="input-xlarge" type="text" id="ch_fai_cat_nom" name="ch_fai_cat_nom">
              <br>
              <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">30 caract&egrave;res max.</span></div>
          </div>
          <!-- Icone -->
          <div id="sprytextfield3" class="control-group">
            <label class="control-label" for="ch_fai_cat_icon">Ic&ocirc;ne <a href="#" rel="clickover" title="Ic&ocirc;ne" data-content="L'ic&ocirc;ne sert &agrave; repr&eacute;senter la cat&eacute;gorie dans l'ensemble du site. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="input-xlarge" type="text" name="ch_fai_cat_icon" id="ch_fai_cat_icon" value="">
              <br>
              <span class="textfieldRequiredMsg">une ic&ocirc;ne est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
          </div>
          <!-- Couleur -->
          <div id="" class="control-group">
            <label class="control-label" for="ch_fai_cat_icon">Couleur <a href="#" rel="clickover" title="Couleur" data-content="Choisissez une couleur de fond pour la cat&eacute;gorie"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <div class="input-append color" data-color="#06C" data-color-format="hex" id="cp3">
                <input type="text" class="span2" value="" name="ch_fai_cat_couleur" id="ch_fai_cat_couleur">
                <span class="add-on"><i style="background-color: #06C)"></i></span> </div>
            </div>
          </div>
          <!-- Description -->
          <div id="sprytextarea1" class="control-group">
            <label class="control-label" for="ch_fai_cat_desc">Description <a href="#" rel="clickover" title="Description" data-content="Donnez en quelques lignes des informations qui permettrons de comprendre l'objet de cette cat&eacute;gorie. 400 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <textarea rows="6" name="ch_fai_cat_desc" class="input-xlarge" id="ch_fai_cat_desc"></textarea>
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

<!-- Liste des evennements non-classés
        ================================================== -->
<?php if ($row_new_fait) { ?>
<div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <h3>Histoire des pays</h3>
  <p>Les éléments suivants ne sont toujours pas index&eacute;s&nbsp;:</p>
  <ul class="listes">
    <!-- Requetes pour infos et icones des catégories du monuments -->
    <?php do {  ?>
      <!-- Item monument -->
      <li class="row-fluid"> 
        <!-- Image du monument -->
        <div class="span2 img-listes">
          <?php if ($row_new_fait['ch_his_lien_img1']) {?>
          <img src="<?php echo $row_new_fait['ch_his_lien_img1']; ?>" alt="image <?php echo $row_new_fait['ch_his_nom']; ?>">
          <?php } else { ?>
          <img src="../assets/img/imagesdefaut/ville.jpg" alt="monument">
          <?php } ?>
        </div>
        <!-- Nom, date et lien vers la page du monument -->
        <div class="span6 info-listes">
          <h4><?php echo $row_new_fait['ch_his_nom']; ?></h4>
          <p><strong>Derni&egrave;re mise &agrave; jour&nbsp;: </strong>le
            <?php  echo date("d/m/Y", strtotime($row_new_fait['ch_his_mis_jour'])); ?>
            &agrave; <?php echo date("G:i:s", strtotime($row_new_fait['ch_his_mis_jour'])); ?> </p>
          <a class="btn btn-primary" href="../page-fait-historique.php?ch_his_id=<?php echo $row_new_fait['ch_his_id']; ?>">Visiter</a> </div>
        <!-- Affichage des categories du monument --> 
      </li>
      <?php } while ($row_new_fait = mysql_fetch_assoc($new_fait)); ?>
  </ul>
</div>
<?php }?>

<!-- Classer des faits hist
        ================================================== -->
<div class="titre-gris" id="classer-fait-hist" class="anchor">
<h3>Classer des éléments historiques dans une catégorie</h3>
</div>
<div class="row-fluid"> 
  <!-- Liste pour choix de la categories -->
  <div id="select-categorie">
    <form action="institut_histoire.php#classer-fait-hist" method="GET">
      <select name="fai_catID" id="fai_catID" onchange="this.form.submit()">
        <option value="" <?php if ($colname_classer_fait_his == NULL) {?>selected<?php } ?>>S&eacute;lectionnez une cat&eacute;gorie&nbsp;</option>
        <?php do { ?>
        <option value="<?php echo $row_liste_fait_cat2['ch_fai_cat_ID']; ?>" <?php if ($colname_classer_fait_his == $row_liste_fait_cat2['ch_fai_cat_ID']) {?>selected<?php } ?>><?php echo $row_liste_fait_cat2['ch_fai_cat_nom']; ?></option>
        <?php } while ($row_liste_fait_cat2 = mysql_fetch_assoc($liste_fait_cat2)); ?>
      </select>
    </form>
  </div>
  <!-- Liste des faits de la categorie -->
  <ul class="listes">
    <!-- Requetes pour infos et icones des catégories du fait historique -->
    <?php do { 
	  
			$listcategories = $row_classer_fait_his['listcat'];
			if ($row_classer_fait_his['listcat']) {
          

$query_liste_fait_cat3 = "SELECT * FROM faithist_categories WHERE ch_fai_cat_ID In ($listcategories)";
$liste_fait_cat3 = mysql_query($query_liste_fait_cat3, $maconnexion) or die(mysql_error());
$row_liste_fait_cat3 = mysql_fetch_assoc($liste_fait_cat3);
$totalRows_liste_fait_cat3 = mysql_num_rows($liste_fait_cat3);
			 } ?>
    <?php if ($row_classer_fait_his) {?>
    <!-- Item fait hist -->
    <li class="row-fluid"> 
      <!-- Image du fait hist -->
      <div class="span2 img-listes">
        <?php if ($row_classer_fait_his['ch_his_lien_img1']) {?>
        <img src="<?php echo $row_classer_fait_his['ch_his_lien_img1']; ?>" alt="image <?php echo $row_classer_fait_his['ch_his_nom']; ?>">
        <?php } else { ?>
        <img src="../assets/img/imagesdefaut/ville.jpg" alt="monument">
        <?php } ?>
      </div>
      <!-- Nom, date et lien vers la page du fait historique -->
      <div class="span6 info-listes">
        <h4><?php echo $row_classer_fait_his['ch_his_nom']; ?></h4>
        <p><strong>Derni&egrave;re mise &agrave; jour&nbsp;: </strong>le
          <?php  echo date("d/m/Y", strtotime($row_classer_fait_his['ch_his_mis_jour'])); ?>
          &agrave; <?php echo date("G:i:s", strtotime($row_classer_fait_his['ch_his_mis_jour'])); ?> </p>
        <a class="btn btn-primary" href="../page-fait-historique.php?ch_his_id=<?php echo $row_classer_fait_his['ch_disp_fait_hist_id']; ?>">Visiter</a> </div>
      <!-- Affichage des categories du fait historique -->
      <div class="span4">
        <?php if (($colname_classer_fait_his != NULL) AND ($colname_classer_fait_his != "")) { // affiche bouton ajouter si une categorie est choisie ?>
        <!-- Boutons supprimer fait de la catégorie --> 
        <a class="pull-right" href="../php/histoire-supprimmer-fait-categorie-modal.php?ch_disp_FH_id=<?php echo $row_classer_fait_his['id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="supprimer ce fait de cette cat&eacute;gorie"><i class="icon-remove"></i></a>
        <?php } ?>
        <?php if ($row_liste_fait_cat3) {?>
        <?php do { ?>
          <!-- Icone et popover de la categorie -->
          <div class="span2 icone-categorie"><a href="#" rel="clickover" title="<?php echo $row_liste_fait_cat3['ch_fai_cat_nom']; ?>" data-placement="left" data-content="<?php echo $row_liste_fait_cat3['ch_fai_cat_desc']; ?>"><img src="<?php echo $row_liste_fait_cat3['ch_fai_cat_icon']; ?>" alt="icone <?php echo $row_liste_fait_cat3['ch_fai_cat_nom']; ?>" style="background-color:<?php echo $row_liste_fait_cat3['ch_fai_cat_couleur']; ?>; <?php if ($row_liste_fait_cat3['ch_fai_cat_statut'] == 2 ) {?>opacity:0.5;<?php }?>"></a></div>
          <?php } while ($row_liste_fait_cat3 = mysql_fetch_assoc($liste_fait_cat3)); ?>
        <?php } ?>
      </div>
    </li>
    <?php } ?>
    <?php } while ($row_classer_fait_his = mysql_fetch_assoc($classer_fait_his)); ?>
  </ul>
  <p>&nbsp;</p>
  <!-- Boutons Ajouter element dans la catégorie -->
  <?php if (($colname_classer_fait_his != NULL) AND ($colname_classer_fait_his != "") AND ($row_liste_fait_restants['nb_faits_restants']) ) { // affiche bouton ajouter si une categorie est choisie ?>
  <a class="btn btn-primary btn-margin-left" href="../php/histoire-ajouter-fait-dans-categorie-modal.php?fai_catID=<?php echo $colname_classer_fait_his; ?>" data-toggle="modal" data-target="#Modal-Monument" title="Ajouter un fait historique dans cette cat&eacute;gorie">ajouter un &eacute;l&eacute;ment dans cette catégorie</a>
  <?php } else { ?>
  <a class="btn btn-primary btn-margin-left disabled">ajouter un &eacute;l&eacute;ment dans cette catégorie</a>
  <?php }?>
  <!-- Modal et script -->
  <div class="modal container fade" id="#Modal-Monument" data-width="760"></div>
  <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script>
  <p>&nbsp;</p>
  <!-- Pagination liste des elements de la categorie -->
  <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_classer_fait_his + 1) ?> &agrave; <?php echo min($startRow_classer_fait_his + $maxRows_classer_fait_his, $totalRows_classer_fait_his) ?> sur <?php echo $totalRows_classer_fait_his ?>
    <?php if ($pageNum_classer_fait_his > 0) { // Show if not first page ?>
      <a class="btn" href="<?php printf("%s?pageNum_classer_fait_his=%d%s#classer-fait-hist", $currentPage, max(0, $pageNum_classer_fait_his - 1), $queryString_classer_fait_his); ?>"><i class=" icon-backward"></i></a>
      <?php } // Show if not first page ?>
    <?php if ($pageNum_classer_fait_his < $totalPages_classer_fait_his) { // Show if not last page ?>
      <a class="btn" href="<?php printf("%s?pageNum_classer_fait_his=%d%s#classer-fait-hist", $currentPage, min($totalPages_classer_fait_his, $pageNum_classer_fait_his + 1), $queryString_classer_fait_his); ?>"> <i class="icon-forward"></i></a>
      <?php } // Show if not last page ?>
    </small> </p>
</div>
</section>
</div>
<!-- END CONTENT
    ================================================== --> 

<!-- Footer
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/footerback.php'); ?>
</body>
</html>
<script type="text/javascript">
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {minChars:2, maxChars:30, validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "url", {minChars:2, maxChars:250, validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {maxChars:400, validateOn:["change"], isRequired:false, useCharacterMasking:false});
</script>
<?php
mysql_free_result($institut);
mysql_free_result($liste_fait_cat);
mysql_free_result($liste_fait_cat2);
mysql_free_result($classer_fait_his);
mysql_free_result($liste_fait_restants);
?>