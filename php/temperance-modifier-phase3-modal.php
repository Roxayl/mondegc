<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//recuperation ID temperance
$colname_temperance = "-1";
if (isset($_GET['ch_temp_id'])) {
  $colname_temperance = $_GET['ch_temp_id'];
}

//recuperation element
$element = "-1";
if (isset($_GET['element'])) {
  $element = $_GET['element'];
}

//recuperation element-id
$element_id = "-1";
if (isset($_GET['element_id'])) {
  $element_id = $_GET['element_id'];
}


//recuperation nb juges
$nb_juges= "-1";
if (isset($_GET['nb_juges'])) {
  $nb_juges = $_GET['nb_juges'];
}

	//requete notation-temperance

$query_questionnaires = sprintf("SELECT DISTINCT SUM(ch_not_temp_q1+ch_not_temp_q2+ch_not_temp_q3+ch_not_temp_q4+ch_not_temp_q5+ch_not_temp_q6+ch_not_temp_q7+ch_not_temp_q8+ch_not_temp_q9+ch_not_temp_q10) AS note FROM notation_temperance WHERE ch_not_temp_temperance_id=%s", GetSQLValueString( $colname_temperance, "int"));
$questionnaires = mysql_query($query_questionnaires, $maconnexion) or die(mysql_error());
$row_questionnaires = mysql_fetch_assoc($questionnaires);
$totalRows_questionnaires = mysql_num_rows($questionnaires);


		//Calcul r�sultat sur 60 points quelque soit le nb de juges
$note = $row_questionnaires['note'] / $nb_juges * 3;

		//Recherche note precedente

$query_note_prec = sprintf("SELECT ch_temp_note FROM temperance WHERE ch_temp_statut='3' AND ch_temp_element=%s AND ch_temp_element_id=%s ORDER BY ch_temp_mis_jour DESC", GetSQLValueString( $element, "text"), GetSQLValueString( $element_id, "int"));
$note_prec = mysql_query($query_note_prec, $maconnexion) or die(mysql_error());
$row_note_prec = mysql_fetch_assoc($note_prec);
$totalRows_note_prec = mysql_num_rows($note_prec);	
  	
if (($row_note_prec['ch_temp_note'] != NULL) OR ($row_note_prec['ch_temp_note'] != "")) { 
if ($note > $row_note_prec['ch_temp_note'])	{ 
$ch_temp_tendance = "sup";
}
if ($note == $row_note_prec['ch_temp_note'])	{ 
$ch_temp_tendance = "stable";
}
if ($note < $row_note_prec['ch_temp_note'])	{ 
$ch_temp_tendance = "inf";
}
} else {
	$ch_temp_tendance = "stable";
}	



if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "phase-temperance")) {
  $updateSQL = sprintf("UPDATE temperance SET ch_temp_statut=%s, ch_temp_mis_jour=%s, ch_temp_note=%s, ch_temp_tendance=%s WHERE ch_temp_id=%s",
                       GetSQLValueString($_POST['ch_temp_statut'], "int"),
                       GetSQLValueString($_POST['ch_temp_mis_jour'], "date"),
					   GetSQLValueString($_POST['ch_temp_note'], "int"),
					   GetSQLValueString($_POST['ch_temp_tendance'], "text"),
					   GetSQLValueString($_POST['ch_temp_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

  $updateGoTo = DEF_URI_PATH . "back/institut_economie.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  $adresse = $updateGoTo .'#liste-temperance';
  mysql_free_result($questionnaires);
  header(sprintf("Location: %s", $adresse));
}
?>

<!-- Modal Header-->
<form action="<?php echo $editFormAction; ?>" name="phase-temperance" method="POST" class="form-horizontal" id="phase-temperance">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
    <h3 id="myModalLabel">Fermeture des votes</h3>
  </div>
  <div class="modal-body">
    <p>Clore les votes permet de calculer la note finale et de la publier sur le site.</p>
    <div class="alert alert-danger">
      <p><i class="icon-warning-sign"></i> Cette action sera d&eacute;finitive.</p>
    </div>
    <!-- Boutons cach�s -->
    <?php $now= date("Y-m-d G:i:s");?>
    <input name="ch_temp_statut" type="hidden" value="3">
    <input name="ch_temp_mis_jour" type="hidden" value="<?php echo $now; ?>">
    <input name="ch_temp_id" type="hidden" value="<?php echo $colname_temperance; ?>">
    <input name="ch_temp_note" type="hidden" value="<?php echo $note; ?>">
    <input name="ch_temp_tendance" type="hidden" value="<?php echo $ch_temp_tendance; ?>">
    </p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Publier</button>
    <input type="hidden" name="MM_update" value="phase-temperance">
  </div>
</form>
<?php
mysql_free_result($questionnaires);
mysql_free_result($note_prec);
unset ($note);
?>