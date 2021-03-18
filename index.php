<?php

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
<meta name="description" content="Le Monde GC rassemble une communauté de joueurs du site Génération City qui ont souhaité s'unir pour construire leur propre monde et développer une nouvelle expérience de jeu.">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css">
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
	background-image: url('assets/img/2019/GC2020_banniere.png');
    background-position: 0 -240px;
    background-attachment: fixed;
}
</style>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
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
      <h1>Bienvenue dans le Monde de Génération City</h1>
        <h2>C'est votre Monde.</h2>
        <p><em>Le Monde GC rassemble une communaut&eacute; de joueurs de <a href="https://www.forum-gc.com/">G&eacute;n&eacute;ration City</a> qui ont souhait&eacute; s'unir pour construire leur propre monde et d&eacute;velopper <a href="participer.php#faq">une nouvelle expérience de jeu</a>.</em></p>
    </div>
    <div class="Master-link">
      <p class="hidden-phone">Débutez l'exploration&nbsp;:</p>
      <a href="<?= DEF_URI_PATH ?>map" class="btn btn-primary btn-theme-geographie btn-large">Carte</a>
      <a href="histoire.php" class="btn btn-primary btn-theme-histoire btn-large">Histoire</a>
      <a href="patrimoine.php" class="btn btn-primary btn-theme-patrimoine btn-large">Culture</a>
      <a href="economie.php" class="btn btn-primary btn-theme-economie btn-large">Économie</a>
      <a href="politique.php" class="btn btn-primary btn-theme-politique btn-large">Politique</a>
    </div>
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
    <div class="span8" id="postswrapper" style="padding-bottom: 5px;">

        <?php renderElement('errormsgs'); ?>

        <div id="actu" class="titre-vert no-bg anchor">
          <h1 style="font-size: 26px; padding-left: 16px;">Derni&egrave;res actualit&eacute;s</h1>
        </div>
        <?php include('last_MAJ.php'); ?>
    </div>
    <div class="span4" style="background-color: #EDEDED;">

        <div class="well"></div>

        <div id="actu" class="titre-bleu no-bg anchor">
          <h1 style="font-size: 26px; padding-left: 16px;">Communiqués publiés</h1>
        </div>
        <?php
        $query_communiquesPays = "
SELECT communique_pays.ch_com_label AS type_notification, communique_pays.ch_com_ID AS id, communique_pays.ch_com_statut AS statut, communique_pays.ch_com_categorie AS sous_categorie, communique_pays.ch_com_element_id AS id_element, communique_pays.ch_com_user_id AS id_auteur, communique_pays.ch_com_date AS date, communique_pays.ch_com_titre AS titre, ch_pay_id AS id_institution, ch_pay_nom AS institution, ch_pay_lien_imgdrapeau AS img_institution, ch_pay_id AS pays_institution, CONCAT('page-pays.php?ch_pay_id=', ch_pay_id) AS elem_url
FROM communiques communique_pays 
INNER JOIN pays ON communique_pays.ch_com_element_id = ch_pay_id
WHERE communique_pays.ch_com_statut = 1 AND communique_pays.ch_com_categorie='pays'
UNION 
SELECT communique_organisation.ch_com_label AS type_notification, communique_organisation.ch_com_ID AS id, communique_organisation.ch_com_statut AS statut, communique_organisation.ch_com_categorie AS sous_categorie, communique_organisation.ch_com_element_id AS id_element, communique_organisation.ch_com_user_id AS id_auteur, communique_organisation.ch_com_date AS date, communique_organisation.ch_com_titre AS titre, organisation.id AS id_institution, organisation.name AS institution, organisation.flag AS img_institution, organisation.id AS pays_institution, CONCAT('organisation/', organisation.id) AS elem_url
FROM communiques communique_organisation 
INNER JOIN organisation ON ch_com_element_id = organisation.id 
WHERE communique_organisation.ch_com_statut = 1 AND communique_organisation.ch_com_categorie ='organisation'
UNION 
SELECT communique_ville.ch_com_label AS type_notification, communique_ville.ch_com_ID AS id, communique_ville.ch_com_statut AS statut, communique_ville.ch_com_categorie AS sous_categorie, communique_ville.ch_com_element_id AS id_element, communique_ville.ch_com_user_id AS id_auteur, communique_ville.ch_com_date AS date, communique_ville.ch_com_titre AS titre, ch_vil_ID AS id_institution, ch_vil_nom AS institution, ch_vil_armoiries AS img_institution, ch_vil_paysID AS pays_institution, CONCAT('page-ville.php?ch_ville_id=', ch_vil_ID) AS elem_url
FROM communiques communique_ville 
INNER JOIN villes ON ch_com_element_id = ch_vil_ID 
WHERE communique_ville.ch_com_statut = 1 AND communique_ville.ch_com_categorie ='ville'
UNION 
SELECT communique_institut.ch_com_label AS type_notification, communique_institut.ch_com_ID AS id, communique_institut.ch_com_statut AS statut, communique_institut.ch_com_categorie AS sous_categorie, communique_institut.ch_com_element_id AS id_element, communique_institut.ch_com_user_id AS id_auteur, communique_institut.ch_com_date AS date, communique_institut.ch_com_titre AS titre, ch_ins_ID AS id_institution, ch_ins_nom AS institution, ch_ins_logo AS img_institution, ch_ins_ID AS pays_institution, NULL AS elem_url
FROM communiques communique_institut 
INNER JOIN instituts ON ch_com_element_id = ch_ins_ID 
WHERE communique_institut.ch_com_statut = 1 AND communique_institut.ch_com_categorie ='institut' 
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
                    <img style="width: 55px;" src="<?= e($row_communiquesPays['img_institution']) ?>" />
                </div>
                <div class="span10"  style="vertical-align: middle;">
                    <?php if(!is_null($row_communiquesPays['elem_url'])): ?>
                        <a href="<?= __s($row_communiquesPays['elem_url']) ?>">
                            <?= __s($row_communiquesPays['institution']) ?></a>
                    <?php else: ?>
                        <?= __s($row_communiquesPays['institution']) ?>
                    <?php endif; ?>
                <br>
                <small style="margin: 0; padding: 0;"><?php echo date("d/m/Y", strtotime($row_communiquesPays['date'])); ?></small>
                </div>
            </div>
            <div class="row" style="padding-left: 15px;">
                <h4 style="text-align: left;"><a href="php/communique-modal.php?com_id=<?= e($row_communiquesPays['id']) ?>" data-toggle="modal" data-target="#myModal"><?php echo htmlspecialchars($row_communiquesPays['titre']); ?></a></h4>
            </div>
            </li>
      <?php } while ($row_communiquesPays = mysql_fetch_assoc($communiquesPays)); ?>
        </ul>
        </div>

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