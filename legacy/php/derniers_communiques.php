<?php 



// *** Connexion communique categorie pays

$maxRows_LastCommuniquePays = 3;
$pageNum_LastCommuniquePays = 0;
if (isset($_GET['pageNum_LastCommuniquePays'])) {
  $pageNum_LastCommuniquePays = $_GET['pageNum_LastCommuniquePays'];
}
$startRow_LastCommuniquePays = $pageNum_LastCommuniquePays * $maxRows_LastCommuniquePays;


$query_LastCommuniquePays = "SELECT ch_com_ID, ch_com_statut, ch_com_categorie, ch_com_element_id, ch_com_user_id, ch_com_date, ch_com_titre, ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, ch_use_lien_imgpersonnage, ch_use_nom_dirigeant, ch_use_paysID, ch_use_prenom_dirigeant, ch_use_titre_dirigeant FROM communiques INNER JOIN pays ON ch_com_element_id = ch_pay_id INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_statut = 1 AND ch_com_categorie='pays' OR ch_com_categorie='com_pays' ORDER BY ch_com_date DESC";
$query_limit_LastCommuniquePays = sprintf("%s LIMIT %d, %d", $query_LastCommuniquePays, $startRow_LastCommuniquePays, $maxRows_LastCommuniquePays);
$LastCommuniquePays = mysql_query($query_limit_LastCommuniquePays, $maconnexion);
$row_LastCommuniquePays = mysql_fetch_assoc($LastCommuniquePays);

if (isset($_GET['totalRows_LastCommuniquePays'])) {
  $totalRows_LastCommuniquePays = $_GET['totalRows_LastCommuniquePays'];
} else {
  $all_LastCommuniquePays = mysql_query($query_LastCommuniquePays);
  $totalRows_LastCommuniquePays = mysql_num_rows($all_LastCommuniquePays);
}
$totalPages_LastCommuniquePays = ceil($totalRows_LastCommuniquePays/$maxRows_LastCommuniquePays)-1;

// *** Connexion communique categorie villes

$maxRows_LastCommuniqueVilles = 3;
$pageNum_LastCommuniqueVilles = 0;
if (isset($_GET['pageNum_LastCommuniqueVilles'])) {
  $pageNum_LastCommuniqueVilles = $_GET['pageNum_LastCommuniqueVilles'];
}
$startRow_LastCommuniqueVilles = $pageNum_LastCommuniqueVilles * $maxRows_LastCommuniqueVilles;


$query_LastCommuniqueVilles = "SELECT ch_com_ID, ch_com_statut, ch_com_categorie, ch_com_element_id, ch_com_user_id, ch_com_date, ch_com_titre, ch_vil_ID, ch_vil_paysID, ch_vil_nom, ch_vil_armoiries, ch_use_lien_imgpersonnage, ch_use_nom_dirigeant, ch_use_paysID, ch_use_prenom_dirigeant, ch_use_titre_dirigeant FROM communiques INNER JOIN villes ON ch_com_element_id = ch_vil_ID INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_statut = 1 AND ch_com_categorie ='ville' OR ch_com_categorie ='com_ville' ORDER BY ch_com_date DESC";
$query_limit_LastCommuniqueVilles = sprintf("%s LIMIT %d, %d", $query_LastCommuniqueVilles, $startRow_LastCommuniqueVilles, $maxRows_LastCommuniqueVilles);
$LastCommuniqueVilles = mysql_query($query_limit_LastCommuniqueVilles, $maconnexion);
$row_LastCommuniqueVilles = mysql_fetch_assoc($LastCommuniqueVilles);

if (isset($_GET['totalRows_LastCommuniqueVilles'])) {
  $totalRows_LastCommuniqueVilles = $_GET['totalRows_LastCommuniqueVilles'];
} else {
  $all_LastCommuniqueVilles = mysql_query($query_LastCommuniqueVilles);
  $totalRows_LastCommuniqueVilles = mysql_num_rows($all_LastCommuniqueVilles);
}
$totalPages_LastCommuniqueVilles = ceil($totalRows_LastCommuniqueVilles/$maxRows_LastCommuniqueVilles)-1;

