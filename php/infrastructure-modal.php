<?php

include('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=utf-8');


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$ch_inf_id = -1 ;
if (isset ($_GET['ch_inf_id'])){
	$ch_inf_id = $_GET['ch_inf_id'];
	}


mysql_select_db($database_maconnexion, $maconnexion);
$query_infrastructure = sprintf("SELECT * FROM infrastructures INNER JOIN infrastructures_officielles ON infrastructures.ch_inf_off_id=infrastructures_officielles.ch_inf_off_id LEFT OUTER JOIN users ON ch_inf_juge=ch_use_id WHERE ch_inf_id = %s ORDER BY ch_inf_date DESC", GetSQLValueString($ch_inf_id, "int"));
$query_limit_infrastructure = sprintf("%s LIMIT %d, %d", $query_infrastructure, $startRow_infrastructure, $maxRows_infrastructure);
$infrastructure = mysql_query($query_infrastructure, $maconnexion) or die(mysql_error());
$row_infrastructure = mysql_fetch_assoc($infrastructure);
?> 

<!-- Modal Header-->
  <div class="modal-header">
  <div class="pull-left"><img style="width:100px; margin-right: 10px; margin-top:-50px;" src="<?php echo $row_infrastructure['ch_inf_off_icone']; ?>" alt="icone <?php echo $row_infrastructure['ch_inf_off_nom']; ?>"></div>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel"><?php echo $row_infrastructure['ch_inf_off_nom']; ?></h3>
  </div>
  <div class="modal-body">
  <?php if ($row_infrastructure['ch_inf_statut'] == 2) {?>
   <div class="alert alert-success">
            <img src="../assets/img/statutinfra_<?php echo $row_infrastructure['ch_inf_statut']; ?>.png" alt="Statut"> Acceptée par les juges tempérants
            <?php if ($row_infrastructure['ch_inf_juge'] != NULL) { ?><em>(jug&eacute; par <?php echo $row_infrastructure['ch_use_login']; ?>)</em><?php }?>
            </div>
  <?php } elseif ($row_infrastructure['ch_inf_statut'] == 3) { ?>
<div class="alert alert-danger">
            <p><img src="../assets/img/statutinfra_<?php echo $row_infrastructure['ch_inf_statut']; ?>.png" alt="Statut"> Refusée par les juges tempérants. Cette infrastructure n'influence pas l'économie.<p>
            <?php if (($row_infrastructure['ch_inf_commentaire_juge'] != NULL) OR ($row_infrastructure['ch_inf_commentaire_juge'] != "")) { ?>
			<p><strong>Raison&nbsp;: <em>"<?php echo $row_infrastructure['ch_inf_commentaire_juge']; ?>"</em></strong></p>
  	<?php }?>
            <?php if ($row_infrastructure['ch_inf_juge'] != NULL) { ?><em>(jug&eacute; par <?php echo $row_infrastructure['ch_use_login']; ?>)</em><?php }?>
            </div>
           <?php } else { ?>
<div class="alert">
            <img src="../assets/img/statutinfra_<?php echo $row_infrastructure['ch_inf_statut']; ?>.png" alt="Statut"> En attente de jugement. Son influence n'est pas encore prise en compte.
            </div><?php }?>
  
    <div class="row-fluid">
    <div class="span6">
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
        <?php if ($row_infrastructure['ch_inf_lien_image4']) { ?>
         <div class="span2 list-thumb-ressource">
         <img onClick="ChangeImage(this.src);" class="img-thumb-ressource" src="<?php echo $row_infrastructure['ch_inf_lien_image4']; ?>" alt="image n°4">
         </div>
        <?php } ?>
        <?php if ($row_infrastructure['ch_inf_lien_image5']) { ?>
         <div class="span2 list-thumb-ressource">
         <img onClick="ChangeImage(this.src);" class="img-thumb-ressource" src="<?php echo $row_infrastructure['ch_inf_lien_image5']; ?>" alt="image n°5">
         </div>
        <?php } ?>
        </div>
    <strong><p>Commentaire du membre&nbsp;:</p></strong>
    <p><em><?php echo $row_infrastructure['ch_inf_commentaire']; ?></em></p>
    <?php if ($row_infrastructure['ch_inf_lien_forum']) { ?>
    <a href="<?php echo $row_infrastructure['ch_inf_lien_forum']; ?>" target="_blank">Lien sur le forum</a>
    <?php } ?>
    </div>
    <div class="span6">
    <h3>Influence sur l'économie</h3>
             <div class="well icone-ressources">
                <img src="../assets/img/ressources/budget.png" alt="icone Budget"><p>&nbsp;Budget&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_budget']; ?></strong></p>
                <img src="../assets/img/ressources/industrie.png" alt="icone Industrie"><p>&nbsp;Industrie&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Industrie']; ?></strong></p>
                <img src="../assets/img/ressources/bureau.png" alt="icone Commerce"><p>&nbsp;Commerce&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Commerce']; ?></strong></p>
                <img src="../assets/img/ressources/agriculture.png" alt="icone Agriculture"><p>&nbsp;Agriculture&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Agriculture']; ?></strong></p>
                <img src="../assets/img/ressources/tourisme.png" alt="icone Tourisme"><p>&nbsp;Tourisme&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Tourisme']; ?></strong></p>
                <img src="../assets/img/ressources/recherche.png" alt="icone Recherche"><p>&nbsp;Recherche&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Recherche']; ?></strong></p>
                <img src="../assets/img/ressources/environnement.png" alt="icone Evironnement"><p>&nbsp;Environnement&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Environnement']; ?></strong></p>
                <img src="../assets/img/ressources/education.png" alt="icone Education"><p>&nbsp;Education&nbsp;: <strong><?php echo $row_infrastructure['ch_inf_off_Education']; ?></strong></p>
            </div>
            <p>&nbsp;</p>
             <strong><p>R&egrave;gle&nbsp;:</p></strong>
    <p><em><?php echo $row_infrastructure['ch_inf_off_desc']; ?></em></p>
    </div>
  </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
  </div>
  <script language="javascript">
		function ChangeImage(url) {
			document.getElementById("img").src = url;
		}
		</script> 