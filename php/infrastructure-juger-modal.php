<?php

use App\CustomUser;
use App\Models\Infrastructure;
use App\Notifications\InfrastructureJudged;
use Illuminate\Support\Facades\Notification;

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
header('Content-Type: text/html; charset=utf-8');


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);


//Requete info infrastructure
$ch_inf_id = -1 ;
if (isset($_GET['ch_inf_id'])) {
	$ch_inf_id = $_GET['ch_inf_id'];
    $thisInfra = new \GenCity\Monde\Temperance\Infrastructure($ch_inf_id);
}

//Définition infrastructure selon jugement
if (isset($_POST['ch_inf_statut_accepter'])) {
 
    $_POST['ch_inf_statut'] = 2;
 
} elseif (isset($_POST['ch_inf_statut_refuser'])) {
 
    $_POST['ch_inf_statut'] = 3;
 
} else {
 
    $_POST['ch_inf_statut'] = 2;
}


//Actualisation BDD accepter infrastructure après jugement
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "accepter_infrastructure")) {

    $thisInfra = new \GenCity\Monde\Temperance\Infrastructure($_POST['ch_inf_id']);

  $updateSQL = sprintf("UPDATE infrastructures SET ch_inf_date=%s, ch_inf_statut=%s, ch_inf_juge=%s, ch_inf_commentaire_juge=%s WHERE ch_inf_id=%s",
                       GetSQLValueString($_POST['ch_inf_date'], "date"),
                       GetSQLValueString($_POST['ch_inf_statut_accepter'], "int"),
                       GetSQLValueString($_POST['ch_inf_juge'], "int"),
                       GetSQLValueString($_POST['ch_inf_commentaire_juge'], "text"),
                       GetSQLValueString($_POST['ch_inf_id'], "int"));


  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

  getErrorMessage('success',
      '<div style="display: inline-block; background-color: #51a351"><i class="icon-jugement icon-white"></i></div> ' .
    " L'infrastructure " . __s($thisInfra->get('nom_infra')) .
    " a été <strong>acceptée</strong> !");
}

//Actualisation BDD refuser infrastructure après jugement
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "refuser_infrastructure")) {

    $thisInfra = new \GenCity\Monde\Temperance\Infrastructure($_POST['ch_inf_id']);

  $updateSQL = sprintf("UPDATE infrastructures SET ch_inf_date=%s, ch_inf_statut=%s, ch_inf_juge=%s, ch_inf_commentaire_juge=%s WHERE ch_inf_id=%s",
                       GetSQLValueString($_POST['ch_inf_date'], "date"),
                       GetSQLValueString($_POST['ch_inf_statut_refuser'], "int"),
                       GetSQLValueString($_POST['ch_inf_juge'], "int"),
                       GetSQLValueString($_POST['ch_inf_commentaire_juge'], "text"),
                       GetSQLValueString($_POST['ch_inf_id'], "int"));


  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

  getErrorMessage('success',
      '<div style="display: inline-block; background-color: #a34643"><i class="icon-jugement icon-white"></i></div> ' .
    " L'infrastructure " . __s($thisInfra->get('nom_infra')) .
    " a été <strong>refusée</strong> ! <i>La force de dire non !</i>");

}

if(isset($_POST['MM_update'])) {

    if($_POST["MM_update"] == "refuser_infrastructure") {
        $type_notif = 'infra_juge_refuse';
    } else {
        $type_notif = 'infra_juge_accepte';
    }

    /*** Notification ***/
    $notification = new \GenCity\Monde\Notification\Notification(null);
    $notification->set('type_notif', $type_notif);
    $notification->set('element', (int)$_POST['ch_inf_id']);
    $recipients = array();

    // On ajoute le créateur de l'infra aux destinataires de la notification.
    if(!is_null($thisInfra->get('user_creator'))) {
      $recipients[] = $thisInfra->get('user_creator');
    }

    // On ajoute les gestionnaires de pays aux destinataires de la notification.
    $thisVille = new \GenCity\Monde\Ville($thisInfra->get('ch_inf_villeid'));
    $thisPays = new \GenCity\Monde\Pays($thisVille->get('ch_vil_paysID'));
    $paysLeaders = $thisPays->getLeaders();
    $leaders = array_column(
          $thisPays->getLeaders(), 'ID_user'
    );
    foreach($leaders as $leader) {
      if(!in_array($leader, $recipients))
          $recipients[] = $leader;
    }
    $notification->emit($recipients);

    // Nouveau système de notification basé sur Laravel.
    $eloquentInfrastructure = Infrastructure::find($_POST['ch_inf_id']);
    $userList = CustomUser::whereIn('ch_use_id', $recipients)->get();
    Notification::send($userList, new InfrastructureJudged($eloquentInfrastructure));

    // Redirection
    $updateGoTo = DEF_URI_PATH . "back/Temperance_jugement.php";
    if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    }
    header(sprintf("Location: %s", $updateGoTo));
    exit;

}


