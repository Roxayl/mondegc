<?php

require_once('../Connections/maconnexion.php');


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-temperance")) {
  $insertSQL = sprintf("INSERT INTO temperance (ch_temp_label, ch_temp_date, ch_temp_mis_jour, ch_temp_element, ch_temp_element_id, ch_temp_statut, ch_temp_note, ch_temp_tendance) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_temp_label'], "text"),
                       GetSQLValueString($_POST['ch_temp_date'], "date"),
                       GetSQLValueString($_POST['ch_temp_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_temp_element'], "text"),
                       GetSQLValueString($_POST['ch_temp_element_id'], "int"),
                       GetSQLValueString($_POST['ch_temp_statut'], "int"),
                       GetSQLValueString($_POST['ch_temp_note'], "int"),
					   GetSQLValueString($_POST['ch_temp_tendance'], "text"));
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = '../back/institut_economie.php';
  if (isset($_SERVER['QUERY_STRING'])) {
$colname_ville = $_POST['ch_temp_element_id'];

//requete ville
mysql_select_db($database_maconnexion, $maconnexion);
$query_mail = sprintf("SELECT ch_vil_nom, ch_use_mail FROM villes INNER JOIN users ON ch_vil_user=ch_use_id WHERE ch_vil_ID=%s", GetSQLValueString($colname_ville, "int"));
$mail = mysql_query($query_mail, $maconnexion) or die(mysql_error());
$row_mail = mysql_fetch_assoc($mail);
$totalRows_mail = mysql_num_rows($mail);

$mail = $row_mail['ch_use_mail'];
$nom_ville = $row_mail['ch_vil_nom'];

//envoi mail
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====D�claration des messages au format texte et au format HTML.
$message_txt = "Cher membre de G&eacute;n&eacute;ration City. Vous dirigez la ville $nom_ville dans le Monde GC. Les Temp&eacute;rants viennent de lancer une proc&eacute;dure afin de noter la coh�rence globale de votre ville et les informations que vous affichez sur la page du site. A partir de la date d'ouverture de cette proc&eacute;dure, vous disposez d'un mois pour, si vous souhaitez, remettre en coh&eacute;rance les chiffres de votre ville. Au del&agrave; de ce delai, la page sera not&eacute;e par les juges. Nous vous remercions de l'inter&ecirc;t que vous portez &agrave; notre site. l'&eacute;quipe de G&eacute;n&eacute;ration City";
$message_html = "<html><head></head><body><b>Cher membre de G&eacute;n&eacute;ration city</b>,<br><br>Vous dirigez la ville $nom_ville dans le Monde GC. Les Temp&eacute;rants viennent de lancer une proc&eacute;dure afin de noter la coh�rence globale de votre ville et les informations que vous affichez sur la page du site.<br>A partir de la date d'ouverture de cette proc&eacute;dure, vous disposez d'un mois pour, si vous souhaitez, remettre en coh&eacute;rance les chiffres de votre ville. Au del&agrave; de ce delai, la page sera not&eacute;e par les juges.<br>Nous vous remercions de l'inter&ecirc;t que vous portez &agrave; notre site.<br><br><br><em><i>L'&eacute;quipe de G&eacute;n&eacute;ration City</i></em></body></html>";
//==========

//=====Cr�ation de la boundary
$boundary = "-----=".md5(rand());
//==========

//=====D�finition du sujet.
$sujet = "Lancement d'une proc&eacute;dure de temp&eacute;rance";
//=========

//=====Cr�ation du header de l'e-mail.
$header = "From: \"Generation City\"<monde@generation-city.com>".$passage_ligne;
$header.= "Reply-to: \"Generation City\"<monde@generation-city.com>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========

//=====Cr�ation du message.
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
  header(sprintf("Location: %s", $insertGoTo));
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  $adresse = $insertGoTo .'#liste-temperance';
  header(sprintf("Location: %s", $adresse));
}

//requete villes
mysql_select_db($database_maconnexion, $maconnexion);
$query_villes = "SELECT ch_vil_ID, ch_vil_nom FROM villes INNER JOIN pays ON ch_vil_paysID=ch_pay_id WHERE ch_vil_capitale != 3 AND ch_pay_publication=1 ORDER BY ch_vil_nom";
$villes = mysql_query($query_villes, $maconnexion) or die(mysql_error());
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);
?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="ajout-temperance" method="POST" class="form-horizontal" id="ajout-temperance">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
    <h3 id="myModalLabel">Temp&eacute;rer une ville</h3>
  </div>
  <div class="modal-body"> 
    <!-- Boutons cach�s -->
    <?php $now= date("Y-m-d G:i:s");?>
    <input name="ch_temp_label" type="hidden" value="temperance">
    <input name="ch_temp_date" type="hidden" value="<?php echo $now; ?>">
    <input name="ch_temp_mis_jour" type="hidden" value="<?php echo $now; ?>">
    <input name="ch_temp_element" type="hidden" value="ville">
    
    <!-- Selection villes -->
    <div class="control-group">
    <label class="control-label" for="ch_temp_element_id">Choisissez une ville &agrave; temp&eacute;rer</label>
    <div class="controls">
    <select name="ch_temp_element_id" id="ch_temp_element_id" class="span3">
      <?php do { ?>
          <option value="<?php echo $row_villes['ch_vil_ID']; ?>"><?php echo $row_villes['ch_vil_nom']; ?></option>
      <?php } while ($row_villes = mysql_fetch_assoc($villes)); ?>
     </select>
    </div>
    </div>
<div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      Un mail sera envoy� au maire de la ville pour l'avertir du lancement de la proc&eacute;dure</div>
  </div>
          <input name="ch_temp_statut" type="hidden" value="1">
    <input name="ch_temp_note" type="hidden" value="">
    <input name="ch_temp_tendance" type="hidden" value="">
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">D&eacute;marrer la temp&eacute;rance</button>
  </div>
  <input type="hidden" name="MM_insert" value="ajout-temperance">
</form>
<?php
mysql_free_result($villes);
?>