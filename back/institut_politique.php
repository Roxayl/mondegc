<?php


if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
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

$_SESSION['last_work'] = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php'.'?'.$_SERVER['QUERY_STRING'];

//requete instituts
$institut_id = 6;

$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

$_SESSION['last_work'] = "institut_economie.php";

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-groupe")) {
  $insertSQL = sprintf("INSERT INTO membres_groupes (ch_mem_group_label, ch_mem_group_statut, ch_mem_group_date, ch_mem_group_mis_jour, ch_mem_group_nb_update, ch_mem_group_nom, ch_mem_group_desc, ch_mem_group_icon, ch_mem_group_couleur) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_mem_group_label'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_statut'], "int"),
                       GetSQLValueString($_POST['ch_mem_group_date'], "date"),
                       GetSQLValueString($_POST['ch_mem_group_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_mem_group_nb_update'], "int"),
                       GetSQLValueString($_POST['ch_mem_group_nom'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_desc'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_icon'], "text"),
					   GetSQLValueString($_POST['ch_mem_group_couleur'], "text"));

  
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = DEF_URI_PATH . "back/institut_politique.php";
  appendQueryString($insertGoTo);
  header(sprintf("Location: %s", $insertGoTo));
}

//requete liste categories membres
$maxRows_liste_mem_group = 10;
$pageNum_liste_mem_group = 0;
if (isset($_GET['pageNum_liste_mem_group'])) {
  $pageNum_liste_mem_group = $_GET['pageNum_liste_mem_group'];
}
$startRow_liste_mem_group = $pageNum_liste_mem_group * $maxRows_liste_mem_group;


$query_liste_mem_group = "SELECT * FROM membres_groupes ORDER BY ch_mem_group_mis_jour DESC";
$query_limit_liste_mem_group = sprintf("%s LIMIT %d, %d", $query_liste_mem_group, $startRow_liste_mem_group, $maxRows_liste_mem_group);
$liste_mem_group = mysql_query($query_limit_liste_mem_group, $maconnexion) or die(mysql_error());
$row_liste_mem_group = mysql_fetch_assoc($liste_mem_group);

if (isset($_GET['totalRows_liste_mem_group'])) {
  $totalRows_liste_mem_group = $_GET['totalRows_liste_mem_group'];
} else {
  $all_liste_mem_group = mysql_query($query_liste_mem_group);
  $totalRows_liste_mem_group = mysql_num_rows($all_liste_mem_group);
}
$totalPages_liste_mem_group = ceil($totalRows_liste_mem_group/$maxRows_liste_mem_group)-1;

$queryString_liste_mem_group = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_liste_mem_group") == false && 
        stristr($param, "totalRows_liste_mem_group") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_liste_mem_group = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_liste_mem_group = sprintf("&totalRows_liste_mem_group=%d%s", $totalRows_liste_mem_group, $queryString_liste_mem_group);



//requete liste categories membres pour pouvoir selectionner la categorie 

$query_liste_mem_group2 = "SELECT * FROM membres_groupes ORDER BY ch_mem_group_mis_jour DESC";
$liste_mem_group2 = mysql_query($query_liste_mem_group2, $maconnexion) or die(mysql_error());
$row_liste_mem_group2 = mysql_fetch_assoc($liste_mem_group2);
$totalRows_liste_mem_group2 = mysql_num_rows($liste_mem_group2);


//requete liste  membres d'une catégorie 
$maxRows_classer_mem = 10;
$pageNum_classer_mem = 0;
if (isset($_GET['pageNum_classer_mem'])) {
  $pageNum_classer_mem = $_GET['pageNum_classer_mem'];
}
$startRow_classer_mem = $pageNum_classer_mem * $maxRows_classer_mem;

$colname_classer_mem = "-1";
if (isset($_GET['mem_groupID'])) {
	if ($_GET['mem_groupID'] == "") {
	$colname_classer_mem = NULL;
} else {
  $colname_classer_mem = $_GET['mem_groupID'];
} } else {
  $colname_classer_mem = NULL;
} 

