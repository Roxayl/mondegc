<?php

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if(!isset($_SESSION['userObject'])) {
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

// suppression du fait hist

// obtenir infos sur le pays
$getHistory = mysql_query(sprintf('SELECT ch_his_paysID FROM histoire WHERE ch_his_id = %s',
    GetSQLValueString($_POST['ch_his_id'])));
$getHistory = mysql_fetch_assoc($getHistory);
$thisPays = new \GenCity\Monde\Pays($getHistory['ch_his_paysID']);

if ((isset($_POST['ch_his_id'])) && ($_POST['ch_his_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM histoire WHERE ch_his_id=%s",
                       GetSQLValueString($_POST['ch_his_id'], "int"));
  
  $Result1 = mysql_query($deleteSQL, $maconnexion);


$deleteSQL2 = sprintf("DELETE FROM dispatch_fait_his_cat WHERE ch_disp_fait_hist_id=%s",
                       GetSQLValueString($_POST['monument_ID'], "int"));

  $Result2 = mysql_query($deleteSQL, $maconnexion);
  
  getErrorMessage('success', "Ce fait historique a été supprimé avec succès.");

  $deleteGoTo = DEF_URI_PATH . "back/page_pays_back.php?paysID=" . (int)$thisPays->get('ch_pay_id') . "#faits-historiques";
  appendQueryString($deleteGoTo);
  header(sprintf("Location: %s", $deleteGoTo));
 exit;
}
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Supprimer un fait historique</title>
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
    <h1>Suppression du fait historique en cours...</h1>
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
