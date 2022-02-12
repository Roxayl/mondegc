<?php

use App\Models\Infrastructure;

$maxRows_LastCommunique = 20;
$pageNum_LastCommunique = 0;
if (isset($_GET['pageNum_LastCommunique'])) {
  $pageNum_LastCommunique = $_GET['pageNum_LastCommunique'];
}
$startRow_LastCommunique = $pageNum_LastCommunique * $maxRows_LastCommunique;


$query_LastCommunique = /** @lang MySQL */
    "
SELECT communique_pays.ch_com_label AS type_notification, communique_pays.ch_com_ID AS id, communique_pays.ch_com_statut AS statut, communique_pays.ch_com_categorie AS sous_categorie, communique_pays.ch_com_element_id AS id_element, communique_pays.ch_com_user_id AS id_auteur, communique_pays.ch_com_date AS date, communique_pays.ch_com_titre AS titre, lien_img AS photo_auteur, nom_personnage AS nom_auteur, entity_id AS paysID_auteur, prenom_personnage AS prenom_auteur, titre_personnage AS titre_auteur, ch_pay_id AS id_institution, ch_pay_nom AS institution, ch_pay_lien_imgdrapeau AS img_institution, ch_pay_id AS pays_institution
FROM communiques communique_pays 
INNER JOIN users ON communique_pays.ch_com_user_id = ch_use_id 
INNER JOIN pays ON communique_pays.ch_com_element_id = ch_pay_id
LEFT JOIN personnage ON(entity_id = ch_pay_id AND entity = 'pays')
WHERE communique_pays.ch_com_statut = 1 AND communique_pays.ch_com_categorie='pays' OR communique_pays.ch_com_categorie='com_pays' 
UNION 
SELECT communique_ville.ch_com_label AS type_notification, communique_ville.ch_com_ID AS id, communique_ville.ch_com_statut AS statut, communique_ville.ch_com_categorie AS sous_categorie, communique_ville.ch_com_element_id AS id_element, communique_ville.ch_com_user_id AS id_auteur, communique_ville.ch_com_date AS date, communique_ville.ch_com_titre AS titre, lien_img AS photo_auteur, nom_personnage AS nom_auteur, entity_id AS paysID_auteur, prenom_personnage AS prenom_auteur, titre_personnage AS titre_auteur, ch_vil_ID AS id_institution, ch_vil_nom AS institution, ch_vil_armoiries AS img_institution, ch_vil_paysID AS pays_institution
FROM communiques communique_ville 
INNER JOIN villes ON ch_com_element_id = ch_vil_ID 
INNER JOIN users ON communique_ville.ch_com_user_id = ch_use_id 
LEFT JOIN personnage ON(entity_id = ch_vil_ID AND entity = 'ville')
WHERE communique_ville.ch_com_statut = 1 AND communique_ville.ch_com_categorie ='ville' OR communique_ville.ch_com_categorie ='com_ville'
UNION 
SELECT communique_organisation.ch_com_label AS communique_organisation, communique_organisation.ch_com_ID AS id, communique_organisation.ch_com_statut AS statut, communique_organisation.ch_com_categorie AS sous_categorie, communique_organisation.ch_com_element_id AS id_element, communique_organisation.ch_com_user_id AS id_auteur, communique_organisation.ch_com_date AS date, communique_organisation.ch_com_titre AS titre, lien_img AS photo_auteur, nom_personnage AS nom_auteur, entity_id AS paysID_auteur, prenom_personnage AS prenom_auteur, titre_personnage AS titre_auteur, organisation.id AS id_institution, organisation.name AS institution, organisation.flag AS img_institution, organisation.id AS pays_institution
FROM communiques communique_organisation 
INNER JOIN organisation ON communique_organisation.ch_com_element_id = organisation.id
LEFT JOIN personnage ON(entity_id = organisation.id AND entity = 'organisation')
WHERE communique_organisation.ch_com_statut = 1 AND communique_organisation.ch_com_categorie ='organisation' OR communique_organisation.ch_com_categorie ='com_organisation' AND organisation.deleted_at IS NULL
UNION 
SELECT communique_institut.ch_com_label AS type_notification, communique_institut.ch_com_ID AS id, communique_institut.ch_com_statut AS statut, communique_institut.ch_com_categorie AS sous_categorie, communique_institut.ch_com_element_id AS id_element, communique_institut.ch_com_user_id AS id_auteur, communique_institut.ch_com_date AS date, communique_institut.ch_com_titre AS titre, lien_img AS photo_auteur, nom_personnage AS nom_auteur, entity_id AS paysID_auteur, prenom_personnage AS prenom_auteur, titre_personnage AS titre_auteur, ch_ins_ID AS id_institution, ch_ins_nom AS institution, ch_ins_logo AS img_institution, ch_ins_ID AS pays_institution
FROM communiques communique_institut 
INNER JOIN instituts ON ch_com_element_id = ch_ins_ID 
INNER JOIN users ON communique_institut.ch_com_user_id = ch_use_id 
LEFT JOIN personnage ON(entity_id = ch_ins_ID AND entity = 'institut')
WHERE communique_institut.ch_com_statut = 1 AND communique_institut.ch_com_categorie ='institut' 
UNION
SELECT communique_monument.ch_com_label AS type_notification, communique_monument.ch_com_ID AS id, communique_monument.ch_com_statut AS statut, communique_monument.ch_com_categorie AS sous_categorie, communique_monument.ch_com_element_id AS id_element, communique_monument.ch_com_user_id AS id_auteur, communique_monument.ch_com_date AS date, communique_monument.ch_com_titre AS titre, lien_img AS photo_auteur, nom_personnage AS nom_auteur, entity_id AS paysID_auteur, prenom_personnage AS prenom_auteur, titre_personnage AS titre_auteur, ch_pat_id AS id_institution, ch_pat_nom AS institution, ch_pat_lien_img1 AS img_institution, ch_pat_paysID AS pays_institution
FROM communiques communique_monument 
INNER JOIN patrimoine ON ch_com_element_id = ch_pat_id
INNER JOIN users ON communique_monument.ch_com_user_id = ch_use_id 
LEFT JOIN personnage ON(entity_id = ch_pat_villeID AND entity = 'ville')
WHERE communique_monument.ch_com_statut = 1 AND communique_monument.ch_com_categorie ='com_monument'
UNION
SELECT communique_monument.ch_com_label AS type_notification, communique_monument.ch_com_ID AS id, communique_monument.ch_com_statut AS statut, communique_monument.ch_com_categorie AS sous_categorie, communique_monument.ch_com_element_id AS id_element, communique_monument.ch_com_user_id AS id_auteur, communique_monument.ch_com_date AS date, communique_monument.ch_com_titre AS titre, lien_img AS photo_auteur, nom_personnage AS nom_auteur, entity_id AS paysID_auteur, prenom_personnage AS prenom_auteur, titre_personnage AS titre_auteur, ch_his_id AS id_institution, ch_his_nom AS institution, ch_his_lien_img1 AS img_institution, ch_his_paysID AS pays_institution
FROM communiques communique_monument 
INNER JOIN histoire ON ch_com_element_id = ch_his_id
INNER JOIN users ON communique_monument.ch_com_user_id = ch_use_id 
LEFT JOIN personnage ON(entity_id = ch_his_paysID AND entity = 'pays')
WHERE communique_monument.ch_com_statut = 1 AND communique_monument.ch_com_categorie ='com_fait_his' 
UNION  
SELECT commentaire_emis.ch_com_label AS type_notification, commentaire_emis.ch_com_ID AS id, commentaire_emis.ch_com_statut AS statut, commentaire_emis.ch_com_categorie AS sous_categorie, commentaire_emis.ch_com_element_id AS id_element, commentaire_emis.ch_com_user_id AS id_auteur, commentaire_emis.ch_com_date AS date, commentaire_emis.ch_com_titre AS titre, lien_img AS photo_auteur, nom_personnage AS nom_auteur, entity_id AS paysID_auteur, prenom_personnage AS prenom_auteur, titre_personnage AS titre_auteur, commentaire_emis.ch_com_element_id AS id_institution, institution.ch_com_titre AS institution, auteur.ch_use_lien_imgpersonnage AS img_institution, institution.ch_com_element_id AS pays_institution
FROM communiques commentaire_emis
INNER JOIN users visiteur ON ch_com_user_id = ch_use_id 
INNER JOIN communiques institution ON commentaire_emis.ch_com_element_id = institution.ch_com_ID
INNER JOIN users auteur ON commentaire_emis.ch_com_user_id = auteur.ch_use_id 
LEFT JOIN personnage ON(entity_id = commentaire_emis.ch_com_pays_id AND entity = 'pays')
WHERE institution.ch_com_statut = 1 AND commentaire_emis.ch_com_categorie ='com_communique'
UNION 
SELECT ch_pay_label AS type_notification, ch_pay_id AS id, ch_pay_publication AS statut, ch_pay_label AS sous_categorie, ch_pay_id AS id_element, ch_pay_id AS id_auteur, ch_pay_mis_jour AS date, ch_pay_nom AS titre, lien_img AS photo_auteur, nom_personnage AS nom_auteur, entity_id AS paysID_auteur, prenom_personnage AS prenom_auteur, titre_personnage AS titre_auteur, ch_pay_id AS id_institution, ch_pay_nom AS institution, ch_pay_lien_imgdrapeau AS img_institution, ch_pay_id AS pays_institution
FROM pays
LEFT JOIN personnage ON(entity_id = pays.ch_pay_id AND entity = 'pays')
WHERE ch_pay_publication = 1
UNION 
SELECT ch_vil_label AS type_notification, ch_vil_ID AS id, ch_vil_capitale AS statut, ch_vil_label AS sous_categorie, ch_vil_paysID AS id_element, ch_vil_user AS id_auteur, ch_vil_mis_jour AS date, ch_vil_nom AS titre, ch_use_lien_imgpersonnage AS photo_auteur, ch_use_nom_dirigeant AS nom_auteur, ch_vil_paysID AS paysID_auteur, ch_use_prenom_dirigeant AS prenom_auteur, ch_use_titre_dirigeant AS titre_auteur, ch_vil_ID AS id_institution, ch_vil_nom AS institution, ch_vil_lien_img1 AS img_institution, ch_vil_paysID AS pays_institution
FROM villes INNER JOIN users ON ch_use_id = ch_vil_user INNER JOIN pays ON ch_vil_paysID = ch_pay_id
WHERE ch_vil_capitale != 3 AND ch_pay_publication = 1
UNION 
SELECT ch_pat_label AS type_notification, ch_pat_id AS id, ch_pat_statut AS statut, ch_pat_label AS sous_categorie, ch_pat_villeID AS id_element, ch_vil_user AS id_auteur, ch_pat_date AS date, ch_pat_nom AS titre, ch_use_lien_imgpersonnage AS photo_auteur, ch_use_nom_dirigeant AS nom_auteur, ch_vil_paysID AS paysID_auteur, ch_use_prenom_dirigeant AS prenom_auteur, ch_use_titre_dirigeant AS titre_auteur, ch_pat_villeID AS id_institution, ch_vil_nom AS institution, ch_pat_lien_img1 AS img_institution, ch_pat_paysID AS pays_institution
FROM patrimoine 
INNER JOIN villes ON ch_pat_villeID = ch_vil_ID INNER JOIN pays ON ch_vil_paysID = ch_pay_id
INNER JOIN users ON ch_use_id = ch_vil_user
WHERE ch_vil_capitale != 3 AND ch_pay_publication = 1 
UNION
SELECT ch_his_label AS type_notification, ch_his_id AS id, ch_his_personnage AS statut, ch_his_date_fait AS sous_categorie, ch_his_date_fait2 AS id_element, ch_use_paysID AS id_auteur, ch_his_date AS date, ch_his_nom AS titre, ch_use_lien_imgpersonnage AS photo_auteur, ch_use_nom_dirigeant AS nom_auteur, ch_use_paysID AS paysID_auteur, ch_use_prenom_dirigeant AS prenom_auteur, ch_use_titre_dirigeant AS titre_auteur, ch_his_paysID AS id_institution, ch_pay_nom AS institution, ch_his_lien_img1 AS img_institution, ch_his_paysID AS pays_institution
FROM histoire
INNER JOIN users ON ch_use_paysID = ch_his_paysID AND ch_use_statut >=10
INNER JOIN pays ON ch_his_paysID = ch_pay_id
WHERE ch_his_statut = 1 AND ch_pay_publication = 1 AND ch_use_statut >=10 GROUP BY ch_his_id
UNION 
SELECT ch_disp_mon_label AS type_notification, ch_disp_mon_id AS id, ch_mon_cat_statut AS statut, ch_pat_label AS sous_categorie, ch_pat_villeID AS id_element, ch_disp_mon_id AS id_auteur, ch_disp_date AS date, ch_mon_cat_nom AS titre, ch_pat_lien_img1 AS photo_auteur, ch_pat_nom AS nom_auteur, ch_pat_paysID AS paysID_auteur, ch_pat_nom AS prenom_auteur, ch_pat_legende_img1 AS titre_auteur, ch_mon_cat_ID AS id_institution, ch_mon_cat_nom AS institution, ch_mon_cat_icon AS img_institution, ch_mon_cat_couleur AS pays_institution
FROM dispatch_mon_cat
INNER JOIN patrimoine ON ch_disp_mon_id = ch_pat_id INNER JOIN villes ON ch_pat_villeID = ch_vil_ID INNER JOIN pays ON ch_vil_paysID = ch_pay_id
INNER JOIN monument_categories ON ch_mon_cat_ID = ch_disp_cat_id
WHERE ch_vil_capitale != 3 AND ch_pay_publication = 1 
UNION 
SELECT ch_disp_FH_label AS type_notification, ch_disp_fait_hist_id AS id, ch_fai_cat_statut AS statut, ch_his_label AS sous_categorie, ch_his_paysID AS id_element, ch_disp_fait_hist_id AS id_auteur, ch_disp_FH_date AS date, ch_fai_cat_nom AS titre, ch_his_lien_img1 AS photo_auteur, ch_his_nom AS nom_auteur, ch_his_paysID AS paysID_auteur, ch_his_nom AS prenom_auteur, ch_his_legende_img1 AS titre_auteur, ch_fai_cat_ID AS id_institution, ch_fai_cat_nom AS institution, ch_fai_cat_icon AS img_institution, ch_fai_cat_couleur AS pays_institution
FROM dispatch_fait_his_cat
INNER JOIN histoire ON ch_disp_fait_hist_id = ch_his_id INNER JOIN pays ON ch_his_paysID = ch_pay_id
INNER JOIN faithist_categories ON ch_fai_cat_ID = ch_disp_fait_hist_cat_id
WHERE ch_fai_cat_statut = 1 AND ch_his_statut = 1 AND ch_pay_publication = 1
UNION 
SELECT ch_inf_label AS type_notification, ch_inf_id AS id, ch_inf_statut AS statut, ch_inf_off_icone AS sous_categorie, infrastructures.ch_inf_off_id AS id_element, null AS id_auteur, COALESCE(judged_at, ch_inf_date) AS date, ch_inf_off_nom AS titre, null AS photo_auteur, null AS nom_auteur, null AS paysID_auteur, null AS prenom_auteur, null AS titre_auteur, null AS id_institution, null AS institution, ch_inf_lien_image AS img_institution, null AS pays_institution
FROM  infrastructures
INNER JOIN infrastructures_officielles ON infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id
WHERE ch_inf_statut = 2
UNION
SELECT 'vote_ag_finished' AS type_notification, id AS id, is_valid AS statut, 'proposition' AS sous_categorie, id AS id_element, null AS id_auteur, debate_end AS date, question AS titre, null AS photo_auteur, null AS nom_auteur, ID_pays AS paysID_auteur, null AS prenom_auteur, null AS titre_auteur, null AS id_institution, null AS institution, null AS img_institution, null AS pays_institution
FROM ocgc_proposals
JOIN pays ON ID_pays = ch_pay_id
WHERE is_valid = 2 AND debate_end < NOW()
UNION
SELECT 'vote_ag_new' AS type_notification, id AS id, is_valid AS statut, 'proposition' AS sous_categorie, id AS id_element, null AS id_auteur, created AS date, question AS titre, null AS photo_auteur, null AS nom_auteur, ID_pays AS paysID_auteur, null AS prenom_auteur, null AS titre_auteur, ch_pay_id AS id_institution, null AS institution, ch_pay_lien_imgdrapeau AS img_institution, ch_pay_nom AS pays_institution
FROM ocgc_proposals
JOIN pays ON ID_pays = ch_pay_id
WHERE is_valid = 2
ORDER BY date DESC";
$query_limit_LastCommunique = sprintf("%s LIMIT %d, %d", $query_LastCommunique, $startRow_LastCommunique, $maxRows_LastCommunique);
$LastCommunique = mysql_query($query_limit_LastCommunique, $maconnexion) or die(mysql_error());
$row_LastCommunique = mysql_fetch_assoc($LastCommunique);

