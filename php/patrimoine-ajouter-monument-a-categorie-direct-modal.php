<?php

use App\Events\Patrimoine\PatrimoineCategorized;
use App\Models\Patrimoine;

header('Content-Type: text/html; charset=utf-8');

// renvoyer les données POST à soi-même
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

$mon_ID = isset($_GET['mon_id']) ? (int)$_GET['mon_id'] : 0;

// Traitement données POST
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-mon_categorie_direct")) {

    $eloquentPatrimoine = Patrimoine::findOrFail($mon_ID);

    if(!auth()->check() || !auth()->user()->can('manageCategories', Patrimoine::class)) {
        abort(403);
    }

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

    event(new PatrimoineCategorized($eloquentPatrimoine));

    getErrorMessage('success', "Quête catégorisée avec succès !", true);

    header('Location: ' . $_POST['previous_url']);
    exit;
}

//requete monument
$query_liste_mon_cat = sprintf("SELECT ch_pat_id, ch_pat_nom, ch_pat_statut FROM patrimoine WHERE ch_pat_id = %s", GetSQLValueString($mon_ID, ""));
$liste_mon_cat = mysql_query($query_liste_mon_cat, $maconnexion) or die(mysql_error());
$this_mon_cat = mysql_fetch_assoc($liste_mon_cat);

//requete catégorie 0
$query_mon_cat = sprintf("SELECT * FROM monument_categories WHERE ch_mon_cat_couleur BETWEEN 0 AND 99 ORDER BY ch_mon_cat_couleur", GetSQLValueString($mon_ID, "int"));
$mon_cat = mysql_query($query_mon_cat, $maconnexion) or die(mysql_error());
$row_mon_cat = mysql_fetch_assoc($mon_cat);
$totalRows_mon_cat = mysql_num_rows($mon_cat);

//requete catégorie 1
$query_mon_cat_a = sprintf("SELECT * FROM monument_categories WHERE ch_mon_cat_couleur BETWEEN 100 AND 199 ORDER BY ch_mon_cat_couleur", GetSQLValueString($mon_ID, "int"));
$mon_cat_a = mysql_query($query_mon_cat_a, $maconnexion) or die(mysql_error());
$row_mon_cat_a = mysql_fetch_assoc($mon_cat_a);
$totalRows_mon_cat_a = mysql_num_rows($mon_cat_a);

//requete catégorie 2
$query_mon_cat_b = sprintf("SELECT * FROM monument_categories WHERE ch_mon_cat_couleur BETWEEN 200 AND 299 ORDER BY ch_mon_cat_couleur", GetSQLValueString($mon_ID, "int"));
$mon_cat_b = mysql_query($query_mon_cat_b, $maconnexion) or die(mysql_error());
$row_mon_cat_b = mysql_fetch_assoc($mon_cat_b);
$totalRows_mon_cat_b = mysql_num_rows($mon_cat_b);

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


// Comptages
$nb_cat_a = 0;
$compte_a = mysql_query($query_mon_cat_a);
while($row = mysql_fetch_assoc($compte_a)) {
    if($row_mon_cat_a['ch_mon_cat_statut'] == $this_mon_cat['ch_pat_statut']) {
        $nb_cat_a = $nb_cat_a + 1;}} mysql_data_seek($compte_a, 0);

$nb_cat_a_ok = 0;
$compte_a_ok = mysql_query($query_mon_cat_a);
while($row = mysql_fetch_assoc($compte_a_ok)) {
    if($row_mon_cat_a['ch_mon_cat_ID'] == $current_cat_list['ch_disp_cat_id']) {
        $nb_cat_a_ok = $nb_cat_a_ok + 1;}} mysql_data_seek($compte_a_ok, 0);

