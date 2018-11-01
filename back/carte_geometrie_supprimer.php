<?php
session_start();

require_once('../Connections/maconnexion.php'); 
//deconnexion
include('../php/logout.php');

if ($_SESSION['statut'])
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
	}

if ((isset($_GET['ch_geo_id'])) && ($_GET['ch_geo_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM geometries WHERE ch_geo_id=%s",
                       GetSQLValueString($_GET['ch_geo_id'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
//recherche des mesures des zones de la carte pour calcul ressources
mysql_select_db($database_maconnexion, $maconnexion);
$query_geometries = sprintf("SELECT SUM(ch_geo_mesure) as mesure, ch_geo_type FROM geometries WHERE ch_geo_pay_id = %s AND ch_geo_type != 'maritime' AND ch_geo_type != 'region' GROUP BY ch_geo_type ORDER BY ch_geo_geometries", GetSQLValueString($_GET['ch_geo_pay_id'], "int"));
$geometries = mysql_query($query_geometries, $maconnexion) or die(mysql_error());
$row_geometries = mysql_fetch_assoc($geometries);

//Calcul total des ressources de la carte.
     do { 
		$surface = $row_geometries['mesure'];
		$typeZone = $row_geometries['ch_geo_type'];
		ressourcesGeometrie($surface, $typeZone, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population);
		$tot_budget = $tot_budget + $budget;
		$tot_industrie = $tot_industrie + $industrie;
		$tot_commerce = $tot_commerce + $commerce;
		$tot_agriculture = $tot_agriculture + $agriculture;
		$tot_tourisme = $tot_tourisme + $tourisme;
		$tot_recherche = $tot_recherche + $recherche;
		$tot_environnement = $tot_environnement + $environnement;
		$tot_education = $tot_education + $education;
		$tot_population = $tot_population + $population;
		 } while ($row_geometries = mysql_fetch_assoc($geometries));

//Enregistrement du total des ressources de la carte.
$updateSQL = sprintf("UPDATE pays SET ch_pay_budget_carte=%s, ch_pay_industrie_carte=%s, ch_pay_commerce_carte=%s, ch_pay_agriculture_carte=%s, ch_pay_tourisme_carte=%s, ch_pay_recherche_carte=%s, ch_pay_environnement_carte=%s, ch_pay_education_carte=%s, ch_pay_population_carte=%s WHERE ch_pay_id=%s",
                       GetSQLValueString($tot_budget, "int"),
					   GetSQLValueString($tot_industrie, "int"),
					   GetSQLValueString($tot_commerce, "int"),
					   GetSQLValueString($tot_agriculture, "int"),
                       GetSQLValueString($tot_tourisme, "int"),
                       GetSQLValueString($tot_recherche, "int"),
                       GetSQLValueString($tot_environnement, "int"),
                       GetSQLValueString($tot_education, "int"),
                       GetSQLValueString($tot_population, "int"),
					   GetSQLValueString($_GET['ch_geo_pay_id'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result2 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
  mysql_free_result($geometries);
  $deleteGoTo = "../Carte-modifier.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="iso-8859-1">
<title>Supprimer une g&eacute;ometrie</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
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
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>

<!-- Subhead
================================================== -->
<div id="introheader" class="jumbotron">
  <div class="container">
    <h2>Suppression d'une g&eacute;ometrie en cours...</h2>
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>
</body>
</html>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
 <script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
    </script>