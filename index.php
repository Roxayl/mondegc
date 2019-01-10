<?php 
header('Content-Type: text/html; charset=utf-8');
session_start();
require_once('Connections/maconnexion.php');
//Connexion et deconnexion
include('php/log.php');

//Initialisation des dates pour utilisation dans last_MAJ.php
$_SESSION['aujourdhui']=true;
$_SESSION['hier']=true;
$_SESSION['avanthier']=true;
$_SESSION['avantavanthier']=true;
$_SESSION['semaine']=true;
$_SESSION['deuxsemaine']=true;
$_SESSION['mois']=true;
$_SESSION['deuxmois']=true;
$_SESSION['troismois']=true;
$_SESSION['sixmois']=true;
$_SESSION['an']=true;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Le Monde GC</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
<!-- TemplateEndEditable -->
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
	background-image: url('http://generation-city.com/forum/new/img/cat2.jpg');
}
</style>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
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

</head>
<body>
<!-- Navbar
    ================================================== -->
<?php $accueil=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->

<div id="introheader" class="jumbotron">
  <div class="container">
    <div class="header">
      <h1>Bienvenue sur le site du Monde de Génération City</h1>
      <p><em>Le Monde GC rassemble une communaut&eacute; de joueurs du site G&eacute;n&eacute;ration City qui ont souhait&eacute; s'unir pour construire leur propre monde et d&eacute;velopper <a href="participer.php#faq">une nouvelle expérience de jeu</a>.</em></p>
    </div>
    <div class="Master-link">
      <p class="hidden-phone">Débutez l'exploration&nbsp;:</p>
      <a href="Page-carte.php" class="btn btn-primary btn-large">Carte</a> <a href="histoire.php" class="btn btn-primary btn-large">Histoire</a> <a href="patrimoine.php" class="btn btn-primary btn-large">Patrimoine</a> <a href="economie.php" class="btn btn-primary btn-large">Economie</a> <a href="politique.php" class="btn btn-primary btn-large">Politique</a> </div>
  </div>
</div>
<!-- Bandeau stat
================================================== -->
<?php include('php/bandeauStat.php'); ?>
<!-- Icon Start
================================================== -->
<div class="container corps-page">

  <!-- CATEGORIE Dernières actualites
================================================== -->
  <!-- LISTE Dernières actualites
================================================== -->

<div class="row-fluid">
    <div class="span8" id="postswrapper">

        <div id="actu" class="titre-vert no-bg anchor"> <img src="assets/img/IconesBDD/100/Membre1.png" alt="icone user">
          <h1>Derni&egrave;res actualit&eacute;s</h1>
        </div>
        <?php include('last_MAJ.php'); ?>
    </div>
    <div class="span4" style="background-color: #EDEDED;">

        <div id="actu" class="titre-bleu no-bg anchor"> <img src="assets/img/IconesBDD/Bleu/100/Communique_bleu.png" alt="icone user">
          <h1>Communiqués publiés</h1>
        </div>
        <?php
        $query_communiquesPays = "