if (isset($_GET['totalRows_LastCommunique'])) {
  $totalRows_LastCommunique = $_GET['totalRows_LastCommunique'];
} else {
  $all_LastCommunique = mysql_query($query_LastCommunique);
  $totalRows_LastCommunique = mysql_num_rows($all_LastCommunique);
}
$totalPages_LastCommunique = ceil($totalRows_LastCommunique/$maxRows_LastCommunique)-1;

$queryString_LastCommunique = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_LastCommunique") == false && 
        stristr($param, "totalRows_LastCommunique") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_LastCommunique = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_LastCommunique = sprintf("&totalRows_LastCommunique=%d%s", $totalRows_LastCommunique, $queryString_LastCommunique);


//Variables pour affichage barres reperes temporels
$_SESSION['now'] = date('Y-m-d');
// date 24 avant 
$_SESSION['datehier'] = date('Y-m-d', time() - (3600 * 24 * 1));
// date 24 avant 
$_SESSION['dateavanthier'] = date('Y-m-d', time() - (3600 * 24 * 2));
// date 7 jours avant 
$_SESSION['datesemaine'] = date('Y-m-d', time() - (3600 * 24 * 7));
// date 14 jours avant 
$_SESSION['datedeuxsemaines'] = date('Y-m-d', time() - (3600 * 24 * 14));
// 1 mois avant :
$_SESSION['datemois'] = date('Y-m-d', time() - (3600 * 24 * 31));
// 2 mois avant :
$_SESSION['datedeuxmois'] = date('Y-m-d', time() - (3600 * 24 * 62));
// 3 mois avant :
$_SESSION['datetroismois'] = date('Y-m-d', time() - (3600 * 24 * 93));
// 6 mois avant :
$_SESSION['datesixmois'] = date('Y-m-d', time() - (3600 * 24 * 186));
// 1 an avant :
$_SESSION['dateunan'] = date('Y-m-d', time() - (3600 * 24 * 365));

