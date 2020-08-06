<?php

//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');

if ($_SESSION['statut'])
{
} else {
// Redirection vers page de connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ' . legacyPage('connexion'));
exit();
}
$_SESSION['last_work'] = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php'.'?'.$_SERVER['QUERY_STRING'];

//Recuperation variables
$colname_User = $_SESSION['Temp_userID'];
if (isset($_REQUEST['userID'])) {
$_SESSION['Temp_userID'] = $_REQUEST['userID'];
$colname_User = $_SESSION['Temp_userID'];
unset($_REQUEST['userID']);
}


$query_User = sprintf("SELECT * FROM users WHERE ch_use_id = %s", GetSQLValueString($colname_User, "int"));
$User = mysql_query($query_User, $maconnexion) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

//Mise a jour parametres donnees personnelles
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ProfilUser")) {
  include(DEF_ROOTPATH . "php/config.php");
  if (isset ($_POST['ch-use_password'])) {
  $hashed_password = md5($_POST['ch-use_password'].$salt);
  unset($_POST['ch-use_password']);
  } else {
  $hashed_password = $row_User['ch_use_password'];
  }
  
  
  if($_POST['ch_use_acces_Checkbox']==1){
  $banni="";
  } else {
 $banni=1;
  }
  
  $updateSQL = sprintf("UPDATE users SET ch_use_acces=%s, ch_use_statut=%s, ch_use_paysID=%s, ch_use_login=%s, ch_use_password=%s, ch_use_mail=%s WHERE ch_use_id=%s",
                       GetSQLValueString($banni, "int"),
                       GetSQLValueString($_POST['ch_use_statut'], "int"),
                       GetSQLValueString($_POST['ch_use_paysID'], "int"),
                       GetSQLValueString($_POST['ch_use_login'], "text"),
                       GetSQLValueString($hashed_password, "text"),
                       GetSQLValueString($_POST['ch_use_mail'], "text"),
                       GetSQLValueString($_POST['ch_use_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

  $updateGoTo = DEF_URI_PATH . "back/membre-modifier_back.php";
  appendQueryString($updateGoTo);
  header(sprintf("Location: %s", $updateGoTo));
 exit;
}

//Mise a jour profil infos personnage
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "InfoUser")) {

	$updateSQL = sprintf("UPDATE personnage SET nom_personnage = %s, predicat = %s,
                      prenom_personnage = %s, biographie = %s, titre_personnage = %s
                      WHERE id = %s",
        GetSQLValueString($_POST['ch_use_nom_dirigeant'], 'text'),
        GetSQLValueString($_POST['ch_use_predicat_dirigeant'], 'text'),
        GetSQLValueString($_POST['ch_use_prenom_dirigeant'], 'text'),
        GetSQLValueString($_POST['ch_use_biographie_dirigeant'], "text"),
        GetSQLValueString($_POST['ch_use_titre_dirigeant'], "text"),
        GetSQLValueString($_POST['personnage_id'], "int"));

	$selectSQL = mysql_query(sprintf('SELECT entity_id FROM personnage WHERE id = %s',
        GetSQLValueString($_POST['personnage_id'], 'int')));
	$personnageData = mysql_fetch_assoc($selectSQL);
	$thisPays = new \GenCity\Monde\Pays($personnageData['entity_id']);

    
    $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

    $updateGoTo = DEF_URI_PATH . "back/page_pays_back.php?paysID={$thisPays->ch_pay_id}";
    appendQueryString($updateGoTo);
    header(sprintf("Location: %s", $updateGoTo));
 exit;
    exit;
}


//Ajout de groupe
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-groupe")) {
  $insertSQL = sprintf("INSERT INTO membres_groupes (ch_mem_group_label, ch_mem_group_statut, ch_mem_group_date, ch_mem_group_mis_jour, ch_mem_group_nb_update, ch_mem_group_nom, ch_mem_group_desc, ch_mem_group_icon, ch_mem_group_couleur) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_mem_group_label'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_statut'], "int"),
                       GetSQLValueString($_POST['ch_mem_group_date'], "date"),
                       GetSQLValueString($_POST['ch_mem_group_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_mem_group_nb_update'], "int"),
                       GetSQLValueString($_POST['ch_mem_group_nom'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_desc'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_icon'], "text"),
					   GetSQLValueString($_POST['ch_mem_group_couleur'], "text"));

  
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());
$nouveau_groupe_id = mysql_insert_id(); 

