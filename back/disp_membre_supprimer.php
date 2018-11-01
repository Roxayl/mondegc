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

if ((isset($_POST['ch_disp_group_id'])) && ($_POST['ch_disp_group_id'] != "")) {
	$cat= $_POST['ch_disp_group_id'];
  }

if ((isset($_POST['ch_disp_MG_id'])) && ($_POST['ch_disp_MG_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM dispatch_mem_group WHERE ch_disp_MG_id=%s",
                       GetSQLValueString($_POST['ch_disp_MG_id'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
  $deleteGoTo = $_SESSION['last_work'];
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
    <meta charset="iso-8859-1">
    <title>Haut-Conseil - Retrait d'un membre</title>
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
    <h2>Retrait d'un membre en cours...</h2>
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