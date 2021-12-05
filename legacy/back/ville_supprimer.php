<?php

use App\Models\Infrastructure;
use App\Models\Ville;

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if(!isset($_SESSION['userObject'])) {
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

// suppression des villes

if(isset($_POST['ville_ID'])) {
    $colname_ville_ID = $_POST['ville_ID'];

    /** @var Ville $eloquentVille */
    $eloquentVille = Ville::findOrFail($colname_ville_ID);

    if(auth()->user()->cannot('delete', $eloquentVille)) {
        abort(403);
    }

    $query_monument = sprintf("SELECT ch_pat_id FROM patrimoine WHERE ch_pat_villeID = %s", GetSQLValueString($colname_ville_ID, "int"));
    $monument = mysql_query($query_monument, $maconnexion) or die(mysql_error());

    $eloquentVille->delete();

    $deleteSQL = sprintf("DELETE FROM villes WHERE ch_vil_ID=%s",
        GetSQLValueString($colname_ville_ID, "int"));
    $Result1 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());

    $deleteSQL2 = sprintf("DELETE FROM patrimoine WHERE ch_pat_villeID=%s",
        GetSQLValueString($colname_ville_ID, "int"));
    $Result2 = mysql_query($deleteSQL2, $maconnexion) or die(mysql_error());

    $deleteSQL3 = sprintf("DELETE FROM communiques WHERE ch_com_element_id=%s AND ch_com_categorie='ville'",
        GetSQLValueString($colname_ville_ID, "int"));
    $Result3 = mysql_query($deleteSQL3, $maconnexion) or die(mysql_error());

    $deleteSQL4 = sprintf("DELETE FROM infrastructures WHERE infrastructurable_id = %s AND infrastructurable_type = %s",
        GetSQLValueString($colname_ville_ID, "int"),
        GetSQLValueString(Infrastructure::getMorphFromUrlParameter('ville')));
    $Result4 = mysql_query($deleteSQL4, $maconnexion) or die(mysql_error());

     while($row_monument = mysql_fetch_assoc($monument)) {
        $deleteSQL5 = sprintf("DELETE FROM dispatch_mon_cat WHERE ch_disp_mon_id=%s",
            GetSQLValueString($row_monument['ch_pat_id'], "int"));
        $Result5 = mysql_query($deleteSQL5, $maconnexion) or die(mysql_error());
    }

    getErrorMessage('success', "La ville a été supprimée avec succès.");

    $deleteGoTo = DEF_URI_PATH . "back/page_pays_back.php";
    appendQueryString($deleteGoTo);
    header(sprintf("Location: %s", $deleteGoTo));
    exit;
}
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Supprimer une ville</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
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
    <h1>Suppression de la ville en cours...</h1>
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
