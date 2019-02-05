<?php

require_once('../Connections/maconnexion.php');
//deconnexion
include('../php/logout.php');

if ($_SESSION['statut'])
{
} else {
// Redirection vers Haut Conseil
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
}

// suppression des villes

if ((isset($_POST['ville_ID'])) && ($_POST['ville_ID'] != "")) {
$colname_ville_ID = $_POST['ville_ID'];
mysql_select_db($database_maconnexion, $maconnexion);
$query_monument = sprintf("SELECT ch_pat_id FROM patrimoine WHERE ch_pat_villeID = %s", GetSQLValueString($colname_ville_ID, "int"));
$monument = mysql_query($query_monument, $maconnexion) or die(mysql_error());
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);
	
  $deleteSQL = sprintf("DELETE FROM villes WHERE ch_vil_ID=%s",
                       GetSQLValueString($_POST['ville_ID'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
  
    $deleteSQL2 = sprintf("DELETE FROM patrimoine WHERE ch_pat_villeID=%s",
                       GetSQLValueString($_POST['ville_ID'], "int"));
					   
 mysql_select_db($database_maconnexion, $maconnexion);
  $Result2 = mysql_query($deleteSQL2, $maconnexion) or die(mysql_error());
  
  
  $deleteSQL3 = sprintf("DELETE FROM communiques WHERE ch_com_element_id=%s AND ch_com_categorie='ville'",
                       GetSQLValueString($_POST['ville_ID'], "int"));
					   
 mysql_select_db($database_maconnexion, $maconnexion);
  $Result3 = mysql_query($deleteSQL3, $maconnexion) or die(mysql_error());
  
   $deleteSQL4 = sprintf("DELETE FROM infrastructures WHERE ch_inf_villeid=%s",
                       GetSQLValueString($_POST['ville_ID'], "int"));
					   
 mysql_select_db($database_maconnexion, $maconnexion);
  $Result4 = mysql_query($deleteSQL4, $maconnexion) or die(mysql_error());


do { 
$colname_monumentID = $row_monument['ch_pat_id'];
 $deleteSQL5 = sprintf("DELETE FROM dispatch_mon_cat WHERE ch_disp_mon_id=%s",
                       GetSQLValueString($colname_monumentID, "int"));

 mysql_select_db($database_maconnexion, $maconnexion);
  $Result5 = mysql_query($deleteSQL5, $maconnexion) or die(mysql_error());

} while ($row_monument = mysql_fetch_assoc($monument));

  
  $deleteGoTo = "page_pays_back.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="iso-8859-1">
<title>Supprimer une ville</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/GenerationCity.css" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>
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