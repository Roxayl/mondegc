<?php

use GenCity\Monde\Pays;
use GenCity\Monde\User;



header('Content-Type: text/html; charset=utf-8');

// renvoyer les données POST à soi-même
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

$user_pays_ID = isset($_GET['user_pays_ID']) ? (int)$_GET['user_pays_ID'] : 0;

$query_users_pays = mysql_query(sprintf(
    "SELECT id, ID_pays, ID_user, permissions FROM users_pays WHERE id = %s",
        GetSQLValueString($user_pays_ID, 'int')));
$result_users_pays = mysql_fetch_assoc($query_users_pays);
if(empty($result_users_pays)) {
    echo 'Erreur';
    exit;
}

$thisUser = new User($result_users_pays['ID_user']);
$thisPays = new Pays($result_users_pays['ID_pays']);

// Traitement données POST
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "pays_leader_edit")) {

    if($_SESSION['userObject']->minStatus('OCGC', '<') &&
       $thisPays->getUserPermission() < Pays::$permissions['dirigeant']) {
        getErrorMessage('error', "Accès interdit");
        exit;
    }

    $permissions = $_POST['permissions'];

    if($thisPays->getUserPermission($thisUser) >= Pays::$permissions['dirigeant'] &&
        count($thisPays->getLeaders(Pays::$permissions['dirigeant'])) <= 1 &&
        $permissions < Pays::$permissions['dirigeant']) {
        getErrorMessage('error', "Vous ne pouvez pas vous retirer les droits de dirigeant " .
                                            "car vous n'êtes que le seul dirigeant.");
    } else {
        $update_query = mysql_query(sprintf(
            'UPDATE users_pays SET permissions = %s WHERE id = %s',
            GetSQLValueString($permissions, 'int'),
            GetSQLValueString($user_pays_ID, 'int')
        ));
        getErrorMessage('success', "{$thisUser->ch_use_login} a été défini comme " .
                        Pays::getPermissionName($permissions) . ' !');
    }

    $adresse = DEF_URI_PATH . 'back/page_pays_back.php?paysID=' . $result_users_pays['ID_pays'] . '#dirigeants';
    header(sprintf("Location: %s", $adresse));

    exit;

} ?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="pays_leader_edit" method="POST" class="form-horizontal" id="ajout-mon_categorie">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Gérer l'accès de <?= $thisUser->ch_use_login ?></h3>
        <h4><?= $thisPays->ch_pay_nom ?></h4>
  </div>
  <div class="modal-body">
      <input name="user_pays_id" type="hidden" value="<?php echo $user_pays_ID; ?>">
      <ul>
        <li><input id="form_permission_dirigeant" name="permissions" type="radio" value="10"
           <?= $result_users_pays['permissions'] == Pays::$permissions['dirigeant'] ? 'checked' : '' ?>>
            <label for="form_permission_dirigeant">Dirigeant</label>
        <small>Le dirigeant peut modifier tous les aspects du pays. Il peut définir d'autres dirigeants, les destituer, mais aussi modifier la présentation et créer, modifier et supprimer les villes du pays.</small></li>
        <li><input id="form_permission_codirigeant" name="permissions" type="radio" value="5"
           <?= $result_users_pays['permissions'] == Pays::$permissions['codirigeant'] ? 'checked' : '' ?>>
            <label for="form_permission_codirigeant">Co-dirigeant</label>
        <small>Le co-dirigeant peut créer, modifier et supprimer la présentation du pays et les villes du pays.</small></li>
      </ul>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_insert" value="pays_leader_edit">
</form>
