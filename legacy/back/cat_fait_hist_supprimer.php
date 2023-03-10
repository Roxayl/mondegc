<?php

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=20))
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ' . legacyPage('connexion'));
exit();
	}

if ((isset($_POST['ch_fai_cat_ID'])) && ($_POST['ch_fai_cat_ID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM faithist_categories WHERE ch_fai_cat_ID=%s",
                       GetSQLValueString($_POST['ch_fai_cat_ID'], "int"));

  
  $Result1 = mysql_query($deleteSQL, $maconnexion);

$deleteSQL = sprintf("DELETE FROM dispatch_fait_his_cat WHERE ch_disp_fait_hist_cat_id=%s",
                       GetSQLValueString($_POST['ch_fai_cat_ID'], "int"));

  
  $Result2 = mysql_query($deleteSQL, $maconnexion);

  $deleteGoTo = DEF_URI_PATH . "back/institut_histoire.php";
  appendQueryString($deleteGoTo);
  header(sprintf("Location: %s", $deleteGoTo));
 exit;
}
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Supprimer un fait historique</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
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
	background-image: url('../assets/img/fond_haut-conseil.jpg');
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
<div id="introheader" class="jumbotron">
  <div class="container">
    <h2>Suppression de la cat&eacute;gorie en cours...</h2>
  </div>
</div>
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
