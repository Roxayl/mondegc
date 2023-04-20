<?php

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if (!($_SESSION['statut'] and ($_SESSION['statut'] >= 30))) {
    // Redirection vers page connexion
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

$colname_Pays = "-1";
if (isset($_POST['paysID'])) {
  $colname_Pays = $_POST['paysID'];
}

$query_Pays = sprintf("SELECT ch_pay_id, ch_pay_nom, ch_pay_devise, ch_pay_lien_imgheader FROM pays WHERE ch_pay_id = %s", escape_sql($colname_Pays, "int"));
$Pays = mysql_query($query_Pays, $maconnexion);
$row_Pays = mysql_fetch_assoc($Pays);
$totalRows_Pays = mysql_num_rows($Pays);

$currentPage = $_SERVER["PHP_SELF"];
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Supprimer un pays</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('<?= e($row_Pays['ch_pay_lien_imgheader']) ?>');
	background-position:center;
}
</style>
<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div id="introheader" class="jumbotron">
  <div class="container">
    <h1>Attention&nbsp;!</h1>
    <p>Souhaitez-vous r&eacute;ellement supprimer le pays <?= e($row_Pays['ch_pay_nom']) ?>&nbsp;?</p>
      <p>Cette action sera irr&eacute;versible</p>
    <form action="<?= DEF_URI_PATH ?>back/page_pays_supprimer.php" method="post" class="form-button-inline">
      <input name="Pays_ID" type="hidden" value="<?= e($row_Pays['ch_pay_id']) ?>">
      <button type="submit" class="btn btn-large btn-danger" title="supprimer le Pays"><i class="icon-trash icon-white"></i> Supprimer</button>
      </form>
      <form action="<?= DEF_URI_PATH ?>back/page_pays_back.php" method="post" class="form-button-inline">
        <input name="paysID" type="hidden" value="<?= e($row_Pays['ch_pay_id']) ?>">
      <button type="submit" class="btn btn-large btn-success" title="retour &agrave; la page de modification du pays">Annuler</button>
    </form>
    </p>
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/footer.php'); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- CARTE -->
<script src="../assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="../assets/js/OpenLayers.js" type="text/javascript"></script>
<?php require(DEF_LEGACYROOTPATH . 'php/carteemplacements.php'); ?>
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
    $(function() {
        $('[rel="clickover"]').clickover();
    });
</script>
</body>
</html>