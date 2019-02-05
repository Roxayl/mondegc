<?php
include('Connections/maconnexion.php');


//deconnexion
include('php/log.php');

mysql_select_db($database_maconnexion, $maconnexion);
$query_HautConseil = "SELECT ch_use_login, ch_use_statut FROM users WHERE ch_use_statut = 15 ORDER BY ch_use_login ASC";
$HautConseil = mysql_query($query_HautConseil, $maconnexion) or die(mysql_error());
$row_HautConseil = mysql_fetch_assoc($HautConseil);
$totalRows_HautConseil = mysql_num_rows($HautConseil);
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="iso-8859-1">
<title>Juges temp&eacute;rants</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
<link rel="shortcut icon" href="assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('assets/img/fond_haut-conseil.jpg');
	background-position: center;
}
</style>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div id="introheader" class="jumbotron">
  <div class="container">
  <div class="pull-right span5" style="text-align:right;">
  <p>&nbsp;</p>
      <a href="economie.php" class="btn btn-primary">Retour Institut d'Economie</a>
    </div>
    <div class="span5 align-left">
      <h2>Qui sont les juges temp&eacute;rants&nbsp;?</h2>
      <p>En 2013 et par la volont&eacute; de la majorit&eacute; des membres du monde GC,  trois juges dits Temp&eacute;rants ont &eacute;t&eacute; choisis pour mener, avec  impartialit&eacute; et justice, deux missions nouvelles dans le but de  d&eacute;velopper notre communaut&eacute; et lui proposer les meilleurs outils.</p>
      <h5>Juges temp&eacute;rants sont&nbsp;:</h5>
      <span><em style="color: #FF9900">Sakuro</em> -</span>
      <?php do { ?>
        <?php if ($row_HautConseil['ch_use_statut']==30) { ?>
        <span><em style="color: #FF4F4F"><?php echo $row_HautConseil['ch_use_login'] ?></em> -</span>
        <?php } elseif ($row_HautConseil['ch_use_statut']==20) { ?>
        <span><em style="color: #FF9900"><?php echo $row_HautConseil['ch_use_login'] ?></em> -</span>
        <?php } else { ?>
        <span><em><?php echo $row_HautConseil['ch_use_login'] ?></em> -</span>
        <?php } ?>
        <?php } while ($row_HautConseil = mysql_fetch_assoc($HautConseil)); ?>
    </div>
  </div>
</div>
<div class="container">
  <div class="corps-page">
    <div class="row-fluid">
      <div class="titre-bleu anchor" id="presentation"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
        <h1>2 missions pour les juges tempérants</h1>
      </div>
      <h3>Validation des infrastructures</h3>
      <div class="well">
        <p> La  premi&egrave;re mission des juges consiste &agrave; valider ou non les infrastructures propos&eacute;es par chaque membre volontaire. En mettant &agrave;  jour chacune de leur ville, un repr&eacute;sentant d'un pays ou un maire  partenaire peut effectivement proposer une <a href="liste infrastructures.php" title="lien vers la listes compl&egrave;te des infrastructures">multitude d'infrastructures</a>  qu'il poss&egrave;de et qui influencera le nouveau syst&egrave;me de ressource de  notre communaut&eacute; si elles sont valid&eacute;es.</p>
        <p><em> "Oublions le syst&egrave;me de tokens, jouons avec nos v&eacute;ritables ressources !"</em></p>
        <p> Un  refus d'infrastructure ne signifie pas un mauvais travail ou un vote  sanction. Il implique la n&eacute;cessit&eacute; de d&eacute;velopper une structure qui ne  satisferait pas les indices de validation mis en place (et disponible en  choisissant une cat&eacute;gorie d'infrastructures dans la liste) ou le  changement d'image permettant aux juges de mieux observer le type  d'infrastructure propos&eacute; pour la valider.</p>
        <p>&nbsp; </p>
      </div>
      <h3>Notation et coh&eacute;rence avec le projet Temp&eacute;rance</h3>
      <div class="well">
        <p>Leur  deuxi&egrave;me mission est de noter, &agrave; l'instar des notations de banques dans  le monde r&eacute;el, la cr&eacute;dibilit&eacute; des chiffres affich&eacute;s avec la mise en  sc&egrave;ne des villes ou des pays propos&eacute;s par les membres. Une note sera  affect&eacute;e suite &agrave; l'&eacute;tude d'un certain nombre de crit&egrave;res intangibles et  sera accompagn&eacute;e d'un rapport mettant en sc&egrave;ne les commentaires des  trois juges. Ainsi, la note obtenue et absolument fictive permettra  d'influencer le comportement des membres, dans leur r&ocirc;le-playing, dans  leur relation avec le membre jug&eacute;. Ce projet n'est pas con&ccedil;u pour  d&eacute;valoriser les conceptions des membres et ne remet pas en cause leur  choix de cr&eacute;ation. Il apporte simplement une conception nouvelle cr&eacute;ant  une homog&eacute;n&eacute;it&eacute; des chiffres et invite, tout comme l'outil des  infrastructures, &agrave; d&eacute;velopper ses cr&eacute;ations.</p>
        <p>Pour plus de renseignements, rendez-vous sur <a href="Projet-temperance.php">"En savoir plus"</a> sur le projet Temp&eacute;rance.</p>
      </div>
      <p>&nbsp;</p>
    </div>
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<?php
mysql_free_result($HautConseil);
?>
