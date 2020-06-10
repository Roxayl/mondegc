<?php

use GenCity\Monde\Pays;
use GenCity\Monde\User;

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');

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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "pays_leader_delete")) {

    if($_SESSION['userObject']->minStatus('OCGC', '<') &&
       $thisPays->getUserPermission() < Pays::$permissions['dirigeant']) {
        getErrorMessage('error', "Accès interdit");
        exit;
    }

    if($thisPays->getUserPermission($thisUser) >= Pays::$permissions['dirigeant'] &&
        count($thisPays->getLeaders(Pays::$permissions['dirigeant'])) <= 1) {
        getErrorMessage('error', "Vous ne pouvez pas vous retirer les droits de dirigeant " .
                                            "car vous n'êtes que le seul dirigeant.");
    } else {
        $thisPays->removeLeader($thisUser);
        getErrorMessage('success', "L'accès de {$thisUser->ch_use_login} a été supprimé.");
    }

    $adresse = DEF_URI_PATH . 'back/page_pays_back.php?paysID=' . $result_users_pays['ID_pays'] . '#dirigeants';
    header(sprintf("Location: %s", $adresse));

    exit;

} ?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="pays_leader_edit" method="POST" class="form-horizontal" id="pays_leader_delete">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Confirmer la suppression des accès de <?= $thisUser->ch_use_login ?></h3>
        <h4><?= $thisPays->ch_pay_nom ?></h4>
  </div>
  <div class="modal-body">
      Supprimer l'accès de <?= $thisUser->ch_use_login ?> à votre pays ?
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_insert" value="pays_leader_delete">
</form>
