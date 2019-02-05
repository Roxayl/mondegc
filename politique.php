<?php

require_once('Connections/maconnexion.php');

//Connexion et deconnexion
include('php/log.php');

//requete instituts
$institut_id = 6;
mysql_select_db($database_maconnexion, $maconnexion);
$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

//requete liste groupes groupess pour pouvoir selectionner la categorie 
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mem_group2 = "SELECT * FROM membres_groupes WHERE ch_mem_group_statut = 1  ORDER BY ch_mem_group_mis_jour DESC";
$liste_mem_group2 = mysql_query($query_liste_mem_group2, $maconnexion) or die(mysql_error());
$row_liste_mem_group2 = mysql_fetch_assoc($liste_mem_group2);
$totalRows_liste_mem_group2 = mysql_num_rows($liste_mem_group2);


//requete liste  groupes d'une catégorie 
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
mysql_select_db($database_maconnexion, $maconnexion);
$query_classer_mem = sprintf("SELECT membre.ch_disp_MG_id as id, membre.ch_disp_mem_id, membre.ch_disp_mem_statut AS satut_membre, ch_use_id, ch_use_last_log, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_titre_dirigeant, ch_use_paysID, ch_use_lien_imgpersonnage, (SELECT GROUP_CONCAT(groupes.ch_disp_group_id) FROM dispatch_mem_group as groupes WHERE membre.ch_disp_mem_id = groupes.ch_disp_mem_id AND groupes.ch_disp_mem_statut != 3) AS listgroup
FROM dispatch_mem_group as membre 
INNER JOIN users ON membre.ch_disp_mem_id = ch_use_id 
WHERE membre.ch_disp_group_id = %s OR %s IS NULL AND membre.ch_disp_mem_statut <> 3
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


//requete info sur catégorie
mysql_select_db($database_maconnexion, $maconnexion);
$query_info_group = sprintf("SELECT ch_mem_group_nom, ch_mem_group_desc, ch_mem_group_icon, ch_mem_group_couleur
FROM membres_groupes
WHERE ch_mem_group_ID = %s OR %s IS NULL AND ch_mem_group_statut = 1", GetSQLValueString($colname_classer_mem, "int"), GetSQLValueString($colname_classer_mem, "int"));
$info_group = mysql_query($query_info_group, $maconnexion) or die(mysql_error());
$row_info_group = mysql_fetch_assoc($info_group);
$totalRows_info_group = mysql_num_rows($info_group);
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC- politique</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
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
	background-image: url('assets/img/fond_haut-conseil.jpg');
}
</style>
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
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
<!-- EDITEUR -->
<script type="text/javascript" src="assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php $institut=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron jumbotron-institut subhead anchor" id="info-institut" >
  <div class="container">
    <h1><?php echo $row_institut['ch_ins_nom']; ?></h1>
  </div>
</header>
<div class="container"> 
  
  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <li class="row-fluid"><a href="#info-institut">
          <?php if ($row_institut['ch_ins_logo']) { ?>
          <img src="<?php echo $row_institut['ch_ins_logo']; ?>">
          <?php } else { ?>
          <img src="assets/img/imagesdefaut/blason.jpg">
          <?php }?>
          <p><strong><?php echo $row_institut['ch_ins_sigle']; ?></strong></p>
          <p><em><?php echo $row_institut['ch_ins_nom']; ?></em></p>
          </a></li>
        <li><a href="#presentation">Pr&eacute;sentation</a></li>
        <li><a href="#groupes">Groupes Politiques</a></li>
        <li><a href="#communiques">Communiqu&eacute;s officiels</a></li>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== --> 
    
    <!-- Page CONTENT
    ================================================== -->
    <div class="span9 corps-page">

    <ul class="breadcrumb">
      <li><a href="OCGC.php">OCGC</a> <span class="divider">/</span></li>
      <li class="active">Politique</li>
    </ul>

      <!-- Presentation
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="presentation"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
          <h1>Présentation</h1>
        </div>
        <div class="well">
          <div class="row-fluid">
            <div class="span7">
              <p><?php echo $row_institut['ch_ins_desc']; ?></p>
            </div>
            <div class="span5"><img src="<?php echo $row_institut['ch_ins_img']; ?>"></div>
          </div>
        </div>
      </section>
      <!-- Monument indexe
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="groupes"> <img src="assets/img/IconesBDD/Bleu/100/Membre1_bleu.png">
          <h1>Groupes Politiques</h1>
        </div>
        <div class="row-fluid"> 
          <!-- Liste pour choix de la groupes -->
          <div id="select-categorie">
            <form action="politique.php#groupes" method="GET">
              <select name="mem_groupID" id="mem_groupID" onchange="this.form.submit()">
                <option value="" <?php if ($colname_classer_mem == NULL) {?>selected<?php } ?>>S&eacute;lectionnez un groupe&nbsp;</option>
                <?php do { ?>
                <option value="<?php echo $row_liste_mem_group2['ch_mem_group_ID']; ?>" <?php if ($colname_classer_mem == $row_liste_mem_group2['ch_mem_group_ID']) {?>selected<?php } ?>><?php echo $row_liste_mem_group2['ch_mem_group_nom']; ?></option>
                <?php } while ($row_liste_mem_group2 = mysql_fetch_assoc($liste_mem_group2)); ?>
              </select>
            </form>
          </div>
          <!-- Affichage si des informations de la catégorie  -->
          <?php if (($colname_classer_mem != NULL) AND ($colname_classer_mem != "")) { // affiche bouton ajouter si une categorie est choisie ?>
          <div class="well">
            <div class="row-fluid">
              <div class="span8">
                <p><strong><?php echo $row_info_group['ch_mem_group_nom']; ?></strong></p>
                <p><?php echo $row_info_group['ch_mem_group_desc']; ?></p>
              </div>
              <div class="span2 icone-categorie icone-large"><img src="<?php echo $row_info_group['ch_mem_group_icon']; ?>" alt="icone <?php echo $row_info_group['ch_mem_group_nom']; ?>" style="background-color:<?php echo $row_info_group['ch_mem_group_couleur']; ?>;"></div>
            </div>
          </div>
          <?php }?>
          <?php if ($row_classer_mem) {?>
          <!-- Liste des groupess de la categorie -->
          <ul class="listes">
            <!-- Requetes pour infos et icones des catégories du groupess -->
            <?php do { 
	  
			$listgroupes = $row_classer_mem['listgroup'];
			if ($row_classer_mem['listgroup']) {
          
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mem_group3 = "SELECT * FROM membres_groupes WHERE ch_mem_group_ID In ($listgroupes) AND ch_mem_group_statut=1";
$liste_mem_group3 = mysql_query($query_liste_mem_group3, $maconnexion) or die(mysql_error());
$row_liste_mem_group3 = mysql_fetch_assoc($liste_mem_group3);
$totalRows_liste_mem_group3 = mysql_num_rows($liste_mem_group3);
			 } ?>
            <!-- Item groupes -->
            <li class="row-fluid"> 
              <!-- Image du groupes -->
              <div class="span2 img-listes">
                <?php if ($row_classer_mem['ch_use_lien_imgpersonnage']) {?>
                <img src="<?php echo $row_classer_mem['ch_use_lien_imgpersonnage']; ?>" alt="image <?php echo $row_classer_mem['ch_use_id']; ?>">
                <?php } else { ?>
                <img src="assets/img/imagesdefaut/ville.jpg" alt="groupes">
                <?php } ?>
              </div>
              <!-- Nom, date et lien vers la page du groupes -->
              <div class="span6 info-listes">
                <h4><?php echo $row_classer_mem['ch_use_prenom_dirigeant']; ?> <?php echo $row_classer_mem['ch_use_nom_dirigeant']; ?></h4>
                <p><?php echo $row_classer_mem['ch_use_titre_dirigeant']; ?></p>
                <p><strong>Derni&egrave;re connexion&nbsp;: </strong>le
                  <?php  echo date("d/m/Y", strtotime($row_classer_mem['ch_use_last_log'])); ?>
                  &agrave; <?php echo date("G:i:s", strtotime($row_classer_mem['ch_use_last_log'])); ?> </p>
                <a class="btn btn-primary" href="page-pays.php?ch_pay_id=<?php echo $row_classer_mem['ch_use_paysID']; ?>#diplomatie">Voir profil</a> </div>
              <!-- Affichage de sgroupes du groupes -->
              <?php if ($row_liste_mem_group3) {?>
              <div class="span4 icone-categorie">
                <?php do { ?>
                  <!-- Icone et popover de la categorie -->
                  <div class=""><a href="#" rel="clickover" title="<?php echo $row_liste_mem_group3['ch_mem_group_nom']; ?>" data-placement="top" data-content="<?php echo $row_liste_mem_group3['ch_mem_group_desc']; ?>"><img src="<?php echo $row_liste_mem_group3['ch_mem_group_icon']; ?>" alt="icone <?php echo $row_liste_mem_group3['ch_mem_group_nom']; ?>" style="background-color:<?php echo $row_liste_mem_group3['ch_mem_group_couleur']; ?>;"></a></div>
                  <?php } while ($row_liste_mem_group3 = mysql_fetch_assoc($liste_mem_group3)); ?>
              </div>
              <?php } ?>
            </li>
            <?php } while ($row_classer_mem = mysql_fetch_assoc($classer_mem)); ?>
          </ul>
          <div class="modal container fade" id="Modal-Monument"></div>
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
          <!-- Pagination liste des groupess de la categorie -->
          <small class="pull-right">de <?php echo ($startRow_classer_mem + 1) ?> &agrave; <?php echo min($startRow_classer_mem + $maxRows_classer_mem, $totalRows_classer_mem) ?> sur <?php echo $totalRows_classer_mem ?>
            <?php if ($pageNum_classer_mem > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_classer_mem=%d%s#groupes", $currentPage, max(0, $pageNum_classer_mem - 1), $queryString_classer_mem); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_classer_mem < $totalPages_classer_mem) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_classer_mem=%d%s#groupes", $currentPage, min($totalPages_classer_mem, $pageNum_classer_mem + 1), $queryString_classer_mem); ?>"> <i class="icon-forward"></i></a>
          <?php } // Show if not last page ?></small>
          <?php } else { ?>
          <p>Ce groupe n'as pas encore de membres</p>
          <?php }  ?>
        </div>
      </section>
      <!-- communique officiel
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="communiques"> <img src="assets/img/IconesBDD/Bleu/100/Communique_bleu.png">
          <h1>Communiqu&eacute;s officiels</h1>
        </div>
        <?php 
	 $ch_com_categorie = 'institut';
	  $ch_com_element_id = $institut_id;
	  include('php/communiques.php'); ?>
      </section>
    </div>
    <!-- END CONTENT
    ================================================== --> 
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>