$query_classer_mem = sprintf("SELECT membre.ch_disp_MG_id as id, membre.ch_disp_mem_id, membre.ch_disp_mem_statut AS satut_membre, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_titre_dirigeant, ch_use_last_log, ch_use_lien_imgpersonnage, ch_use_paysID, (SELECT GROUP_CONCAT(categories.ch_disp_group_id) FROM dispatch_mem_group as categories WHERE membre.ch_disp_mem_id = categories.ch_disp_mem_id AND categories.ch_disp_mem_statut != 3) AS listgroup
FROM dispatch_mem_group as membre 
INNER JOIN users ON membre.ch_disp_mem_id = ch_use_id 
WHERE membre.ch_disp_group_id = %s OR %s IS NULL
GROUP BY membre.ch_disp_mem_id
ORDER BY membre.ch_disp_MG_date DESC", GetSQLValueString($colname_classer_mem, "int"), GetSQLValueString($colname_classer_mem, "int"));
$query_limit_classer_mem = sprintf("%s LIMIT %d, %d", $query_classer_mem, $startRow_classer_mem, $maxRows_classer_mem);
$classer_mem = mysql_query($query_limit_classer_mem, $maconnexion) or die(mysql_error());
$row_classer_mem = mysql_fetch_assoc($classer_mem);

if (isset($_GET['totalRows_classer_mem'])) {
  $totalRows_classer_mem = $_GET['totalRows_classer_mem'];
} else {
  $all_classer_mem = mysql_query($query_classer_mem);
  $totalRows_classer_mem = mysql_num_rows($all_classer_mem);
}
$totalPages_classer_mem = ceil($totalRows_classer_mem/$maxRows_classer_mem)-1;

$queryString_classer_mem = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_classer_mem") == false && 
        stristr($param, "totalRows_classer_mem") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_classer_mem = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_classer_mem = sprintf("&totalRows_classer_mem=%d%s", $totalRows_classer_mem, $queryString_classer_mem);



//requete listes membres restants

$query_liste_mem_restants = sprintf("SELECT ch_use_id AS nb_mem_restants FROM users WHERE ch_use_id NOT IN (SELECT ch_disp_mem_id FROM dispatch_mem_group WHERE ch_disp_group_id = %s OR %s IS NULL)", GetSQLValueString($colname_classer_mem, "int"), GetSQLValueString($colname_classer_mem, "int"));
$liste_mem_restants = mysql_query($query_liste_mem_restants, $maconnexion) or die(mysql_error());
$row_liste_mem_restants = mysql_fetch_assoc($liste_mem_restants);
$totalRows_liste_mem_restants = mysql_num_rows($liste_mem_restants);

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
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
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
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
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
  <form class="pull-right-cta" action="<?= DEF_URI_PATH ?>back/insitut_modifier.php" method="post" style="margin-top: 30px;">
    <input name="institut_id" type="hidden" value="<?php echo $row_institut['ch_ins_ID']; ?>">
    <button class="btn btn-primary btn-cta" type="submit" title="modifier les informations sur l'institut"><i class="icon-edit icon-white"></i> Modifier la description</button>
  </form>
  <!-- Liste des Communiqués
        ================================================== -->
  <div id="titre_institut" class="titre-bleu anchor">
    <h1>G&eacute;rer l'<?php echo $row_institut['ch_ins_nom']; ?></h1>
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
$com_element_id = 6;
include(DEF_ROOTPATH . 'php/communiques-back.php'); ?>
  </div>
  <!-- liste des groupes
     ================================================== -->
  <div class="span6">
    <div class="titre-gris">
      <h3>Groupes de Membres</h3>
    </div>
    <div id="liste-categories" class="anchor">
      <ul class="listes">
        <?php do { ?>
          <li class="row-fluid"> 
            <!-- ICONE groupe -->
            <div class="span2 icone-categorie"><img src="<?php echo $row_liste_mem_group['ch_mem_group_icon']; ?>" alt="icone <?php echo $row_liste_mem_group['ch_mem_group_nom']; ?>" style="background-color:<?php echo $row_liste_mem_group['ch_mem_group_couleur']; ?>; <?php if ($row_liste_mem_group['ch_mem_group_statut'] ==2 ) {?>opacity:0.5;<?php }?>"></div>
            <!-- contenu groupe -->
            <div class="span10 info-listes"> 
              <!-- Boutons modifier / supprimer --> 
              <a class="pull-right" href="../php/groupe-supprimmer-modal.php?mem_group_ID=<?php echo $row_liste_mem_group['ch_mem_group_ID']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="supprimer ce groupe"><i class="icon-remove"></i></a> <a class="pull-right" href="../php/groupe-modifier-modal.php?mem_group_ID=<?php echo $row_liste_mem_group['ch_mem_group_ID']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="modifier ce groupe"><i class="icon-pencil"></i></a> 
              <!-- Desc categorie -->
              <h4><?php echo $row_liste_mem_group['ch_mem_group_nom']; ?></h4>
              <p><?php echo $row_liste_mem_group['ch_mem_group_desc']; ?></p>
            </div>
          </li>
          <?php } while ($row_liste_mem_group = mysql_fetch_assoc($liste_mem_group)); ?>
      </ul>
      <!-- Pagination de la liste -->
       <p>&nbsp;</p>
        <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_liste_mem_group + 1) ?> &agrave; <?php echo min($startRow_liste_mem_group + $maxRows_liste_mem_group, $totalRows_liste_mem_group) ?> sur <?php echo $totalRows_liste_mem_group ?>
            <?php if ($pageNum_liste_mem_group > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_mem_group=%d%s#liste-categories", $currentPage, max(0, $pageNum_liste_mem_group - 1), $queryString_liste_mem_group); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_liste_mem_group < $totalPages_liste_mem_group) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_mem_group=%d%s#liste-categories", $currentPage, min($totalPages_liste_mem_group, $pageNum_liste_mem_group + 1), $queryString_liste_mem_group); ?>"> <i class="icon-forward"></i></a>
          <?php } // Show if not last page ?></small>
  </p>
    </div>
    <!-- Modal et script -->
    <div class="modal container fade" id="Modal-Groupe" data-width="760"></div>
    <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Groupe').modal('hide');
});
</script> 
    <!-- Ajouter un groupe
        ================================================== --> 
    <!-- Button to trigger modal --> 
    <a href="#ajouter-cat" role="button" class="btn btn-primary" title="Ajouter une cat&eacute;gorie" data-toggle="modal">Ajouter un groupe</a> 
    <!-- Modal -->
    <div id="ajouter-cat" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-width="760">
      <form action="<?php echo $editFormAction; ?>" name="ajout-categorie" method="POST" class="form-horizontal" id="ajout-groupe">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="myModalLabel">Ajouter un nouveau groupe de membres</h3>
        </div>
        <div class="modal-body"> 
          <!-- Boutons cachés -->
          <?php 
				  $now= date("Y-m-d G:i:s");?>
          <input name="ch_mem_group_label" type="hidden" value="mem_group">
          <input name="ch_mem_group_date" type="hidden" value="<?php echo $now; ?>">
          <input name="ch_mem_group_mis_jour" type="hidden" value="<?php echo $now; ?>">
          <input name="ch_mem_group_nb_update" type="hidden" value=0 >
          <!-- Statut -->
          <div id="spryradio1" class="control-group">
            <div class="control-label">Statut <a href="#" rel="clickover" title="Statut du groupe" data-content="
    Visible : ce groupe sera visible sur la partie publique du site.
    Invisible : ce groupe sera invisible sur la partie publique du site."><i class="icon-info-sign"></i></a></div>
            <div class="controls">
              <label>
                <input type="radio" name="ch_mem_group_statut" value="1" id="ch_mem_group_statut_1" checked="CHECKED">
                visible</label>
              <label>
                <input name="ch_mem_group_statut" type="radio" id="ch_mem_group_statut_2" value="2">
                invisible</label>
              <span class="radioRequiredMsg">Choisissez un statut pour ce groupe de membre</span></div>
          </div>
          <!-- Nom-->
          <div id="sprytextfield2" class="control-group">
            <label class="control-label" for="ch_mem_group_nom">Nom du groupe <a href="#" rel="clickover" title="Nom du groupe" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier le groupe dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="input-xlarge" type="text" id="ch_mem_group_nom" name="ch_mem_group_nom">
              <br>
              <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">30 caract&egrave;res max.</span></div>
          </div>
          <!-- Icone -->
          <div id="sprytextfield3" class="control-group">
            <label class="control-label" for="ch_mem_group_icon">Ic&ocirc;ne <a href="#" rel="clickover" title="Ic&ocirc;ne" data-content="L'ic&ocirc;ne sert &agrave; repr&eacute;senter le groupe dans l'ensemble du site. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="input-xlarge" type="text" name="ch_mem_group_icon" id="ch_mem_group_icon" value="">
              <br>
              <span class="textfieldRequiredMsg">une ic&ocirc;ne est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
          </div>
          <!-- Couleur -->
          <div id="" class="control-group">
            <label class="control-label" for="ch_mem_group_icon">Couleur <a href="#" rel="clickover" title="Couleur" data-content="Choisissez une couleur de fond pour ce groupe"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <div class="input-append color" data-color="#06C" data-color-format="hex" id="cp3">
                <input type="text" class="span2" value="" name="ch_mem_group_couleur" id="ch_mem_group_couleur">
                <span class="add-on"><i style="background-color: #06C)"></i></span> </div>
            </div>
          </div>
          <!-- Description -->
          <div id="sprytextarea1" class="control-group">
            <label class="control-label" for="ch_mem_group_desc">Description <a href="#" rel="clickover" title="Description" data-content="Donnez en quelques lignes des informations qui permettrons de comprendre l'objet de ce groupe. 400 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <textarea rows="6" name="ch_mem_group_desc" class="input-xlarge" id="ch_mem_group_desc"></textarea>
              <br>
              <span class="textareaMaxCharsMsg">400 caract&egrave;res max.</span></div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
          <button type="submit" class="btn btn-primary">Enregistrer</button>
          <input type="hidden" name="MM_insert" value="ajout-groupe">
        </div>
      </form>
    </div>
  </div>
