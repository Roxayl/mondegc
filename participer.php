<?php if(!isset($mondegc_config['front-controller'])) require_once('Connections/maconnexion.php');
 
if(!isset($mondegc_config['front-controller'])) require_once('Connections/maconnexion.php');
//Connexion et deconnexion
include('php/log.php');


$query_Last24H = "SELECT ch_use_login, ch_use_statut, ch_use_paysID FROM users WHERE ch_use_last_log > DATE_SUB(NOW(), INTERVAL 2 DAY) ORDER BY ch_use_login";
$Last24H = mysql_query($query_Last24H, $maconnexion) or die(mysql_error());
$row_Last24H = mysql_fetch_assoc($Last24H);
$totalRows_Last24H = mysql_num_rows($Last24H);

$pageParticiper = new \GenCity\Monde\Page('participer');
$pageParticiperCadre = new \GenCity\Monde\Page('participer_cadre');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- head Html -->
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Le Monde GC - Participer</title>
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
<!-- Le javascript
    ================================================== -->
<!-- CARTE -->
<script src="assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="assets/js/OpenLayers.js" type="text/javascript"></script>
<?php include('php/carteemplacements-publique.php'); ?>
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
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
</style>
</head>

<body>
<!-- Navbar
    ================================================== -->
<?php $participer=true; include('php/navbar.php'); ?>
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
      <div id="info">

        <?= $pageParticiperCadre->content() ?>

        <div>
          <h4>Joueurs connect&eacute;s ces derni&egrave;res 48 heures</h4>
          <strong>
          <p>
            <?php do { 
      if ($row_Last24H['ch_use_statut'] == 30) {?>
            <a href="page-pays.php?ch_pay_id=<?php echo $row_Last24H['ch_use_paysID']; ?>#diplomatie" style="color:#FF4F4F; text-decoration:none"> <?php echo $row_Last24H['ch_use_login']; ?> </a> -
            <?php } elseif ($row_Last24H['ch_use_statut'] == 20) {?>
            <a href="page-pays.php?ch_pay_id=<?php echo $row_Last24H['ch_use_paysID']; ?>#diplomatie" style=" color:#FF9900; text-decoration:none"> <?php echo $row_Last24H['ch_use_login']; ?> </a> -
            <?php } else {?>
            <a href="page-pays.php?ch_pay_id=<?php echo $row_Last24H['ch_use_paysID']; ?>#diplomatie" style=" color:#FFFFFF; text-decoration:none"> <?php echo $row_Last24H['ch_use_login']; ?> </a> -
            <?php } ?>
            <?php } while ($row_Last24H = mysql_fetch_assoc($Last24H)); ?>
          </p>
          </strong>
        </div>
      </div>
  </div>
  </div>
</header>
<div class="container corps-page"> 
  <!-- Liste
    ================================================== -->

    <?= $pageParticiper->content() ?>

</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($Last24H);
?>