// *** Connexion communique categorie communiques

$maxRows_LastCommuniqueReaction = 3;
$pageNum_LastCommuniqueReaction = 0;
if (isset($_GET['pageNum_LastCommuniqueReaction'])) {
  $pageNum_LastCommuniqueReaction = $_GET['pageNum_LastCommuniqueReaction'];
}
$startRow_LastCommuniqueReaction = $pageNum_LastCommuniqueReaction * $maxRows_LastCommuniqueReaction;


$query_LastCommuniqueReaction = "SELECT ch_com_ID, ch_com_statut, ch_com_categorie, ch_com_element_id, ch_com_user_id, ch_com_date, ch_com_titre, ch_use_lien_imgpersonnage, ch_use_nom_dirigeant, ch_use_paysID, ch_use_prenom_dirigeant, ch_use_titre_dirigeant FROM communiques INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_statut = 1 AND ch_com_categorie ='com_communique' ORDER BY ch_com_date DESC";
$query_limit_LastCommuniqueReaction = sprintf("%s LIMIT %d, %d", $query_LastCommuniqueReaction, $startRow_LastCommuniqueReaction, $maxRows_LastCommuniqueReaction);
$LastCommuniqueReaction = mysql_query($query_limit_LastCommuniqueReaction, $maconnexion);
$row_LastCommuniqueReaction = mysql_fetch_assoc($LastCommuniqueReaction);

if (isset($_GET['totalRows_LastCommuniqueReaction'])) {
  $totalRows_LastCommuniqueReaction = $_GET['totalRows_LastCommuniqueReaction'];
} else {
  $all_LastCommuniqueReaction = mysql_query($query_LastCommuniqueReaction);
  $totalRows_LastCommuniqueReaction = mysql_num_rows($all_LastCommuniqueReaction);
}
$totalPages_LastCommuniqueReaction = ceil($totalRows_LastCommuniqueReaction/$maxRows_LastCommuniqueReaction)-1;
?>

<!-- CATEGORIE Dernières actualités
================================================== -->

<div class="titre-vert"> <img src="assets/img/IconesBDD/100/Membre1.png" alt="icone user">
  <h1>Derni&egrave;res actualit&eacute;s</h1>
</div>
<div class="row-fluid"> 
  <!-- Titre Categorie pays
================================================== -->
  <div class="span4">
    <div class="titre-gris"> <img src="assets/img/IconesBDD/100/Pays1.png" alt="icone pays">
      <h3>Dans les pays</h3>
    </div>
    
    <!-- Categorie pays
================================================== -->
    <ul class="notification">
      <?php do { ?>
        <li> 
          <!-- Si c'est un commentaire sur le pays
================================================== -->
          <?php if ( $row_LastCommuniquePays['ch_com_categorie'] == "com_pays") {?>
          <div class="avatar"> <a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_use_paysID']) ?>#diplomatie"><img src="<?= e($row_LastCommuniquePays['ch_use_lien_imgpersonnage']) ?>" alt="dirigeant"></a> <a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_pay_id']) ?>#commentaireID<?= e($row_LastCommuniquePays['ch_com_ID']) ?>"><img src="<?= e($row_LastCommuniquePays['ch_pay_lien_imgdrapeau']) ?>" alt="pays"></a> </div>
          <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommuniquePays['ch_com_date'])); ?> &agrave; <?php  echo date("G:i", strtotime($row_LastCommuniquePays['ch_com_date'])); ?>
          </small>
          <div class="arrow_box">
            <p><a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_use_paysID']) ?>#diplomatie"> <?= e($row_LastCommuniquePays['ch_use_prenom_dirigeant']) ?> <?= e($row_LastCommuniquePays['ch_use_nom_dirigeant']) ?></a> <?= e($row_LastCommuniquePays['ch_use_titre_dirigeant']) ?> a visit&eacute; le pays <a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_pay_id']) ?>#commentaireID<?= e($row_LastCommuniquePays['ch_com_ID']) ?>"> <?= e($row_LastCommuniquePays['ch_pay_nom']) ?></a></p>
          </div>
          <div class="clearfix"></div>
          <hr>
          <?php } ?>
          <!-- Si c'est un communique emmanant du pays
