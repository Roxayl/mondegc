<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
header('Content-Type: text/html; charset=utf-8');

// renvoyer les données POST à soi-même
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$mon_ID = isset($_GET['mon_id']) ? (int)$_GET['mon_id'] : 0;

// Traitement données POST
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-mon_categorie_direct")) {

    

    $new_cat_list = empty($_POST['ch_disp_cat_id']) ? array() : $_POST['ch_disp_cat_id'];

    // Obtenir l'affiliation actuelle du monument
    $query_current_monument_dispatch = sprintf("
        SELECT ch_disp_id, ch_disp_cat_id, ch_disp_mon_id FROM dispatch_mon_cat
        WHERE ch_disp_mon_id = %s
    ", GetSQLValueString($mon_ID, 'int'));
    $sql_current_monument_dispatch = mysql_query($query_current_monument_dispatch);

    $current_cat_list = array();
    while($row_monument_dispatch = mysql_fetch_assoc($sql_current_monument_dispatch)) {
        $current_cat_list[] = $row_monument_dispatch['ch_disp_cat_id'];
    }

    mysql_free_result($sql_current_monument_dispatch);

    // Insérer les catégories qui sont dans le nouvel array et qui n'existent pas encore.
    foreach($current_cat_list as $current_cat) {
        if(!in_array($current_cat, $new_cat_list)) {
            $sqlQuery = sprintf("
                DELETE FROM dispatch_mon_cat WHERE ch_disp_cat_id = %s AND ch_disp_mon_id = %s",
                GetSQLValueString($current_cat, 'int'),
                GetSQLValueString($mon_ID, 'int'));
            mysql_query($sqlQuery);
        }
    }

    // Supprimer les catégories existantes qui ne figurent pas dans le nouvel array.
    foreach($new_cat_list as $new_cat) {
        if(!in_array($new_cat, $current_cat_list)) {
            $sqlQuery = sprintf("
                INSERT INTO dispatch_mon_cat(ch_disp_mon_label, ch_disp_cat_id, ch_disp_mon_id, ch_disp_date)
                VALUES('disp_mon', %s, %s, NOW())",
                GetSQLValueString($new_cat, 'int'),
                GetSQLValueString($mon_ID, 'int'));
            mysql_query($sqlQuery);
        }
    }

    $insertGoTo = '../back/institut_patrimoine.php?mon_cat_ID=' .$row_mon_cat['ch_mon_cat_ID'].'';
    if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    $adresse = $insertGoTo .'#classer-monument';
    header(sprintf("Location: %s", $adresse));

    exit;

}


//requete monument

$query_liste_mon_cat = sprintf("SELECT ch_pat_id, ch_pat_nom FROM patrimoine WHERE ch_pat_id = %s", GetSQLValueString($mon_ID, ""));
$liste_mon_cat = mysql_query($query_liste_mon_cat, $maconnexion) or die(mysql_error());
$this_mon_cat = mysql_fetch_assoc($liste_mon_cat);

//requete tous catégories

$query_mon_cat = sprintf("SELECT ch_mon_cat_ID, ch_mon_cat_nom FROM monument_categories", GetSQLValueString($mon_ID, "int"));
$mon_cat = mysql_query($query_mon_cat, $maconnexion) or die(mysql_error());
$row_mon_cat = mysql_fetch_assoc($mon_cat);
$totalRows_mon_cat = mysql_num_rows($mon_cat);

// requête catégories du monument
// Obtenir l'affiliation actuelle du monument
$query_current_monument_dispatch = sprintf("
    SELECT ch_disp_id, ch_disp_cat_id, ch_disp_mon_id FROM dispatch_mon_cat
    WHERE ch_disp_mon_id = %s
", GetSQLValueString($mon_ID, 'int'));
$sql_current_monument_dispatch = mysql_query($query_current_monument_dispatch);

$current_cat_list = array();
while($row_monument_dispatch = mysql_fetch_assoc($sql_current_monument_dispatch)) {
    $current_cat_list[] = $row_monument_dispatch['ch_disp_cat_id'];
}

?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="ajout-mon_categorie" method="POST" class="form-horizontal" id="ajout-mon_categorie">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Ajouter <?= $this_mon_cat['ch_pat_nom'] ?> aux catégories</h3>
  </div>
  <div class="modal-body">
    <!-- Boutons cachés -->
    <?php
    $now= date("Y-m-d G:i:s");?>
    <input name="ch_mon_id" type="hidden" value="<?php echo $mon_ID; ?>">
    <input name="ch_disp_mon_label" type="hidden" value="disp_mon">
    <input name="ch_disp_date" type="hidden" value="<?php echo $now; ?>">

    <ul>
    <?php do { ?>
        <li>
            <label for="ch_disp_cat_id_<?= $row_mon_cat['ch_mon_cat_ID'] ?>"
               style="display: inline-block;">
            <?php echo $row_mon_cat['ch_mon_cat_nom']; ?></label>
            <input type="checkbox" id="ch_disp_cat_id_<?= $row_mon_cat['ch_mon_cat_ID'] ?>"
                   name="ch_disp_cat_id[]" value="<?php echo $row_mon_cat['ch_mon_cat_ID']; ?>"
                   <?= in_array($row_mon_cat['ch_mon_cat_ID'], $current_cat_list) ? 'checked' : '' ?> />
        </li>
    <?php } while ($row_mon_cat = mysql_fetch_assoc($mon_cat)); ?>
    </ul>

  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_insert" value="ajout-mon_categorie_direct">
</form>
<?php
mysql_free_result($liste_mon_cat);
mysql_free_result($mon_cat);?>
