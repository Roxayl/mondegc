<?php

require_once('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=utf-8');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-fai_categorie")) {
  $insertSQL = sprintf("INSERT INTO dispatch_fait_his_cat (ch_disp_fait_hist_cat_id, ch_disp_FH_label, ch_disp_fait_hist_id, ch_disp_FH_date) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_disp_fait_hist_cat_id'], "int"),
                       GetSQLValueString($_POST['ch_disp_FH_label'], "text"),
                       GetSQLValueString($_POST['ch_disp_fait_hist_id'], "int"),
                       GetSQLValueString($_POST['ch_disp_FH_date'], "date"));
					   
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = '../back/institut_histoire.php?fai_catID='. $row_fai_cat['ch_his_cat_ID'] .'';
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  $adresse = $insertGoTo .'#classer-fait-hist';
  header(sprintf("Location: %s", $adresse));
}



//requete listes faits hist
$colname_classer_fait = "-1";
if (isset($_GET['fai_catID'])) {
  $colname_classer_fait = $_GET['fai_catID'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_fait_cat = sprintf("SELECT ch_his_id, ch_his_nom FROM histoire WHERE ch_his_id NOT IN (SELECT ch_disp_fait_hist_id FROM dispatch_fait_his_cat WHERE ch_disp_fait_hist_cat_id = %s)  ORDER BY ch_his_mis_jour DESC", GetSQLValueString($colname_classer_fait, ""));
$liste_fait_cat = mysql_query($query_liste_fait_cat, $maconnexion) or die(mysql_error());
$row_liste_fait_cat = mysql_fetch_assoc($liste_fait_cat);
$totalRows_liste_fait_cat = mysql_num_rows($liste_fait_cat);


//requete info catégorie
mysql_select_db($database_maconnexion, $maconnexion);
$query_fai_cat = sprintf("SELECT ch_fai_cat_ID, ch_fai_cat_nom FROM faithist_categories WHERE ch_fai_cat_ID = %s", GetSQLValueString($colname_classer_fait, "int"));
$fai_cat = mysql_query($query_fai_cat, $maconnexion) or die(mysql_error());
$row_fai_cat = mysql_fetch_assoc($fai_cat);
$totalRows_fai_cat = mysql_num_rows($fai_cat);
?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="ajout-fai_categorie" method="POST" class="form-horizontal" id="ajout-fai_categorie">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Ajouter un monument dans la cat&eacute;gorie <?php echo $row_fai_cat['ch_fai_cat_nom']; ?></h3>
  </div>
  <div class="modal-body"> 
    <!-- Boutons cachés -->
    <?php 
				  $now= date("Y-m-d G:i:s");?>
    <input name="ch_disp_fait_hist_cat_id" type="hidden" value="<?php echo $row_fai_cat['ch_fai_cat_ID']; ?>">
    <input name="ch_disp_FH_label" type="hidden" value="disp_fai">
    <input name="ch_disp_FH_date" type="hidden" value="<?php echo $now; ?>">
    <select name="ch_disp_fait_hist_id" id="ch_disp_fait_hist_id">
      <?php do { ?>
      <option value="<?php echo $row_liste_fait_cat['ch_his_id']; ?>"><?php echo $row_liste_fait_cat['ch_his_nom']; ?></option>
      <?php } while ($row_liste_fait_cat = mysql_fetch_assoc($liste_fait_cat)); ?>
    </select>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_insert" value="ajout-fai_categorie">
</form>
<?php
mysql_free_result($liste_fait_cat);
mysql_free_result($fai_cat);?>
