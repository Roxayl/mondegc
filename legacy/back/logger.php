<?php

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if (!($_SESSION['statut'] and ($_SESSION['statut'] >= 20))) {
    // Redirection vers page connexion
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

// Find out how many items are in the table
$total = \GenCity\Monde\Logger\Log::getTotal();

// How many items to list per page
$limit = 20;
// How many pages will there be
$pages = ceil($total / $limit);
// What page are we currently on?
$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
    'options' => array(
        'default'   => 1,
        'min_range' => 1,
    ),
)));

// Calculate the offset for the query
$offset = ($page - 1)  * $limit;

// Some information to display to the user
$start = $offset + 1;
$end = min(($offset + $limit), $total);

// The "back" link
$prevlink = ($page > 1) ? '<li><a href="?page=1" title="Page 1">&laquo;</a></li> <li><a href="?page=' . ($page - 1) . '" title="Page précédente">&lsaquo;</a></li>' : '<li><span class="disabled">&laquo;</span></li> <li><span class="disabled">&lsaquo;</span></li>';

// The "forward" link
$nextlink = ($page < $pages) ? '<li><a href="?page=' . ($page + 1) . '" title="Page suivante">&rsaquo;</a></li> <li><a href="?page=' . $pages . '" title="Dernière page">&raquo;</a></li>' : '<li><span class="disabled">&rsaquo;</span></li> <li><span class="disabled">&raquo;</span></li>';

/** @var \GenCity\Monde\Logger\Log[] $logs */
$logs = \GenCity\Monde\Logger\Log::getAll($limit, $offset);


?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Journalisation</title>
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
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140">
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
    <h1>Journalisation</h1>
  </div>
  <section>
    <div class="well" id="categories">

        <?= app(\Roxayl\MondeGC\View\Components\Blocks\Flash::class)->render(); ?>

        <p>Cette page contient les actions d'administration effectuées par les utilisateurs, conservées à des fins
            d'historique.</p>

        <?php
        // Display the paging information
        echo '<div class="pagination pull-right" style="margin-top: 0; margin-right: 10px;"><ul>',
            $prevlink,
            "<li><span class='btn-small'>Page $page sur $pages</span></li>",
            $nextlink,
            '</ul></div>';
        ?>

        <h3>Éléments du journal</h3>

        <?php if(count($logs)): ?>

            <?php renderElement('logger/log_list', array('logs' => $logs)); ?>

        <?php else: ?>
            <p>Aucun élément dans le journal.</p>
        <?php endif; ?>

        <?php
        // Display the paging information
        echo '<div class="pagination pull-right" style="margin-top: 0; margin-right: 10px;"><ul>',
            $prevlink,
            "<li><span class='btn-small'>Page $page sur $pages</span></li>",
            $nextlink,
            '</ul></div>';
        ?>

    </div>
  </section>
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