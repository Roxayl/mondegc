<?php

use App\Models\CustomUser;
use App\Models\Pays;
use App\Notifications\PaysRegistered;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;


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

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "InfoHeader")) {
	if ($_POST['ch_pay_emplacement'] >= 3 and $_POST['ch_pay_emplacement'] <= 4 ){ $ch_pay_continent = "RFGC";}
	if ($_POST['ch_pay_emplacement'] >= 5 and $_POST['ch_pay_emplacement'] < 14 ){ $ch_pay_continent = "Aurinea";}
	if ($_POST['ch_pay_emplacement'] < 3 ){ $ch_pay_continent = "Aurinea";}
	if ($_POST['ch_pay_emplacement'] >= 14 and $_POST['ch_pay_emplacement'] < 18 ){ $ch_pay_continent = "Oceania";}
	if ($_POST['ch_pay_emplacement'] >= 18 and $_POST['ch_pay_emplacement'] < 24 ){ $ch_pay_continent = "Volcania";}
	if ($_POST['ch_pay_emplacement'] >= 24 and $_POST['ch_pay_emplacement'] <= 27 ){ $ch_pay_continent = "Aldesyl";}
	if ($_POST['ch_pay_emplacement'] >= 27 and $_POST['ch_pay_emplacement'] <= 42 ){ $ch_pay_continent = "Philicie";}
	if( $_POST['ch_pay_emplacement'] > 42 and $_POST['ch_pay_emplacement'] <= 56 ){ $ch_pay_continent = "Aldesyl";}
	if( $_POST['ch_pay_emplacement'] >= 56 and $_POST['ch_pay_emplacement'] <= 57 ){ $ch_pay_continent = "Volcania";}
	if ($_POST['ch_pay_emplacement'] >= 57 and $_POST['ch_pay_emplacement'] <= 58 ){ $ch_pay_continent = "Aldesyl";}
	if ($_POST['ch_pay_emplacement'] >= 59){ $ch_pay_continent = "Volcania";}

  $insertSQL = sprintf("INSERT INTO pays (ch_pay_id, ch_pay_label, ch_pay_publication, ch_pay_emplacement, ch_pay_lien_forum, lien_wiki, ch_pay_continent, ch_pay_nom, ch_pay_devise, ch_pay_lien_imgheader, ch_pay_lien_imgdrapeau, ch_pay_date, ch_pay_mis_jour, ch_pay_nb_update, ch_pay_forme_etat, ch_pay_capitale, ch_pay_langue_officielle, ch_pay_monnaie, ch_pay_header_presentation, ch_pay_text_presentation, ch_pay_header_geographie, ch_pay_text_geographie, ch_pay_header_politique, ch_pay_text_politique, ch_pay_header_histoire, ch_pay_text_histoire, ch_pay_header_economie, ch_pay_text_economie, ch_pay_header_transport, ch_pay_text_transport, ch_pay_header_sport, ch_pay_text_sport, ch_pay_header_culture, ch_pay_text_culture, ch_pay_header_patrimoine, ch_pay_text_patrimoine, ch_pay_budget_carte, ch_pay_industrie_carte, ch_pay_commerce_carte, ch_pay_agriculture_carte, ch_pay_tourisme_carte, ch_pay_recherche_carte, ch_pay_environnement_carte, ch_pay_education_carte, ch_pay_population_carte, ch_pay_emploi_carte ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_pay_id'], "int"),
                       GetSQLValueString($_POST['ch_pay_label'], "text"),
                       GetSQLValueString($_POST['ch_pay_publication'], "int"),
                       GetSQLValueString($_POST['ch_pay_emplacement'], "int"),
                       GetSQLValueString($_POST['ch_pay_lien_forum'], "text"),
                       GetSQLValueString($_POST['lien_wiki'], "text"),
					   GetSQLValueString($ch_pay_continent, "text"),
                       GetSQLValueString($_POST['ch_pay_nom'], "text"),
                       GetSQLValueString($_POST['ch_pay_devise'], "text"),
                       GetSQLValueString($_POST['ch_pay_lien_imgheader'], "text"),
                       GetSQLValueString($_POST['ch_pay_lien_imgdrapeau'], "text"),
                       GetSQLValueString($_POST['ch_pay_date'], "date"),
                       GetSQLValueString($_POST['ch_pay_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_pay_nb_update'], "int"),
                       GetSQLValueString($_POST['ch_pay_forme_etat'], "text"),
                       GetSQLValueString($_POST['ch_pay_capitale'], "text"),
                       GetSQLValueString($_POST['ch_pay_langue_officielle'], "text"),
                       GetSQLValueString($_POST['ch_pay_monnaie'], "text"),
                       GetSQLValueString($_POST['ch_pay_header_presentation'], "text"),
                       GetSQLValueString($_POST['ch_pay_text_presentation'], "text"),
                       GetSQLValueString($_POST['ch_pay_header_geographie'], "text"),
                       GetSQLValueString($_POST['ch_pay_text_geographie'], "text"),
                       GetSQLValueString($_POST['ch_pay_header_politique'], "text"),
                       GetSQLValueString($_POST['ch_pay_text_politique'], "text"),
                       GetSQLValueString($_POST['ch_pay_header_histoire'], "text"),
                       GetSQLValueString($_POST['ch_pay_text_histoire'], "text"),
                       GetSQLValueString($_POST['ch_pay_header_economie'], "text"),
                       GetSQLValueString($_POST['ch_pay_text_economie'], "text"),
                       GetSQLValueString($_POST['ch_pay_header_transport'], "text"),
                       GetSQLValueString($_POST['ch_pay_text_transport'], "text"),
                       GetSQLValueString($_POST['ch_pay_header_sport'], "text"),
                       GetSQLValueString($_POST['ch_pay_text_sport'], "text"),
                       GetSQLValueString($_POST['ch_pay_header_culture'], "text"),
                       GetSQLValueString($_POST['ch_pay_text_culture'], "text"),
                       GetSQLValueString($_POST['ch_pay_header_patrimoine'], "text"),
					   GetSQLValueString($_POST['ch_pay_text_patrimoine'], "text"),
					   GetSQLValueString($_POST['ch_pay_budget_carte'], "int"),
					   GetSQLValueString($_POST['ch_pay_industrie_carte'], "int"),
					   GetSQLValueString($_POST['ch_pay_commerce_carte'], "int"),
					   GetSQLValueString($_POST['ch_pay_agriculture_carte'], "int"),
					   GetSQLValueString($_POST['ch_pay_tourisme_carte'], "int"),
					   GetSQLValueString($_POST['ch_pay_recherche_carte'], "int"),
					   GetSQLValueString($_POST['ch_pay_environnement_carte'], "int"),
					   GetSQLValueString($_POST['ch_pay_education_carte'], "int"),
                       GetSQLValueString($_POST['ch_pay_population_carte'], "int"),
					   GetSQLValueString($_POST['ch_pay_emploi_carte'], "int"));


  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());
  $this_pays_id = mysql_insert_id();

  getErrorMessage('success', "Nouveau pays ajouté !");

  // Journalisation
  $thisPays = new \GenCity\Monde\Pays($this_pays_id);
  \GenCity\Monde\Logger\Log::createItem('pays', $thisPays->get('ch_pay_id'), 'insert',
      null, array('entity' => $thisPays->model->getInfo()));

  // Ancien système de notification.
  $userList = new \GenCity\Monde\User\UserList();
  $notification = new \GenCity\Monde\Notification\Notification(array(
      'type_notif' => 'nv_pays_bienvenue',
      'element' => $thisPays->get('ch_pay_id')
  ));
  $notification->emit($userList->getActive());

  // Nouveau système de notification basé sur Laravel.
  $eloquentPays = Pays::find($this_pays_id);

  $date_inactive = Carbon::today()->subMonths(4);
  $activeUsers = CustomUser::where('ch_use_last_log', '>', $date_inactive)->get();
  Notification::send($activeUsers, new PaysRegistered($eloquentPays));

  $insertGoTo = DEF_URI_PATH . "back/liste-pays.php";
  appendQueryString($insertGoTo);
  header(sprintf("Location: %s", $insertGoTo));
  exit;
}
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Nouveau pays</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
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
#map {
	height: 350px;
	background-color: #fff;
}
img.olTileImage {
	max-width: none;
}
@media (max-width: 480px) {
#map {
	height: 260px;
}
}
</style>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div class="container corps-page">
  <?php require(DEF_LEGACYROOTPATH . 'php/menu-haut-conseil.php'); ?>
  <div class="row-fluid">
  <!-- Debut formulaire Page Pays
        ================================================== -->
  <section>
    <div id="info-generales" class="titre-bleu anchor">
      <h1>Cr&eacute;er un nouveau pays</h1>
    </div>
    <form action="<?php echo $editFormAction; ?>" name="InfoHeader" method="POST" class="form-horizontal" id="InfoHeader">
      <!-- Boutons cachés -->
      <?php 
				  $now= date("Y-m-d G:i:s");
                  $nbupdate = "0"; ?>
      <input name="ch_pay_label" type="hidden" value="pays">
      <input name="ch_pay_date" type="hidden" value="<?php echo $now; ?>">
      <input name="ch_pay_mis_jour" type="hidden" value="<?php echo $now; ?>">
      <input name="ch_pay_nb_update" type="hidden" value="<?php echo $nbupdate; ?>">
      <input name="ch_pay_id" type="hidden" value="<?= e($row_Info_generale['ch_pay_id']) ?>">
      <input name="ch_pay_date" type="hidden" value="<?php echo $now; ?>">
      <input name="ch_pay_budget_carte" type="hidden" value="0">
      <input name="ch_pay_industrie_carte" type="hidden" value="0">
      <input name="ch_pay_commerce_carte" type="hidden" value="0">
      <input name="ch_pay_agriculture_carte" type="hidden" value="0">
      <input name="ch_pay_tourisme_carte" type="hidden" value="0">
      <input name="ch_pay_recherche_carte" type="hidden" value="0">
      <input name="ch_pay_environnement_carte" type="hidden" value="0">
      <input name="ch_pay_education_carte" type="hidden" value="0">
      <input name="ch_pay_population_carte" type="hidden" value="0">
      <input name="ch_pay_emploi_carte" type="hidden" value="0">

      <div class="">
        <h3>Voir les emplacements libres&nbsp;:</h3>
        <div id="map"></div>
      </div>
      <!-- choix emplacement -->
      <h3>Choisir un emplacement&nbsp;:</h3>
        <p class="btn-margin-left"><a href="https://romukulot.fr/kaleera/view.php?id=TdrfA" target="_blank">Emplacements supplémentaires</a></p>
      <div id="spryradio2">
        <ul class="Icone-thumb">
          <?php for ($nb_emplacement = 1; $nb_emplacement <= 59; $nb_emplacement++) {?>
          <li class=""> <img src="../Carto/Emplacements/emplacement<?php echo $nb_emplacement ?>.jpg">
            <label>
              <input type="radio" name="ch_pay_emplacement" value="<?php echo $nb_emplacement ?>" id="ch_pay_emplacement_<?php echo $nb_emplacement ?>">
              N°<?php echo $nb_emplacement ?> </label>
          </li>
          <?php }?>
        </ul>
      </div>
      <br>
      <span class="radioRequiredMsg">Effectuez une sélection.</span>
      </div>
      <div class="span12">
        <p>&nbsp;</p>
      </div>
      <!-- Definir statut du pays -->
      <h3>D&eacute;finir le statut du pays :</h3>
      <div id="spryradio1" class="well">
        <label class="radio" for="ch_pay_publicatio_1">
          <input name="ch_pay_publication" type="radio" id="ch_pay_publication_0" value="1" checked="CHECKED" selected="selected">
          Visible<a href="#" rel="clickover" title="Visible" data-content="Le pays sera visible dans le menu des continents et sur la carte du mondeGC"><i class="icon-info-sign"></i></a></label>
        <br>
        <label class="radio" for="ch_pay_publicatio_2">
          <input type="radio" name="ch_pay_publication" value="2" id="ch_pay_publication_1">
          Archiv&eacute;<a href="#" rel="clickover" title="Archiv&eacute;" data-content="Le pays est conserv&eacute; en tant qu'archive, dans l'histoire du Monde GC."><i class="icon-info-sign"></i></a></label>
        <br>
        <span class="radioRequiredMsg">Effectuez une sélection.</span></div>
      <h3>Formulaires page pays</h3>
      <div class="alert alert-tips">
        <button type="button" class="close" data-dismiss="alert">×</button>
        Ces formulaires sont destin&eacute;s a &ecirc;tre rempli directement par le dirigeant du pays. Si n&eacute;c&eacute;ssaire, vous pouvez modifier les r&eacute;glages par defaut.</div>
      <div class="accordion" id="accordion2"> 
        <!-- Informations Générales
        ================================================== -->
        <div class="accordion-group">
          <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseOne"> Informations G&eacute;n&eacute;rales </a> </div>
          <div id="collapseOne" class="accordion-body collapse">
            <div class="accordion-inner"> 
              <!-- Nom pays -->
              <div id="sprytextfield3" class="control-group">
                <label class="control-label" for="ch_pay_nom">Nom du pays <a href="#" rel="clickover" title="Nom du pays" data-placement="bottom" data-content="35 caract&egrave;res maximum. Ce nom servira &agrave; identifier votre pays dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                  <input class="input-xlarge" type="text" id="ch_pay_nom" name="ch_pay_nom" value="Territoire vierge">
                  <span class="textfieldRequiredMsg">Le nom du pays est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">35 caract&egrave;res max.</span></div>
              </div>
              <!-- Lien Forum -->
              <div id="sprytextfield10" class="control-group">
                <label class="control-label" for="ch_pay_lien_forum">Lien du sujet sur le forum <a href="#" rel="clickover" title="Lien du sujet" data-content="250 caract&egrave;res maximum. Copiez/collez ici le lien vers le sujet consacré à votre pays sur le forum. Cette information sevira à poster des messages dans votre sujet directement depuis le site"><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                  <input class="input-xlarge" type="text" id="ch_pay_lien_forum" name="ch_pay_lien_forum">
                  <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <!-- Lien Wiki -->
              <div id="sprytextfield_lien_wiki" class="control-group">
                <label class="control-label" for="lien_wiki">Lien Wiki GC <a href="#" rel="clickover" data-placement="bottom" title="Lien Wiki GC" data-content="250 caract&egrave;res maximum. Le lien vers le wiki GC."><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                  <input class="span12" type="text" id="lien_wiki" name="lien_wiki" value="<?= e($row_InfoGenerale['lien_wiki']) ?>">
                  <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <!-- Devise -->
              <div id="sprytextfield2" class="control-group">
                <label class="control-label" for="ch_pay_devise">Devise du pays <a href="#" rel="clickover" title="Devise du pays" data-content="100 caract&egrave;res maximum. Mettez-ici une phrase d'accroche ou une devise du type : Libert&eacute; - Egalit&eacute; - Fraternit&eacute;"><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                  <input class="input-xlarge" type="text" id="ch_pay_devise" name="ch_pay_devise" value="Dans cette contr&eacute;e, tout reste &agrave; construire" maxlength="100" >
                  <span class="textfieldMaxCharsMsg">100 caract&egrave;res max.</span></div>
              </div>
              <!-- Lien image fond -->
              <div id="sprytextfield1" class="control-group">
                <label class="control-label" for="ch_pay_lien_imgheader">Lien image de fond <a href="#" rel="clickover" title="Image d'en-t&ecirc;te" data-content="Il s'agit de l'image qui sera affich&eacute;e en en-t&ecirc;te de la page de votre pays. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                  <input class="input-xlarge" name="ch_pay_lien_imgheader" type="text" id="ch_pay_lien_imgheader" value="http://www.generation-city.com/monde/assets/img/imagesdefaut/Imgheader.jpg" maxlength="250">
                  <span class="textfieldInvalidFormatMsg">Format non valide.</span> <span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <!-- Lien Image drapeau -->
              <div id="sprytextfield4" class="control-group">
                <label class="control-label" for="ch_pay_lien_imgdrapeau">Lien image drapeau <a href="#" rel="clickover" title="Image drapeau" data-content="l'image du drapeau sera automatiquement redimensionn&eacute;e en 250 pixel de large et 150 pixels de haut. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                  <input class="input-xlarge" name="ch_pay_lien_imgdrapeau" type="text" id="ch_pay_lien_imgdrapeau" value="http://www.generation-city.com/monde/assets/img/imagesdefaut/drapeau.jpg" maxlength="250">
                  <span class="textfieldInvalidFormatMsg">Format non valide.</span> <span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span> <br>
                  <br>
                  <!-- Image de contrôle drapeau --> 
                  <img src="http://www.generation-city.com/monde/assets/img/imagesdefaut/drapeau.jpg" alt="Drapeau" width="250" height="150" title=""></div>
              </div>
              <!-- Forme de l'état -->
              <div id="sprytextfield6" class="control-group">
                <label class="control-label" for="ch_pay_forme_etat">Forme de l'&eacute;tat <a href="#" rel="clickover" title="R&eacute;gime politique" data-content="Fait r&eacute;f&eacute;rence &agrave; la mani&egrave;re dont le pouvoir est organis&eacute; et exerc&eacute; au sein de votre pays. Par exemple s'il s'agit d'une r&eacute;publique ou d'un royaume. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                  <input class="input-xlarge" name="ch_pay_forme_etat" type="text" id="ch_pay_forme_etat" value="pas de forme définie" maxlength="50">
                  <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
              </div>
              <!-- Capitale -->
              <div id="sprytextfield7" class="control-group">
                <label class="control-label" for="ch_pay_capitale">Nom de votre capitale <a href="#" rel="clickover" title="Capitale" data-content="50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                  <input class="input-xlarge" name="ch_pay_capitale" type="text" id="ch_pay_capitale" value="pas de capitale choisie" maxlength="50">
                  <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
              </div>
              
              <!-- Langue officielle -->
              <div id="sprytextfield8" class="control-group">
                <label class="control-label" for="ch_pay_langue_officielle">Langue officielle <a href="#" rel="clickover" title="Langue officielle" data-content="50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                  <input class="input-xlarge" name="ch_pay_langue_officielle" type="text" id="ch_pay_langue_officielle" value="dialectes" maxlength="50">
                  <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
              </div>
              
              <!-- Monnaie -->
              <div id="sprytextfield9" class="control-group">
                <label class="control-label" for="ch_pay_monnaie">monnaie officielle <a href="#" rel="clickover" title="Monnaie" data-content=" La monnaie de votre pays reste fictive. Vous pouvez choisir le nom que vous souhaitez. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                  <input class="input-xlarge" name="ch_pay_monnaie" type="text" id="ch_pay_monnaie" value="troc seulement" maxlength="50">
                  <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
              </div>
            </div>
          </div>
        </div>
        <!-- Présentation
        ================================================== -->
        <div class="accordion-group">
          <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsefour"> Présentation </a> </div>
          <div id="collapsefour" class="accordion-body collapse">
            <div class="accordion-inner"> 
              <!-- Header -->
              <div id="sprytextarea2">
                <label for="ch_pay_header_presentation">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te présentation" data-content="Donnez en quelques mots les informations qui r&eacute;sument la pr&eacute;sentation de votre pays afin de faciliter une lecture rapide du contenu de votre page. L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="3" name="ch_pay_header_presentation" class="span10" id="ch_pay_header_presentation"></textarea>
                  <br>
                  <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <p>&nbsp;</p>
              <!-- Contenu -->
              <div>
                <label for="ch_pay_text_presentation">Contenu <a href="#" rel="clickover" title="Contenu pr&eacute;sentation" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre de pr&eacute;sentation de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="20" name="ch_pay_text_presentation" class="span12 wysiwyg" id="ch_pay_text_presentation"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Geographie
        ================================================== -->
        <div class="accordion-group">
          <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsefive"> G&eacute;ographie </a> </div>
          <div id="collapsefive" class="accordion-body collapse">
            <div class="accordion-inner"> 
              <!-- Header -->
              <div id="sprytextarea3">
                <label for="ch_pay_header_geographie">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te g&eacute;ographie" data-content="Donnez en quelques mots les informations qui r&eacute;sument la g&eacute;ographie de votre pays afin de faciliter une lecture rapide du contenu de votre page. L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="3" name="ch_pay_header_geographie" class="span10" id="ch_pay_header_geographie"></textarea>
                  <br>
                  <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <p>&nbsp;</p>
              <!-- Contenu -->
              <div>
                <label for="ch_pay_text_geographie">Contenu <a href="#" rel="clickover" title="Contenu g&eacute;ographie" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; &agrave; la g&eacute;ographie de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="20" name="ch_pay_text_geographie" class="span12 wysiwyg" id="ch_pay_text_geographie"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Politique
        ================================================== -->
        <div class="accordion-group">
          <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsetwelve"> G&eacute;ographie </a> </div>
          <div id="collapsetwelve" class="accordion-body collapse">
            <div class="accordion-inner"> 
              <!-- Header -->
              <div id="sprytextarea10">
                <label for="ch_pay_header_politique">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te politique" data-content="Donnez en quelques mots les informations qui r&eacute;sument la politique de votre pays afin de faciliter une lecture rapide du contenu de votre page. L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="3" name="ch_pay_header_politique" class="span10" id="ch_pay_header_politique"></textarea>
                  <br>
                  <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <p>&nbsp;</p>
              <!-- Contenu -->
              <div>
                <label for="ch_pay_text_politique">Contenu <a href="#" rel="clickover" title="Contenu politique" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; &agrave; la politique de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="20" name="ch_pay_text_politique" class="span12 wysiwyg" id="ch_pay_text_politique"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Histoire
        ================================================== -->
        
        <div class="accordion-group">
          <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsesix"> Histoire </a> </div>
          <div id="collapsesix" class="accordion-body collapse">
            <div class="accordion-inner"> 
              <!-- Header -->
              <div id="sprytextarea4">
                <label for="ch_pay_header_histoire">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te histoire" data-content="Donnez en quelques mots les informations qui r&eacute;sument l'histoire de votre pays afin de faciliter une lecture rapide du contenu de votre page. L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="3" name="ch_pay_header_histoire" class="span10" id="ch_pay_header_histoire"></textarea>
                  <br>
                  <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <p>&nbsp;</p>
              <!-- Contenu -->
              <div>
                <label for="ch_pay_text_histoire">Contenu <a href="#" rel="clickover" title="Contenu histoire" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; &agrave; l'histoire de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="20" name="ch_pay_text_histoire" class="span12 wysiwyg" id="ch_pay_text_histoire"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Economie
        ================================================== -->
        
        <div class="accordion-group">
          <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseseven"> Economie </a> </div>
          <div id="collapseseven" class="accordion-body collapse">
            <div class="accordion-inner"> 
              
              <!-- Header -->
              <div id="sprytextarea5">
                <label for="ch_pay_header_economie">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te &eacute;conomie" data-content="Donnez en quelques mots les informations qui r&eacute;sument l'&eacute;conomie de votre pays afin de faciliter une lecture rapide du contenu de votre page. L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="3" name="ch_pay_header_economie" class="span10" id="ch_pay_header_economie"></textarea>
                  <br>
                  <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <p>&nbsp;</p>
              <!-- Contenu -->
              <div>
                <label for="ch_pay_text_economie">Contenu <a href="#" rel="clickover" title="Contenu histoire" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; &agrave; l'histoire de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="20" name="ch_pay_text_economie" class="span12 wysiwyg" id="ch_pay_text_economie"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Transport
        ================================================== -->
        
        <div class="accordion-group">
          <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseeight"> Transport </a> </div>
          <div id="collapseeight" class="accordion-body collapse">
            <div class="accordion-inner"> 
              
              <!-- Header -->
              <div id="sprytextarea6">
                <label for="ch_pay_header_transport">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te transport" data-content="Donnez en quelques mots les informations qui r&eacute;sument les transports dans votre pays afin de faciliter une lecture rapide du contenu de votre page. L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="3" name="ch_pay_header_transport" class="span10" id="ch_pay_header_transport"></textarea>
                  <br>
                  <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <p>&nbsp;</p>
              <!-- Contenu -->
              <div>
                <label for="ch_pay_text_transport">Contenu <a href="#" rel="clickover" title="Contenu transport" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; aux transports de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="20" name="ch_pay_text_transport" class="span12 wysiwyg" id="ch_pay_text_transport"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Sport
        ================================================== -->
        
        <div class="accordion-group">
          <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsenine"> Sport </a> </div>
          <div id="collapsenine" class="accordion-body collapse">
            <div class="accordion-inner"> 
              
              <!-- Header -->
              <div id="sprytextarea7">
                <label for="ch_pay_header_sport">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te sport" data-content="Donnez en quelques mots les informations qui r&eacute;sument les sports dans votre pays afin de faciliter une lecture rapide du contenu de votre page. L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="3" name="ch_pay_header_sport" class="span10" id="ch_pay_header_transport"></textarea>
                  <br>
                  <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <p>&nbsp;</p>
              <!-- Contenu -->
              <div>
                <label for="ch_pay_text_sport">Contenu <a href="#" rel="clickover" title="Contenu sport" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; aux sports de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="20" name="ch_pay_text_sport" class="span12 wysiwyg" id="ch_pay_text_sport"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Culture
        ================================================== -->
        
        <div class="accordion-group">
          <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseten"> Culture </a> </div>
          <div id="collapseten" class="accordion-body collapse">
            <div class="accordion-inner"> 
              
              <!-- Header -->
              <div id="sprytextarea8">
                <label for="ch_pay_header_culture">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te culture" data-content="Donnez en quelques mots les informations qui r&eacute;sument la culture de votre pays afin de faciliter une lecture rapide du contenu de votre page. L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="3" name="ch_pay_header_culture" class="span10" id="ch_pay_header_culture"></textarea>
                  <br>
                  <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <p>&nbsp;</p>
              <!-- Contenu -->
              <div>
                <label for="ch_pay_text_culture">Contenu <a href="#" rel="clickover" title="Contenu culture" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute;  &agrave; la culture de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="20" name="ch_pay_text_culture" class="span12 wysiwyg" id="ch_pay_text_culture"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Patrimoine
        ================================================== -->
        
        <div class="accordion-group">
          <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseeleven"> Patrimoine </a> </div>
          <div id="collapseeleven" class="accordion-body collapse">
            <div class="accordion-inner"> 
              
              <!-- Header -->
              <div id="sprytextarea9">
                <label for="ch_pay_header_patrimoine">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te patrimoine" data-content="Donnez en quelques mots les informations qui r&eacute;sument le patrimoine de votre pays afin de faciliter une lecture rapide du contenu de votre page. L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="3" name="ch_pay_header_patrimoine" class="span10" id="ch_pay_header_patrimoine"></textarea>
                  <br>
                  <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span></div>
              </div>
              <p>&nbsp;</p>
              <!-- Contenu -->
              <div>
                <label for="ch_pay_text_patrimoine">Contenu <a href="#" rel="clickover" title="Contenu culture" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute;  au patrimoine de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                <div class="">
                  <textarea rows="20" name="ch_pay_text_patrimoine" class="span12 wysiwyg" id="ch_pay_text_patrimoine"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Bouton envoyer
        ================================================== -->
      <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong> Attention ! </strong> Pour que ce nouveau pays soit fonctionnel, n'oubliez pas de l'assigner ensuite &agrave; un compte membre.</div>
      <div class="control-group">
        <div class="controls">
          <button type="submit" class="btn btn-primary btn-margin-left">Enregistrer</button>
        </div>
      </div>
      <input type="hidden" name="MM_insert" value="InfoHeader">
    </form>
    <!-- FIN formulaire Page Pays
        ================================================== --> 
  </section>