SELECT communique_pays.ch_com_label AS type_notification, communique_pays.ch_com_ID AS id, communique_pays.ch_com_statut AS statut, communique_pays.ch_com_categorie AS sous_categorie, communique_pays.ch_com_element_id AS id_element, communique_pays.ch_com_user_id AS id_auteur, communique_pays.ch_com_date AS date, communique_pays.ch_com_titre AS titre, ch_use_lien_imgpersonnage AS photo_auteur, ch_use_nom_dirigeant AS nom_auteur, ch_use_paysID AS paysID_auteur, ch_use_prenom_dirigeant AS prenom_auteur, ch_use_titre_dirigeant AS titre_auteur, ch_pay_id AS id_institution, ch_pay_nom AS institution, ch_pay_lien_imgdrapeau AS img_institution, ch_pay_id AS pays_institution
FROM communiques communique_pays 
INNER JOIN users ON communique_pays.ch_com_user_id = ch_use_id 
INNER JOIN pays ON communique_pays.ch_com_element_id = ch_pay_id
WHERE communique_pays.ch_com_statut = 1 AND communique_pays.ch_com_categorie='pays' OR communique_pays.ch_com_categorie='com_pays' 
UNION 
SELECT communique_ville.ch_com_label AS type_notification, communique_ville.ch_com_ID AS id, communique_ville.ch_com_statut AS statut, communique_ville.ch_com_categorie AS sous_categorie, communique_ville.ch_com_element_id AS id_element, communique_ville.ch_com_user_id AS id_auteur, communique_ville.ch_com_date AS date, communique_ville.ch_com_titre AS titre, ch_use_lien_imgpersonnage AS photo_auteur, ch_use_nom_dirigeant AS nom_auteur, ch_use_paysID AS paysID_auteur, ch_use_prenom_dirigeant AS prenom_auteur, ch_use_titre_dirigeant AS titre_auteur, ch_vil_ID AS id_institution, ch_vil_nom AS institution, ch_vil_armoiries AS img_institution, ch_vil_paysID AS pays_institution
FROM communiques communique_ville 
INNER JOIN villes ON ch_com_element_id = ch_vil_ID 
INNER JOIN users ON communique_ville.ch_com_user_id = ch_use_id 
WHERE communique_ville.ch_com_statut = 1 AND communique_ville.ch_com_categorie ='ville' OR communique_ville.ch_com_categorie ='com_ville' 
UNION 
SELECT communique_institut.ch_com_label AS type_notification, communique_institut.ch_com_ID AS id, communique_institut.ch_com_statut AS statut, communique_institut.ch_com_categorie AS sous_categorie, communique_institut.ch_com_element_id AS id_element, communique_institut.ch_com_user_id AS id_auteur, communique_institut.ch_com_date AS date, communique_institut.ch_com_titre AS titre, ch_use_lien_imgpersonnage AS photo_auteur, ch_use_nom_dirigeant AS nom_auteur, ch_use_paysID AS paysID_auteur, ch_use_prenom_dirigeant AS prenom_auteur, ch_use_titre_dirigeant AS titre_auteur, ch_ins_ID AS id_institution, ch_ins_nom AS institution, ch_ins_logo AS img_institution, ch_ins_ID AS pays_institution
FROM communiques communique_institut 
INNER JOIN instituts ON ch_com_element_id = ch_ins_ID 
INNER JOIN users ON communique_institut.ch_com_user_id = ch_use_id 
WHERE communique_institut.ch_com_statut = 1 AND communique_institut.ch_com_categorie ='institut' 
UNION
SELECT communique_monument.ch_com_label AS type_notification, communique_monument.ch_com_ID AS id, communique_monument.ch_com_statut AS statut, communique_monument.ch_com_categorie AS sous_categorie, communique_monument.ch_com_element_id AS id_element, communique_monument.ch_com_user_id AS id_auteur, communique_monument.ch_com_date AS date, communique_monument.ch_com_titre AS titre, ch_use_lien_imgpersonnage AS photo_auteur, ch_use_nom_dirigeant AS nom_auteur, ch_use_paysID AS paysID_auteur, ch_use_prenom_dirigeant AS prenom_auteur, ch_use_titre_dirigeant AS titre_auteur, ch_pat_id AS id_institution, ch_pat_nom AS institution, ch_pat_lien_img1 AS img_institution, ch_pat_paysID AS pays_institution
FROM communiques communique_monument 
INNER JOIN patrimoine ON ch_com_element_id = ch_pat_id
INNER JOIN users ON communique_monument.ch_com_user_id = ch_use_id 
WHERE communique_monument.ch_com_statut = 1 AND communique_monument.ch_com_categorie ='com_monument'
UNION
SELECT communique_monument.ch_com_label AS type_notification, communique_monument.ch_com_ID AS id, communique_monument.ch_com_statut AS statut, communique_monument.ch_com_categorie AS sous_categorie, communique_monument.ch_com_element_id AS id_element, communique_monument.ch_com_user_id AS id_auteur, communique_monument.ch_com_date AS date, communique_monument.ch_com_titre AS titre, ch_use_lien_imgpersonnage AS photo_auteur, ch_use_nom_dirigeant AS nom_auteur, ch_use_paysID AS paysID_auteur, ch_use_prenom_dirigeant AS prenom_auteur, ch_use_titre_dirigeant AS titre_auteur, ch_his_id AS id_institution, ch_his_nom AS institution, ch_his_lien_img1 AS img_institution, ch_his_paysID AS pays_institution
FROM communiques communique_monument 
INNER JOIN histoire ON ch_com_element_id = ch_his_id
INNER JOIN users ON communique_monument.ch_com_user_id = ch_use_id 
WHERE communique_monument.ch_com_statut = 1 AND communique_monument.ch_com_categorie ='com_fait_his'
ORDER BY date DESC LIMIT 0, 15";
        $communiquesPays = mysql_query($query_communiquesPays, $maconnexion) or die(mysql_error());
        $row_communiquesPays = mysql_fetch_assoc($communiquesPays);
        ?>

        <div class="well">
        <ul class="liste-transparente">
        <?php do { ?>
            <li class="item">
            <div class="row-fluid" style="vertical-align: middle;">
                <div class="span2"  style="vertical-align: middle;">
                    <img style="width: 55px;" src="<?= $row_communiquesPays['img_institution'] ?>" />
                </div>
                <div class="span10"  style="vertical-align: middle;">
                    <?= $row_communiquesPays['institution'] ?><br>
                <small><?php echo date("d/m/Y", strtotime($row_communiquesPays['date'])); ?></small>
                </div>
            </div>
            <div class="row" style="padding-left: 15px;">
                <h4 style="text-align: left;"><a href="php/communique-modal.php?com_id=<?= $row_communiquesPays['id'] ?>" data-toggle="modal" data-target="#myModal"><?php echo htmlspecialchars($row_communiquesPays['titre']); ?></a></h4>
            </div>
            </li>
      <?php } while ($row_communiquesPays = mysql_fetch_assoc($communiquesPays)); ?>
        </ul>
        </div>

    </div>
</div>

<div class="modal container fade" id="myModal"></div>
<script>
    $("a[data-toggle=modal]").click(function (e) {
      lv_target = $(this).attr('data-target');
      lv_url = $(this).attr('href');
      $(lv_target).load(lv_url);
    });

    $('#closemodal').click(function() {
        $('#myModal').modal('hide');
    });
</script>

<!-- Footer
    ================================================== -->

<?php include('php/footer.php'); ?>
</body>
</html>