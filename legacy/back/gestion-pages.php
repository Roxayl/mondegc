<?php

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if (!($_SESSION['statut'] and ($_SESSION['statut'] >= 20))) {
    // Redirection vers page connexion
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

/** @var \GenCity\Monde\Page[] $allPages */
$allPages = \GenCity\Monde\Page::getAllPages();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach($allPages as $thisPage) {
        $oldPage = new \GenCity\Monde\Page($thisPage->get('this_id'));
        $thisContent = $_POST['ch_page_' . $thisPage->this_id];
        $thisPage->updatePage($thisContent);
        if($oldPage->get('content') !== $thisContent) {
            \GenCity\Monde\Logger\Log::createItem('pages', $thisPage->get('id'), 'update',
                null, array('entity' => $thisPage->model->getInfo(), 'old_entity' => $oldPage->model->getInfo()));
        }
    }
    getErrorMessage('success', "Les pages ont été modifiées avec succès !");
    $allPages = \GenCity\Monde\Page::getAllPages();
}

?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Gestion des pages</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('../assets/img/fond_haut-conseil.jpg');
}
</style>

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/navbar.php'); ?>
<!-- Navbar haut-conseil
    ================================================== -->
<div class="container corps-page">
<?php require(DEF_LEGACYROOTPATH . 'php/menu-haut-conseil.php'); ?>
  <!-- Page CONTENT
    ================================================== -->
  <div class="titre-bleu">
    <h1>Gestion des pages</h1>
  </div>
  <section>
    <div class="well" id="categories">

        <?= app(\Roxayl\MondeGC\View\Components\Blocks\Flash::class)->render(); ?>

        <p>Bienvenue sur la page de gestion des pages (huhu...). Vous pouvez modifier des blocs de texte prédéfinis
            via le panneau d'administration.</p>

    <form method="POST">

    <?php foreach($allPages as $thisPage): ?>

        <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" href="#collapse_<?= $thisPage->this_id ?>"
                ><?= $thisPage->this_id ?></a>
        </div>
        <div id="collapse_<?= $thisPage->this_id ?>" class="accordion-body collapse">
            <div class="accordion-inner">
                <div class="control-group">
                  <label class="control-label" for="ch_page_<?= $thisPage->this_id ?>">
                      <?= $thisPage->this_id ?>
                  </label>
                  <div class="controls">
                    <textarea name="ch_page_<?= $thisPage->this_id ?>" id="ch_page_<?= $thisPage->this_id ?>" class="wysiwyg" rows="15"><?= __s($thisPage->content) ?></textarea>
                  </div>
                </div>
            </div>
        </div>
        </div>

    <?php endforeach; ?>

    <br>
    <input type="submit" value="Envoyer" class="btn btn-primary">

    </form>

    </div>
  </section>
</div>
</div>
<!-- Footer
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/footer.php'); ?>

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
<!-- EDITEUR -->
<script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../assets/js/Editeur.js"></script>
<script type="text/javascript">
    $(function () {
        $('[rel="clickover"]').clickover();
    })
</script>
</body>
</html>