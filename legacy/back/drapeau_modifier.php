<?php
 
//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');
if(!isset($_SESSION['userObject'])) {
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

//R�cup�ration variables
$colname_pays = isset($_SESSION['paysID']) ? $_SESSION['paysID'] : 0;
if (isset($_POST['paysID'])) {
  $colname_pays = (int)$_POST['paysID'];
  unset($_POST['paysID']);
}

$thisPays = new \GenCity\Monde\Pays($colname_pays);

?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Modifier le drapeau de <?= __s($thisPays->get('ch_pay_nom')) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('../assets/img/ImgIntroheader.jpg');
}
</style>

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!-- Navbar
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/navbar.php'); ?>

<!-- Page CONTENT
    ================================================== -->
<div class="container corps-page">
  <div class="row-fluid">
 	<div id="info-generales" class="titre-vert anchor">
          <h1>Modifier le drapeau de votre pays</h1>
        </div>

    <ul class="breadcrumb">
      <li class="active"><a href="page_pays_back.php?paysID=<?= __s($thisPays->get('ch_pay_id')) ?>">Gestion du pays : <?= __s($thisPays->get('ch_pay_nom')) ?></a></li> <span class="divider">/</span>
      <li class="active">Modifier le drapeau de votre pays</li>
    </ul>

    <section>
      <?php require(DEF_LEGACYROOTPATH . 'php/upload.php');
if (isset($uploadconfirm)) {
  $updateSQL = sprintf("UPDATE pays SET ch_pay_lien_imgdrapeau=%s WHERE ch_pay_id=%s",
                       GetSQLValueString($link, "text"),
                       GetSQLValueString($colname_pays, "int"));
  
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
}


$query_drapeau = sprintf("SELECT ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau FROM pays WHERE ch_pay_id = %s", GetSQLValueString($colname_pays, "int"));
$drapeau = mysql_query($query_drapeau, $maconnexion) or die(mysql_error());
$row_drapeau = mysql_fetch_assoc($drapeau);
$totalRows_drapeau = mysql_num_rows($drapeau);
?>
      
      <!-- Debut formulaire -->
      <div class="well well-large"> 
        <!-- Image de contr�le drapeau --> 
        <img src="<?= e($row_drapeau['ch_pay_lien_imgdrapeau']) ?>" alt="Drapeau du pays n�<?= e($row_drapeau['ch_pay_id']) ?>" title="drapeau <?= e($row_drapeau['ch_pay_nom']) ?>">
        <p>&nbsp;</p>
        <p>Drapeau du pays <?= e($row_drapeau['ch_pay_nom']) ?>
        <p>&nbsp;</p>
        <form action="<?= DEF_URI_PATH ?>back/drapeau_modifier.php" method="post" enctype="multipart/form-data">
          <input type="file" name="fileToUpload" id="fileToUpload" data-filename-placement="inside" title="Choisir une nouvelle image">
          <input name="userID" id="userID" type="hidden" value="<?= e($_SESSION['user_ID']) ?>">
          <input name="paysID" id="paysID" type="hidden" value="<?php echo $colname_pays; ?>">
          <input name="maxwidth" id="maxwidth" type="hidden" value="250">
          <input name="ThumbMaxwidth" id="ThumbMaxwidth" type="hidden" value="100">
          <input name="SmallThumbMaxwidth" id="SmallThumbMaxwidth" type="hidden" value="25">
          <input type="submit" name="submit" value="Envoyer" class="btn btn-primary"/>
          <a class="btn btn-primary" href="page_pays_back.php?paysID=<?= $colname_pays ?>" title="Retour &agrave; la page de gestion de votre pays">Retour</a>
        </form>
      </div>
    </section>
    <!-- END CONTENT
    ================================================== --> 
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/footerback.php'); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script src="../assets/js/bootstrap-filestyle.js"></script>
<script type="text/javascript">
    $('input[type=file]').bootstrapFileInput();
</script>
<script type="text/javascript">
    $(function () {
        $('[rel="clickover"]').clickover();
    })
</script>
</body>
</html>
