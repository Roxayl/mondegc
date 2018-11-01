<?php
session_start();
include('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=iso-8859-1');


//requete listes monuments
$colname_group_id = "-1";
if (isset($_GET['mem_group_id'])) {
  $colname_group_id = $_GET['mem_group_id'];
}
$colname_membre_id = "-1";
if (isset($_GET['membre_id'])) {
  $colname_membre_id = $_GET['membre_id'];
}

mysql_select_db($database_maconnexion, $maconnexion);
$query_groupe = sprintf("SELECT ch_mem_group_ID, ch_mem_group_nom, ch_mem_group_icon, ch_mem_group_couleur FROM membres_groupes WHERE ch_mem_group_ID = %s", GetSQLValueString($colname_group_id, "int"));
$groupe = mysql_query($query_groupe, $maconnexion) or die(mysql_error());
$row_groupe = mysql_fetch_assoc($groupe);
$totalRows_groupe = mysql_num_rows($groupe);
$nomGroupe = $row_groupe['ch_mem_group_nom'];

mysql_select_db($database_maconnexion, $maconnexion);
$query_list_admin = sprintf("SELECT ch_disp_mem_id, ch_use_mail, ch_use_login FROM dispatch_mem_group INNER JOIN users ON ch_use_id=ch_disp_mem_id WHERE ch_disp_group_id = %s AND ch_disp_mem_statut=2", GetSQLValueString($colname_group_id, "int"));
$list_admin = mysql_query($query_list_admin, $maconnexion) or die(mysql_error());
$row_list_admin = mysql_fetch_assoc($list_admin);
$totalRows_list_admin = mysql_num_rows($list_admin);



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajoutgroup")) {
  $insertSQL = sprintf("INSERT INTO dispatch_mem_group (ch_disp_group_id, ch_disp_MG_label, ch_disp_mem_id, ch_disp_MG_date, ch_disp_mem_statut) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_disp_group_id'], "int"),
                       GetSQLValueString($_POST['ch_disp_MG_label'], "text"),
                       GetSQLValueString($_POST['ch_disp_mem_id'], "int"),
                       GetSQLValueString($_POST['ch_disp_MG_date'], "date"),
                       GetSQLValueString($_POST['ch_disp_mem_statut'], "int"));
					   
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());
  
do { 
$mail = $row_list_admin['ch_use_mail'];// Déclaration de l'adresse de destination.
$nomGroupe = $row_groupe['ch_mem_group_nom']; // Déclaration du nom du groupe.
$GroupeID = $row_groupe['ch_mem_group_ID']; // Déclaration id du groupe.

if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
$message_txt = "Cher membre de G&eacute;n&eacute;ration City. Un membre a souhait&eacute; rejoindre le groupe $nomGroupe. En tant qu'administrateur, il vous appartient d'accepter ou non son int&eacute;gration. Pour cela, cliquez sur le lien ci-dessous : http://www.generation-city.com/monde/back/membre-modifier_back.php?mem_groupID=$GroupeID#classer-membres. Pour accepter sa demande, vous devez lui attribuer un statut en cliquant sur le bouton éditer. Pour la refuser, il vous suffit de supprimer le membre de ce groupe. Nous vous remercions de l'inter&ecirc;t que vous portez &agrave; notre monde. l'&eacute;quipe de G&eacute;n&eacute;ration City";
$message_html = "<html><head></head><body><b>Cher membre de G&eacute;n&eacute;ration city</b>,<br><br> Un membre a souhait&eacute; rejoindre le groupe $nomGroupe. <br><br>En tant qu'administrateur, il vous appartient d'accepter ou non son int&eacute;gration. Pour cela, cliquez sur le lien ci-dessous :<br>
<a href='http://www.generation-city.com/monde/back/membre-modifier_back.php?mem_groupID=$GroupeID#classer-membres'>http://www.generation-city.com/monde/back/membre-modifier_back.php?mem_groupID=$GroupeID#classer-membres</a><br><br>Pour accepter sa demande, vous devez lui attribuer un statut en cliquant sur le bouton éditer.<br>Pour la refuser, il vous suffit de supprimer le membre de ce groupe.<br>Nous vous remercions de l'inter&ecirc;t que vous portez &agrave; notre monde.<br><br><br><em><i>L'&eacute;quipe de G&eacute;n&eacute;ration City</i></em></body></html>";
//==========

//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========

//=====Définition du sujet.
$sujet = "Un membre souhaite rejoindre votre groupe";
//=========

//=====Création du header de l'e-mail.
$header = "From: \"Generation City\"<monde@generation-city.com>".$passage_ligne;
$header.= "Reply-to: \"Generation City\"<monde@generation-city.com>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========

//=====Création du message.
$message = $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format HTML
$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
//==========

//=====Envoi de l'e-mail.
mail($mail,$sujet,$message,$header);
//==========
    if (!mail($to, $subject, $body, $headers)) {
              $redirect_error= "error.php"; // Redirect if there is an error.
      header( "Location: ".$redirect_error ) ;
    }
} while ($row_list_admin  = mysql_fetch_assoc($list_admin ));	
	
if ($_SESSION['last_work'] == "institut_politique.php") {
  $insertGoTo = '../back/institut_politique.php?mem_groupID='. $row_info_group['ch_mem_group_ID'] .'';
} else {
  $insertGoTo = '../back/membre-modifier_back.php?mem_groupID='. $row_info_group['ch_mem_group_ID'] .'';
  }
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  $adresse = $insertGoTo .'#liste-categories';
  header(sprintf("Location: %s", $adresse));
}
?>
<!-- Modal Header-->
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  <h3 id="myModalLabel">Rejoindre le groupe <?php echo $row_groupe['ch_mem_group_nom']; ?></h3>
</div>
<div class="modal-body">
  <div class="row-fluid">
    <div class="span10">
      <h4>Souhaitez-vous faire une demande pour rejoindre ce groupe ?</h4>
      <p>Vous devrez attendre que les administrateurs de ce groupe acceptent votre demande pour en faire pleinement partie.</p>
    </div>
    <div class="span2 icone-categorie"><img src="<?php echo $row_groupe['ch_mem_group_icon']; ?>" alt="icone <?php echo $row_groupe['ch_mem_group_nom']; ?>" style="background-color:<?php echo $row_groupe['ch_mem_group_couleur']; ?>;"></div>
  </div>
</div>
<div class="modal-footer"> 
  <!-- Boutons cachés -->
  <form action="<?php echo $editFormAction; ?>" name="ajoutgroup" method="POST" class="form-horizontal" id="ajoutgroup">
    <?php $now= date("Y-m-d G:i:s");?>
    <input name="ch_disp_group_id" type="hidden" value="<?php echo $colname_group_id; ?>">
    <input name="ch_disp_MG_label" type="hidden" value="disp_mem">
    <input name="ch_disp_MG_date" type="hidden" value="<?php echo $now; ?>">
    <input name="ch_disp_mem_id" type="hidden" value="<?php echo $colname_membre_id; ?>">
    <input name="ch_disp_mem_statut" type="hidden" value= "3">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Rejoindre</button>
    <input type="hidden" name="MM_insert" value="ajoutgroup">
  </form>
</div>
<?php
mysql_free_result($groupe);
?>
