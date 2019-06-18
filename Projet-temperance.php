<?php

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
<meta charset="iso-8859-1">
<title>Projet temp&eacute;rance</title>
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
      <h2>Qu'est-ce que le projet Temp&eacute;rance&nbsp;?</h2>
      <p><em>"Le projet Temp&eacute;rance est propos&eacute;, comme son nom l'indique, pour  temp&eacute;rer les donn&eacute;es &eacute;conomiques et g&eacute;n&eacute;rales retranscrites par les  membres du forum GC et du site du m&ecirc;me nom. Le but est d'annoncer des  donn&eacute;es en ad&eacute;quation avec les villes ou les pays qui s'y rattachent et  qui sont pr&eacute;sent&eacute;s"</em></p>
    </div>
  </div>
</div>
<div class="container">
  <div class="corps-page">

    <ul class="breadcrumb">
      <li><a href="OCGC.php">OCGC</a> <span class="divider">/</span></li>
      <li><a href="economie.php">Économie</a> <span class="divider">/</span></li>
      <li class="active">Projet Tempérance</li>
    </ul>

    <div class="row-fluid">
      <div class="titre-bleu anchor" id="presentation">
        <h1>Principe de Tempérance</h1>
      </div>
      <div class="well">
        <p>&nbsp;</p>
        <div class="alert alert-success">
          <p>Le créateur du projet choisira quel pays ou quelle ville fera l'objet d'une future notation Tempérance. Lors du choix crucial, un message automatique faisant valoir ce que de droit sera envoyé au membre concerné par cette notation. Un mois est autorisé à ce dernier pour se préparer à la notation en réévaluant ses chiffres et en ajustant toutes les données importantes qu'il juge capable de faire baisser sa note finale.
            A la fin du temps imparti, les juges noteront le pays sur des critères généraux suivants :</p>
        </div>
        <ul>
          <li>
            <h4>La cohérence visuelle</h4>
            <p>Dont la note est influencée par le nombre d'images et d'informations proposées par le membre, sur la page de son pays.</p>
          </li>
          <li>
            <h4>La cohérence des chiffres par rapport aux visuels</h4>
            <p>Valider un chiffre, c'est montrer tout simplement que son existence est légitime.</p>
          </li>
          <li>
            <h4>L'exploitation des données chiffrables</h4>
            <p>Grâce à l'outil "Infrastructures", les ressources Budget et Commerce seront étudiées ainsi que la participation du membre à cet outil. Tempérance est outil de l'Institut Economique après tout.</p>
          </li>
          <li>
            <h4>Evaluation comportementale</h4>
            <p>Notation non pas faite sur le comportement quotidien du membre sur le forum mais sur sa capacité à s'adapter au monde GC et à s'y intégrer (avec son Histoire, son activité etc.) ou inviter les autres à y participer.</p>
          </li>
        </ul>
        <p>&nbsp;</p>
        <p>A cette note s'ajoutera le commentaire de chacun des juges pour chaque question à laquelle il répond.</p>
        <div class="alert alert-success">
          <h4>Note fictive, boostant le RP</h4>
            <p>Je rappelle que la note obtenue est une note fictive et qui, de par ce titre, ne critique pas le travail fait par le membre jugé par le projet et ne le discrimine sur aucun point. Elle est simplement la source d'un meilleur RP pour notre communauté. Cet outil est conçu pour englober de nouveaux critères plus généraux qui invitent les membres à alimenter leurs présentations et à les faire évoluer. Elle met en valeur les présentations riches d'informations. </p>
        </div>
        <div class="alert alert-success">
        <h4> La richesse est différente de la beauté</h4>
          <p>Je rappelle également que les juges ne sont pas là pour discriminer l'esthétisme de certaines créations mais plutôt leur valeur ou leur richesse. Les gouts et les couleurs n'étant pas chiffrables et différents pour tous, Tempérance s'appuie sur l'organisation générale d'une présentation, son contenu et la façon de présenter du membre plutôt que sa façon de créer et de construire ce qu'il présente. </p>
         </div>
        <em>
        <p>Sakuro </p>
        </em> </div>
         <!-- Liste des temperances
     ================================================== -->
      <div class="titre-bleu anchor" id="presentation">
        <h1>Pays et villes not&eacute;s par le projet Temp&eacute;rance</h1>
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