</div>
</div>
<!-- Footer
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/footerback.php'); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- CARTE -->
<script src="../assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="../assets/js/OpenLayers.js" type="text/javascript"></script>
<?php require(DEF_LEGACYROOTPATH . 'php/carteemplacements.php'); ?>
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
<!-- EDITEUR -->
<script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../assets/js/Editeur.js"></script>

<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {maxChars:100, isRequired:false, validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {minChars:2, maxChars:35, validateOn:["change"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield_lien_wiki = new Spry.Widget.ValidationTextField("sprytextfield_lien_wiki", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextarea2 = new Spry.Widget.ValidationTextarea("sprytextarea2", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea3 = new Spry.Widget.ValidationTextarea("sprytextarea3", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea4 = new Spry.Widget.ValidationTextarea("sprytextarea4", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea5 = new Spry.Widget.ValidationTextarea("sprytextarea5", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea6 = new Spry.Widget.ValidationTextarea("sprytextarea6", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea7 = new Spry.Widget.ValidationTextarea("sprytextarea7", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea8 = new Spry.Widget.ValidationTextarea("sprytextarea8", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea9 = new Spry.Widget.ValidationTextarea("sprytextarea9", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea10 = new Spry.Widget.ValidationTextarea("sprytextarea10", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
var spryradio2 = new Spry.Widget.ValidationRadio("spryradio2", {validateOn:["change"]});
</script>
</body>
</html>