================================================== -->
          <?php if ( $row_LastCommuniquePays['ch_com_categorie'] == "pays") {?>
          <div class="avatar"> <a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_use_paysID']) ?>#diplomatie"><img src="<?= e($row_LastCommuniquePays['ch_use_lien_imgpersonnage']) ?>" alt="dirigeant"></a> <a href="pays/page-communique.php?com_id=<?= e($row_LastCommuniquePays['ch_com_ID']) ?>"><img src="<?= e($row_LastCommuniquePays['ch_pay_lien_imgdrapeau']) ?>" alt="pays"></a> </div>
          <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommuniquePays['ch_com_date'])); ?> &agrave; <?php  echo date("G:i", strtotime($row_LastCommuniquePays['ch_com_date'])); ?>
          </small>
          <div class="arrow_box">
            <p><a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_use_paysID']) ?>#diplomatie"> <?= e($row_LastCommuniquePays['ch_use_prenom_dirigeant']) ?> <?= e($row_LastCommuniquePays['ch_use_nom_dirigeant']) ?></a> <?= e($row_LastCommuniquePays['ch_use_titre_dirigeant']) ?> a lan&ccedil;&eacute; un communiqu&eacute; au nom de son pays <a href="pays/page-communique.php?com_id=<?= e($row_LastCommuniquePays['ch_com_ID']) ?>"> <?= e($row_LastCommuniquePays['ch_pay_nom']) ?></a></p>
          </div>
          <div class="clearfix"></div>
          <hr>
          <?php } ?>
        </li>
        <?php } while ($row_LastCommuniquePays = mysql_fetch_assoc($LastCommuniquePays)); ?>
    </ul>
    <div class="pull-center"><a href="#" class="btn btn-primary">Afficher la suite</a></div>
  </div>
  
  <!-- Titre Categorie villes
================================================== -->
  <div class="span4">
    <div class="titre-gris"> <img src="assets/img/IconesBDD/100/Ville1.png" alt="icone ville">
      <h3>Dans les villes</h3>
    </div>
    
    <!-- Categorie villes
================================================== -->
    <ul class="notification">
      <?php do { ?>
        <li> 
          <!-- Si c'est un commentaire sur la ville
================================================== -->
          <?php if ( $row_LastCommuniqueVilles['ch_com_categorie'] == "com_ville") {?>
          <div class="avatar"> <a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_use_paysID']) ?>#diplomatie"><img src="<?= e($row_LastCommuniqueVilles['ch_use_lien_imgpersonnage']) ?>" alt="dirigeant"></a> 
          <?php if ($row_LastCommuniqueVilles['ch_vil_armoiries']) {?>
          <a href="pays/page-ville.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_vil_paysID']) ?>&ch_ville_id=<?= e($row_LastCommuniqueVilles['ch_vil_ID']) ?>#commentaireID<?= e($row_LastCommuniqueVilles['ch_com_ID']) ?>"><img src="<?= e($row_LastCommuniqueVilles['ch_vil_armoiries']) ?>" alt="armoiries"></a><?php } ?>
          </div>
          <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommuniqueVilles['ch_com_date'])); ?> &agrave; <?php  echo date("G:i", strtotime($row_LastCommuniqueVilles['ch_com_date'])); ?>
          </small>
          <div class="arrow_box">
            <p><a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_use_paysID']) ?>#diplomatie"> <?= e($row_LastCommuniqueVilles['ch_use_prenom_dirigeant']) ?> <?= e($row_LastCommuniqueVilles['ch_use_nom_dirigeant']) ?></a> <?= e($row_LastCommuniqueVilles['ch_use_titre_dirigeant']) ?> a visit&eacute; la ville <a href="pays/page-ville.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_vil_paysID']) ?>&ch_ville_id=<?= e($row_LastCommuniqueVilles['ch_vil_ID']) ?>#commentaireID<?= e($row_LastCommuniqueVilles['ch_com_ID']) ?>"><?= e($row_LastCommuniqueVilles['ch_vil_nom']) ?></a></p>
          </div>
          <div class="clearfix"></div>
          <hr>
          <?php } ?>
          <!-- Si c'est un communique emmanant de la ville
