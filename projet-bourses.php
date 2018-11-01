<?php
session_start();
include('Connections/maconnexion.php'); 

//deconnexion
include('php/log.php');

//requete liste temperances
$type_classement = 'ch_inf_off_nom ASC';
if (isset($_GET['type_classement_inf'])) {
  $type_classement = $_GET['type_classement_inf'];
} 

$maxRows_liste_temperance = 10;
$pageNum_liste_temperance = 0;
if (isset($_GET['pageNum_liste_temperance'])) {
  $pageNum_liste_temperance = $_GET['pageNum_liste_temperance'];
}
$startRow_liste_temperance = $pageNum_liste_temperance * $maxRows_liste_temperance;

mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_temperance = sprintf("SELECT ch_temp_id as id, ch_pay_nom as nom, ch_temp_element as element, ch_temp_element_id as element_id, ch_pay_id as pays_id, ch_temp_date as date, ch_temp_mis_jour as mis_jour, ch_pay_lien_imgdrapeau as image, ch_temp_note as note, ch_temp_tendance as tendance FROM temperance LEFT JOIN pays ON ch_temp_element_id = ch_pay_id WHERE ch_temp_element='pays' AND ch_temp_statut='3'
UNION
SELECT ch_temp_id as id, ch_vil_nom as nom, ch_temp_element as element, ch_temp_element_id as element_id, ch_pay_id as pays_id, ch_temp_date as date, ch_temp_mis_jour as mis_jour, ch_vil_armoiries as image, ch_temp_note as note, ch_temp_tendance as tendance FROM temperance LEFT JOIN villes ON ch_temp_element_id = ch_vil_ID LEFT JOIN pays ON ch_vil_paysID = ch_pay_id WHERE ch_temp_element='ville' AND ch_temp_statut='3'
ORDER BY date asc");
$query_limit_liste_temperance = sprintf("%s LIMIT %d, %d", $query_liste_temperance, $startRow_liste_temperance, $maxRows_liste_temperance);
$liste_temperance = mysql_query($query_limit_liste_temperance, $maconnexion) or die(mysql_error());
$row_liste_temperance = mysql_fetch_assoc($liste_temperance);

if (isset($_GET['totalRows_liste_temperance'])) {
  $totalRows_liste_temperance = $_GET['totalRows_liste_temperance'];
} else {
  $all_liste_temperance = mysql_query($query_liste_temperance);
  $totalRows_liste_temperance = mysql_num_rows($all_liste_temperance);
}
$totalPages_liste_temperance = ceil($totalRows_liste_temperance/$maxRows_liste_temperance)-1;

$queryString_liste_temperance = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_liste_temperance") == false && 
        stristr($param, "totalRows_liste_temperance") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_liste_temperance = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_liste_temperance = sprintf("&totalRows_liste_temperance=%d%s", $totalRows_liste_temperance, $queryString_liste_temperance);
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Bourses mondiales</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="assets/css/colorpicker.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
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
	background-image: url('assets/img/fond_haut-conseil.jpg');
	background-position: center;
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
<!-- Color Picker  -->
<script src="assets/js/bootstrap-colorpicker.js" type="text/javascript"></script>
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script>
		$(function(){
			window.prettyPrint && prettyPrint()
			$('#cp3').colorpicker({
format: 'hex'});
$('#cp4').colorpicker({
format: 'hex'});
		});
	</script>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div id="introheader" class="jumbotron">
  <div class="container">
    <div class="pull-right span5" style="text-align:right;">
      <p>&nbsp;</p>
      <a href="economie.php" class="btn btn-primary">Retour Institut d'Economie</a> </div>
    <div class="span5 align-left">
      <h2>Qu'est-ce qu'une Bourse mondiale ?</h2>
      <p><em>Votre Bourse mondiale est la vitrine mettant en valeur vos ressources obtenues grâce aux infrastructures et grâce à l'outil "Zoning" du Monde GC. Elle vous permettra d'obtenir des points et une tendance, définissant ainsi votre valeur en bourse, en fonction de votre activité économique et de l'activité des autres membres.</em></p>
    </div>
  </div>
</div>
<div class="container">
  <div class="corps-page">
    <div class="row-fluid">
      <div class="titre-bleu anchor" id="presentation"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
        <h1>Principe des Bourses mondiales :</h1>
      </div>
      <div class="well">
        <p>&nbsp;</p>
        <div class="alert alert-success">
          <p>Certaines de vos ressources seront mises en relation entre elles et comparées avec la moyenne mondiale afin de définir une valeur de départ de votre Bourse. L'équation, qui sera dévoilée très prochainement, permettra une mise à jour spontanée et automatique de cette valeur de départ en fonction de vos infrastructures mises en ligne, mais en les comparant dorénavant à la tendance mondiale.</p>
        </div>
        <ul>
          <li>
            <h4>Le premier objectif</h4>
            <p>Le but de ce projet est de rajouter une dynamique économique et politique sur le site GC. De projeter les membres dans le phénomène de la mondialisation et de les confronter à l'évolution constante de notre Monde.</p>
          </li>
          <li>
            <h4>Le second objectif</h4>
            <p>Le second objectif est bien évidemment de booster vos créations afin de faire évoluer la valeur de votre Bourse et battre la tendance mondiale !</p>
          </li>
          <li>
            <h4>Un résultat plus que bénéfique !</h4>
            <p>Le résultat n'est pas seulement la création physique d'une Bourse dans votre pays. C'est de vous donner la possibilité qu'elle joue, pour vous, un rôle majeur et dynamique dans les relations internationales. Qu'elle vous permette d'être en perpétuel mouvement, actif voire même réactif pour ne pas vous laisser distancer.</p>
          </li>
          <li>
            <h4>La mise en place d'outils supplémentaires</h4>
            <p>Qui dit projet supplémentaire dit mise en place de nouveaux outils intéressants sur le site GC vous permettant de controler au mieux votre Bourse.</p>
          </li>
        </ul>
        <p>&nbsp;</p>
        <p>Comme</p>
        <div class="alert alert-success">
          <h4>Le tableau dynamique</h4>
            <p>La mise en place d'un tableau dynamique à la manière des bourses dans le monde réel afin que vous puissiez suivre la tendance de votre pays, du monde GC et ainsi vous réadapter au marché !</p>
        </div>
        <div class="alert alert-success">
        <h4> En construction</h4>
          <p>En construction</p>
         </div>
        <em>
        <p>Sakuro </p>
        </em> </div>
         <!-- Liste des temperances
     ================================================== -->
      <div class="titre-bleu anchor" id="presentation">
        <h1>Pays et villes not&eacute;s par le projet temp&eacute;rance</h1>
      </div>
      <div>
        <div class="titre-gris anchor" id="liste-temperance">
          <h3>Liste des temp&eacute;rances</h3>
        </div>
        <ul class="listes">
          <?php do { 
$idtemperance = $row_liste_temperance ['id'];
		// requete nb de juges votants
				mysql_select_db($database_maconnexion, $maconnexion);
$query_nb_juges = sprintf("SELECT COUNT(ch_not_temp_juge) as nbjuges FROM notation_temperance WHERE ch_not_temp_temperance_id =%s", GetSQLValueString( $idtemperance, "int"));
$nb_juges = mysql_query($query_nb_juges, $maconnexion) or die(mysql_error());
$row_nb_juges = mysql_fetch_assoc($nb_juges);
$totalRows_nb_juges = mysql_num_rows($nb_juges);
?>
          <li class="row-fluid liste-temp"> 
            <!-- Boutons modifier -->
            <div class="span1">
              <?php	if ($row_liste_temperance['element'] == "pays") { ?>
              <a href="page-pays.php?ch_pay_id=<?php echo $row_liste_temperance['element_id']; ?>" title="lien vers la page du pays"><img src="<?php echo $row_liste_temperance['image']; ?>"></a>
              <?php } ?>
              <?php	if ($row_liste_temperance['element'] == "ville") { ?>
              <a href="page-ville.php?ch_pay_id=<?php echo $row_liste_temperance['pays_id']; ?>&ch_ville_id=<?php echo $row_liste_temperance['element_id']; ?>" title="lien vers la page de la ville"><img src="<?php echo $row_liste_temperance['image']; ?>"></a>
              <?php } ?>
            </div>
            <!-- Nom element tempere -->
            <div class="span3">
              <h5><?php echo $row_liste_temperance['element']; ?>&nbsp;: <?php echo $row_liste_temperance['nom']; ?></h5>
            </div>
            <!-- contenu categorie -->
            <div class="span4"> <em>Lanc&eacute;e il y a
              <?php 
		$now= date("Y-m-d G:i:s");
	  $d1 = new DateTime($row_liste_temperance['date']);
	  $d2 = new DateTime($now);
	  $diff = get_timespan_string_hour($d1, $d2);
	  echo $diff;?>
              </em></div>
            <div class="span3">
              <h5>Nombre de juges ayant vot&eacute;&nbsp;: <?php echo $row_nb_juges['nbjuges']; ?></h5>
            </div>
            <div class="span1"> 
              <!-- Boutons modifier -->
              <?php 
				if ($row_liste_temperance['element'] == "pays") { ?>
              <a class="btn btn-primary" href="php/temperance-rapport-pays.php?ch_temp_id=<?php echo $row_liste_temperance['id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="voir le rapport publi&eacute; sur le site"><?php echo get_note_finale($row_liste_temperance['note']); ?>
              <?php	if ($row_liste_temperance['tendance'] == "sup") { ?>
              <i class="icon-arrow-up icon-white"></i>
              <?php } elseif ($row_liste_temperance['tendance'] == "inf") { ?>
              <i class="icon-arrow-down icon-white"></i>
              <?php } else { ?>
              <i class=" icon-arrow-right icon-white"></i>
              <?php } ?>
              </a>
              <?php }
                if ($row_liste_temperance['element'] == "ville") { ?>
              <a class="btn btn-primary" href="php/temperance-rapport-ville.php?ch_temp_id=<?php echo $row_liste_temperance['id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="voir le rapport publi&eacute; sur le site">Note&nbsp;: <?php echo get_note_finale($row_liste_temperance['note']); ?>
              <?php	if ($row_liste_temperance['tendance'] == "sup") { ?>
              <i class="icon-arrow-up icon-white"></i>
              <?php } elseif ($row_liste_temperance['tendance'] == "inf") { ?>
              <i class="icon-arrow-down icon-white"></i>
              <?php } else { ?>
              <i class=" icon-arrow-right icon-white"></i>
              <?php } ?>
              </a>
              <?php } ?>
            </div>
          </li>
          <?php
		  mysql_free_result($nb_juges);
		   } while ($row_liste_temperance = mysql_fetch_assoc($liste_temperance)); ?>
        </ul>
        <!-- Pagination de la liste -->
        <p>&nbsp;</p>
        <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_liste_temperance + 1) ?> &agrave; <?php echo min($startRow_liste_temperance + $maxRows_liste_temperance, $totalRows_liste_temperance) ?> sur <?php echo $totalRows_liste_temperance ?>
          <?php if ($pageNum_liste_temperance > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_temperance=%d%s#liste-categories", $currentPage, max(0, $pageNum_liste_temperance - 1), $queryString_liste_temperance); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
          <?php if ($pageNum_liste_temperance < $totalPages_liste_temperance) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_temperance=%d%s#liste-categories", $currentPage, min($totalPages_liste_temperance, $pageNum_liste_temperance + 1), $queryString_liste_temperance); ?>"> <i class="icon-forward"></i></a>
            <?php } // Show if not last page ?>
          </small> </p>
        <!-- Modal et script -->
        <div class="modal container fade" id="Modal-Monument" data-width="760"></div>
        <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script> 
      </div>
    </div>
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($liste_temperance);
?>
