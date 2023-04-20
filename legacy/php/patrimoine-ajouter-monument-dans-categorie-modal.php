<?php

use Roxayl\MondeGC\Events\Patrimoine\PatrimoineCategorized;
use Roxayl\MondeGC\Models\Patrimoine;

header('Content-Type: text/html; charset=utf-8');

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-mon_categorie")) {

  $eloquentPatrimoine = Patrimoine::query()->findOrFail($_POST['ch_disp_mon_id']);

  if(!auth()->check() || !auth()->user()->can('manageCategories', Patrimoine::class)) {
        abort(403);
    }

  $insertSQL = sprintf("INSERT INTO dispatch_mon_cat (ch_disp_cat_id, ch_disp_mon_label, ch_disp_mon_id, ch_disp_date) VALUES (%s, %s, %s, %s)",
                       escape_sql($_POST['ch_disp_cat_id'], "int"),
                       escape_sql($_POST['ch_disp_mon_label'], "text"),
                       escape_sql($_POST['ch_disp_mon_id'], "int"),
                       escape_sql($_POST['ch_disp_date'], "date"));
  
  $Result1 = mysql_query($insertSQL, $maconnexion);

  event(new PatrimoineCategorized($eloquentPatrimoine));

  getErrorMessage('success', "Quête catégorisée avec succès !", true);

  $insertGoTo = DEF_URI_PATH . 'back/institut_patrimoine.php?mon_cat_ID = %s' .$row_mon_cat['ch_mon_cat_ID'].'';
  appendQueryString($insertGoTo);
  $adresse = $insertGoTo .'#classer-monument';
  header(sprintf("Location: %s", $adresse));
  exit;
}



//requete listes monuments
$colname_classer_mon = "-1";
if (isset($_GET['mon_cat_ID'])) {
  $colname_classer_mon = $_GET['mon_cat_ID'];
}

$query_liste_mon_cat = sprintf("SELECT ch_pat_id, ch_pat_nom FROM patrimoine WHERE ch_pat_id NOT IN (SELECT ch_disp_mon_id FROM dispatch_mon_cat WHERE ch_disp_cat_id = %s)  ORDER BY ch_pat_mis_jour DESC", escape_sql($colname_classer_mon, ""));
$liste_mon_cat = mysql_query($query_liste_mon_cat, $maconnexion);
$row_liste_mon_cat = mysql_fetch_assoc($liste_mon_cat);
$totalRows_liste_mon_cat = mysql_num_rows($liste_mon_cat);


//requete info catégorie

$query_mon_cat = sprintf("SELECT ch_mon_cat_ID, ch_mon_cat_nom FROM monument_categories WHERE ch_mon_cat_ID = %s", escape_sql($colname_classer_mon, "int"));
$mon_cat = mysql_query($query_mon_cat, $maconnexion);
$row_mon_cat = mysql_fetch_assoc($mon_cat);
$totalRows_mon_cat = mysql_num_rows($mon_cat);
?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="ajout-mon_categorie" method="POST" class="form-horizontal" id="ajout-mon_categorie">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Ajouter un monument dans la cat&eacute;gorie <?= e($row_mon_cat['ch_mon_cat_nom']) ?></h3>
  </div>
  <div class="modal-body"> 
    <!-- Boutons cachés -->
    <?php 
				  $now= date("Y-m-d G:i:s");?>
    <input name="ch_disp_cat_id" type="hidden" value="<?= e($row_mon_cat['ch_mon_cat_ID']) ?>">
    <input name="ch_disp_mon_label" type="hidden" value="disp_mon">
    <input name="ch_disp_date" type="hidden" value="<?php echo $now; ?>">
    <select name="ch_disp_mon_id" id="ch_disp_mon_id">
      <?php do { ?>
      <option value="<?= e($row_liste_mon_cat['ch_pat_id']) ?>"><?= e($row_liste_mon_cat['ch_pat_nom']) ?></option>
      <?php } while ($row_liste_mon_cat = mysql_fetch_assoc($liste_mon_cat)); ?>
    </select>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_insert" value="ajout-mon_categorie">
</form>
