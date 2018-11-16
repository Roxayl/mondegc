<?php
session_start();
require_once('Connections/maconnexion.php');

//Connexion et deconnexion
include('php/log.php');

// *** Requête monument.
$colname_monument = "-1";
if (isset($_GET['ch_pat_id'])) {
  $colname_monument = $_GET['ch_pat_id'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_monument = sprintf("SELECT ch_pat_id, ch_pat_label, ch_pat_statut, ch_pat_paysID, ch_pat_villeID, ch_pat_date, ch_pat_mis_jour, ch_pat_nb_update, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_nom, ch_pat_lien_img1, ch_pat_lien_img2, ch_pat_lien_img3, ch_pat_lien_img4, ch_pat_lien_img5, ch_pat_legende_img1, ch_pat_legende_img2, ch_pat_legende_img3, ch_pat_legende_img4, ch_pat_legende_img5, ch_pat_description, ch_pay_nom, ch_vil_nom, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_ID = ch_disp_mon_id) AS listcat FROM patrimoine INNER JOIN pays ON ch_pat_paysID = ch_pay_id INNER JOIN villes ON ch_pat_villeID = ch_vil_ID WHERE ch_pat_id = %s", GetSQLValueString($colname_monument, "int"));
$monument = mysql_query($query_monument, $maconnexion) or die(mysql_error());
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);

// *** Ressources patrimoine
$query_monument_ressources = sprintf("SELECT SUM(ch_mon_cat_budget) AS budget,SUM(ch_mon_cat_industrie) AS industrie, SUM(ch_mon_cat_commerce) AS commerce, SUM(ch_mon_cat_agriculture) AS agriculture, SUM(ch_mon_cat_tourisme) AS tourisme, SUM(ch_mon_cat_recherche) AS recherche, SUM(ch_mon_cat_environnement) AS environnement, SUM(ch_mon_cat_education) AS education FROM monument_categories
  INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
  INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_id = %s", GetSQLValueString($colname_monument, "int"));
$monument_ressources = mysql_query($query_monument_ressources, $maconnexion) or die(mysql_error());
$row_monument_ressources = mysql_fetch_assoc($monument_ressources);

// Connection infos dirigeant pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_users = sprintf("SELECT ch_use_id, ch_use_login FROM users WHERE ch_use_paysID = %s", GetSQLValueString($row_monument['ch_pat_paysID'], "int"));
$users = mysql_query($query_users, $maconnexion) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

// *** Requête pour infos sur les categories.
$listcategories = ($row_monument['listcat']);
			if ($row_monument['listcat']) {
          
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) AND ch_mon_cat_statut =1";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion) or die(mysql_error());
$row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
}
$_SESSION['last_work'] = 'page-monument.php?ch_pat_id='.$row_monument['ch_pat_id'];
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="iso-8859-1">
<title>Monde GC- Monument</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
<link rel="shortcut icon" href="assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('<?php echo $background_jumbotron ?>');
}
</style>
<!-- Le javascript
    ================================================== -->
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
<script src="assets/js/application.js"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
</script>
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
<!-- EDITEUR -->
<script type="text/javascript" src="assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140">
<!-- Navbar
    ================================================== -->
<?php $pays=true; include('php/navbar.php'); ?>
<header id="info-ville" class="jumbotron subhead anchor"> 
  <!-- Titre  et Carousel
    ================================================== -->
  <div class="container container-carousel">
    <?php if ($row_monument['ch_pat_lien_img1'] OR $row_monument['ch_pat_lien_img2'] OR $row_monument['ch_pat_lien_img3'] OR $row_monument['ch_pat_lien_img4'] OR $row_monument['ch_pat_lien_img5']) { ?>
    <h1 class="titre-caroussel"><?php echo $row_monument['ch_pat_nom']; ?></h1>
    <section id="myCarousel" class="carousel slide">
      <div class="carousel-inner">
        <?php if ($row_monument['ch_pat_lien_img1']) { ?>
        <div class="item active" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img1']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_monument['ch_pat_legende_img1']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img2']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img2']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_monument['ch_pat_legende_img2']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img3']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img3']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_monument['ch_pat_legende_img3']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img4']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img4']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_monument['ch_pat_legende_img4']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img5']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img5']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_monument['ch_pat_legende_img5']; ?></p>
          </div>
        </div>
        <?php } ?>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a> <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a> </section>
    <!-- Titre si pas de carrousel
    ================================================== -->
    <?php } else { ?>
    <h1><?php echo $row_monument['ch_pat_nom']; ?></h1>
    <?php } ?>
  </div>
