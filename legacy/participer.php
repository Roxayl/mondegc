<?php

//Connexion et deconnexion
include('php/log.php');

$query_Last24H = "SELECT ch_use_login, ch_use_statut, last_activity FROM users
    WHERE last_activity > DATE_SUB(NOW(), INTERVAL 2 DAY) ORDER BY ch_use_login";
$Last24H = mysql_query($query_Last24H, $maconnexion);

$pageParticiper = new \GenCity\Monde\Page('participer');
$pageParticiperCadre = new \GenCity\Monde\Page('participer_cadre');

?><!DOCTYPE html>
<!-- head Html -->
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Le Monde GC - Participer</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="carto/OLdefault.css" rel="stylesheet">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
<!-- TemplateEndEditable -->
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
<?php require('php/carteemplacements-publique.php'); ?>
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

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>

<body>
<!-- Navbar
    ================================================== -->
<?php $participer=true; require('php/navbar.php'); ?>
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
          <h4>Joueurs connectés ces dernières 48 heures</h4>
          <p>
          <?php while($row_Last24H = mysql_fetch_assoc($Last24H)) {
            if ($row_Last24H['ch_use_statut'] == 30): ?>
                <span style="color:#FF4F4F;"><?= e($row_Last24H['ch_use_login']) ?></span>
            <?php elseif ($row_Last24H['ch_use_statut'] == 20): ?>
                <span style="color:#FF9900;"><?= e($row_Last24H['ch_use_login']) ?></span>
            <?php else: ?>
                <span><?= e($row_Last24H['ch_use_login']) ?></span>
            <?php endif; ?>

            <?php
            // Afficher l'utilisateur comme connecté en cas d'activité durant les 15 dernières minutes.
            if(strtotime($row_Last24H['last_activity']) > time() - 60 * 15): ?>
                <span style="display: inline-block; background-color: green; border-radius: 5px;
                            width: 10px; height: 10px;" title="Connecté"></span>
            <?php endif; ?>
            &#183;
          <?php } ?>
          </p>
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
<?php require('php/footer.php'); ?>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
</body>
</html>