================================================== -->
          <?php if ( $row_LastCommuniqueVilles['ch_com_categorie'] == "ville") {?>
          <div class="avatar"> <a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_use_paysID']) ?>#diplomatie"><img src="<?= e($row_LastCommuniqueVilles['ch_use_lien_imgpersonnage']) ?>" alt="dirigeant"></a>
          <?php if ($row_LastCommuniqueVilles['ch_vil_armoiries']) {?>
          <a href="pays/page-communique.php?com_id=<?= e($row_LastCommuniqueVilles['ch_com_ID']) ?>"><img src="<?= e($row_LastCommuniqueVilles['ch_vil_armoiries']) ?>" alt="armoiries"></a>
          <?php } ?>
          </div>
          <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommuniqueVilles['ch_com_date'])); ?> &agrave; <?php  echo date("G:i", strtotime($row_LastCommuniqueVilles['ch_com_date'])); ?>
          </small>
          <div class="arrow_box">
            <p><a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_use_paysID']) ?>#diplomatie"> <?= e($row_LastCommuniqueVilles['ch_use_prenom_dirigeant']) ?> <?= e($row_LastCommuniqueVilles['ch_use_nom_dirigeant']) ?></a> <?= e($row_LastCommuniqueVilles['ch_use_titre_dirigeant']) ?> a lan&ccedil;&eacute; un communiqu&eacute; au nom de la ville <a href="pays/page-communique.php?com_id=<?= e($row_LastCommuniqueVilles['ch_com_ID']) ?>"> <?= e($row_LastCommuniqueVilles['ch_vil_nom']) ?></a></p>
          </div>
          <div class="clearfix"></div>
          <hr>
          <?php } ?>
        </li>
        <?php } while ($row_LastCommuniqueVilles = mysql_fetch_assoc($LastCommuniqueVilles)); ?>
    </ul>
    <div class="pull-center"><a href="#" class="btn btn-primary">Afficher la suite</a></div>
  </div>
  
  <!-- TITRE Categorie communiqués
================================================== -->
  <div class="span4">
    <div class="titre-gris"> <img src="assets/img/IconesBDD/100/Communique.png" alt="icone ville">
      <h3>R&eacute;actions Officielles</h3>
    </div>
    
    <!-- Categorie communiqués
================================================== -->
    <ul class="notification">
      <?php do { ?>
        <li>
          <div class="avatar"> <a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueReaction['ch_use_paysID']) ?>#diplomatie"><img src="<?= e($row_LastCommuniqueReaction['ch_use_lien_imgpersonnage']) ?>" alt="dirigeant"></a> </div>
          <small>le
          <?php  echo date("d/m/Y", strtotime($row_LastCommuniqueReaction['ch_com_date'])); ?> &agrave; <?php  echo date("G:i", strtotime($row_LastCommuniqueReaction['ch_com_date'])); ?>
          </small>
          <div class="arrow_box">
            <p><em><a href="pays/page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueReaction['ch_use_paysID']) ?>#diplomatie"> <?= e($row_LastCommuniqueReaction['ch_use_prenom_dirigeant']) ?> <?= e($row_LastCommuniqueReaction['ch_use_nom_dirigeant']) ?></a></em> <?= e($row_LastCommuniqueReaction['ch_use_titre_dirigeant']) ?> a r&eacute;agit <em><a href="pays/page-communique.php?com_id=<?= e($row_LastCommuniqueReaction['ch_com_element_id']) ?>#commentaireID<?= e($row_LastCommuniqueReaction['ch_com_ID']) ?>">&agrave; un communiqu&eacute;.</a></em></p>
          </div>
          <div class="clearfix"></div>
          <hr>
        </li>
        <?php } while ($row_LastCommuniqueReaction = mysql_fetch_assoc($LastCommuniqueReaction)); ?>
    </ul>
    <div class="pull-center"><a href="#" class="btn btn-primary">Afficher la suite</a></div>
  </div>
</div>
<?php

mysql_free_result($LastCommuniquePays);

mysql_free_result($LastCommuniqueVilles);

mysql_free_result($LastCommuniqueReaction);
?>
