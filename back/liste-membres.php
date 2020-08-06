<?php

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

$order_by = "ch_use_last_log";
$tri = "DESC";
if (isset($_GET['order_by'])) {
  $order_by = $_GET['order_by'];
  $nom_colonne = $_GET['order_by'];
}
if (isset($_GET['tri'])) {
  $tri = $_GET['tri'];
}

$maxRows_listemembres = 30;
$pageNum_listemembres = 0;
if (isset($_GET['pageNum_listemembres'])) {
  $pageNum_listemembres = $_GET['pageNum_listemembres'];
}
$startRow_listemembres = $pageNum_listemembres * $maxRows_listemembres;


$query_listemembres = "SELECT ch_use_id, ch_use_date, ch_use_last_log, ch_use_login, ch_use_password, ch_use_mail, ch_use_paysID, ch_use_statut, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_biographie_dirigeant FROM users ORDER BY $order_by $tri";
$query_limit_listemembres = sprintf("%s LIMIT %d, %d", $query_listemembres, $startRow_listemembres, $maxRows_listemembres);
$listemembres = mysql_query($query_limit_listemembres, $maconnexion) or die(mysql_error());
$row_listemembres = mysql_fetch_assoc($listemembres);

if (isset($_GET['totalRows_listemembres'])) {
  $totalRows_listemembres = $_GET['totalRows_listemembres'];
} else {
  $all_listemembres = mysql_query($query_listemembres);
  $totalRows_listemembres = mysql_num_rows($all_listemembres);
}
$totalPages_listemembres = ceil($totalRows_listemembres/$maxRows_listemembres)-1;

?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Liste des membres</title>
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
    <h1>Liste des membres</h1>
  </div>
  <section>
    <div id="categories">
      <table width="100%" class="table table-hover " cellspacing="1">
        <thead>
          <tr class="tablehead2">
            <th scope="col" id="<?php
			  if ( $nom_colonne != "ch_use_statut" ) {echo 'tri_actuel';}?>"><a href="liste-membres.php?order_by=ch_use_statut&tri=ASC"><i class="icon-globe"></i></a></th>
            <th scope="col" id="tri_actuel">Photo</th>
            <th scope="col" id="<?php 
			  if ( $nom_colonne != "ch_use_login" ) { echo 'tri_actuel';}?>"><a href="liste-membres.php?order_by=ch_use_login&tri=ASC">Login</a></th>
            <th scope="col" id="<?php 
			  if ( $nom_colonne != "ch_use_last_log" ) { echo 'tri_actuel'; }?>"><a href="liste-membres.php?order_by=ch_use_last_log&tri=DESC">Derni&egrave;re connection</a></th>
            <th scope="col" id="tri_actuel">&nbsp;</th>
            <?php if ($_SESSION['statut'] >= 30)
{?>
            <th scope="col" id="tri_actuel">&nbsp;</th>
            <?php }  ?>
          </tr>
        </thead>
        <tbody>
          <?php do { ?>
            <tr>
              <td><?php if ($row_listemembres['ch_use_statut'] == 10) { ?>
                <i class="icon-user"></i>
                <?php ;
			   } elseif ($row_listemembres['ch_use_statut']  == 20) { ?>
                <i class="icon-eye-open"></i>
                <?php ; 
			   } elseif ($row_listemembres['ch_use_statut']  == 30) { ?>
                <i class="icon-wrench"></i>
                <?php ; 
			   } else { ?>
                <?php ; } ?></td>
              <td><img src="<?php echo $row_listemembres['ch_use_lien_imgpersonnage']; ?>" alt="Personnage" width="80px"></td>
              <td><?php echo $row_listemembres['ch_use_login']; ?></td>
              <td><?php echo date("d/m/Y à G:i:s", strtotime($row_listemembres['ch_use_last_log'])); ?></td>
              <td><form action="<?= DEF_URI_PATH ?>back/membre-modifier_back.php" method="post">
                  <input name="userID" type="hidden" value="<?php echo $row_listemembres['ch_use_id']; ?>">
                  <button class="btn" type="submit" title="modifier le profil"><i class="icon-pencil"></i></button>
                </form></td>
              <?php if ($_SESSION['statut'] >= 30)
{?>
              <td><form action="<?= DEF_URI_PATH ?>back/membre_confirmation_supprimer.php" method="post">
                  <input name="ch_use_id" type="hidden" value="<?php echo $row_listemembres['ch_use_id']; ?>">
                  <button class="btn" type="submit" title="supprimer le profil"><i class="icon-trash"></i></button>
                </form></td>
              <?php }  ?>
            </tr>
            <?php } while ($row_listemembres = mysql_fetch_assoc($listemembres)); ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="8"><div class="btn-group pull-right">
                <?php for($i=0; $i<=$totalPages_listemembres; $i++) //On fait notre boucle
{
	$nbpage=$i+1;
     //On va faire notre condition
     if($i==$pageNum_listemembres) //Si il s'agit de la page actuelle...
     {
         echo '<a class="btn disabled" href="#">'.$nbpage.'</a>'; 
     }	
     else //Sinon...
     {
          echo '<a class="btn" href="liste-membres.php?order_by='.$order_by.'&tri='.$tri.'&pageNum_listemembres='.$i.'">'.$nbpage.'</a>';
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
<?php
mysql_free_result($listemembres);
?>