$query_infrastructure = sprintf("SELECT ch_inf_id, ch_inf_date, ch_inf_statut, ch_inf_villeid, nom_infra, ch_inf_lien_image, ch_inf_lien_image2, ch_inf_lien_image3, ch_inf_lien_image4, ch_inf_lien_image5, ch_inf_lien_forum, infrastructures.lien_wiki, ch_inf_commentaire, ch_inf_commentaire_juge, ch_inf_off_nom, ch_inf_off_desc, ch_inf_off_icone, ch_inf_off_budget, ch_inf_off_Industrie, ch_inf_off_Commerce, ch_inf_off_Agriculture, ch_inf_off_Tourisme, ch_inf_off_Recherche, ch_inf_off_Environnement, ch_inf_off_Education, ch_vil_nom, ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau FROM infrastructures INNER JOIN infrastructures_officielles ON infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID INNER JOIN pays ON ch_vil_paysID = ch_pay_id WHERE ch_inf_id = %s ORDER BY ch_inf_date DESC", GetSQLValueString($ch_inf_id, "int"));
$query_limit_infrastructure = sprintf("%s LIMIT %d, %d", $query_infrastructure, $startRow_infrastructure, $maxRows_infrastructure);
$infrastructure = mysql_query($query_infrastructure, $maconnexion) or die(mysql_error());
$row_infrastructure = mysql_fetch_assoc($infrastructure);


//calcul ressources de la ville
$ville_id = $row_infrastructure['ch_inf_villeid'];

