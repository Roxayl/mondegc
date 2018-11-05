<?php
session_start();
require_once('../Connections/maconnexion.php');
//deconnexion
include('../php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=30))
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
	}

if ((isset($_POST['Pays_ID'])) && ($_POST['Pays_ID'] != "")) {
	
$colname_Pays_ID = $_POST['Pays_ID'];
mysql_select_db($database_maconnexion, $maconnexion);
$query_ville = sprintf("SELECT ch_vil_ID FROM villes WHERE ch_vil_paysID = %s", GetSQLValueString($colname_Pays_ID, "int"));
$ville = mysql_query($query_ville, $maconnexion) or die(mysql_error());
$row_ville = mysql_fetch_assoc($ville);
$totalRows_ville = mysql_num_rows($ville);

mysql_select_db($database_maconnexion, $maconnexion);
$query_monument = sprintf("SELECT ch_pat_id FROM patrimoine WHERE ch_pat_paysID = %s", GetSQLValueString($colname_Pays_ID, "int"));
$monument = mysql_query($query_monument, $maconnexion) or die(mysql_error());
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);

	
  $deleteSQL = sprintf("DELETE FROM pays WHERE ch_pay_id=%s",
                       GetSQLValueString($_POST['Pays_ID'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
  
  $deleteSQL2 = sprintf("DELETE FROM villes WHERE ch_vil_paysID=%s",
                       GetSQLValueString($_POST['Pays_ID'], "int"));
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result2 = mysql_query($deleteSQL2, $maconnexion) or die(mysql_error());


 $deleteSQL3 = sprintf("DELETE FROM patrimoine WHERE ch_pat_paysID=%s",
                       GetSQLValueString($_POST['Pays_ID'], "int"));
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result3 = mysql_query($deleteSQL3, $maconnexion) or die(mysql_error());


$deleteSQL4 = sprintf("DELETE FROM communiques WHERE ch_com_element_id=%s AND ch_com_categorie='pays'",
                       GetSQLValueString($_POST['Pays_ID'], "int"));
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result4 = mysql_query($deleteSQL4, $maconnexion) or die(mysql_error());


$deleteSQL5 = sprintf("DELETE FROM histoire WHERE ch_his_paysID=%s",
                       GetSQLValueString($_POST['Pays_ID'], "int"));
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result5 = mysql_query($deleteSQL5, $maconnexion) or die(mysql_error());
  
$deleteSQL9 = sprintf("DELETE FROM geometries WHERE ch_geo_pay_id=%s",
                       GetSQLValueString($_POST['Pays_ID'], "int"));
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result9 = mysql_query($deleteSQL9, $maconnexion) or die(mysql_error());

 do { 
$colname_villeID = $row_ville['ch_vil_ID'];
  $deleteSQL6 = sprintf("DELETE FROM communiques WHERE ch_com_element_id=%s AND ch_com_categorie='ville'",
                       GetSQLValueString($colname_villeID, "int"));
					   
 mysql_select_db($database_maconnexion, $maconnexion);
  $Result6 = mysql_query($deleteSQL6, $maconnexion) or die(mysql_error());
  
   $deleteSQL7 = sprintf("DELETE FROM infrastructures WHERE ch_inf_villeid=%s",
                       GetSQLValueString($colname_villeID, "int"));
					   
 mysql_select_db($database_maconnexion, $maconnexion);
  $Result7 = mysql_query($deleteSQL7, $maconnexion) or die(mysql_error());
  
} while ($row_ville = mysql_fetch_assoc($ville));

do { 
$colname_monumentID = $row_monument['ch_pat_id'];
 $deleteSQL8 = sprintf("DELETE FROM dispatch_mon_cat WHERE ch_disp_mon_id=%s",
                       GetSQLValueString($colname_monumentID, "int"));

 mysql_select_db($database_maconnexion, $maconnexion);
  $Result8 = mysql_query($deleteSQL8, $maconnexion) or die(mysql_error());

} while ($row_monument = mysql_fetch_assoc($monument));

  $deleteGoTo = "liste-pays.php";
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
<title>Haut-Conseil - Supprimer un pays</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
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
 background-position:center;
}
</style>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>
<!-- Subhead
================================================== -->
<div id="introheader" class="jumbotron">
  <div class="container">
    <h2>Suppression du pays en cours...</h2>
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