?>

<!-- CATEGORIE Dernières actualités
================================================== -->
<!--<small class="pull-right">de <?php echo ($startRow_LastCommunique + 1) ?> &agrave; <?php echo min($startRow_LastCommunique + $maxRows_LastCommunique, $totalRows_LastCommunique) ?> sur <?php echo $totalRows_LastCommunique ?></small>
  <div class="clearfix"></div>-->
  <ul class="liste-transparente">
    <!-- Titres reperes temporels
================================================== -->
<?php
do {

    ?>
    <hr />

    <!--<div class="pull-center" style="color: #375c08;">
      <h4>
  <?php

    if (($row_LastCommunique['date'] > $_SESSION['now']) AND ($_SESSION['aujourdhui']==true)) {
		  $_SESSION['aujourdhui']=false;
		  echo $temps_text = "Aujourd'hui";
    }
    if (($row_LastCommunique['date'] < $_SESSION['now']) AND ($row_LastCommunique['date'] >= $_SESSION['datehier']) AND ($_SESSION['hier']==true)) {
		  $_SESSION['hier']=false;
		  echo $temps_text = "Hier";
    }
    if (($row_LastCommunique['date'] < $_SESSION['datehier']) AND ($row_LastCommunique['date'] >= $_SESSION['dateavanthier']) AND ($_SESSION['avanthier']==true)) {
		  $_SESSION['avanthier']=false;
		  echo $temps_text = "Avant-hier";
    }
    if (($row_LastCommunique['date'] < $_SESSION['dateavanthier']) AND ($row_LastCommunique['date'] >= $_SESSION['datesemaine']) AND ($_SESSION['avantavanthier']==true)) {
		  $_SESSION['avantavanthier']=false;
		  echo $temps_text = "Les jours pr&eacute;c&eacute;dents";
    }
    if (($row_LastCommunique['date'] <= $_SESSION['datesemaine']) AND ($row_LastCommunique['date'] >= $_SESSION['datedeuxsemaines']) AND ($_SESSION['semaine']==true)) {
		  $_SESSION['semaine']=false;
		  echo $temps_text = "Il y a plus d'une semaine";
    }
    if (($row_LastCommunique['date'] <= $_SESSION['datedeuxsemaines']) AND ($row_LastCommunique['date'] >= $_SESSION['datemois']) AND ($_SESSION['deuxsemaine']==true)) {
		  $_SESSION['deuxsemaine']=false;
		  echo $temps_text = "Il y a plus de deux semaines";
    }
    if (($row_LastCommunique['date'] <= $_SESSION['datemois']) AND ($row_LastCommunique['date'] >= $_SESSION['datedeuxmois']) AND ($_SESSION['mois']==true)) {
		  $_SESSION['mois']=false;
		  echo $temps_text = "Il y a plus d'un mois";
    }
    if (($row_LastCommunique['date'] <= $_SESSION['datedeuxmois']) AND ($row_LastCommunique['date'] >= $_SESSION['datetroismois']) AND ($_SESSION['deuxmois']==true)) {
		  $_SESSION['deuxmois']=false;
		  echo $temps_text = "Il y a plus de deux mois";
    }
    if (($row_LastCommunique['date'] <= $_SESSION['datetroismois']) AND ($row_LastCommunique['date'] >= $_SESSION['datesixmois']) AND ($_SESSION['troismois']==true)) {
		  $_SESSION['troismois']=false;
		  echo $temps_text = "Il y a plus de trois mois";
    }
    if (($row_LastCommunique['date'] <= $_SESSION['datesixmois']) AND ($row_LastCommunique['date'] >= $_SESSION['dateunan']) AND ($_SESSION['sixmois']==true)) {
		  $_SESSION['sixmois']=false;
		  echo $temps_text = "Il y a plus de six mois";
    }
    if (($row_LastCommunique['date'] <= $_SESSION['dateunan']) AND ($_SESSION['an']==true)) {
        $_SESSION['an']=false;
        echo $temps_text = "Il y a plus d'un an";
    }
    ?>
      </h4>
    </div>
    -->

    <?php if ( $row_LastCommunique['type_notification'] == "communique") {?>
    <!-- Si c'est un communique
================================================== -->
    <?php if ( $row_LastCommunique['sous_categorie'] == "com_pays") {?>
    <!-- Si c'est un commentaire sur le pays
================================================== -->
    <li class="fond-notification item">
      <div class="row-fluid">
        <div class="span1 auteur"> <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"><img class="auteur" src="<?= e($row_LastCommunique['photo_auteur']) ?>" alt="visiteur"></a> </div>
        <div class="span10"> <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
          &agrave;
          <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
          </small>
          <p><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"> <?= e($row_LastCommunique['prenom_auteur']) ?> <?= e($row_LastCommunique['nom_auteur']) ?></a> <?= e($row_LastCommunique['titre_auteur']) ?> <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['id_institution']) ?>#commentaireID<?= e($row_LastCommunique['id']) ?>"> a visit&eacute;</a> le pays <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['id_institution']) ?>"> <?= e($row_LastCommunique['institution']) ?></a></p>
        </div>
        <div class="span1 auteur"> <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['id_institution']) ?>"><img class="auteur" src="<?= e($row_LastCommunique['img_institution']) ?>" alt="pays"></a> </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['sous_categorie'] == "pays") {?>
    <!-- Si c'est un communique emmanant du pays
================================================== -->
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Nouveau communiqu&eacute;</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span2 auteur"><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"><img src="<?= e($row_LastCommunique['photo_auteur']) ?>" alt="auteur"></a> </div>
            <div class="span8 desc"> <small>le
              <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
              &agrave;
              <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"> <?= e($row_LastCommunique['prenom_auteur']) ?> <?= e($row_LastCommunique['nom_auteur']) ?></a> <?= e($row_LastCommunique['titre_auteur']) ?>  a lanc&eacute; un communiqu&eacute; au nom de son pays <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['id_institution']) ?>"> <?= e($row_LastCommunique['institution']) ?></a> intitul&eacute;&nbsp;:</p>
              <h4><a href="page-communique.php?com_id=<?= e($row_LastCommunique['id']) ?>"> <?= e($row_LastCommunique['titre']) ?> </a> </h4>
            </div>
            <div class="span2 auteur"> <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['id_institution']) ?>"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="pays"></a> </div>
        </div>
      </div>
    </li>
    <?php } ?>

    <?php if ( $row_LastCommunique['sous_categorie'] == "organisation") {?>
    <!-- Si c'est un communique d'organisation
================================================== -->
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Nouveau communiqu&eacute;</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span10 desc"> <small>le
              <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
              &agrave;
              <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p><a href="<?= route('organisation.showslug', ['id' => $row_LastCommunique['id_institution'], 'slug' => \Illuminate\Support\Str::slug($row_LastCommunique['institution'])]) ?>"><?= __s($row_LastCommunique['institution']) ?></a> a publié un communiqué :</p>
              <h4><a href="page-communique.php?com_id=<?= e($row_LastCommunique['id']) ?>"> <?= __s($row_LastCommunique['titre']) ?> </a> </h4>
            </div>
            <div class="span2 auteur"> <a href="page-pays.php?ch_pay_id=<?= __s($row_LastCommunique['id_institution']) ?>"><img src="<?= __s($row_LastCommunique['img_institution']) ?>" alt="Drapeau de <?= __s($row_LastCommunique['institution']) ?>"></a> </div>
        </div>
      </div>
    </li>
    <?php } ?>

    <?php if ( $row_LastCommunique['sous_categorie'] == "com_ville") {?>
    <!-- Si c'est un commentaire sur la ville
================================================== -->
    <li class="fond-notification item">
      <div class="row-fluid">
        <div class="span1 auteur"> <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"><img src="<?= e($row_LastCommunique['photo_auteur']) ?>" alt="visiteur"></a> </div>
        <div class="span10"> <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
          &agrave;
          <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
          </small>
          <p><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"> <?= e($row_LastCommunique['prenom_auteur']) ?> <?= e($row_LastCommunique['nom_auteur']) ?></a> <?= e($row_LastCommunique['titre_auteur']) ?> <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>#commentaireID<?= e($row_LastCommunique['id']) ?>"> a visit&eacute;</a> la ville <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>"><?= e($row_LastCommunique['institution']) ?></a></p>
        </div>
        <div class="span1 auteur">
          <?php if ($row_LastCommunique['img_institution']) {?>
          <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="armoiries"></a>
          <?php } else {?>
          <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>" alt="armoiries"><img src="assets/img/imagesdefaut/blason.jpg" alt="photo armoiries"></a>
          <?php }?>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['sous_categorie'] == "ville") {?>
    <!-- Si c'est un communique emmanant de la ville
================================================== -->
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Nouveau communiqu&eacute;</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span2 auteur"> </div>
            <div class="span8"> <small>le
              <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
              &agrave;
              <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p>La ville <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>"> <?= e($row_LastCommunique['institution']) ?></a> a publié un communiqué intitul&eacute;&nbsp;:</p>
              <h4><a href="page-communique.php?com_id=<?= e($row_LastCommunique['id']) ?>"> <?= e($row_LastCommunique['titre']) ?> </a> </h4>
            </div>
            <div class="span2 auteur">
              <?php if ($row_LastCommunique['img_institution']) {?>
              <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="armoiries"></a>
              <?php } else {?>
              <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>" alt="armoiries"><img src="assets/img/imagesdefaut/blason.jpg" alt="photo armoiries"></a>
              <?php }?>
            </div>
          </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['sous_categorie'] == "institut") { ?>
    <!-- Si c'est un communique emmanant d'un institut
================================================== -->
    <?php if ( $row_LastCommunique['id_institution'] == 1){
	  $lien_institut = "OCGC.php";
	  } elseif ( $row_LastCommunique['id_institution'] == 2){
	  $lien_institut = "geographie.php";
	  } elseif ( $row_LastCommunique['id_institution'] == 3){
	  $lien_institut = "patrimoine.php";
	  } elseif ( $row_LastCommunique['id_institution'] == 4){
	  $lien_institut = "histoire.php";
	  } elseif ( $row_LastCommunique['id_institution'] == 5){
	  $lien_institut = "economie.php";
	  } elseif ( $row_LastCommunique['id_institution'] == 6){
	  $lien_institut = "sport.php";
	  } else {
	$lien_institut = "OCGC.php"; }?>
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Nouveau communiqu&eacute;</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span2 auteur"></div>
            <div class="span8"> <small>le
              <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
              &agrave;
              <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p>Le <a href="<?php echo $lien_institut; ?>"><?= e($row_LastCommunique['institution']) ?></a> a publié un nouveau communiqué :</p>
              <h4><a href="page-communique.php?com_id=<?= e($row_LastCommunique['id']) ?>"> <?= e($row_LastCommunique['titre']) ?> </a> </h4>
            </div>
            <div class="span2 auteur">
              <?php if ($row_LastCommunique['img_institution']) {?>
              <a href="<?php echo $lien_institut; ?>"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="armoiries"></a>
              <?php } else {?>
              <a href="<?php echo $lien_institut; ?>"><img src="assets/img/imagesdefaut/blason.jpg" alt="photo armoiries"></a>
              <?php }?>
            </div>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['sous_categorie'] == "com_monument") {?>
    <!-- Si c'est un commentaire sur un monument
================================================== -->
    <li class="fond-notification item">
      <div class="row-fluid">
        <div class="span1 auteur"> <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"><img src="<?= e($row_LastCommunique['photo_auteur']) ?>"></a> </div>
        <div class="span10"> <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
          &agrave;
          <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
          </small>
          <p>Un<a href="page-monument.php?ch_pat_id=<?= e($row_LastCommunique['id_institution']) ?>#commentaireID<?= e($row_LastCommunique['id']) ?>"> nouveau message</a> a été publié sur la page de <a href="page-monument.php?ch_pat_id=<?= e($row_LastCommunique['id_institution']) ?>"><?= e($row_LastCommunique['institution']) ?></a></p>
        </div>
        <div class="span1 auteur">
          <?php if ($row_LastCommunique['img_institution']) {?>
          <a href="page-monument.php?ch_pat_id=<?= e($row_LastCommunique['id_institution']) ?>"><img style="max-width: 120px; margin-left: -4em; max-height: 50px;" src="<?= e($row_LastCommunique['img_institution']) ?>" alt="photo monument"></a>
          <?php } else {?>
          <a href="page-monument.php?ch_pat_id=<?= e($row_LastCommunique['id_institution']) ?>" alt="photo monument"><img src="assets/img/imagesdefaut/ville.jpg" alt="photo monument"></a>
          <?php }?>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['sous_categorie'] == "com_fait_his") {?>
    <!-- Si c'est un commentaire sur un fait historique
================================================== -->
    <li class="fond-notification item">
      <div class="row-fluid">
        <div class="span1 auteur"> <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"><img src="<?= e($row_LastCommunique['photo_auteur']) ?>" alt="visiteur"></a> </div>
        <div class="span10"> <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
          &agrave;
          <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
          </small>
          <p><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"> <?= e($row_LastCommunique['prenom_auteur']) ?> <?= e($row_LastCommunique['nom_auteur']) ?></a> <?= e($row_LastCommunique['titre_auteur']) ?> <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id_institution']) ?>#commentaireID<?= e($row_LastCommunique['id']) ?>"> a comment&eacute; </a> l'&eacute;l&eacute;ment historique <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id_institution']) ?>"><?= e($row_LastCommunique['institution']) ?></a></p>
        </div>
        <div class="span1 auteur">
          <?php if ($row_LastCommunique['img_institution']) {?>
          <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id_institution']) ?>"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="photo illustration"></a>
          <?php } else {?>
          <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id_institution']) ?>" alt="photo monument"><img src="assets/img/imagesdefaut/ville.jpg" alt="photo illustration"></a>
          <?php }?>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['sous_categorie'] == "com_communique") {?>
    <!-- Si c'est un commentaire de communique
================================================== -->
    <li class="fond-notification item">
      <div class="row-fluid">
        <div class="span1 auteur"> <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"><img src="<?= e($row_LastCommunique['photo_auteur']) ?>" alt="visiteur" title="auteur du communiqu&eacute;"></a></div>
        <div class="span10"> <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
          &agrave;
          <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
          </small>
          <p><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"> <?= e($row_LastCommunique['prenom_auteur']) ?> <?= e($row_LastCommunique['nom_auteur']) ?></a> <?= e($row_LastCommunique['titre_auteur']) ?> <a href="page-communique.php?com_id=<?= e($row_LastCommunique['id_element']) ?>#commentaireID<?= e($row_LastCommunique['id']) ?>">a r&eacute;agi</a> au communiqu&eacute; intitul&eacute; <a href="page-communique.php?com_id=<?= e($row_LastCommunique['id_element']) ?>"><?= e($row_LastCommunique['institution']) ?>.</a></p>
        </div>
        <div class="span1 auteur">
          <?php if ($row_LastCommunique['img_institution']) {?>
          <a href="page-communique.php?com_id=<?= e($row_LastCommunique['id_institution']) ?>"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="auteur" title="auteur du communiqu&eacute;"></a>
          <?php } else {?>
          <a href="page-communique.php?com_id=<?= e($row_LastCommunique['id_institution']) ?>" alt="pays"><img src="assets/img/imagesdefaut/personnage.jpg" alt="photo membre"></a>
          <?php }?>
        </div>
      </div>
    </li>
    <!-- Fin communique
================================================== -->
    <?php } ?>
    <?php } ?>
    <?php if ( $row_LastCommunique['type_notification'] == "pays") {?>
    <!-- Si c'est une MAJ de PAYS
================================================== -->
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Pays mis &agrave; jour</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span3">
              <?php if ($row_LastCommunique['img_institution']) {?>
              <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['id']) ?>" alt="pays"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="Drapeau du pays"></a>
              <?php } else {?>
              <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['id']) ?>" alt="pays"><img src="assets/img/imagesdefaut/drapeau.jpg" alt="Drapeau du pays"></a>
              <?php }?>
            </div>
            <div class="span9"> <small>le
              <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
              &agrave;
              <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"> <?= e($row_LastCommunique['prenom_auteur']) ?> <?= e($row_LastCommunique['nom_auteur']) ?></a> <?= e($row_LastCommunique['titre_auteur']) ?> a mis &agrave; jour la page de son pays&nbsp;:</p>
              <h4><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['id']) ?>"> <?= e($row_LastCommunique['titre']) ?> </a> </h4>
            </div>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['type_notification'] == "ville") {

        $thisPays = new \GenCity\Monde\Pays($row_LastCommunique['pays_institution']);

        ?>
    <!-- Si c'est une MAJ de VILLE
================================================== -->
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Ville mise &agrave; jour</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span3">
              <?php if ($row_LastCommunique['img_institution']) {?>
              <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id']) ?>" alt="illustration ville"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="illustration ville"></a>
              <?php } else {?>
              <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id']) ?>" alt="illustration ville"><img src="assets/img/imagesdefaut/ville.jpg" alt="illustration ville"></a>
              <?php }?>
            </div>
            <div class="span9"> <small>le
              <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
              &agrave;
              <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p>La page de la ville située à <a href="page-pays?ch_pay_id=<?= $thisPays->get('ch_pay_id') ?>"><?= __s($thisPays->get('ch_pay_nom')) ?></a> a été mise à jour :</p>
              <h4><a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>"> <?= e($row_LastCommunique['titre']) ?> </a> </h4>
            </div>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['type_notification'] == "monument") {?>
    <!-- Si c'est un nouveau monument
================================================== -->
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Nouvelle quête ouverte !</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span3">
              <?php if ($row_LastCommunique['img_institution']) {?>
              <a href="page-monument.php?ch_pat_id=<?= e($row_LastCommunique['id']) ?>" alt="illustration monument"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="illustration monument"></a>
              <?php } else {?>
              <a href="page-monument.php?ch_pat_id=<?= e($row_LastCommunique['id']) ?>" alt="illustration monument"><img src="assets/img/imagesdefaut/ville.jpg" alt="illustration monument"></a>
              <?php }?>
            </div>
            <div class="span9"> <small>le
              <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
              &agrave;
              <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p><a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>"><?= e($row_LastCommunique['institution']) ?></a> s'est lancé un nouveau défi :</p>
              <h4><a href="page-monument.php?ch_pat_id=<?= e($row_LastCommunique['id']) ?>"> <?= e($row_LastCommunique['titre']) ?> </a> </h4>
            </div>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['type_notification'] == "disp_mon") {?>
    <!-- Si c'est un nouveau monument dans une catégorie
================================================== -->
    <li class="fond-notification item">
      <div class="row-fluid">
        <div class="span2 auteur"><a href="page-monument.php?ch_pat_id=<?= e($row_LastCommunique['id']) ?>"><img src="<?= e($row_LastCommunique['photo_auteur']) ?>" alt="photo monument"></a> </div>
        <div class="span9"> <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
          &agrave;
          <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
          </small>
          <p>Un nouveau stade a été atteint dans la Quête <a href="page-monument.php?ch_pat_id=<?= e($row_LastCommunique['id']) ?>"><?= e($row_LastCommunique['nom_auteur']) ?></a> :  <a href="patrimoine.php?mon_cat_ID=<?= e($row_LastCommunique['id_institution']) ?>#monument"><?= e($row_LastCommunique['institution']) ?></a></p>
        </div>
        <div class="span1 auteur icone-categorie">
          <?php if ($row_LastCommunique['img_institution']) {?>
          <a href="patrimoine.php?mon_catID=<?= e($row_LastCommunique['id_institution']) ?>#monument"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="icone categorie" style="background-color:<?= e($row_LastCommunique['pays_institution']) ?>;"></a>
          <?php } else {?>
          <a href="patrimoine.php?mon_catID=<?= e($row_LastCommunique['id_institution']) ?>#monument"><img src="assets/img/imagesdefaut/blason.jpg" alt="icone categorie" style="background-color:<?= e($row_LastCommunique['pays_institution']) ?>;"></a>
          <?php }?>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['type_notification'] == "fait_histo") {?>
     <!-- Si c'est une periode historique
================================================== -->
    <?php if (( $row_LastCommunique['statut'] == 1) and ( $row_LastCommunique['id_element'] != NULL))  {?>
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Nouvelle p&eacute;riode historique</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span3">
              <?php if ($row_LastCommunique['img_institution']) {?>
              <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>" alt="illustration fait historique"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="illustration fait historique"></a>
              <?php } else {?>
              <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>" alt="illustration fait historique"><img src="assets/img/imagesdefaut/ville.jpg" alt="illustration fait historique"></a>
              <?php }?>
            </div>
            <div class="span9"> <small>le
              <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
              &agrave;
              <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"> <?= e($row_LastCommunique['prenom_auteur']) ?> <?= e($row_LastCommunique['nom_auteur']) ?></a> <?= e($row_LastCommunique['titre_auteur']) ?> a compl&eacute;t&eacute; <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>#histoire">l'histoire de <?= e($row_LastCommunique['institution']) ?> </a> en y ajoutant une nouvelle periode marquante&nbsp;:</p>
              <h4>du <?php echo affDate($row_LastCommunique['sous_categorie']); ?> au <?php echo affDate($row_LastCommunique['id_element']); ?></h4>
              <h4><a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>"> <?= e($row_LastCommunique['titre']) ?> </a> </h4>
            </div>
        </div>
      </div>
    </li>
        <!-- Si c'est un personnage historique
================================================== -->
    <?php } elseif ( $row_LastCommunique['statut'] == 2) {?>
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Nouveau personnage historique</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span3">
              <?php if ($row_LastCommunique['img_institution']) {?>
              <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>" alt="illustration fait historique"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="illustration fait historique"></a>
              <?php } else {?>
              <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>" alt="illustration fait historique"><img src="assets/img/imagesdefaut/ville.jpg" alt="illustration fait historique"></a>
              <?php }?>
            </div>
            <div class="span9"> <small>le
              <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
              &agrave;
              <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"> <?= e($row_LastCommunique['prenom_auteur']) ?> <?= e($row_LastCommunique['nom_auteur']) ?></a> <?= e($row_LastCommunique['titre_auteur']) ?> a compl&eacute;t&eacute; <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>#histoire">l'histoire de <?= e($row_LastCommunique['institution']) ?> </a> en y ajoutant un nouveau personnage marquant&nbsp;</p>
              <h4><a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>"> <?= e($row_LastCommunique['titre']) ?> </a></h4>
              <h4>(<?php echo affDate($row_LastCommunique['sous_categorie']); ?> - <?php echo affDate($row_LastCommunique['id_element']); ?>)</h4>
            </div>
        </div>
      </div>
    </li>
     <!-- Si c'est un fait historique
================================================== -->
    <?php } else { ?>
     <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Nouveau fait historique</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span3">
              <?php if ($row_LastCommunique['img_institution']) {?>
              <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>" alt="illustration fait historique"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="illustration fait historique"></a>
              <?php } else {?>
              <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>" alt="illustration fait historique"><img src="assets/img/imagesdefaut/ville.jpg" alt="illustration fait historique"></a>
              <?php }?>
            </div>
            <div class="span9"> <small>le
              <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
              &agrave;
              <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"> <?= e($row_LastCommunique['prenom_auteur']) ?> <?= e($row_LastCommunique['nom_auteur']) ?></a> <?= e($row_LastCommunique['titre_auteur']) ?> a compl&eacute;t&eacute; <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>#histoire">l'histoire de <?= e($row_LastCommunique['institution']) ?> </a> en y ajoutant un nouveau fait marquant&nbsp;:</p>
              <h4>Le <?php echo affDate($row_LastCommunique['sous_categorie']); ?></h4>
              <h4><a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>"> <?= e($row_LastCommunique['titre']) ?> </a> </h4>
            </div>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php } ?>
    <?php if ( $row_LastCommunique['type_notification'] == "disp_fai") {?>
    <!-- Si c'est un nouveau fait historique dans une catégorie
================================================== -->
    <li class="fond-notification item">
      <div class="row-fluid">
        <div class="span1 auteur"><a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>"><img src="<?= e($row_LastCommunique['photo_auteur']) ?>" alt="photo monument"></a> </div>
        <div class="span10"> <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
          &agrave;
          <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
          </small>
          <p>L'&eacute;l&eacute;ment historique <a href="page-fait-historique.php?ch_his_id=<?= e($row_LastCommunique['id']) ?>"><?= e($row_LastCommunique['nom_auteur']) ?></a> a rejoint la cat&eacute;gorie <a href="histoire.php?fai_catID=<?= e($row_LastCommunique['id_institution']) ?>#fait_hist"><?= e($row_LastCommunique['institution']) ?></a></p>
        </div>
        <div class="span1 auteur icone-categorie">
          <?php if ($row_LastCommunique['img_institution']) {?>
          <a href="histoire.php?fai_catID=<?= e($row_LastCommunique['id_institution']) ?>#fait_hist"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="icone categorie" style="background-color:<?= e($row_LastCommunique['pays_institution']) ?>;"></a>
          <?php } else {?>
          <a href="histoire.php?fai_catID=<?= e($row_LastCommunique['id_institution']) ?>#fait_hist"><img src="assets/img/imagesdefaut/blason.jpg" alt="icone categorie" style="background-color:<?= e($row_LastCommunique['pays_institution']) ?>;"></a>
          <?php }?>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php if ( $row_LastCommunique['type_notification'] == "disp_mem") {?>
    <!-- Si c'est un nouveau membre dans un groupe
================================================== -->
    <li class="fond-notification item">
      <div class="row-fluid">
        <div class="span1 auteur"><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"><img src="<?= e($row_LastCommunique['photo_auteur']) ?>" alt="photo monument"></a> </div>
        <div class="span10"> <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
          &agrave;
          <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
          </small>
          <p>Le membre <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['paysID_auteur']) ?>#diplomatie"><?= e($row_LastCommunique['nom_auteur']) ?> <?= e($row_LastCommunique['prenom_auteur']) ?></a> <?= e($row_LastCommunique['titre_auteur']) ?> a rejoint le groupe <a href="politique.php?mem_groupID=<?= e($row_LastCommunique['id_institution']) ?>#groupes"><?= e($row_LastCommunique['institution']) ?></a></p>
        </div>
        <div class="span1 auteur icone-categorie">
          <?php if ($row_LastCommunique['img_institution']) {?>
          <a href="politique.php?mem_groupID=<?= e($row_LastCommunique['id_institution']) ?>#groupes"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="icone categorie" style="background-color:<?= e($row_LastCommunique['pays_institution']) ?>;"></a>
          <?php } else {?>
          <a href="politique.php?mem_groupID=<?= e($row_LastCommunique['id_institution']) ?>#groupes"><img src="assets/img/imagesdefaut/blason.jpg" alt="icone categorie" style="background-color:<?= e($row_LastCommunique['pays_institution']) ?>;"></a>
          <?php }?>
        </div>
      </div>
    </li>
    <?php } ?>
       <?php if ( $row_LastCommunique['type_notification'] == "infrastructure") {

           $thisInfrastructure = Infrastructure::find($row_LastCommunique['id']);
           if(empty($thisInfrastructure) || empty($thisInfrastructure->infrastructurable)) {
               continue;
           }
           ?>
    <!-- Si c'est une nouvelle infrastructure jugee ok
================================================== -->
    <li class="fond-notification item">
      <div class="row-fluid">
        <div class="span1 auteur"><img src="<?= e($row_LastCommunique['sous_categorie']) ?>" alt="photo infrastructure"></div>
        <div class="span10"> <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommunique['date'])); ?>
          &agrave;
          <?php  echo date("G:i", strtotime($row_LastCommunique['date'])); ?>
          </small>
            <p>L'infrastructure <a href="<?= e(urlFromLegacy($thisInfrastructure->infrastructurable->accessorUrl()) . '#economie') ?>"><?= e($thisInfrastructure->nom_infra) ?></a> (<?= e($row_LastCommunique['titre']) ?>) créée dans <img src="<?= e($thisInfrastructure->infrastructurable->getFlag()) ?>" class="img-menu-drapeau"> <a href="<?= e(urlFromLegacy($thisInfrastructure->infrastructurable->accessorUrl())) ?>"><?= e($thisInfrastructure->infrastructurable->getName()) ?></a> a &eacute;t&eacute; accept&eacute;e par les juges temp&eacute;rants.</p>
        </div>
        <div class="span1 auteur icone-categorie">
          <?php if ($row_LastCommunique['img_institution']) {?>
          <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>#economie"><img src="<?= e($row_LastCommunique['img_institution']) ?>" alt="icone categorie" style="background-color:<?= e($row_LastCommunique['pays_institution']) ?>;"></a>
          <?php } else {?>
          <a href="page-ville.php?ch_pay_id=<?= e($row_LastCommunique['pays_institution']) ?>&ch_ville_id=<?= e($row_LastCommunique['id_institution']) ?>#economie"><img src="assets/img/imagesdefaut/blason.jpg" alt="icone categorie" style="background-color:<?= e($row_LastCommunique['pays_institution']) ?>;"></a>
          <?php }?>
        </div>
      </div>
    </li>
    <?php }

    if ( $row_LastCommunique['type_notification'] == "vote_ag_finished") {

        $thisProposal = new \GenCity\Proposal\Proposal($row_LastCommunique['id_element']);
        $voteList = $thisProposal->getVote();
        $decisionMaker = new \GenCity\Proposal\ProposalDecisionMaker($voteList);
        $decisionFormat = $decisionMaker->outputFormat();

        $bg_color = 'inherit';
        $text_color = 'inherit';
        $info = '';

        if(count($decisionFormat) > 1) {
            $info .= '<em>Second tour :</em> ';
        }

        $i = 0;
        foreach($decisionFormat as $thisDecision) {
            if($thisDecision['color'] === '#fafafa')
                $thisDecision['color'] = '#0a0a0a';
            $info .= '<h4 style="font-style: normal; display: inline; color: ' . $thisDecision['color'] . ';">'
                  . __s($thisDecision['intitule']) . '</h4>';
            if(++$i !== count($decisionFormat)) {
                $info .= " / ";
            }
            $bg_color = $thisDecision['color'];
            $text_color = $thisDecision['color'] !== '#fafafa' ? '#fafafa' : '#0a0a0a';
        }
        ?>
    <!-- Si c'est une proposition à l'AG terminée
================================================== -->
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Proposition votée</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span2">
              <a href="assemblee.php"><img src="http://vasel.yt/wiki/images/5/53/Logo-OCGC-AG.png" alt="Logo AG"></a>
            </div>
            <div class="span10"> <small>le
              <?= date("d/m/Y à G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
              <p>L'Assemblée générale a voté sur la proposition suivante :</p>
              <h4><a href="back/ocgc_proposal.php?id=<?= e($row_LastCommunique['id_element']) ?>"><?php echo __s($row_LastCommunique['titre']) ?></a></h4>
              <div class="btn-margin-left">Résultat : <?= $info ?></div>
            </div>
        </div>
      </div>
    </li>
    <?php }

    if ( $row_LastCommunique['type_notification'] == "vote_ag_new") {

        ?>
    <!-- Si c'est une proposition à l'AG nouvellement créée
================================================== -->
    <li class="item">
      <div class="row-fluid">
          <div class="titre-gris">
            <h3>Nouvelle proposition</h3>
          </div>
          <div class="row-fluid fond-notification">
            <div class="span2">
              <a href="assemblee.php"><img src="http://vasel.yt/wiki/images/5/53/Logo-OCGC-AG.png" alt="Logo AG"></a>
            </div>
            <div class="span8"> <small>le
              <?= date("d/m/Y à G:i", strtotime($row_LastCommunique['date'])); ?>
              </small>
                <p><a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['id_institution']) ?>"><?= __s($row_LastCommunique['pays_institution']) ?></a> a créé une nouvelle proposition portant sur le thème suivant :</p>
              <h4><a href="back/ocgc_proposal.php?id=<?= e($row_LastCommunique['id_element']) ?>"><?php echo __s($row_LastCommunique['titre']) ?></a></h4>
            </div>
            <div class="span2">
              <a href="page-pays.php?ch_pay_id=<?= e($row_LastCommunique['id_institution']) ?>">
                  <img src="<?= __s($row_LastCommunique['img_institution']) ?>"
                       alt="Logo pays <?= __s($row_LastCommunique['pays_institution']) ?>">
              </a>
            </div>
        </div>
      </div>
    </li>
    <?php }


} while ($row_LastCommunique = mysql_fetch_assoc($LastCommunique));?>


  </ul>
  <?php if ($pageNum_LastCommunique < $totalPages_LastCommunique) { // Show if not last page ?>
  <div class="pull-center">
    <p>&nbsp;</p>
    <button id="next" class="btn btn-primary" onclick="this.style.visibility = 'hidden'">Afficher la suite</button>
  </div>
  <?php } else { // Show if last page ?>
  <div class="pull-center">
    <p>&nbsp;</p>
    <small class="pull-center">Il n'y a plus d'actualit&eacute;s disponibles</small>
    <p>&nbsp;</p>
  </div>
  <?php } // Show if last page ?>
  <div id="loadmoreajaxloader" style="display:none; text-align: center; margin: 0 auto;">
      <img src="assets/img/ajax-loader.gif" />
  </div>

<script>
$("button").click(function(){
	$('div#loadmoreajaxloader').show();
    $.ajax({
        url: "<?php printf("last_MAJ.php?pageNum_LastCommunique=%d%s#postswrapper", $currentPage, min($totalPages_LastCommunique, $pageNum_LastCommunique + 1), $queryString_LastCommunique); ?>",
        success: function(html) {
            if(html) {
                $("#postswrapper").append(html);
                $('div#loadmoreajaxloader').hide();
            } else {
                $('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
            }
        }
    });
});
</script>
<?php
mysql_free_result($LastCommunique);
?>
