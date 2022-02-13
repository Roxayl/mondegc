<?php
        
//Connexion et deconnexion
include('php/log.php');

//Connexion BBD Communique
$colname_communique = "-1";
if (isset($_GET['com_id'])) {
  $colname_communique = $_GET['com_id'];}

$query_communique = sprintf("SELECT * FROM communiques WHERE ch_com_ID = %s", GetSQLValueString($colname_communique, "int"));
$communique = mysql_query($query_communique, $maconnexion) or die(mysql_error());
$row_communique = mysql_fetch_assoc($communique);
$totalRows_communique = mysql_num_rows($communique);
$cat = $row_communique['ch_com_categorie'];
$elementID = $row_communique['ch_com_element_id'];

//Connexion BBD Pour info sur l'institution emmitrice
if ( $cat == "pays") {
    $query_com_pays = sprintf("SELECT ch_pay_id, ch_pay_nom, ch_pay_devise, ch_pay_lien_imgdrapeau, ch_pay_lien_imgheader FROM pays WHERE ch_pay_id = %s", GetSQLValueString($elementID, "int"));
    $com_pays = mysql_query($query_com_pays, $maconnexion) or die(mysql_error());
    $row_com_pays = mysql_fetch_assoc($com_pays);
    $totalRows_com_pays = mysql_num_rows($com_pays);

    $ch_com_categorie = $cat;
    $ch_com_element_id = isset($colname_elementid) ?: 0;
    $nom_organisation = $row_com_pays['ch_pay_nom'];
    $insigne = $row_com_pays['ch_pay_lien_imgdrapeau'];
    $soustitre = $row_com_pays['ch_pay_devise'];
    $background_jumbotron = $row_com_pays['ch_pay_lien_imgheader'];
    mysql_free_result($com_pays);

    $pays = new \GenCity\Monde\Pays($elementID);
    $personnage = \GenCity\Monde\Personnage::constructFromEntity($pays);

    $breadcrumb_content = [
        [ 'url' => url('http://mondegc.test/Page-carte.php#liste-pays'),
          'text' => "Pays" ],
        [ 'url' => url('page-pays.php?ch_pay_id=' . $pays->get('ch_pay_id')),
          'text' => $pays->get('ch_pay_nom')
        ],
        [ 'url' => url('page-pays.php?ch_pay_id=' . $pays->get('ch_pay_id')) . '#communiques',
          'text' => "Communiqués"
        ],
        [ 'text' => $row_communique['ch_com_titre'] ],
    ];
}

elseif ( $cat == "ville") {
    $query_villes = sprintf("SELECT ch_vil_ID, ch_vil_nom, ch_vil_specialite, ch_vil_armoiries, ch_pay_id, ch_pay_nom, ch_vil_lien_img1 FROM villes INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id WHERE ch_vil_ID = %s", GetSQLValueString($elementID, "int"));
    $villes = mysql_query($query_villes, $maconnexion) or die(mysql_error());
    $row_villes = mysql_fetch_assoc($villes);
    $totalRows_villes = mysql_num_rows($villes);

    $ch_com_categorie = $cat;
    $ch_com_element_id = $colname_elementid;
    $nom_organisation = $row_villes['ch_vil_nom'];
    $insigne = $row_villes['ch_vil_armoiries'];
    $soustitre = $row_villes['ch_pay_nom'];
    $background_jumbotron = $row_villes['ch_vil_lien_img1'];
    mysql_free_result($villes);

    $villePays = new \GenCity\Monde\Pays($row_villes['ch_pay_id']);

    $breadcrumb_content = [
        [ 'url' => url('http://mondegc.test/Page-carte.php#liste-pays'),
          'text' => "Pays" ],
        [ 'url' => url('page-pays.php?ch_pay_id=' . $villePays->get('ch_pay_id')),
          'text' => $villePays->get('ch_pay_nom')
        ],
        [ 'url' => url('page-pays.php?ch_pay_id=' . $villePays->get('ch_pay_id')) . '#villes',
          'text' => "Villes"
        ],
        [ 'url' => url('page-ville.php?ch_ville_id=' . $row_villes['ch_vil_ID']),
          'text' => $row_villes['ch_vil_nom']
        ],
        [ 'url' => url('page-ville.php?ch_ville_id=' . $row_villes['ch_vil_ID'] . '#communiques'),
          'text' => "Communiqués"
        ],
        [ 'text' => $row_communique['ch_com_titre'] ],
    ];
}