</div>
<p>&nbsp;</p>
<!-- Ajouter des membres dans mes groupes
        ================================================== -->
<div class="titre-gris" id="classer-membres" class="anchor">
<h3>G&eacute;rer les groupes</h3>
</div>
<div class="row-fluid"> 
  <!-- Liste pour choix de la categories -->
  <div id="select-categorie">
    <form action="<?= DEF_URI_PATH ?>back/institut_politique.php#classer-membres" method="GET">
      <select name="mem_groupID" id="mem_groupID" onchange="this.form.submit()">
        <option value="" <?php if ($colname_classer_mem == NULL) {?>selected<?php } ?>>S&eacute;lectionnez un groupe&nbsp;</option>
        <?php do { ?>
        <option value="<?php echo $row_liste_mem_group2['ch_mem_group_ID']; ?>" <?php if ($colname_classer_mem == $row_liste_mem_group2['ch_mem_group_ID']) {?>selected<?php } ?>><?php echo $row_liste_mem_group2['ch_mem_group_nom']; ?></option>
        <?php } while ($row_liste_mem_group2 = mysql_fetch_assoc($liste_mem_group2)); ?>
      </select>
    </form>
  </div>
  <!-- Liste des membres de la categorie -->
  <ul class="listes">
    <!-- Requetes pour infos et icones des catégories du membres -->
    <?php do { 
	  
			$listgroup = $row_classer_mem['listgroup'];
			if ($row_classer_mem['listgroup']) {
          

$query_liste_mem_group3 = "SELECT * FROM membres_groupes WHERE ch_mem_group_ID In ($listgroup)";
$liste_mem_group3 = mysql_query($query_liste_mem_group3, $maconnexion) or die(mysql_error());
$row_liste_mem_group3 = mysql_fetch_assoc($liste_mem_group3);
$totalRows_liste_mem_group3 = mysql_num_rows($liste_mem_group3);
			 } ?>
    <?php if ($row_classer_mem) {?>
    <!-- Item membre -->
    <li class="row-fluid <?php if ($row_classer_mem['satut_membre']==3) {?>attente<?php } ?>"> 
      <!-- Image du membre -->
      <div class="span2 img-listes">
        <?php if ($row_classer_mem['ch_use_lien_imgpersonnage']) {?>
        <img src="<?php echo $row_classer_mem['ch_use_lien_imgpersonnage']; ?>" alt="image <?php echo $row_classer_mem['ch_use_nom_dirigeant']; ?>">
        <?php } else { ?>
        <img src="../assets/img/imagesdefaut/personnage.jpg" alt="membre">
        <?php } ?>
      </div>
      <!-- Nom, date et lien vers la page du membre -->
      <div class="span6 info-listes">
        <h4><?php echo $row_classer_mem['ch_use_nom_dirigeant']; ?> <?php echo $row_classer_mem['ch_use_prenom_dirigeant']; ?></h4>
<p><?php echo $row_classer_mem['ch_use_titre_dirigeant']; ?></p>
        <p><strong>Derni&egrave;re connexion&nbsp;: </strong>le
          <?php  echo date("d/m/Y", strtotime($row_classer_mem['ch_use_last_log'])); ?>
          &agrave; <?php echo date("G:i:s", strtotime($row_classer_mem['ch_use_last_log'])); ?> </p>
        <a class="btn btn-primary" href="../page-pays.php?ch_pay_id=<?php echo $row_classer_mem['ch_use_paysID']; ?>#diplomatie">Voir le profil</a>
        <!-- Si le membre a fait une demande et que l'utilisateur est administrateur du groupe -->
          <?php if (($row_classer_mem['satut_membre']==3) AND ($colname_classer_mem != NULL) AND ($colname_classer_mem != "")) {?>
		  <h4>Ce membre a lanc&eacute; une proc&eacute;dure pour rejoindre ce groupe</h4>
		  <?php } ?>
          </div>
      <!-- Affichage de scategories du membre -->
      <div class="span4">
        <?php if (($colname_classer_mem != NULL) AND ($colname_classer_mem != "")) { // affiche bouton ajouter si une categorie est choisie ?>
        <!-- Boutons supprimer membre de la catégorie --> 
        <a class="pull-right" href="../php/groupe-supprimmer-membre-modal.php?ch_disp_MG_id=<?php echo $row_classer_mem['id']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="supprimer ce membre de cette cat&eacute;gorie"><i class="icon-remove"></i></a>
        <a class="pull-right" href="../php/groupe-modifier-membre-modal.php?ch_disp_MG_id=<?php echo $row_classer_mem['id']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="modifier le statut de ce membre"><i class="icon-edit"></i></a>
        <?php } ?>
        <?php if ($row_liste_mem_group3) {?>
        <?php do { ?>
          <!-- Icone et popover de la categorie -->
          <div class="span2 icone-categorie"><a href="#" rel="clickover" title="<?php echo $row_liste_mem_group3['ch_mem_group_nom']; ?>" data-placement="left" data-content="<?php echo $row_liste_mem_group3['ch_mem_group_desc']; ?>"><img src="<?php echo $row_liste_mem_group3['ch_mem_group_icon']; ?>" alt="icone <?php echo $row_liste_mem_group3['ch_mem_group_nom']; ?>" style="background-color:<?php echo $row_liste_mem_group3['ch_mem_group_couleur']; ?>; <?php if ($row_liste_mem_group3['ch_mem_group_statut'] ==2 ) {?>opacity:0.5;<?php }?>"></a></div>
          <?php } while ($row_liste_mem_group3 = mysql_fetch_assoc($liste_mem_group3)); ?>
          <?php } ?>
      </div>
    </li>
    <?php } ?>
    <?php } while ($row_classer_mem = mysql_fetch_assoc($classer_mem)); ?>
  </ul>
  <p>&nbsp;</p>
  <!-- Bouton ajouter membre dans le groupe -->
  <?php if (($colname_classer_mem != NULL) AND ($colname_classer_mem != "") AND ($row_liste_mem_restants['nb_mem_restants'])) { // affiche bouton ajouter si une categorie est choisie ?>
  <a class="btn btn-primary btn-margin-left" href="../php/groupe-institut-ajouter-membre-modal.php?mem_groupID=<?php echo $colname_classer_mem; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="Ajouter un membre dans ce groupe">Ajouter un membre</a>
  <?php } else { ?>
  <a class="btn btn-primary btn-margin-left disabled">Ajouter un membre</a>
  <?php }?> 
  <!-- Modal et script -->
  <div class="modal container fade" id="#Modal-Groupe" data-width="760"></div>
  <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Groupe').modal('hide');
});
</script>
  <p>&nbsp;</p>
  <!-- Pagination liste des membres de la categorie -->
    <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_classer_mem + 1) ?> &agrave; <?php echo min($startRow_classer_mem + $maxRows_classer_mem, $totalRows_classer_mem) ?> sur <?php echo $totalRows_classer_mem ?>
            <?php if ($pageNum_classer_mem > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_classer_mem=%d%s#classer-membres", $currentPage, max(0, $pageNum_classer_mem - 1), $queryString_classer_mem); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_classer_mem < $totalPages_classer_mem) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_classer_mem=%d%s#classer-membres", $currentPage, min($totalPages_classer_mem, $pageNum_classer_mem + 1), $queryString_classer_mem); ?>"> <i class="icon-forward"></i></a>
          <?php } // Show if not last page ?></small>
  </p>
</div>
</div>
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
mysql_free_result($liste_mem_group);
mysql_free_result($liste_mem_group2);
mysql_free_result($classer_mem);
mysql_free_result($institut);
?>