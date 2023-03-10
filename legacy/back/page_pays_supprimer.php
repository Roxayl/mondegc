<?php

use Roxayl\MondeGC\Models\Infrastructure;
use Roxayl\MondeGC\Models\Pays;

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if(!($_SESSION['statut']) or $_SESSION['statut'] < 30) {
    // Redirection vers page connexion
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

if(isset($_POST['Pays_ID'])) {

    $colname_Pays_ID = $_POST['Pays_ID'];

    /** @var Pays $eloquentPays */
    $eloquentPays = Pays::query()->findOrFail($colname_Pays_ID);

    $query_ville = sprintf("SELECT ch_vil_ID FROM villes WHERE ch_vil_paysID = %s", GetSQLValueString($colname_Pays_ID, "int"));
    $ville = mysql_query($query_ville, $maconnexion);

    $query_monument = sprintf("SELECT ch_pat_id FROM patrimoine WHERE ch_pat_paysID = %s", GetSQLValueString($colname_Pays_ID, "int"));
    $monument = mysql_query($query_monument, $maconnexion);

    $eloquentPays->delete();

    $deleteSQL1 = sprintf("DELETE FROM pays WHERE ch_pay_id=%s",
        GetSQLValueString($_POST['Pays_ID'], "int"));
    $Result1 = mysql_query($deleteSQL1, $maconnexion);

    $deleteSQL2 = sprintf("DELETE FROM villes WHERE ch_vil_paysID=%s",
        GetSQLValueString($_POST['Pays_ID'], "int"));
    $Result2 = mysql_query($deleteSQL2, $maconnexion);

    $deleteSQL3 = sprintf("DELETE FROM patrimoine WHERE ch_pat_paysID=%s",
        GetSQLValueString($_POST['Pays_ID'], "int"));
    $Result3 = mysql_query($deleteSQL3, $maconnexion);

    $deleteSQL4 = sprintf("DELETE FROM communiques WHERE ch_com_element_id=%s AND ch_com_categorie='pays'",
        GetSQLValueString($_POST['Pays_ID'], "int"));
    $Result4 = mysql_query($deleteSQL4, $maconnexion);

    $deleteSQL5 = sprintf("DELETE FROM histoire WHERE ch_his_paysID=%s",
        GetSQLValueString($_POST['Pays_ID'], "int"));
    $Result5 = mysql_query($deleteSQL5, $maconnexion);

    $deleteSQL9 = sprintf("DELETE FROM geometries WHERE ch_geo_pay_id=%s",
        GetSQLValueString($_POST['Pays_ID'], "int"));
    $Result9 = mysql_query($deleteSQL9, $maconnexion);

     while($row_ville = mysql_fetch_assoc($ville)) {
        $colname_villeID = $row_ville['ch_vil_ID'];

        $deleteSQL6 = sprintf("DELETE FROM communiques WHERE ch_com_element_id=%s AND ch_com_categorie='ville'",
            GetSQLValueString($colname_villeID, "int"));
        $Result6 = mysql_query($deleteSQL6, $maconnexion);

        $deleteSQL7 = sprintf("DELETE FROM infrastructures WHERE infrastructurable_id = %s AND infrastructurable_type = %s",
            GetSQLValueString($colname_villeID, "int"),
            GetSQLValueString(Infrastructure::getMorphFromUrlParameter('ville')));
        $Result7 = mysql_query($deleteSQL7, $maconnexion);
    }

    $deleteSQL7 = sprintf("DELETE FROM infrastructures WHERE infrastructurable_id = %s AND infrastructurable_type = %s",
        GetSQLValueString($colname_Pays_ID, "int"),
        GetSQLValueString(Infrastructure::getMorphFromUrlParameter('pays')));
    $Result7 = mysql_query($deleteSQL7, $maconnexion);

    $deleteSQL7 = sprintf("DELETE FROM organisation_members WHERE pays_id = %s",
        GetSQLValueString($colname_Pays_ID, "int"));
    $Result7 = mysql_query($deleteSQL7, $maconnexion);

    while($row_monument = mysql_fetch_assoc($monument)) {
        $deleteSQL8 = sprintf("DELETE FROM dispatch_mon_cat WHERE ch_disp_mon_id=%s",
            GetSQLValueString($row_monument['ch_pat_id'], "int"));
        $Result8 = mysql_query($deleteSQL8, $maconnexion);
    };

    getErrorMessage('success', "Le pays a été supprimé avec succès.");

    $deleteGoTo = DEF_URI_PATH . "back/liste-pays.php";
    appendQueryString($deleteGoTo);
    header(sprintf("Location: %s", $deleteGoTo));
    exit;
}
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
 background-position:center;
}
</style>

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!-- Navbar
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div id="introheader" class="jumbotron">
  <div class="container">
    <h2>Suppression du pays en cours...</h2>
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
