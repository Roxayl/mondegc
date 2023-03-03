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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_ListPays = 30;
$pageNum_ListPays = 0;
if (isset($_GET['pageNum_ListPays'])) {
  $pageNum_ListPays = $_GET['pageNum_ListPays'];
}
$startRow_ListPays = $pageNum_ListPays * $maxRows_ListPays;
$order_by = "ch_pay_mis_jour";
$tri = "DESC";
if (isset($_GET['order_by'])) {
  $order_by = $_GET['order_by'];
  $nom_colonne = $_GET['order_by'];
}
if (isset($_GET['tri'])) {
  $tri = $_GET['tri'];
}

$query_ListPays = "SELECT pays.ch_pay_id, pays.ch_pay_publication, pays.ch_pay_continent, pays.ch_pay_emplacement, pays.ch_pay_nom, pays.ch_pay_lien_imgdrapeau, pays.ch_pay_mis_jour FROM pays ORDER BY $order_by $tri";
$query_limit_ListPays = sprintf("%s LIMIT %d, %d", $query_ListPays, $startRow_ListPays, $maxRows_ListPays);
$ListPays = mysql_query($query_limit_ListPays, $maconnexion);
$row_ListPays = mysql_fetch_assoc($ListPays);

if (isset($_GET['totalRows_ListPays'])) {
  $totalRows_ListPays = $_GET['totalRows_ListPays'];
} else {
  $all_ListPays = mysql_query($query_ListPays);
  $totalRows_ListPays = mysql_num_rows($all_ListPays);
}
$totalPages_ListPays = ceil($totalRows_ListPays/$maxRows_ListPays)-1;

?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Liste des pays</title>
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
<!-- Navbar haut-conseil
    ================================================== -->
<div class="container corps-page">
<?php require(DEF_LEGACYROOTPATH . 'php/menu-haut-conseil.php'); ?>
  <!-- Page CONTENT
    ================================================== -->
  <div class="titre-bleu">
    <h1>Liste des pays</h1>
  </div>
  <section>
    <div id="categories">
      <table width="100%" class="table table-hover " cellspacing="1">
        <thead>
          <tr class="tablehead2">
            <th scope="col" id="<?php
			  if ( $nom_colonne != "ch_pay_publication" ) {echo 'tri_actuel';}?>"><a href="liste-pays.php?order_by=ch_pay_publication&tri=ASC"><i class="icon-globe"></i></a></th>
            <th scope="col" id="tri_actuel">Drapeau</th>
            <th scope="col" id="<?php 
			  if ( $nom_colonne != "ch_pay_nom" ) { echo 'tri_actuel';}?>"><a href="liste-pays.php?order_by=ch_pay_nom&tri=ASC">Pays</a></th>
            <th scope="col" id="<?php
			  if ( $nom_colonne != "ch_pay_emplacement" ) { echo 'tri_actuel'; }?>"><a href="liste-pays.php?order_by=ch_pay_emplacement&tri=ASC">Emplacement</a></th>
            <th scope="col" id="<?php 
			  if ( $nom_colonne != "ch_pay_continent" ) { echo 'tri_actuel'; }?>"><a href="liste-pays.php?order_by=ch_pay_continent&tri=ASC">Continent</a></th>
            <th scope="col" id="<?php
			  if ( $nom_colonne != "ch_use_login" ) { echo 'tri_actuel'; }?>"><a href="liste-pays.php?order_by=ch_use_login&tri=ASC">Dirigeant</a></th>
            <th scope="col" id="<?php 
			  if ( $nom_colonne != "ch_pay_mis_jour" ) { echo 'tri_actuel'; }?>"><a href="liste-pays.php?order_by=ch_pay_mis_jour&tri=DESC">Mise &agrave; jour</a></th>
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
              <td><img src="../assets/img/statutpays<?= e($row_ListPays['ch_pay_publication']) ?>.png" alt="Statut"></td>
              <td><img src="<?= e($row_ListPays['ch_pay_lien_imgdrapeau']) ?>" width="75px"></td>
              <td><?= e($row_ListPays['ch_pay_nom']) ?></td>
              <td>N°<?= e($row_ListPays['ch_pay_emplacement']) ?> <img class="pull-right" src="../Carto/Emplacements/emplacement<?= e($row_ListPays['ch_pay_emplacement']) ?>.jpg" width="50px"></td>
              <td><?= e($row_ListPays['ch_pay_continent']) ?></td>
              <td>
              <?php $thisPays = new \GenCity\Monde\Pays($row_ListPays['ch_pay_id']);
              $listeLeaders = $thisPays->getLeaders(); ?>
                <?php foreach($listeLeaders as $thisLeader): ?>
                <p><?= e($thisLeader['ch_use_login']) ?>
                    <small><?= \GenCity\Monde\Pays::getPermissionName($thisLeader['permissions']); ?></small></p>
                <?php endforeach; ?>
              </td>
              <td><?php echo date("d/m/Y � G:i:s", strtotime($row_ListPays['ch_pay_mis_jour'])); ?></td>
              <td>
                  <a href="page_pays_back.php?paysID=<?= e($row_ListPays['ch_pay_id']) ?>" class="btn" type="submit" title="modifier le pays"><i class="icon-pencil"></i></a></td>
              <?php if ($_SESSION['userObject']->minStatus('Administrateur'))
              {?>
              <td><form action="<?= DEF_URI_PATH ?>back/page_pays_confirmer_supprimer.php" method="post">
                  <input name="paysID" type="hidden" value="<?= e($row_ListPays['ch_pay_id']) ?>">
                  <button class="btn" type="submit" title="supprimer le pays"><i class="icon-trash"></i></button>
                </form></td>
              <?php }  ?>
            </tr>
            <?php } while ($row_ListPays = mysql_fetch_assoc($ListPays)); ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="9"><div class="btn-group pull-right">
                <?php for($i=0; $i<=$totalPages_ListPays; $i++) 
{
	$nbpage=$i+1;
     if($i==$pageNum_ListPays) //Si il s'agit de la page actuelle...
     {
         echo '<a class="btn disabled" href="#">'.$nbpage.'</a>'; 
     }	
     else //Sinon...
     {
          echo '<a class="btn" href="liste-pays.php?order_by='.$order_by.'&tri='.$tri.'&pageNum_ListPays='.$i.'">'.$nbpage.'</a>';
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
