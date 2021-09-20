<?php

//Connexion et deconnexion
include('php/log.php');

//requete liste communiqu�s

$maxRows_communiquesPays = 10;
$pageNum_communiquesPays = 0;
if (isset($_GET['pageNum_communiquesPays'])) {
  $pageNum_communiquesPays = $_GET['pageNum_communiquesPays'];
}
$startRow_communiquesPays = $pageNum_communiquesPays * $maxRows_communiquesPays;


$query_communiquesPays = "SELECT article.ch_com_id, COUNT(commentaire.ch_com_id) AS nb_commentaires, article.ch_com_date, article.ch_com_titre, article.ch_com_contenu, ch_use_login, ch_ins_nom, ch_ins_ID FROM communiques AS article INNER JOIN users ON ch_use_id = article.ch_com_user_id INNER JOIN  instituts ON article.ch_com_element_id = ch_ins_ID LEFT OUTER JOIN communiques AS commentaire ON article.ch_com_id = commentaire.ch_com_element_id WHERE article.ch_com_categorie = 'institut' AND article.ch_com_statut = '1' GROUP BY article.ch_com_id ORDER BY article.ch_com_date DESC";
$query_limit_communiquesPays = sprintf("%s LIMIT %d, %d", $query_communiquesPays, $startRow_communiquesPays, $maxRows_communiquesPays);
$communiquesPays = mysql_query($query_limit_communiquesPays, $maconnexion) or die(mysql_error());
$row_communiquesPays = mysql_fetch_assoc($communiquesPays);

if (isset($_GET['totalRows_communiquesPays'])) {
  $totalRows_communiquesPays = $_GET['totalRows_communiquesPays'];
} else {
  $all_communiquesPays = mysql_query($query_communiquesPays);
  $totalRows_communiquesPays = mysql_num_rows($all_communiquesPays);
}
$totalPages_communiquesPays = ceil($totalRows_communiquesPays/$maxRows_communiquesPays)-1;

$queryString_communiquesPays = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_communiquesPays") == false && 
        stristr($param, "totalRows_communiquesPays") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_communiquesPays = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_communiquesPays = sprintf("&totalRows_communiquesPays=%d%s", $totalRows_communiquesPays, $queryString_communiquesPays);
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Communiqués des comités</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="Carto/OLdefault.css" rel="stylesheet">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css">
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
    background-image: url('assets/img/bannieres-instituts/OCGC.png');
}
#map {
	height: 500px;
	background-color: #fff;
}
#mapPosition {
	height: 500px;
	background-color: #fff;
}
img.olTileImage {
	max-width: none;
}
@media (max-width: 480px) {
#map {
	height: 260px;
}
}
</style>
<!-- CARTE -->
<script src="assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="assets/js/OpenLayers.js" type="text/javascript"></script>
<?php require('php/cartepays.php'); ?>
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
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

<?php
Eventy::action('display.legacy.beforeHeadClosingTag')
?>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php $institut=true; require('php/navbar.php'); ?>
<!-- Page CONTENT
    ================================================== -->
<div class="container">
  <div class="corps-page"> 
    <!-- liste des communique de l'institut
    ================================================== -->
    <section>
    <header class="jumbotron jumbotron-institut jumbotron-small subhead anchor" id="titre">
      <div class="container">
        <h2>Organisation des Cités gécéennes</h2>
        <h1>Communiqués des comités</h1>
      </div>
    </header>

    <ul class="breadcrumb">
      <li><a href="OCGC.php">OCGC</a> <span class="divider">/</span></li>
      <li class="active">Communiqués des comités</li>
    </ul>

    <?php do { ?>
      <div class="row-fluid" id="communiqueID<?= e($row_communiquesPays['ch_com_ID']) ?>">
      <div class="titre-bleu anchor" id="presentation">
          <h1><?= e($row_communiquesPays['ch_com_titre']) ?></h1>

      <p class="pull-right" style="margin-right:10px;">
          Le <strong><?php echo date("d/m/Y", strtotime($row_communiquesPays['ch_com_date'])); ?></strong> &agrave; <?php echo date("G\hi", strtotime($row_communiquesPays['ch_com_date'])); ?> par <?= e($row_communiquesPays['ch_use_login']) ?></p>
      </div>
      <div class="well">
	  <strong><p><?= e($row_communiquesPays['ch_ins_nom']) ?></p></strong>
	  <?= htmlPurify($row_communiquesPays['ch_com_contenu']) ?>
      <a class="btn btn-primary" href="page-communique.php?com_id=<?= e($row_communiquesPays['ch_com_id']) ?>"><?= e($row_communiquesPays['nb_commentaires']) ?> commentaires</a>
      </div>
      <p>&nbsp;</p>
      <?php } while ($row_communiquesPays = mysql_fetch_assoc($communiquesPays)); ?>
      <!-- Pagination
    ================================================== -->
    <small class="pull-right">de <?php echo ($startRow_communiquesPays + 1) ?> &agrave; <?php echo min($startRow_communiquesPays + $maxRows_communiquesPays, $totalRows_communiquesPays) ?> sur <?php echo $totalRows_communiquesPays ?>
    <?php if ($pageNum_communiquesPays > 0) { // Show if not first page ?>
      <a class="btn" href="<?php printf("%s?pageNum_communiquesPays=%d%s#communiques", $currentPage, max(0, $pageNum_communiquesPays - 1), $queryString_communiquesPays); ?>"><i class=" icon-backward"></i></a>
      <?php } // Show if not first page ?>
    <?php if ($pageNum_communiquesPays < $totalPages_communiquesPays) { // Show if not last page ?>
      <a class="btn" href="<?php printf("%s?pageNum_communiquesPays=%d%s#communiques", $currentPage, min($totalPages_communiquesPays, $pageNum_communiquesPays + 1), $queryString_communiquesPays); ?>"> <i class="icon-forward"></i></a>
      <?php } // Show if not last page ?>
    </small></div>
  </section>
</div>
<!-- END CONTENT
    ================================================== -->
<!-- Footer
    ================================================== -->
<?php require('php/footer.php'); ?>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
</body>
</html>
