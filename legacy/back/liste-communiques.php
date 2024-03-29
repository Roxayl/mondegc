<?php
        
//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if (!($_SESSION['statut'] and ($_SESSION['statut'] >= 20))) {
    // Redirection vers page connexion
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

$maxRows_listcommuniques = 30;
$pageNum_listcommuniques = 0;
if (isset($_GET['pageNum_listcommuniques'])) {
  $pageNum_listcommuniques = (int) $_GET['pageNum_listcommuniques'];
}
$startRow_listcommuniques = $pageNum_listcommuniques * $maxRows_listcommuniques;
$order_by = "ch_com_date_mis_jour";
$tri = "DESC";
if (isset($_GET['order_by'])) {
  $order_by = escape_sql($_GET['order_by'], 'order_by_columns', [
      'ch_com_titre', 'ch_com_statut', 'ch_com_categorie', 'ch_com_date_mis_jour', 'ch_use_login',
  ]);
  $nom_colonne = $order_by;
}
if (isset($_GET['tri'])) {
  $tri = escape_sql($_GET['tri'], 'order_by_pos');
}

$query_listcommuniques = "SELECT ch_com_ID, ch_com_statut, ch_com_categorie,ch_com_element_id, ch_com_user_id, ch_com_date, ch_com_date_mis_jour, ch_com_titre, ch_use_login, ch_use_lien_imgpersonnage FROM communiques INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_categorie = 'pays' OR ch_com_categorie = 'ville' OR ch_com_categorie = 'institut' ORDER BY $order_by $tri";
$query_limit_listcommuniques = sprintf("%s LIMIT %d, %d", $query_listcommuniques, escape_sql($startRow_listcommuniques, 'int'), escape_sql($maxRows_listcommuniques, 'int'));
$listcommuniques = mysql_query($query_limit_listcommuniques, $maconnexion);
$row_listcommuniques = mysql_fetch_assoc($listcommuniques);

if (isset($_GET['totalRows_listcommuniques'])) {
  $totalRows_listcommuniques = $_GET['totalRows_listcommuniques'];
} else {
  $all_listcommuniques = mysql_query($query_listcommuniques);
  $totalRows_listcommuniques = mysql_num_rows($all_listcommuniques);
}
$totalPages_listcommuniques = ceil($totalRows_listcommuniques/$maxRows_listcommuniques)-1;
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Liste des communiqu&eacute;s</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
    <h1>Liste des communiqu&eacute;s</h1>
  </div>
  <section>
    <div id="categories">
      <table width="100%" class="table table-hover " cellspacing="1">
        <thead>
          <tr class="tablehead2">
            <th scope="col" id="<?php
			  if ( $nom_colonne != "ch_com_statut" ) {echo 'tri_actuel';}?>"><a href="liste-communiques.php?order_by=ch_com_statut&tri=ASC"><i class="icon-globe"></i></a></th>
            <th scope="col" id="<?php 
			  if ( $nom_colonne != "ch_com_titre" ) { echo 'tri_actuel';}?>"><a href="liste-communiques.php?order_by=ch_com_titre&tri=ASC">Titre</a></th>
            <th scope="col" id="<?php 
			  if ( $nom_colonne != "ch_com_categorie" ) { echo 'tri_actuel'; }?>"><a href="liste-communiques.php?order_by=ch_com_categorie&tri=ASC">Categorie</a></th>
            <th scope="col" id="<?php
			  if ( $nom_colonne != "ch_use_login" ) { echo 'tri_actuel'; }?>"><a href="liste-communiques.php?order_by=ch_use_login&tri=ASC">Membre</a></th>
            <th scope="col" id="<?php 
			  if ( $nom_colonne != "ch_com_date_mis_jour" ) { echo 'tri_actuel'; }?>"><a href="liste-communiques.php?order_by=ch_com_date_mis_jour&tri=DESC">Mise &agrave; jour</a></th>
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
              <td><img src="../assets/img/statutpays<?= e($row_listcommuniques['ch_com_statut']) ?>.png" alt="Statut"></td>
              <td><?= e($row_listcommuniques['ch_com_titre']) ?></td>
              <td><?php if ($row_listcommuniques['ch_com_categorie'] == 'pays') {?>
                <img src="../assets/img/IconesBDD/50/Pays1.png" alt="pays" title="pays">
                <?php }  ?>
                <?php if ($row_listcommuniques['ch_com_categorie'] == 'ville') {?>
                <img src="../assets/img/IconesBDD/50/Ville1.png" alt="ville" title="ville">
                <?php }  ?>
                <?php if ($row_listcommuniques['ch_com_categorie'] == 'institut') {?>
                <img src="../assets/img/IconesBDD/50/ocgc.png" alt="institut" title="institut">
                <?php }  ?>
                </td>
              <td><img src="<?= e($row_listcommuniques['ch_use_lien_imgpersonnage']) ?>" width="50px">
                <p><?= e($row_listcommuniques['ch_use_login']) ?></p></td>
              <td><?php echo date("d/m/Y � G:i:s", strtotime($row_listcommuniques['ch_com_date_mis_jour'])); ?></td>
              <td><form action="<?= DEF_URI_PATH ?>back/communique_modifier.php" method="post">
                  <input name="com_id" type="hidden" value="<?= e($row_listcommuniques['ch_com_ID']) ?>">
                  <button class="btn" type="submit" title="modifier le communiqu&eacute;"><i class="icon-pencil"></i></button>
                </form></td>
              <?php if ($_SESSION['statut'] >= 30)
{?>
              <td><form action="<?= DEF_URI_PATH ?>back/communique_confirmation_supprimer.php" method="post">
                  <input name="communique-ID" type="hidden" value="<?= e($row_listcommuniques['ch_com_ID']) ?>">
                  <button class="btn" type="submit" title="supprimer le communiqu&eacute;"><i class="icon-trash"></i></button>
                </form></td>
              <?php }  ?>
            </tr>
            <?php } while ($row_listcommuniques = mysql_fetch_assoc($listcommuniques)); ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="8"><div class="btn-group pull-right">
                <?php for($i=0; $i<=$totalPages_listcommuniques; $i++) //On fait notre boucle
{
	$nbpage=$i+1;
     //On va faire notre condition
     if($i==$pageNum_listcommuniques) //Si il s'agit de la page actuelle...
     {
         echo '<a class="btn disabled" href="#">'.$nbpage.'</a>'; 
     }	
     else //Sinon...
     {
          echo '<a class="btn" href="liste-communiques.php?order_by='.$order_by.'&tri='.$tri.'&pageNum_listcommuniques='.$i.'">'.$nbpage.'</a>';
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
