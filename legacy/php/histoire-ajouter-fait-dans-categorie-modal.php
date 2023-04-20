<?php

header('Content-Type: text/html; charset=utf-8');

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-fai_categorie")) {
  $insertSQL = sprintf("INSERT INTO dispatch_fait_his_cat (ch_disp_fait_hist_cat_id, ch_disp_FH_label, ch_disp_fait_hist_id, ch_disp_FH_date) VALUES (%s, %s, %s, %s)",
                       escape_sql($_POST['ch_disp_fait_hist_cat_id'], "int"),
                       escape_sql($_POST['ch_disp_FH_label'], "text"),
                       escape_sql($_POST['ch_disp_fait_hist_id'], "int"),
                       escape_sql($_POST['ch_disp_FH_date'], "date"));
					   
  
  $Result1 = mysql_query($insertSQL, $maconnexion);

  $insertGoTo = DEF_URI_PATH . 'back/institut_histoire.php?fai_catID='. $row_fai_cat['ch_his_cat_ID'] .'';
  appendQueryString($insertGoTo);
  $adresse = $insertGoTo .'#classer-fait-hist';
  header(sprintf("Location: %s", $adresse));
 exit;
}



//requete listes faits hist
$colname_classer_fait = "-1";
if (isset($_GET['fai_catID'])) {
  $colname_classer_fait = $_GET['fai_catID'];
}

$query_liste_fait_cat = sprintf("SELECT ch_his_id, ch_his_nom FROM histoire WHERE ch_his_id NOT IN (SELECT ch_disp_fait_hist_id FROM dispatch_fait_his_cat WHERE ch_disp_fait_hist_cat_id = %s)  ORDER BY ch_his_mis_jour DESC", escape_sql($colname_classer_fait, ""));
$liste_fait_cat = mysql_query($query_liste_fait_cat, $maconnexion);
$row_liste_fait_cat = mysql_fetch_assoc($liste_fait_cat);
$totalRows_liste_fait_cat = mysql_num_rows($liste_fait_cat);


//requete info catégorie

$query_fai_cat = sprintf("SELECT ch_fai_cat_ID, ch_fai_cat_nom FROM faithist_categories WHERE ch_fai_cat_ID = %s", escape_sql($colname_classer_fait, "int"));
$fai_cat = mysql_query($query_fai_cat, $maconnexion);
$row_fai_cat = mysql_fetch_assoc($fai_cat);
$totalRows_fai_cat = mysql_num_rows($fai_cat);
?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="ajout-fai_categorie" method="POST" class="form-horizontal" id="ajout-fai_categorie">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Ajouter un monument dans la cat&eacute;gorie <?= e($row_fai_cat['ch_fai_cat_nom']) ?></h3>
  </div>
  <div class="modal-body"> 
    <!-- Boutons cachés -->
    <?php 
				  $now= date("Y-m-d G:i:s");?>
    <input name="ch_disp_fait_hist_cat_id" type="hidden" value="<?= e($row_fai_cat['ch_fai_cat_ID']) ?>">
    <input name="ch_disp_FH_label" type="hidden" value="disp_fai">
    <input name="ch_disp_FH_date" type="hidden" value="<?php echo $now; ?>">
    <select name="ch_disp_fait_hist_id" id="ch_disp_fait_hist_id">
      <?php do { ?>
      <option value="<?= e($row_liste_fait_cat['ch_his_id']) ?>"><?= e($row_liste_fait_cat['ch_his_nom']) ?></option>
      <?php } while ($row_liste_fait_cat = mysql_fetch_assoc($liste_fait_cat)); ?>
    </select>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_insert" value="ajout-fai_categorie">
</form>