</header>

<!-- Page CONTENT
    ================================================== -->
<div class="container corps-page"> 
  <!-- Moderation
     ================================================== -->
  <?php if (($_SESSION['statut'] >= 20) OR ($row_users['ch_use_id'] == $_SESSION['user_ID'])) { ?>
  <form class="pull-right" action="back/monument_confirmation_supprimer.php" method="post">
    <input name="monument_ID" type="hidden" value="<?php echo $row_monument['ch_pat_id']; ?>">
    <button class="btn btn-danger" type="submit" title="supprimer ce monument"><i class="icon-trash icon-white"></i></button>
  </form>
  <form class="pull-right" action="back/monument_modifier.php" method="post">
    <input name="monument_ID" type="hidden" value="<?php echo $row_monument['ch_pat_id']; ?>">
    <button class="btn btn-danger" type="submit" title="modifier ce monument"><i class="icon-pencil icon-white"></i></button>
  </form>
  <?php } ?>
  <?php if ($row_users['ch_use_id'] == $_SESSION['user_ID']) { ?>
  <a class="btn btn-primary pull-right" href="php/partage-monument.php?ch_pat_id=<?php echo $row_monument['ch_pat_id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i>Forum</a>
  <?php } ?>
  
  <div class="clearfix"></div>
  <div class="modal container fade" id="Modal-Monument"></div>
  <div class="titre-vert"> <img src="assets/img/IconesBDD/100/monument1.png" alt="monument">
    <h1><?php echo $row_monument['ch_pat_nom']; ?></h1>
  </div>
  <div class="well">
    <div class="row-fluid">
      <div class="span8">
        <p><strong>Pays&nbsp;:</strong> <a class="" href="page-pays.php?ch_pay_id=<?php echo $row_monument['ch_pat_paysID']; ?>"><?php echo $row_monument['ch_pay_nom']; ?></a></p>
        <p><strong>Ville&nbsp;:</strong> <a class="" href="page-ville.php?ch_pay_id=<?php echo $row_monument['ch_pat_paysID']; ?>&ch_ville_id=<?php echo $row_monument['ch_pat_villeID']; ?>"><?php echo $row_monument['ch_vil_nom']; ?></a></p>
        <p><?php echo $row_monument['ch_pat_description']; ?></p>
        <!-- Liste des categories di monument -->
        <p><strong>Cat&eacute;gories&nbsp;:</strong></p>
        <?php if ($row_monument['listcat']) { ?>
        <ul class="listes">
          <?php do { ?>
            <li class="row-fluid">
              <div class="span1 icone-categorie"><img src="<?php echo $row_liste_mon_cat3['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>" style="background-color:<?php echo $row_liste_mon_cat3['ch_mon_cat_couleur']; ?>;"></div>
              <div class="span7">
                <p><strong><a href="patrimoine.php?mon_catID=<?php echo $row_liste_mon_cat3['ch_mon_cat_ID']; ?>#monument"><?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?></a></strong></p>
              </div>
            </li>
            <?php } while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)); ?>
        </ul>
        <?php mysql_free_result($liste_mon_cat3); ?>
      <?php } else { ?>
      <p>Ce monument ne fait partie d'aucune cat&eacute;gorie.</p>
      <?php }?>

        <p><strong>Influence sur l'économie :</strong></p>
          <?php renderResources($row_monument_ressources); ?>
          <div class="clearfix"></div>
      </div>
    <div class="span4">
      <iframe width="100%" height="300px" frameborder="0" scrolling="no" src="Iframeposition.php?x=<?php echo $row_monument['ch_pat_coord_X']; ?>&y=<?php echo $row_monument['ch_pat_coord_Y']; ?>" name="iframe"></iframe>
    </div>
  </div>
</div>
<!-- Commentaire
        ================================================== -->
<section>
  <div id="commentaires" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Membre1.png" alt="visites">
    <h1>Visites</h1>
  </div>
  <?php 
	  $ch_com_categorie = "com_monument";
	  $ch_com_element_id = $colname_monument;
	  include('php/commentaire.php'); ?>
</section>
<!-- END CONTENT
    ================================================== -->
</div>

<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>
<script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script>
<?php
mysql_free_result($monument);

mysql_free_result($users);

?>