$nb_cat_b = 0;
$compte_b = mysql_query($query_mon_cat_b);
while($row = mysql_fetch_assoc($compte_b)) {
    if($row_mon_cat_b['ch_mon_cat_statut'] == $this_mon_cat['ch_pat_statut']) {
        $nb_cat_b = $nb_cat_b + 1;}} mysql_data_seek($compte_b, 0);
}
?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="ajout-mon_categorie" method="POST" class="form-horizontal" id="ajout-mon_categorie">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Validez de nouveaux objectifs pour <strong><?= e($this_mon_cat['ch_pat_nom']) ?></strong></h3>
  </div>
  <div class="modal-body">
    <!-- Boutons cachés -->
    <?php
    $now = date("Y-m-d G:i:s"); ?>
    <input name="ch_mon_id" type="hidden" value="<?php echo $mon_ID; ?>">
    <input name="ch_disp_mon_label" type="hidden" value="disp_mon">
    <input name="ch_disp_date" type="hidden" value="<?php echo $now; ?>">
    <input name="previous_url" type="hidden" value="<?= e(url()->previous()) ?>">

          <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" href="#cat0">
                    Connection avec les autres plateformes GC
                </a>
            </div>
            <div id="cat0" class="accordion-body collapse">
            <div class="accordion-inner">
    <ul  class="listes">
    <?php do { ?>
        <?php if($row_mon_cat['ch_mon_cat_statut'] == $this_mon_cat['ch_pat_statut']) { ?>
            <li class="row-fluid" style="display: flex; margin-top: 0px; background-size: cover; background-image: url('<?= __s($row_mon_cat['bg_image_url']) ?>');">
            <label for="ch_disp_cat_id_<?= e($row_mon_cat['ch_mon_cat_ID']) ?>"
               style="display: inline-block; text-decoration: <?php if ($row_mon_cat['ch_mon_cat_ID'] == in_array($row_mon_cat['ch_mon_cat_ID'], $current_cat_list) ? 'checked' : '' ) { ?>line-through<?php } else { ?><?php }?>;">
            <img src="<?php echo $row_mon_cat['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_mon_cat['ch_mon_cat_nom']; ?>" style="height: 15px;"> <strong><?= e($row_mon_cat['ch_mon_cat_nom']) ?></strong>, <?= e($row_mon_cat['ch_mon_cat_desc']) ?></label>
            <input type="checkbox" id="ch_disp_cat_id_<?= e($row_mon_cat['ch_mon_cat_ID']) ?>"
                   name="ch_disp_cat_id[]" value="<?= e($row_mon_cat['ch_mon_cat_ID']) ?>"
                   <?= in_array($row_mon_cat['ch_mon_cat_ID'], $current_cat_list) ? 'checked' : '' ?> />
        </li><?php } else { ?><?php }?>
    <?php } while ($row_mon_cat = mysql_fetch_assoc($mon_cat)); ?>
    </ul>
                <div class="clearfix"></div>
            </div>
            </div>
          </div>


          <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" href="#cat2">
                    Secteur(s) d'activité de l'entreprise
                    <!--<br><small><?php echo $nb_cat_a_ok ?> objectifs sur <?php echo $nb_cat_a ?> accomplis</small>-->
                </a>
            </div>
            <div id="cat2" class="accordion-body collapse">
            <div class="accordion-inner">
    <ul  class="listes">
    <?php do { ?>
        <?php if($row_mon_cat_a['ch_mon_cat_statut'] == $this_mon_cat['ch_pat_statut']) { ?>
            <li class="row-fluid" style="display: flex; margin-top: 0px; background-size: cover; background-image: url('<?= __s($row_mon_cat_a['bg_image_url']) ?>');">
            <label for="ch_disp_cat_id_<?= e($row_mon_cat_a['ch_mon_cat_ID']) ?>"
               style="display: inline-block; text-decoration: <?php if ($row_mon_cat_a['ch_mon_cat_ID'] == in_array($row_mon_cat_a['ch_mon_cat_ID'], $current_cat_list) ? 'checked' : '' ) { ?>line-through<?php } else { ?><?php }?>;">
            <img src="<?php echo $row_mon_cat_a['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_mon_cat_a['ch_mon_cat_nom']; ?>" style="height: 15px;"> <strong><?= e($row_mon_cat_a['ch_mon_cat_nom']) ?></strong>, <?= e($row_mon_cat_a['ch_mon_cat_desc']) ?></label>
            <input type="checkbox" id="ch_disp_cat_id_<?= e($row_mon_cat_a['ch_mon_cat_ID']) ?>"
                   name="ch_disp_cat_id[]" value="<?= e($row_mon_cat_a['ch_mon_cat_ID']) ?>"
                   <?= in_array($row_mon_cat_a['ch_mon_cat_ID'], $current_cat_list) ? 'checked' : '' ?> />
        </li><?php } else { ?><?php }?>
    <?php } while ($row_mon_cat_a = mysql_fetch_assoc($mon_cat_a)); ?>
    </ul>
                <div class="clearfix"></div>
            </div>
            </div>
          </div>

                    <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" href="#cat3">
                    Conception
                    <!--<br><small>XX objectifs sur <?php echo $nb_cat_b ?> accomplis</small>-->
                </a>
            </div>
            <div id="cat3" class="accordion-body collapse">
            <div class="accordion-inner">
    <ul  class="listes">
    <?php do { ?>
        <?php if($row_mon_cat_b['ch_mon_cat_statut'] == $this_mon_cat['ch_pat_statut']) { ?>
            <li class="row-fluid" style="margin-top: 0px; background-size: cover; background-image: url('<?= __s($row_mon_cat_b['bg_image_url']) ?>');">
            <label for="ch_disp_cat_id_<?= e($row_mon_cat_b['ch_mon_cat_ID']) ?>"
               style="display: inline-block; text-decoration: <?php if ($row_mon_cat_b['ch_mon_cat_ID'] == in_array($row_mon_cat_b['ch_mon_cat_ID'], $current_cat_list) ? 'checked' : '' ) { ?>line-through<?php } else { ?><?php }?>;">
            <img src="<?php echo $row_mon_cat_b['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_mon_cat_b['ch_mon_cat_nom']; ?>" style="height: 15px;"> <strong><?= e($row_mon_cat_b['ch_mon_cat_nom']) ?></strong>, <?= e($row_mon_cat_b['ch_mon_cat_desc']) ?></label>
            <input type="checkbox" id="ch_disp_cat_id_<?= e($row_mon_cat_b['ch_mon_cat_ID']) ?>"
                   name="ch_disp_cat_id[]" value="<?= e($row_mon_cat_b['ch_mon_cat_ID']) ?>"
                   <?= in_array($row_mon_cat_b['ch_mon_cat_ID'], $current_cat_list) ? 'checked' : '' ?> />
        </li><?php } else { ?><?php }?>
    <?php } while ($row_mon_cat_b = mysql_fetch_assoc($mon_cat_b)); ?>
    </ul>
                <div class="clearfix"></div>
            </div>
            </div>
          </div>

  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
    <button type="submit" class="btn btn-primary">Valider les nouveaux objetifs atteints !</button>
  </div>
  <input type="hidden" name="MM_insert" value="ajout-mon_categorie_direct">
</form>