$insertSQL = sprintf("INSERT INTO dispatch_mem_group (ch_disp_group_id, ch_disp_MG_label, ch_disp_mem_id, ch_disp_MG_date, ch_disp_mem_statut) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($nouveau_groupe_id, "int"),
                       GetSQLValueString("disp_mem", "text"),
                       GetSQLValueString($colname_User, "int"),
                       GetSQLValueString($_POST['ch_mem_group_date'], "date"),
                       GetSQLValueString("2", "int"));
					   
  
  $Result2 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());
  $insertGoTo = DEF_URI_PATH . "back/membre-modifier_back.php";
  appendQueryString($insertGoTo);
  header(sprintf("Location: %s", $insertGoTo));
 exit;
}

//requete liste categories membres pour pouvoir selectionner la categorie 

$query_liste_groupe = sprintf("SELECT ch_disp_mem_statut, ch_mem_group_ID, ch_mem_group_nom, ch_mem_group_desc, ch_mem_group_icon, ch_mem_group_couleur, (SELECT GROUP_CONCAT(membres.ch_disp_mem_id) FROM dispatch_mem_group as membres WHERE grouplist.ch_disp_group_id = membres.ch_disp_group_id AND membres.ch_disp_mem_statut != 3) AS listgroup
FROM dispatch_mem_group as grouplist LEFT OUTER JOIN membres_groupes ON ch_disp_group_id = ch_mem_group_ID WHERE ch_disp_mem_id=%s AND (ch_disp_mem_statut=1 OR ch_disp_mem_statut=2)", GetSQLValueString($colname_User, "int"));
$liste_groupe = mysql_query($query_liste_groupe, $maconnexion) or die(mysql_error());
$row_liste_groupe = mysql_fetch_assoc($liste_groupe);
$totalRows_liste_groupe = mysql_num_rows($liste_groupe);

//requete liste groupes du membre pour les notifications

$query_liste_notifications = sprintf("SELECT ch_disp_mem_statut, ch_disp_group_id FROM dispatch_mem_group WHERE ch_disp_mem_id=%s AND ch_disp_mem_statut=2", GetSQLValueString($colname_User, "int"));
$liste_notifications = mysql_query($query_liste_notifications, $maconnexion) or die(mysql_error());
$row_liste_notifications = mysql_fetch_assoc($liste_notifications);
$totalRows_liste_notifications = mysql_num_rows($liste_notifications);

//requete liste categories membres pour pouvoir selectionner la categorie 

$query_liste_mem_group2 = sprintf("SELECT * FROM membres_groupes LEFT OUTER JOIN dispatch_mem_group ON ch_disp_mem_id = %s AND ch_disp_group_id = ch_mem_group_ID ORDER BY ch_mem_group_mis_jour DESC", GetSQLValueString($colname_User, "int"));
$liste_mem_group2 = mysql_query($query_liste_mem_group2, $maconnexion) or die(mysql_error());
$row_liste_mem_group2 = mysql_fetch_assoc($liste_mem_group2);
$totalRows_liste_mem_group2 = mysql_num_rows($liste_mem_group2);


//requete liste  membres d'une catégorie
$maxRows_classer_mem = 10;
$pageNum_classer_mem = 0;
if (isset($_GET['pageNum_classer_mem'])) {
  $pageNum_classer_mem = $_GET['pageNum_classer_mem'];
}
$startRow_classer_mem = $pageNum_classer_mem * $maxRows_classer_mem;

$colname_classer_mem = "-1";
if (isset($_GET['mem_groupID'])) {
	if ($_GET['mem_groupID'] == "") {
	$colname_classer_mem = NULL;
} else {
  $colname_classer_mem = $_GET['mem_groupID'];
} } else {
  $colname_classer_mem = NULL;
} 

//requete infos groupe choisi 

$query_infoGroupe = sprintf("SELECT ch_mem_group_ID, ch_mem_group_nom, ch_mem_group_desc, ch_mem_group_icon, ch_mem_group_couleur FROM membres_groupes WHERE ch_mem_group_ID = %s", GetSQLValueString($colname_classer_mem, "int"));
$infoGroupe = mysql_query($query_infoGroupe, $maconnexion) or die(mysql_error());
$row_infoGroupe = mysql_fetch_assoc($infoGroupe);
$totalRows_infoGroupe = mysql_num_rows($infoGroupe);


//requete infos statut user dans groupe choisi 

$query_statutGroupeChoisi = sprintf("SELECT ch_disp_mem_statut, ch_disp_mem_id, ch_disp_group_id FROM dispatch_mem_group WHERE ch_disp_group_id = %s AND ch_disp_mem_id = %s", GetSQLValueString($colname_classer_mem, "int"), GetSQLValueString($colname_User, "int"));
$statutGroupeChoisi = mysql_query($query_statutGroupeChoisi, $maconnexion) or die(mysql_error());
$row_statutGroupeChoisi = mysql_fetch_assoc($statutGroupeChoisi);
$totalRows_statutGroupeChoisi = mysql_num_rows($statutGroupeChoisi);


//requete liste  membres d'une catégorie

$query_classer_mem = sprintf("SELECT ch_disp_MG_id, ch_disp_mem_id, ch_disp_mem_statut, ch_use_id, ch_use_predicat_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_titre_dirigeant, ch_use_last_log, ch_use_lien_imgpersonnage, ch_use_paysID
FROM dispatch_mem_group 
INNER JOIN users ON ch_disp_mem_id = ch_use_id 
WHERE ch_disp_group_id = %s
ORDER BY ch_disp_mem_statut DESC", GetSQLValueString($colname_classer_mem, "int"));
$classer_mem = mysql_query($query_classer_mem, $maconnexion) or die(mysql_error());
$row_classer_mem = mysql_fetch_assoc($classer_mem);
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Gérer le compte</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="../assets/css/colorpicker.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
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
<!-- Color Picker  -->
<script src="../assets/js/bootstrap-colorpicker.js" type="text/javascript"></script>
<!-- MODAL -->
<script src="../assets/js/bootstrap-modalmanager.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
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
<?php include(DEF_ROOTPATH . 'php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div class="container" id="overview">

<!-- Page CONTENT
    ================================================== -->
<section class="corps-page">
<!-- NOTIFICATIONS
        ================================================== -->
<?php
  //recherches demande d'adhesion de membre pour notification 
	   do { 

$query_notifications = sprintf("SELECT ch_use_id, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant,  ch_disp_mem_statut, ch_disp_MG_id, ch_mem_group_nom FROM dispatch_mem_group INNER JOIN users ON ch_disp_mem_id = ch_use_id INNER JOIN membres_groupes ON ch_mem_group_ID = ch_disp_group_id WHERE ch_disp_group_id = %s AND ch_disp_mem_statut=3", GetSQLValueString($row_liste_notifications['ch_disp_group_id'], "int"));
$notifications = mysql_query($query_notifications, $maconnexion) or die(mysql_error());
$row_notifications  = mysql_fetch_assoc($notifications );
$totalRows_notifications  = mysql_num_rows($notifications );
if (($row_notifications != NULL) OR ($row_notifications != "") ) {
do {  ?>
  <div class="alert"> <a class="pull-right btn btn-primary" href="../php/groupe-supprimmer-membre-modal.php?ch_disp_MG_id=<?php echo $row_notifications['ch_disp_MG_id']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="supprimer la demande de ce membre">Refuser</a> <a class="pull-right btn btn-primary" href="../php/groupe-modifier-membre-modal.php?ch_disp_MG_id=<?php echo $row_notifications['ch_disp_MG_id']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="choisir le statut de ce membre">Accepter</a> <img src="<?php echo $row_notifications['ch_use_lien_imgpersonnage'] ; ?>" class="Icone-thumb" width="50px"/> <strong><?php echo $row_notifications['ch_use_predicat_dirigeant'] ; ?> <?php echo $row_notifications['ch_use_prenom_dirigeant'] ; ?> <?php echo $row_notifications['ch_use_nom_dirigeant'] ; ?></strong> <em><?php echo $row_notifications['ch_use_titre_dirigeant'] ; ?></em> a fait une demande pour rejoindre le groupe <?php echo $row_notifications['ch_mem_group_nom'] ; ?>. </div>
  <?php
} while ($row_notifications = mysql_fetch_assoc($notifications));
}
 } while ($row_liste_notifications = mysql_fetch_assoc($liste_notifications));
   ?>
<!-- Titre
        ================================================== -->
<div id="titre_institut" class="titre-vert anchor"> 
  <h1>G&eacute;rer mon compte</h1>
</div>

<?php renderElement('errormsgs'); ?>

<!-- Formulaires de modification du personnage
     ================================================== -->
<?php include(DEF_ROOTPATH . 'php/membre-modifier.php'); ?>
<!-- liste des groupes du membre
     ================================================== -->

<?php /* ?>
<div class="titre-gris">
  <h3>Mes groupes</h3>
</div>
<div id="liste-categories" class="anchor">
  <ul class="listes">
    <?php
	if ($row_liste_groupe) {
	  $i=0;
	   do { 
		$listgroup = $row_liste_groupe['listgroup'];
			if (($row_liste_groupe['listgroup']!= "") AND ($row_liste_groupe['listgroup']!= NULL)) {

$query_liste_membres_groupe = sprintf("SELECT ch_use_id, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_titre_dirigeant, ch_disp_mem_statut, ch_use_last_log, ch_use_paysID, ch_disp_MG_id FROM users INNER JOIN dispatch_mem_group ON ch_disp_mem_id = ch_use_id AND ch_disp_group_id = %s  WHERE ch_use_id In ($listgroup) ORDER BY ch_use_last_log DESC ", GetSQLValueString($row_liste_groupe['ch_mem_group_ID'], "int"));
$liste_membres_groupe = mysql_query($query_liste_membres_groupe, $maconnexion) or die(mysql_error());
$row_liste_membres_groupe = mysql_fetch_assoc($liste_membres_groupe);
$totalRows_liste_membres_groupe = mysql_num_rows($liste_membres_groupe);
$i++;
			 }
		?>
    <li class="row-fluid"> 
      <!-- ICONE groupe -->
      <div class="span2 icone-categorie"><img src="<?php echo $row_liste_groupe['ch_mem_group_icon']; ?>" alt="icone <?php echo $row_liste_groupe['ch_mem_group_nom']; ?>" style="background-color:<?php echo $row_liste_groupe['ch_mem_group_couleur']; ?>;"></div>
      <!-- contenu groupe -->
      <div class="span10 info-listes"> 
        <!-- Boutons modifier / supprimer -->
        <?php if ($row_liste_groupe['ch_disp_mem_statut']==2) { ?>
        <a class="pull-right" href="../php/groupe-supprimmer-modal.php?mem_group_ID=<?php echo $row_liste_groupe['ch_mem_group_ID']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="supprimer ce groupe"><i class="icon-remove"></i></a> <a class="pull-right" href="../php/groupe-modifier-modal.php?mem_group_ID=<?php echo $row_liste_groupe['ch_mem_group_ID']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="modifier ce groupe"><i class="icon-pencil"></i></a>
        <?php } ?>
        <!-- Desc categorie -->
        <h4><?php echo $row_liste_groupe['ch_mem_group_nom']; ?></h4>
        <p><?php echo $row_liste_groupe['ch_mem_group_desc']; ?></p>
      </div>
      <!-- Bouton MP --> 
      <a class="btn btn-primary btn-ssmarge pull-right" href="../php/groupe-mp.php?ch_mem_group_ID=<?php echo $row_liste_groupe['ch_mem_group_ID']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="Envoyez un message aux membres de ce groupe"><i class="icon-share icon-white"></i> MP</a> </li>
    <?php
        if (($row_liste_groupe['listgroup']!= "") AND ($row_liste_groupe['listgroup']!= NULL)) { ?>
    <div class="accordion" id="accordion<?php echo $i; ?>">
      <div class="accordion-group">
        <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion<?php echo $i; ?>" href="#collapse<?php echo $i; ?>"> Membres de ce groupe </a> </div>
        <div id="collapse<?php echo $i; ?>" class="accordion-body collapse">
          <div class="accordion-inner">
            <ul class="listes">
              <?php
         do { ?>
                <!-- liste membres du groupe -->
                <li class="row-fluid">
                  <div class="span1"><a href="../page-pays.php?ch_pay_id=<?php echo $row_liste_membres_groupe['ch_use_paysID']; ?>#diplomatie" title="voir le profil de ce dirigeant"><img src="<?php echo $row_liste_membres_groupe['ch_use_lien_imgpersonnage']; ?>" alt="lien"></a> </div>
                  <div class="span3"> <?php echo $row_liste_membres_groupe['ch_use_predicat_dirigeant']; ?> <?php echo $row_liste_membres_groupe['ch_use_prenom_dirigeant']; ?> <?php echo $row_liste_membres_groupe['ch_use_nom_dirigeant'];?>  <?php echo $row_liste_membres_groupe['ch_use_titre_dirigeant']; ?></div>
                  <div class="span2">
                    <?php if ($row_liste_membres_groupe['ch_disp_mem_statut']==1) { ?>
                    <p>Membre</p>
                    <?php } ?>
                    <?php if ($row_liste_membres_groupe['ch_disp_mem_statut']==2) { ?>
                    <p>Administrateur</p>
                    <?php } ?>
                  </div>
                  <div class="span4">Derni&egrave;re connexion : le <?php echo date("d/m/Y à G:i:s", strtotime($row_liste_membres_groupe['ch_use_last_log'])); ?></div>
                  <div class="span1">
                    <?php if (($row_liste_groupe['ch_disp_mem_statut']==2) OR ( $row_liste_membres_groupe['ch_use_id'] == $_SESSION['user_ID']) OR ($_SESSION['statut'] >= 20)) {?>
                    <!-- Boutons supprimer membre du groupe --> 
                    <a class="pull-right" href="../php/groupe-supprimmer-membre-modal.php?ch_disp_MG_id=<?php echo $row_liste_membres_groupe['ch_disp_MG_id']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="enlever ce membre de ce groupe"><i class="icon-remove"></i></a>
                    <?php } ?>
                    <?php if (($row_liste_groupe['ch_disp_mem_statut']==2) OR ($_SESSION['statut'] >= 20)) {?>
                    <a class="pull-right" href="../php/groupe-modifier-membre-modal.php?ch_disp_MG_id=<?php echo $row_liste_membres_groupe['ch_disp_MG_id']; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="modifier le statut de ce membre"><i class="icon-edit"></i></a>
                    <?php } ?>
                  </div>
                </li>
                <?php } while ($row_liste_membres_groupe = mysql_fetch_assoc($liste_membres_groupe));?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <?php }?>
    <?php } while ($row_liste_groupe = mysql_fetch_assoc($liste_groupe)); ?>
  </ul>
</div>
<?php } ?>
<!-- Modal et script -->
<div class="modal container fade" id="Modal-Groupe" data-width="760"></div>
<script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Groupe').modal('hide');
});
</script> 
<!-- Ajouter un groupe
        ================================================== --> 
<!-- Button to trigger modal --> 
<a href="#ajouter-cat" role="button" class="btn btn-primary btn-margin-left" title="Ajouter une cat&eacute;gorie" data-toggle="modal">Cr&eacute;er un nouveau groupe</a> 
<!-- Modal -->
<div id="ajouter-cat" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-width="760">
  <form action="<?php echo $editFormAction; ?>" name="ajout-categorie" method="POST" class="form-horizontal" id="ajout-groupe">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Ajouter un nouveau groupe de membres</h3>
    </div>
    <div class="modal-body"> 
      <!-- Boutons cachés -->
      <?php 
				  $now= date("Y-m-d G:i:s");?>
      <input name="ch_mem_group_label" type="hidden" value="mem_group">
      <input name="ch_mem_group_date" type="hidden" value="<?php echo $now; ?>">
      <input name="ch_mem_group_mis_jour" type="hidden" value="<?php echo $now; ?>">
      <input name="ch_mem_group_nb_update" type="hidden" value=0 >
      <!-- Statut -->
      <div id="spryradio1" class="control-group">
        <div class="control-label">Statut <a href="#" rel="clickover" title="Statut du groupe" data-content="
    Visible : ce groupe sera visible sur la partie publique du site.
    Invisible : ce groupe sera invisible sur la partie publique du site."><i class="icon-info-sign"></i></a></div>
        <div class="controls">
          <label>
            <input type="radio" name="ch_mem_group_statut" value="1" id="ch_mem_group_statut_1" checked="CHECKED">
            visible</label>
          <label>
            <input name="ch_mem_group_statut" type="radio" id="ch_mem_group_statut_2" value="2">
            invisible</label>
          <span class="radioRequiredMsg">Choisissez un statut pour ce groupe</span></div>
      </div>
      <!-- Nom-->
      <div id="sprytextfield2" class="control-group">
        <label class="control-label" for="ch_mem_group_nom">Nom du groupe <a href="#" rel="clickover" title="Nom du groupe" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier le groupe dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
        <div class="controls">
          <input class="input-xlarge" type="text" id="ch_mem_group_nom" name="ch_mem_group_nom">
          <br>
          <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">30 caract&egrave;res max.</span></div>
      </div>
      <!-- Icone -->
      <div id="sprytextfield3" class="control-group">
        <label class="control-label" for="ch_mem_group_icon">Ic&ocirc;ne <a href="#" rel="clickover" title="Ic&ocirc;ne" data-content="L'ic&ocirc;ne sert &agrave; repr&eacute;senter le groupe dans l'ensemble du site. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
        <div class="controls">
          <input class="input-xlarge" type="text" name="ch_mem_group_icon" id="ch_mem_group_icon" value="">
          <br>
          <span class="textfieldRequiredMsg">une ic&ocirc;ne est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
      </div>
      <!-- Couleur -->
      <div id="" class="control-group">
        <label class="control-label" for="ch_mem_group_icon">Couleur <a href="#" rel="clickover" title="Couleur" data-content="Choisissez une couleur de fond pour ce groupe"><i class="icon-info-sign"></i></a></label>
        <div class="controls">
          <div class="input-append color" data-color="#06C" data-color-format="hex" id="cp3">
            <input type="text" class="span2" value="" name="ch_mem_group_couleur" id="ch_mem_group_couleur">
            <span class="add-on"><i style="background-color: #06C)"></i></span> </div>
        </div>
      </div>
      <!-- Description -->
      <div id="sprytextarea1" class="control-group">
        <label class="control-label" for="ch_mem_group_desc">Description <a href="#" rel="clickover" title="Description" data-content="Donnez en quelques lignes des informations qui permettrons de comprendre l'objet de ce groupe. 400 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
        <div class="controls">
          <textarea rows="6" name="ch_mem_group_desc" class="input-xlarge" id="ch_mem_group_desc"></textarea>
          <br>
          <span class="textareaMaxCharsMsg">400 caract&egrave;res max.</span></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
      <button type="submit" class="btn btn-primary">Enregistrer</button>
      <input type="hidden" name="MM_insert" value="ajout-groupe">
    </div>
  </form>
</div>
<!-- Liste des autres groupes
        ================================================== -->
<div class="titre-vert anchor" id="classer-membres"> 
  <h1>Rejoignez des groupes</h1>
</div>
<div class="row-fluid"> 
  <!-- Liste pour choix de la categories -->
  <div id="select-categorie">
    <form action="membre-modifier_back.php#classer-membres" method="GET">
      <select name="mem_groupID" id="mem_groupID" onchange="this.form.submit()">
        <option value="" <?php if ($colname_classer_mem == NULL) {?>selected<?php } ?>>S&eacute;lectionnez un groupe&nbsp;</option>
        <?php do { ?>
        <option value="<?php echo $row_liste_mem_group2['ch_mem_group_ID']; ?>" <?php if ($colname_classer_mem == $row_liste_mem_group2['ch_mem_group_ID']) {?>selected<?php } ?>><?php echo $row_liste_mem_group2['ch_mem_group_nom']; ?></option>
        <?php } while ($row_liste_mem_group2 = mysql_fetch_assoc($liste_mem_group2)); ?>
      </select>
    </form>
  </div>
  <!-- Infos sur groupe choisi -->
  <?php if ($colname_classer_mem == NULL) {?>
  <div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <p>Sélectionnez ci-dessus un groupe pour obtenir plus d'information et &eacute;ventuellement le rejoindre</p>
  </div>
  <?php } else { ?>
  <div class="well"> 
    <!-- ICONE groupe -->
    <div class="span2 icone-categorie"><img src="<?php echo $row_infoGroupe['ch_mem_group_icon']; ?>" alt="icone <?php echo $row_infoGroupe['ch_mem_group_nom']; ?>" style="background-color:<?php echo $row_infoGroupe['ch_mem_group_couleur']; ?>;"></div>
    <!-- contenu groupe -->
    <div class="span10 info-listes"> 
      <!-- Desc categorie -->
      <h4><?php echo $row_infoGroupe['ch_mem_group_nom']; ?></h4>
      <p><?php echo $row_infoGroupe['ch_mem_group_desc']; ?></p>
    </div>
    <!-- Bouton Adhesion -->
    <?php if ($row_statutGroupeChoisi['ch_disp_mem_statut']== NULL) {?>
    <a class="btn btn-primary" href="../php/groupe-ajouter-membre-modal.php?mem_group_id=<?php echo $colname_classer_mem; ?>&membre_id=<?php echo $colname_User; ?>" data-toggle="modal" data-target="#Modal-Groupe" title="Demander &agrave; rejoindre ce groupe">Rejoindre ce groupe</a>
    <?php } else { ?>
    <a class="btn btn-primary disabled">&nbsp;Vous &ecirc;tes d&eacute;j&agrave; membre de ce groupe</a>
    <?php } ?>
  </div>
  <?php } ?>
  <!-- Liste des membres du groupe choisi -->
  <?php
        if ($colname_classer_mem != NULL) { ?>
  <div class="accordion" id="accordionMembresGroupeChoisi">
    <div class="accordion-group">
      <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionMembresGroupeChoisi" href="#collapseMembresGroupeChoisi"> Membres de ce groupe </a> </div>
      <div id="collapseMembresGroupeChoisi" class="accordion-body collapse in">
        <div class="accordion-inner">
          <ul class="listes">
            <?php
         do { ?>
              <!-- liste membres du groupe -->
              <li class="row-fluid">
                <div class="span1"><a href="../page-pays.php?ch_pay_id=<?php echo $row_classer_mem['ch_use_paysID']; ?>#diplomatie" title="voir le profil de ce dirigeant"><img src="<?php echo $row_classer_mem['ch_use_lien_imgpersonnage']; ?>" alt="lien"></a> </div>
                <div class="span3"><?php echo $row_classer_mem['ch_use_predicat_dirigeant']; ?> <?php echo $row_classer_mem['ch_use_prenom_dirigeant']; ?> <?php echo $row_classer_mem['ch_use_nom_dirigeant'];?> <?php echo $row_classer_mem['ch_use_titre_dirigeant']; ?></div>
                <div class="span2">
                  <?php if ($row_classer_mem['ch_disp_mem_statut']==1) { ?>
                  <p>Membre</p>
                  <?php } elseif ($row_classer_mem['ch_disp_mem_statut']==2) { ?>
                  <p>Administrateur</p>
                  <?php } else { ?>
                  <p>Demande en attente</p>
                  <?php } ?>
                </div>
                <div class="span4">Derni&egrave;re connexion : le <?php echo date("d/m/Y à G:i:s", strtotime($row_classer_mem['ch_use_last_log'])); ?></div>
              </li>
              <?php } while ($row_classer_mem = mysql_fetch_assoc($classer_mem));?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php }?>
  <!-- Modal et script -->
  <div class="modal container fade" id="#Modal-Groupe" data-width="760"></div>
  <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Groupe').modal('hide');
});
</script>

<?php */ ?>
</div>
<!-- END CONTENT
    ================================================== --> 

<!-- Footer
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/footerback.php'); ?>
</body>
</html>
<script type="text/javascript">
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {minChars:2, maxChars:30, validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "url", {minChars:2, maxChars:250, validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {maxChars:400, validateOn:["change"], isRequired:false, useCharacterMasking:false});
</script>
<?php
mysql_free_result($liste_groupe);
mysql_free_result($liste_notifications);
mysql_free_result($liste_mem_group2);
mysql_free_result($infoGroupe);
mysql_free_result($statutGroupeChoisi);
mysql_free_result($classer_mem);?>