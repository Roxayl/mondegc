<?php


if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=20))
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ' . legacyPage('connexion'));
exit();
	}

$maxRows_listvilles = 30;
$pageNum_listvilles = 0;
if (isset($_GET['pageNum_listvilles'])) {
  $pageNum_listvilles = $_GET['pageNum_listvilles'];
}
$startRow_listvilles = $pageNum_listvilles * $maxRows_listvilles;
$order_by = "ch_vil_mis_jour";
$tri = "DESC";
if (isset($_GET['order_by'])) {
  $order_by = $_GET['order_by'];
  $nom_colonne = $_GET['order_by'];
}
if (isset($_GET['tri'])) {
  $tri = $_GET['tri'];
}

$query_listvilles = "SELECT villes.ch_vil_ID, villes.ch_vil_paysID, villes.ch_vil_mis_jour, villes.ch_vil_nom, villes.ch_vil_capitale, villes.ch_vil_population, pays.ch_pay_id, pays.ch_pay_nom, ch_use_login FROM villes INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id INNER JOIN users ON ch_vil_user = ch_use_id ORDER BY $order_by $tri";
$query_limit_listvilles = sprintf("%s LIMIT %d, %d", $query_listvilles, $startRow_listvilles, $maxRows_listvilles);
$listvilles = mysql_query($query_limit_listvilles, $maconnexion) or die(mysql_error());
$row_listvilles = mysql_fetch_assoc($listvilles);

if (isset($_GET['totalRows_listvilles'])) {
  $totalRows_listvilles = $_GET['totalRows_listvilles'];
} else {
  $all_listvilles = mysql_query($query_listvilles);
  $totalRows_listvilles = mysql_num_rows($all_listvilles);
}
$totalPages_listvilles = ceil($totalRows_listvilles/$maxRows_listvilles)-1;
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="iso-8859-1">
<title>Haut-Conseil - Liste des villes</title>
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
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/navbar.php'); ?>
<!-- Navbar haut-conseil
    ================================================== -->
<div class="container corps-page">
<?php include(DEF_ROOTPATH . 'php/menu-haut-conseil.php'); ?>
  <!-- Page CONTENT
    ================================================== -->
  <div class="titre-bleu">
    <h1>Liste des villes</h1>
  </div>
  <section>
    <div id="categories">
      <table width="100%" class="table table-hover " cellspacing="1">
        <thead>
          <tr class="tablehead2">
            <th scope="col" class="<?php
			  if ( $nom_colonne != "ch_vil_capitale" ) {echo 'tri_actuel';}?>"><a href="liste-villes.php?order_by=ch_vil_capitale&tri=ASC"><i class="icon-globe"></i></a></th>
            <th scope="col" class="<?php 
			  if ( $nom_colonne != "ch_pay_nom" ) { echo 'tri_actuel';}?>"><a href="liste-villes.php?order_by=ch_pay_nom&tri=ASC">Pays</a></th>
            <th scope="col" class="<?php 
			  if ( $nom_colonne != "ch_vil_nom" ) { echo 'tri_actuel'; }?>"><a href="liste-villes.php?order_by=ch_vil_nom&tri=ASC">Nom</a></th>
             <th scope="col" class="<?php 
			  if ( $nom_colonne != "ch_use_login" ) { echo 'tri_actuel'; }?>"><a href="liste-villes.php?order_by=ch_use_login&tri=ASC">Maire</a></th>  
            <th scope="col" class="<?php
			  if ( $nom_colonne != "ch_vil_population" ) { echo 'tri_actuel'; }?>"><a href="liste-villes.php?order_by=ch_vil_population&tri=DESC">Population</a></th>
            <th scope="col" class="<?php 
			  if ( $nom_colonne != "ch_vil_mis_jour" ) { echo 'tri_actuel'; }?>"><a href="liste-villes.php?order_by=ch_vil_mis_jour&tri=DESC">Mise &agrave; jour</a></th>
            <th scope="col" class="tri_actuel">&nbsp;</th>
            <?php if ($_SESSION['statut'] >= 30)
{?>
            <th scope="col" class="tri_actuel">&nbsp;</th>
            <?php }  ?>
          </tr>
        </thead>
        <tbody>
          <?php do { ?>
            <tr>
              <td><img src="../assets/img/statutvil_<?php echo $row_listvilles['ch_vil_capitale']; ?>.png" alt="Statut"></td>
              <td><?php echo $row_listvilles['ch_pay_nom']; ?></td>
              <td><?php echo $row_listvilles['ch_vil_nom']; ?></td>
              <td><?php echo $row_listvilles['ch_use_login']; ?></td>
              <td><?php echo $row_listvilles['ch_vil_population']; ?></td>
              <td><?php echo date("d/m/Y � G:i:s", strtotime($row_listvilles['ch_vil_mis_jour'])); ?></td>
              <td><form action="<?= DEF_URI_PATH ?>back/ville_modifier.php" method="GET">
                  <input name="ville-ID" type="hidden" value="<?php echo $row_listvilles['ch_vil_ID']; ?>">
                  <button class="btn" type="submit" title="modifier la ville"><i class="icon-pencil"></i></button>
                </form></td>
              <?php if ($_SESSION['statut'] >= 30)
{?>
              <td><form action="<?= DEF_URI_PATH ?>back/ville_confirmation_supprimer.php" method="post">
                  <input name="ville-ID" type="hidden" value="<?php echo $row_listvilles['ch_vil_ID']; ?>">
                  <button class="btn" type="submit" title="supprimer la ville"><i class="icon-trash"></i></button>
                </form></td>
              <?php }  ?>
            </tr>
            <?php } while ($row_listvilles = mysql_fetch_assoc($listvilles)); ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="9"><div class="btn-group pull-right">
                <?php for($i=0; $i<=$totalPages_listvilles; $i++) //On fait notre boucle
{
	$nbpage=$i+1;
     //On va faire notre condition
     if($i==$pageNum_listvilles) //Si il s'agit de la page actuelle...
     {
         echo '<a class="btn disabled" href="#">'.$nbpage.'</a>'; 
     }	
     else //Sinon...
     {
          echo '<a class="btn" href="liste-villes.php?order_by='.$order_by.'&tri='.$tri.'&pageNum_listvilles='.$i.'">'.$nbpage.'</a>';
     }
}
echo '</p>';  ?>
              </div></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </section>
</div>
<!-- Footer
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/footerback.php'); ?>
</body>
</html>
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
      $(function() { 
          $('[rel="clickover"]').clickover();})
    </script>