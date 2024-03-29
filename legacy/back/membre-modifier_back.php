<?php

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if(!isset($_SESSION['userObject'])) {
    // Redirection vers page de connexion
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

$_SESSION['last_work'] = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php' . '?' . $_SERVER['QUERY_STRING'];

//Recuperation variables
$colname_User = $_SESSION['Temp_userID'];
if(isset($_REQUEST['userID'])) {
    $_SESSION['Temp_userID'] = $_REQUEST['userID'];
    $colname_User = $_SESSION['Temp_userID'];
    unset($_REQUEST['userID']);
}

$query_User = sprintf("SELECT * FROM users WHERE ch_use_id = %s", escape_sql($colname_User, "int"));
$User = mysql_query($query_User, $maconnexion);
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

//Mise a jour parametres donnees personnelles
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ProfilUser")) {
    $salt = config('legacy.salt');
    if(isset ($_POST['ch-use_password'])) {
        $hashed_password = md5($_POST['ch-use_password'] . $salt);
        unset($_POST['ch-use_password']);
    } else {
        $hashed_password = $row_User['ch_use_password'];
    }

    if($_POST['ch_use_acces_Checkbox'] == 1) {
        $banni = "";
    } else {
        $banni = 1;
    }

    $updateSQL = sprintf("UPDATE users SET ch_use_acces=%s, ch_use_statut=%s, ch_use_paysID=%s, ch_use_login=%s, ch_use_password=%s, ch_use_mail=%s WHERE ch_use_id=%s",
        escape_sql($banni, "int"),
        escape_sql($_POST['ch_use_statut'], "int"),
        escape_sql($_POST['ch_use_paysID'], "int"),
        escape_sql($_POST['ch_use_login'], "text"),
        escape_sql($hashed_password, "text"),
        escape_sql($_POST['ch_use_mail'], "text"),
        escape_sql($_POST['ch_use_id'], "int"));

    $Result1 = mysql_query($updateSQL, $maconnexion);

    $updateGoTo = DEF_URI_PATH . "back/membre-modifier_back.php";
    appendQueryString($updateGoTo);
    header(sprintf("Location: %s", $updateGoTo));
    exit;
}

//Mise a jour profil infos personnage
if((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "InfoUser")) {

    $updateSQL = sprintf("UPDATE personnage SET nom_personnage = %s, predicat = %s,
                      prenom_personnage = %s, biographie = %s, titre_personnage = %s
                      WHERE id = %s",
        escape_sql($_POST['ch_use_nom_dirigeant'], 'text'),
        escape_sql($_POST['ch_use_predicat_dirigeant'], 'text'),
        escape_sql($_POST['ch_use_prenom_dirigeant'], 'text'),
        escape_sql($_POST['ch_use_biographie_dirigeant'], "text"),
        escape_sql($_POST['ch_use_titre_dirigeant'], "text"),
        escape_sql($_POST['personnage_id'], "int"));

    $selectSQL = mysql_query(sprintf('SELECT entity_id FROM personnage WHERE id = %s',
        escape_sql($_POST['personnage_id'], 'int')));
    $personnageData = mysql_fetch_assoc($selectSQL);
    $thisPays = new \GenCity\Monde\Pays($personnageData['entity_id']);

    $Result1 = mysql_query($updateSQL, $maconnexion);

    $updateGoTo = DEF_URI_PATH . "back/page_pays_back.php?paysID={$thisPays->ch_pay_id}";
    appendQueryString($updateGoTo);
    header(sprintf("Location: %s", $updateGoTo));
    exit;

}

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Gérer le compte</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="../assets/css/colorpicker.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div class="container" id="overview">

<!-- Page CONTENT
    ================================================== -->
<section class="corps-page">

    <!-- Titre
            ================================================== -->
    <div id="titre_institut" class="titre-vert anchor">
      <h1>G&eacute;rer mon compte</h1>
    </div>

    <?= app(\Roxayl\MondeGC\View\Components\Blocks\Flash::class)->render(); ?>

    <!-- Formulaire de modification du membre
         ================================================== -->
    <?php require(DEF_LEGACYROOTPATH . 'php/membre-modifier.php'); ?>

    <?php if(auth()->check() && (int)$row_User['ch_use_id'] === auth()->user()->getKey()): ?>

      <div class="row">
        <div class="span6">
        </div>
        <div class="span6">
            <h4>Outils avancés</h4>
            Jeton d'authentification à l'API :

            <?php if(auth()->user()->api_token): ?>
                <pre style="margin-right: 10px;"><?= e(auth()->user()->api_token) ?></pre>
            <?php else: ?>
                <i>Non défini</i>
            <?php endif; ?>

            <form method="POST"
                  action="<?= e(route('user.update-api-token', [auth()->user()->getKey()])) ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary btn-lg">
                    Générer
                </button>
            </form>
        </div>
      </div>

    <?php endif; ?>

</section>

</div>
<!-- END CONTENT
    ================================================== --> 

<!-- Footer
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/footer.php'); ?>

<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() {
          $('[rel="clickover"]').clickover();})
    </script>
<!-- MODAL -->
<script src="../assets/js/bootstrap-modalmanager.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>

<script type="text/javascript">
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {minChars:2, maxChars:30, validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "url", {minChars:2, maxChars:250, validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {maxChars:400, validateOn:["change"], isRequired:false, useCharacterMasking:false});
</script>
</body>
</html>
