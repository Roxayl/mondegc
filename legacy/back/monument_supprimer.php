<?php

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if(!isset($_SESSION['userObject'])) {
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

$colname_ch_pat_confimation_suppression = "-1";
if (isset($_POST['monument_ID'])) {
  $colname_ch_pat_confimation_suppression = $_POST['monument_ID'];
}

$query_ch_pat_confimation_suppression = sprintf("SELECT ch_pat_id, ch_pat_villeID FROM patrimoine WHERE ch_pat_id = %s", escape_sql($colname_ch_pat_confimation_suppression, "int"));
$ch_pat_confimation_suppression = mysql_query($query_ch_pat_confimation_suppression, $maconnexion);
$row_ch_pat_confimation_suppression = mysql_fetch_assoc($ch_pat_confimation_suppression);
$totalRows_ch_pat_confimation_suppression = mysql_num_rows($ch_pat_confimation_suppression);

// suppression des villes

if((isset($_POST['monument_ID'])) && ($_POST['monument_ID'] != "")) {
    $eloquentPatrimoine = \Roxayl\MondeGC\Models\Patrimoine::query()->findOrFail($_POST['monument_ID']);

    $eloquentPatrimoine->deleteInfluences();

    $deleteSQL = sprintf("DELETE FROM patrimoine WHERE ch_pat_id=%s",
        escape_sql($_POST['monument_ID'], "int"));
    $Result1 = mysql_query($deleteSQL, $maconnexion);

    $deleteSQL2 = sprintf("DELETE FROM dispatch_mon_cat WHERE ch_disp_mon_id=%s",
        escape_sql($_POST['monument_ID'], "int"));
    $Result2 = mysql_query($deleteSQL2, $maconnexion);

    getErrorMessage('success', "Le monument a été supprimé avec succès.");

    $deleteGoTo = DEF_URI_PATH . "back/ville_modifier.php#mes-monuments";
    appendQueryString($deleteGoTo);
    header(sprintf("Location: %s", $deleteGoTo));
    exit;
}
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Supprimer un monument</title>
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
	background-image: url('../assets/img/fond_haut-conseil.jpg');
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
    <h1>Suppression du monument en cours...</h1>
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
