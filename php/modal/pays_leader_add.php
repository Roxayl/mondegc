<?php

use GenCity\Monde\Pays;
use GenCity\Monde\User;



header('Content-Type: text/html; charset=utf-8');

// renvoyer les données POST à soi-même
$editFormAction = DEF_URI_PATH . 'php/modal/pays_leader_add.php';
appendQueryString($editFormAction);

$pays_ID = isset($_GET['pays_ID']) ? (int)$_GET['pays_ID'] : 0;

$thisPays = new Pays($pays_ID);

// Traitement données POST
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "pays_leader_add")) {

    if($_SESSION['userObject']->minStatus('OCGC', '<') &&
       $thisPays->getUserPermission() < Pays::$permissions['dirigeant']) {
        getErrorMessage('error', "Accès interdit");
        exit;
    }

    $user_ID = User::getUserIdFromLogin($_POST['user_login']);
    if($user_ID === null) {
        getErrorMessage('error', "Cet utilisateur n'existe pas.");
    }

    else {
        $permissions = $_POST['permissions'];
        $thisUser = new User($user_ID);
        $thisPays->addLeader($thisUser, $permissions);
        getErrorMessage('success', "{$thisUser->ch_use_login} a été ajouté comme "
            . Pays::getPermissionName($permissions) . " !");
    }

    $adresse = DEF_URI_PATH . 'back/page_pays_back.php?paysID=' . $thisPays->ch_pay_id . '#dirigeants';
    header(sprintf("Location: %s", $adresse));

    exit;

} ?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="pays_leader_edit" method="POST" class="form-horizontal" id="ajout-mon_categorie">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Ajouter des droits d'accès à un membre</h3>
        <h4><?= $thisPays->ch_pay_nom ?></h4>
  </div>
  <div class="modal-body">
      <label for="form_user_login">Pseudo de l'utilisateur : </label>
      <input name="user_login" id="form_user_login" type="text" value="">
      <ul>
        <li><input id="form_permission_dirigeant" name="permissions" type="radio" value="10">
            <label for="form_permission_dirigeant">Dirigeant</label>
        <small>Le dirigeant peut modifier tous les aspects du pays. Il peut définir d'autres dirigeants, les destituer, mais aussi modifier la présentation et créer, modifier et supprimer les villes du pays.</small></li>
        <li><input id="form_permission_codirigeant" name="permissions" type="radio" value="5">
            <label for="form_permission_codirigeant">Co-dirigeant</label>
        <small>Le co-dirigeant peut créer, modifier et supprimer la présentation du pays et les villes du pays.</small></li>
      </ul>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_insert" value="pays_leader_add">
</form>