$query_ressources_ville = sprintf("SELECT 
SUM(ch_inf_off_budget) AS sum_ville_budget,
SUM(ch_inf_off_Industrie) AS sum_ville_industrie,
SUM(ch_inf_off_Commerce) AS sum_ville_commerce,
SUM(ch_inf_off_Agriculture) AS sum_ville_agriculture,
SUM(ch_inf_off_Tourisme) AS sum_ville_tourisme,
SUM(ch_inf_off_Recherche) AS sum_ville_recherche,
SUM(ch_inf_off_Environnement) AS sum_ville_environnement,
SUM(ch_inf_off_Education) AS sum_ville_education
FROM infrastructures INNER JOIN  infrastructures_officielles ON infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id WHERE ch_inf_villeid = $ville_id AND ch_inf_statut=2");
$ressources_ville = mysql_query($query_ressources_ville, $maconnexion) or die(mysql_error());
$row_ressources_ville = mysql_fetch_assoc($ressources_ville);
$totalRows_ressources_ville = mysql_num_rows($ressources_ville);


//calcul ressources du pays 
$ch_pay_id = $row_infrastructure['ch_pay_id'];

$query_ressources_pays = sprintf("SELECT 
SUM(ch_inf_off_budget) AS sum_pays_budget,
SUM(ch_inf_off_Industrie) AS sum_pays_industrie,
SUM(ch_inf_off_Commerce) AS sum_pays_commerce,
SUM(ch_inf_off_Agriculture) AS sum_pays_agriculture,
SUM(ch_inf_off_Tourisme) AS sum_pays_tourisme,
SUM(ch_inf_off_Recherche) AS sum_pays_recherche,
SUM(ch_inf_off_Environnement) AS sum_pays_environnement,
SUM(ch_inf_off_Education) AS sum_pays_education
FROM infrastructures INNER JOIN  infrastructures_officielles ON infrastructures.ch_inf_off_id = infrastructures_officielles.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = $ch_pay_id AND ch_inf_statut=2");
$ressources_pays = mysql_query($query_ressources_pays, $maconnexion) or die(mysql_error());
$row_ressources_pays = mysql_fetch_assoc($ressources_pays);
$totalRows_ressources_pays = mysql_num_rows($ressources_pays);
?>
<!-- Modal Header-->

<div class="modal-header">
  <div class="row-fluid">
    <div class="span2"><img src="<?php echo $row_infrastructure['ch_inf_off_icone']; ?>" alt="icone <?php echo $row_infrastructure['ch_inf_off_nom']; ?>"></div>
    <div class="span10">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel"><?= __s($row_infrastructure['nom_infra']) ?>
      <small><?php echo $row_infrastructure['ch_inf_off_nom']; ?></small></h3>
      <p><em><?php echo $row_infrastructure['ch_inf_off_desc']; ?></em></p>
    </div>
  </div>
</div>
<div class="modal-body">
  <?php if ($row_infrastructure['ch_inf_statut'] == 2) {?>
  <div class="alert alert-success"> <img src="../assets/img/statutinfra_<?php echo $row_infrastructure['ch_inf_statut']; ?>.png" alt="Statut"> Acceptée par les juges tempérants</div>
  <?php } elseif ($row_infrastructure['ch_inf_statut'] == 3) { ?>
  <div class="alert alert-danger">
    <p><img src="../assets/img/statutinfra_<?php echo $row_infrastructure['ch_inf_statut']; ?>.png" alt="Statut"> Refusée par les juges tempérants. Cette infrastructure n'influence pas l'économie.
    <p>
      <?php if (($row_infrastructure['ch_inf_commentaire_juge'] != NULL) OR ($row_infrastructure['ch_inf_commentaire_juge'] != "")) { ?>
    <p><strong>Raison&nbsp;: <em>"<?php echo $row_infrastructure['ch_inf_commentaire_juge']; ?>"</em></strong></p>
    <?php }?>
  </div>
  <?php } else { ?>
  <div class="alert"> <img src="../assets/img/statutinfra_<?php echo $row_infrastructure['ch_inf_statut']; ?>.png" alt="Statut"> En attente de jugement. Son influence n'est pas encore prise en compte. </div>
  <?php }?>
  <div class="row-fluid">
    <div class="span12">
    <img class="hidden-phone img-modal-ressource" id="img" src="<?php echo $row_infrastructure['ch_inf_lien_image']; ?>" alt="image de l'infrastrucutre">
    <div class="row-fluid">
         <div class="span2 list-thumb-ressource">
         <img onClick="ChangeImage(this.src);" class="img-thumb-ressource" src="<?php echo $row_infrastructure['ch_inf_lien_image']; ?>" alt="image n°1">
         </div>
     <?php if ($row_infrastructure['ch_inf_lien_image2']) { ?>
         <div class="span2 list-thumb-ressource">
         <img onClick="ChangeImage(this.src);" class="img-thumb-ressource" src="<?php echo $row_infrastructure['ch_inf_lien_image2']; ?>" alt="image n°2">
         </div>
        <?php } ?>
        <?php if ($row_infrastructure['ch_inf_lien_image3']) { ?>
        <div class="span2 list-thumb-ressource">
         <img onClick="ChangeImage(this.src);" class="img-thumb-ressource" src="<?php echo $row_infrastructure['ch_inf_lien_image3']; ?>" alt="image n°3">
         </div>
        <?php } ?>
        </div>
      <div class="well">
        <p>La ville <a href="../page-ville.php?ch_pay_id=<?php echo $row_infrastructure['ch_pay_id']; ?>&ch_ville_id=<?php echo $row_infrastructure['ch_inf_villeid']; ?>"><?php echo $row_infrastructure['ch_vil_nom']; ?></a> du pays <a href="../page-pays.php?ch_pay_id=<?php echo $row_infrastructure['ch_pay_id']; ?>"><?php echo $row_infrastructure['ch_pay_nom']; ?></a> souhaite ajouter cette infrastructure et modifier ses statistiques &eacute;conomiques</p>
            <strong><p>Description&nbsp;:</p></strong>
    <p><em><?php echo $row_infrastructure['ch_inf_commentaire']; ?></em></p>
    <?php if (!empty($row_infrastructure['ch_inf_lien_forum'])) { ?>
    <a href="<?php echo $row_infrastructure['ch_inf_lien_forum']; ?>" target="_blank">
        <div class="external-link-icon"
             style="background-image:url('http://www.generation-city.com/forum/new/favicon.png');"></div>
        Lien sur le forum</a>
    <?php } ?>
    <?php if (!empty($row_infrastructure['lien_wiki'])) { ?>
        <a href="<?php echo $row_infrastructure['lien_wiki']; ?>" target="_blank">
        <div class="external-link-icon"
             style="background-image:url('https://romukulot.fr/kaleera/images/h4FQp.png');"></div>
            Lien sur le wiki</a>
    <?php } ?>

    <p>&nbsp;</p>
   
      </div>
    </div>
  </div>
  <h3>Influence de cette infrastructure sur l'économie</h3>
  <div class="well">
    <div class="row-fluid">
      <div class="span6 icone-ressources">
        <img src="../assets/img/ressources/budget.png" alt="icone Budget"><p>Budget&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_budget']; ?></strong></p>
        <img src="../assets/img/ressources/industrie.png" alt="icone Industrie"><p>Industrie&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Industrie']; ?></strong></p>
        <img src="../assets/img/ressources/bureau.png" alt="icone Commerce"><p>Commerce&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Commerce']; ?></strong></p>
        <img src="../assets/img/ressources/agriculture.png" alt="icone Agriculture"><p>Agriculture&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Agriculture']; ?></strong></p>
      </div>
      <div class="span6 icone-ressources">
        <img src="../assets/img/ressources/tourisme.png" alt="icone Tourisme"><p>Tourisme&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Tourisme']; ?></strong></p>
        <img src="../assets/img/ressources/recherche.png" alt="icone Recherche"><p>Recherche&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Recherche']; ?></strong></p>
        <img src="../assets/img/ressources/environnement.png" alt="icone Evironnement"><p>Environnement&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Environnement']; ?></strong></p>
        <img src="../assets/img/ressources/education.png" alt="icone Education"><p>Education&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Education']; ?></strong></p>
      </div>
    </div>
  </div>
  <h3>Informations utiles</h3>
  <div class="accordion-group">
    <div class="accordion-heading"><a class="accordion-toggle well" data-toggle="collapse" href="#collapseone">Ressources actuelles de la ville : <?php echo $row_infrastructure['ch_vil_nom']; ?></a></div>
    <div id="collapseone" class="accordion-body collapse">
      <div class="accordion-inner">
        <div class="row-fluid">
          <div class="span6">
            <p>Budget&nbsp;: <strong><?php echo $row_ressources_ville['sum_ville_budget']; ?></strong></p>
            <p>Industrie&nbsp;: <strong><?php echo $row_ressources_ville['sum_ville_industrie']; ?></strong></p>
            <p>Commerce&nbsp;: <strong><?php echo $row_ressources_ville['sum_ville_commerce']; ?></strong></p>
            <p>Agriculture&nbsp;: <strong><?php echo $row_ressources_ville['sum_ville_agriculture']; ?></strong></p>
          </div>
          <div class="span6">
            <p>Tourisme&nbsp;: <strong><?php echo $row_ressources_ville['sum_ville_tourisme']; ?></strong></p>
            <p>Recherche&nbsp;: <strong><?php echo $row_ressources_ville['sum_ville_recherche']; ?></strong></p>
            <p>Environnement&nbsp;: <strong><?php echo $row_ressources_ville['sum_ville_environnement']; ?></strong></p>
            <p>Education&nbsp;: <strong><?php echo $row_ressources_ville['sum_ville_education']; ?></strong></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="accordion-group">
    <div class="accordion-heading"><a class="accordion-toggle well" data-toggle="collapse" href="#collapsetwo">Ressources actuelles du pays : <?php echo $row_infrastructure['ch_pay_nom']; ?></a> </div>
    <div id="collapsetwo" class="accordion-body collapse">
      <div class="accordion-inner">
        <div class="row-fluid">
          <div class="span6">
            <p>Budget&nbsp;: <strong><?php echo $row_ressources_pays['sum_pays_budget']; ?></strong></p>
            <p>Industrie&nbsp;: <strong><?php echo $row_ressources_pays['sum_pays_industrie']; ?></strong></p>
            <p>Commerce&nbsp;: <strong><?php echo $row_ressources_pays['sum_pays_commerce']; ?></strong></p>
            <p>Agriculture&nbsp;: <strong><?php echo $row_ressources_pays['sum_pays_agriculture']; ?></strong></p>
          </div>
          <div class="span6">
            <p>Tourisme&nbsp;: <strong><?php echo $row_ressources_pays['sum_pays_tourisme']; ?></strong></p>
            <p>Recherche&nbsp;: <strong><?php echo $row_ressources_pays['sum_pays_recherche']; ?></strong></p>
            <p>Environnement&nbsp;: <strong><?php echo $row_ressources_pays['sum_pays_environnement']; ?></strong></p>
            <p>Education&nbsp;: <strong><?php echo $row_ressources_pays['sum_pays_education']; ?></strong></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <form action="<?php echo $editFormAction; ?>" name="accepter_infrastructure" Id="accepter_infrastructure" method="post" class="form-button-inline">
    <?php $now = date("Y-m-d G:i:s"); ?>
    <input name="ch_inf_id" type="hidden" value="<?php echo $row_infrastructure['ch_inf_id']; ?>">
    <input name="ch_inf_date" type="hidden" value="<?php echo $now; ?>" >
    <input name="ch_inf_juge" type="hidden" value="<?php echo $_SESSION['user_ID']; ?>" >
    <input name="ch_inf_statut_accepter" type="hidden" value="2" >
    <input name="ch_inf_commentaire_juge" type="hidden" value="<?php echo $row_infrastructure['ch_inf_commentaire_juge'];?>" >
     <?php if (($row_infrastructure['ch_inf_statut'] == 2) OR ($row_infrastructure['ch_inf_statut'] == 3)) {?>
 <h4 style="float:left;">Modifier le jugement :</h4>
<?php }?>
    <button type="submit" class="btn btn-success" title="accepter l'infrastructure"><i class="icon-jugement"></i> Accepter</button>
    <button class="btn btn-danger" data-toggle="modal" href="#stack2" title="refuser l'infrastructure"><i class="icon-jugement"></i> Refuser</button>
    <input type="hidden" name="MM_update" value="accepter_infrastructure">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
  </form>
  <div id="stack2" class="modal hide fade" tabindex="-1" data-focus-on="input:first">
    <form action="<?php echo $editFormAction; ?>" name="refuser_infrastructure" Id="refuser_infrastructure" method="post" class="form-button-inline">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Refuser une infrastructure</h3>
      </div>
      <div class="modal-body">
        <?php $now = date("Y-m-d G:i:s"); ?>
        <input name="ch_inf_id" type="hidden" value="<?php echo $row_infrastructure['ch_inf_id']; ?>">
        <input name="ch_inf_date" type="hidden" value="<?php echo $now; ?>" >
        <input name="ch_inf_statut_refuser" type="hidden" value="3" >
    <input name="ch_inf_juge" type="hidden" value="<?php echo $_SESSION['user_ID']; ?>" >
        <div class="control-group">
          <label class="control-label" for="ch_inf_commentaire_juge">Expliquez en quelques lignes les raisons de votre d&eacute;cision</label>
          <div class="controls">
            <textarea name="ch_inf_commentaire_juge" id="ch_inf_commentaire_juge" class="span5" rows="6"><?php echo $row_infrastructure['ch_inf_commentaire_juge'];?></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" title="refuser l'infrastructure"><i class="icon-jugement"></i> Refuser</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
        <input type="hidden" name="MM_update" value="refuser_infrastructure">
      </div>
    </form>
  </div>
</div>
<?php
mysql_free_result($infrastructure);
mysql_free_result($ressources_ville);
mysql_free_result($ressources_pays);
?>
  </div>
  <script language="javascript">
		function ChangeImage(url) {
			document.getElementById("img").src = url;
		}
		</script> 