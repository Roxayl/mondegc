<?php

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if(!isset($_SESSION['userObject'])) {
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

?>
<?php

$colname_ch_vil_confimation_suppression = "-1";
if (isset($_POST['ville-ID'])) {
  $colname_ch_vil_confimation_suppression = $_POST['ville-ID'];
}

$query_ch_vil_confimation_suppression = sprintf("SELECT ch_vil_ID, ch_vil_paysID, ch_vil_nom, ch_vil_lien_img1 FROM villes WHERE ch_vil_ID = %s", GetSQLValueString($colname_ch_vil_confimation_suppression, "int"));
$ch_vil_confimation_suppression = mysql_query($query_ch_vil_confimation_suppression, $maconnexion);
$row_ch_vil_confimation_suppression = mysql_fetch_assoc($ch_vil_confimation_suppression);
$totalRows_ch_vil_confimation_suppression = mysql_num_rows($ch_vil_confimation_suppression);
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Supprimer<?= e($row_ch_vil_confimation_suppression['ch_vil_nom']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
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
	background-image: url('<?php echo $row_ch_vil_confimation_suppression['ch_vil_lien_img1']; ?>');
	background-position: center;
}
</style>

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron subhead" id="overview">
  <div class="container">
    <h1>Attention&nbsp;!</h1>
    <p>Souhaitez-vous r&eacute;ellement supprimer <?= e($row_ch_vil_confimation_suppression['ch_vil_nom']) ?>&nbsp;?</p>
    <p>Cette action sera irr&eacute;versible</p>
    <form action="ville_supprimer.php" method="post" class="form-button-inline">
      <input name="ville_ID" type="hidden" value="<?= e($row_ch_vil_confimation_suppression['ch_vil_ID']) ?>">
      <button type="submit" class="btn btn-large btn-danger" title="supprimer la ville"><i class="icon-trash icon-white"></i> Supprimer</button>
    </form>
    <form action="ville_modifier.php" method="get" class="form-button-inline">
      <input name="ville_ID" type="hidden" value="<?= e($row_ch_vil_confimation_suppression['ch_vil_ID']) ?>">
      <button type="submit" class="btn btn-large btn-success" title="retour &agrave; la page de modification de la ville">Annuler</button>
    </form>
  </div>
</header>
<div class="container corps-page"> </div>

<!-- Footer
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/footer.php'); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
    $(function () {
        $('[rel="clickover"]').clickover();
    })
</script>
</body>
</html>