elseif ( $cat == "institut") {
    $query_com_institut = sprintf("SELECT ch_ins_ID, ch_ins_nom, ch_ins_sigle, ch_ins_logo FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($elementID, "int"));
    $com_institut = mysql_query($query_com_institut, $maconnexion) or die(mysql_error());
    $row_com_institut = mysql_fetch_assoc($com_institut);
    $totalRows_com_institut = mysql_num_rows($com_institut);

    $ch_com_categorie = $cat;
    $ch_com_element_id = $colname_elementid;
    $nom_organisation = $row_com_institut['ch_ins_sigle'];
    $insigne = $row_com_institut['ch_ins_logo'];
    $soustitre = $row_com_institut['ch_ins_nom'];
    $background_jumbotron = "assets/img/fond_haut-conseil.jpg";
    mysql_free_result($com_institut);
}

elseif($cat == 'organisation') {
    $organisation = \App\Models\Organisation::findOrFail($row_communique['ch_com_element_id']);

    $ch_com_categorie = $cat;
    $ch_com_element_id = $colname_elementid;
    $nom_organisation = $organisation->name;
    $insigne = $organisation->flag;
    $soustitre = "Organisation";
    $background_jumbotron = "assets/img/fond_haut-conseil.jpg";
    $breadcrumb_content = [
        [ 'url' => url('politique.php#organisations'), 'text' => "Organisations" ],
        [ 'url' => route('organisation.showslug', $organisation->showRouteParameter()),
          'text' => $organisation->name
        ],
        [ 'url' => route('organisation.showslug', $organisation->showRouteParameter()) . '#actualites',
          'text' => 'Communiqués'
        ],
        [ 'text' => $row_communique['ch_com_titre'] ],
    ];
}

// Ce code est un bordel. J'ajoute ce qui suit pour pallier à ce code bordélique.
// Je sais même pas comment le commenter.
// Il vise à gérer les permissions d'accès dans le cas où le communiqué est une organisation.
// $check_organisation = true permet d'ignorer cette règle de permission lorsque le
// communiqué n'est pas lié à une organisation.
// Franchement Calimero tu déconnes wsh, tu aurais pu faire un effort merde. :aie:
$check_organisation = true;
if($cat == 'organisation') {
    if(!auth()->check() || !auth()->user()->can('administrate', $organisation)) {
        $check_organisation = false;
    }
}

//Connexion BBD user pour info sur l'auteur
$colname_user = $row_communique['ch_com_user_id'];

$query_user = sprintf("SELECT ch_use_id, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_login FROM users WHERE ch_use_id = %s", GetSQLValueString($colname_user, "int"));
$user = mysql_query($query_user, $maconnexion) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

$_SESSION['last_work'] = 'page-communique.php?com_id='.$row_communique['ch_com_ID'];

$eloquentCommunique = \App\Models\Communique::findOrFail($colname_communique);

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Communiqu&eacute; : <?= __s($row_communique['ch_com_titre']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css">
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
	background-image: url('<?= e($background_jumbotron) ?>');
}
</style>
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
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

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140">
<!-- Navbar
    ================================================== -->
<?php require('php/navbar.php'); ?>
<!-- Page CONTENT
    ================================================== -->
<div class="container corps-page">

    <?php renderElement('errormsgs'); ?>

    <?php if(isset($breadcrumb_content)): ?>
        <ul class="breadcrumb" style="padding-top: 0;">
            <?php foreach($breadcrumb_content as $breadcrumb): ?>
            <li <?= !isset($breadcrumb['url']) ? 'class="active"' : '' ?>>
                <?= isset($breadcrumb['url']) ? '<a href="' . __s($breadcrumb['url']) . '">' : '' ?>
                    <?= __s($breadcrumb['text']) ?>
                <?= isset($breadcrumb['url']) ? '</a>' : '' ?>
                <?= isset($breadcrumb['url']) ? '<span class="divider">/</span>' : '' ?>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

  <div class="row-fluid communique" style="background-color: #e6eaff; padding: 20px 0; margin-top: -10px;">

    <!-- EN-tête Personnage pour communiquées officiels et commentaire-->
    <div class="span3 thumb">
      <?php if(isset($personnage)): ?>
      <img src="<?= $personnage->get('lien_img') ?>" alt="photo <?= $personnage->get('nom_personnage') ?>">
      <div class="titre-gris">
        <p><?= $personnage->get('predicat') ?></p>
        <h3><?= $personnage->get('prenom_personnage') ?> <?= $personnage->get('nom_personnage') ?></h3>
        <p><small><?= $personnage->get('titre_personnage') ?></small></p>
      </div>
      <?php endif; ?>
    </div>

    <!-- EN-tête Institution pour communiqués officiels-->
    <div class="offset6 span3 thumb">
       <?php if ( $cat == "ville") {?>
        <?php if ($insigne == NULL) {?>
        <img src="assets/img/imagesdefaut/blason.jpg" alt="armoirie">
        <?php } else { ?>
        <img src="<?php echo $insigne; ?>" alt="armoirie">
        <?php } ?>
        <?php } elseif ( $cat == "pays") {?>
        <?php if ($insigne == NULL) {?>
        <img src="assets/img/imagesdefaut/drapeau.jpg" alt="drapeau">
        <?php } else { ?>
        <img src="<?php echo $insigne; ?>" alt="drapeau">
        <?php } ?>
        <?php } elseif ( $cat == "institut" || $cat == "organisation") {?>
        <?php if ($insigne == NULL) {?>
        <img src="assets/img/imagesdefaut/blason.jpg" alt="logo">
        <?php } else { ?>
        <img src="<?php echo $insigne; ?>" alt="logo">
        <?php }
		 } else {?>
                <img src="<?php echo $insigne; ?>">
                <?php } ?>
      <div class="titre-gris">
        <h3><?php echo $nom_organisation; ?></h3>
        <p><small><?php echo $soustitre; ?></small></p>
      </div>
    </div>

  </div>

  <div class="row-fluid">

  <!-- Moderation
     ================================================== -->
  <div class="cta-container" style="position: relative; top: 25px; margin-right: -15px;">
    <?php if ($check_organisation && ($_SESSION['statut'] >= 20) OR ($row_user['ch_use_id'] == $_SESSION['user_ID'])) { ?>
     <div class="moderation">
      <form class="pull-right" action="<?= DEF_URI_PATH ?>back/communique_confirmation_supprimer.php" method="post">
        <input name="communique_ID" type="hidden" value="<?= e($row_communique['ch_com_ID']) ?>">
        <button class="btn btn-danger" type="submit" title="supprimer ce communiqu&eacute;"
            style="margin-left: 5px;"><i class="icon-trash icon-white"></i></button>
      </form>
      <form class="pull-right" action="<?= DEF_URI_PATH ?>back/communique_modifier.php" method="post">
        <input name="com_id" type="hidden" value="<?= e($row_communique['ch_com_ID']) ?>">
        <button class="btn btn-primary" type="submit" title="modifier ce communiqu&eacute;"
            style="margin-left: 5px;"><i class="icon-pencil icon-white"></i></button>
      </form>
      </div>
      <?php } ?>
      <?php if ($row_user['ch_use_id'] == $_SESSION['user_ID']) { ?>
      <a class="btn btn-primary pull-right" href="php/partage-communique.php?com_id=<?= e($row_communique['ch_com_ID']) ?>" data-toggle="modal" data-target="#myModal" title="Poster sur le forum"><i class="icon-share icon-white"></i> Partager sur le forum</a>
      <?php } ?>
  </div>

    <!-- Titre  -->
  <div class="titre-vert">
      <h1><?= __s($row_communique['ch_com_titre']) ?></h1>
  </div>
  <div class="pull-right">
      <small>Publié le <?= e($eloquentCommunique->ch_com_date->format('d/m/Y')) ?></small>
  </div>
  <!-- Contenu -->
  <div class="well"><?= htmlPurify($row_communique['ch_com_contenu']) ?></div>
    <!-- Commentaire
        ================================================== -->
    <section>
      <div id="commentaires" class="titre-vert anchor">
        <h1>R&eacute;actions</h1>
      </div>
      <?php 
	  $ch_com_categorie = "com_communique";
	  $ch_com_element_id = $colname_communique;
	  require('php/commentaire.php'); ?>
    </section>

    <div class="modal container fade" id="myModal"></div>

    <!-- END CONTENT
    ================================================== --> 
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php require('php/footer.php'); ?>

<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>

<script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('.popover-html').popover({ html : true});
</script>
</body>
</html>