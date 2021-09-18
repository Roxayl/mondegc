<?php 
// *** variable dernière visite
$dernierevisite = $_SESSION['derniere_visite'];

// *** Connexion communique categorie pays
$maxRows_LastCommuniquePays = 50;
$pageNum_LastCommuniquePays = 0;
if (isset($_GET['pageNum_LastCommuniquePays'])) {
  $pageNum_LastCommuniquePays = $_GET['pageNum_LastCommuniquePays'];
}
$startRow_LastCommuniquePays = $pageNum_LastCommuniquePays * $maxRows_LastCommuniquePays;



$query_LastCommuniquePays = "SELECT ch_com_ID, ch_com_statut, ch_com_categorie, ch_com_element_id, ch_com_user_id, ch_com_date, ch_com_titre, ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, ch_use_lien_imgpersonnage, ch_use_nom_dirigeant, ch_use_paysID, ch_use_prenom_dirigeant, ch_use_titre_dirigeant FROM communiques INNER JOIN pays ON ch_com_element_id = ch_pay_id INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_statut = 1 AND ch_com_date >= '$dernierevisite' AND (ch_com_categorie='pays' OR ch_com_categorie='com_pays') ORDER BY ch_com_date DESC";
$query_limit_LastCommuniquePays = sprintf("%s LIMIT %d, %d", $query_LastCommuniquePays, $startRow_LastCommuniquePays, $maxRows_LastCommuniquePays);
$LastCommuniquePays = mysql_query($query_limit_LastCommuniquePays, $maconnexion) or die(mysql_error());
$row_LastCommuniquePays = mysql_fetch_assoc($LastCommuniquePays);

if (isset($_GET['totalRows_LastCommuniquePays'])) {
  $totalRows_LastCommuniquePays = $_GET['totalRows_LastCommuniquePays'];
} else {
  $all_LastCommuniquePays = mysql_query($query_LastCommuniquePays);
  $totalRows_LastCommuniquePays = mysql_num_rows($all_LastCommuniquePays);
}
$totalPages_LastCommuniquePays = ceil($totalRows_LastCommuniquePays/$maxRows_LastCommuniquePays)-1;

// *** Connexion communique categorie villes

$maxRows_LastCommuniqueVilles = 50;
$pageNum_LastCommuniqueVilles = 0;
if (isset($_GET['pageNum_LastCommuniqueVilles'])) {
  $pageNum_LastCommuniqueVilles = $_GET['pageNum_LastCommuniqueVilles'];
}
$startRow_LastCommuniqueVilles = $pageNum_LastCommuniqueVilles * $maxRows_LastCommuniqueVilles;


$query_LastCommuniqueVilles = "SELECT ch_com_ID, ch_com_statut, ch_com_categorie, ch_com_element_id, ch_com_user_id, ch_com_date, ch_com_titre, ch_vil_ID, ch_vil_paysID, ch_vil_nom, ch_vil_armoiries, ch_use_lien_imgpersonnage, ch_use_nom_dirigeant, ch_use_paysID, ch_use_prenom_dirigeant, ch_use_titre_dirigeant FROM communiques INNER JOIN villes ON ch_com_element_id = ch_vil_ID INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_statut = 1 AND ch_com_date >= '$dernierevisite' AND (ch_com_categorie='ville' OR ch_com_categorie='com_ville') ORDER BY ch_com_date DESC";
$query_limit_LastCommuniqueVilles = sprintf("%s LIMIT %d, %d", $query_LastCommuniqueVilles, $startRow_LastCommuniqueVilles, $maxRows_LastCommuniqueVilles);
$LastCommuniqueVilles = mysql_query($query_limit_LastCommuniqueVilles, $maconnexion) or die(mysql_error());
$row_LastCommuniqueVilles = mysql_fetch_assoc($LastCommuniqueVilles);

if (isset($_GET['totalRows_LastCommuniqueVilles'])) {
  $totalRows_LastCommuniqueVilles = $_GET['totalRows_LastCommuniqueVilles'];
} else {
  $all_LastCommuniqueVilles = mysql_query($query_LastCommuniqueVilles);
  $totalRows_LastCommuniqueVilles = mysql_num_rows($all_LastCommuniqueVilles);
}
$totalPages_LastCommuniqueVilles = ceil($totalRows_LastCommuniqueVilles/$maxRows_LastCommuniqueVilles)-1;

// *** Connexion communique categorie communiques

$maxRows_LastCommuniqueReaction = 50;
$pageNum_LastCommuniqueReaction = 0;
if (isset($_GET['pageNum_LastCommuniqueReaction'])) {
  $pageNum_LastCommuniqueReaction = $_GET['pageNum_LastCommuniqueReaction'];
}
$startRow_LastCommuniqueReaction = $pageNum_LastCommuniqueReaction * $maxRows_LastCommuniqueReaction;


$query_LastCommuniqueReaction = sprintf("SELECT ch_com_ID, ch_com_statut, ch_com_categorie, ch_com_element_id, ch_com_user_id, ch_com_date, ch_com_titre, ch_use_lien_imgpersonnage, ch_use_nom_dirigeant, ch_use_paysID, ch_use_prenom_dirigeant, ch_use_titre_dirigeant FROM communiques INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_statut = 1 AND ch_com_date >= %s AND ch_com_categorie ='com_communique' ORDER BY ch_com_date DESC", GetSQLValueString($dernierevisite, "date"));
$query_limit_LastCommuniqueReaction = sprintf("%s LIMIT %d, %d", $query_LastCommuniqueReaction, $startRow_LastCommuniqueReaction, $maxRows_LastCommuniqueReaction);
$LastCommuniqueReaction = mysql_query($query_limit_LastCommuniqueReaction, $maconnexion) or die(mysql_error());
$row_LastCommuniqueReaction = mysql_fetch_assoc($LastCommuniqueReaction);

if (isset($_GET['totalRows_LastCommuniqueReaction'])) {
  $totalRows_LastCommuniqueReaction = $_GET['totalRows_LastCommuniqueReaction'];
} else {
  $all_LastCommuniqueReaction = mysql_query($query_LastCommuniqueReaction);
  $totalRows_LastCommuniqueReaction = mysql_num_rows($all_LastCommuniqueReaction);
}
$totalPages_LastCommuniqueReaction = ceil($totalRows_LastCommuniqueReaction/$maxRows_LastCommuniqueReaction)-1;


// *** Connexion MAJ pays

$maxRows_MAJPays = 50;
$pageNum_MAJPays = 0;
if (isset($_GET['pageNum_MAJPays'])) {
  $pageNum_MAJPays = $_GET['pageNum_MAJPays'];
}
$startRow_MAJPays = $pageNum_MAJPays * $maxRows_MAJPays;


$query_MAJPays = printf("SELECT ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, ch_pay_mis_jour FROM pays WHERE ch_pay_publication = 1 AND ch_pay_mis_jour >= %s ORDER BY ch_pay_mis_jour DESC", GetSQLValueString($dernierevisite, "date"));
$query_limit_MAJPays = sprintf("%s LIMIT %d, %d", $query_MAJPays, $startRow_MAJPays, $maxRows_MAJPays);
$MAJPays = mysql_query($query_limit_MAJPays, $maconnexion) or die(mysql_error());
$row_MAJPays = mysql_fetch_assoc($MAJPays);

if (isset($_GET['totalRows_MAJPays'])) {
  $totalRows_MAJPays = $_GET['totalRows_MAJPays'];
} else {
  $all_MAJPays = mysql_query($query_MAJPays);
  $totalRows_MAJPays = mysql_num_rows($all_MAJPays);
}
$totalPages_MAJPays = ceil($totalRows_MAJPays/$maxRows_MAJPays)-1;

// *** Connexion MAJ villes

$maxRows_MAJVilles = 50;
$pageNum_MAJVilles = 0;
if (isset($_GET['pageNum_MAJVilles'])) {
  $pageNum_MAJVilles = $_GET['pageNum_MAJVilles'];
}
$startRow_MAJVilles = $pageNum_MAJVilles * $maxRows_MAJVilles;


$query_MAJVilles = "SELECT ch_vil_ID, ch_vil_paysID, ch_vil_mis_jour, ch_vil_nom, ch_vil_armoiries FROM villes WHERE ch_vil_ID <> 3 AND ch_vil_mis_jour >= '$dernierevisite' ORDER BY ch_vil_mis_jour DESC";
$query_limit_MAJVilles = sprintf("%s LIMIT %d, %d", $query_MAJVilles, $startRow_MAJVilles, $maxRows_MAJVilles);
$MAJVilles = mysql_query($query_limit_MAJVilles, $maconnexion) or die(mysql_error());
$row_MAJVilles = mysql_fetch_assoc($MAJVilles);

if (isset($_GET['totalRows_MAJVilles'])) {
  $totalRows_MAJVilles = $_GET['totalRows_MAJVilles'];
} else {
  $all_MAJVilles = mysql_query($query_MAJVilles);
  $totalRows_MAJVilles = mysql_num_rows($all_MAJVilles);
}
$totalPages_MAJVilles = ceil($totalRows_MAJVilles/$maxRows_MAJVilles)-1;
?>
<!-- Last post / Derniers communiqués
================================================== -->
<section class="span9" id="mur">
  <div class="span9">
    <div class="span9 align-left">
      <ul class="unstyled">
        <!-- Si c'est une MAJ de pays
================================================== -->
        <?php if ($row_MAJPays) {?>
        <h3><img src="../assets/img/IconesBDD/50/Pays1.png" alt="icone pays"> Mise &agrave; jour de pays</h3>
        <?php do { ?>
          <li>
            <div class="span8 alert alert-success">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <div class="span1"><a href="../page-pays.php?ch_pay_id=<?= e($row_MAJPays['ch_pay_id']) ?>"><img src="<?= e($row_MAJPays['ch_pay_lien_imgdrapeau']) ?>" alt="drapeau"></a></div>
              <div class="span5"> <small>le
                <?php  echo date("d/m/Y à G:i", strtotime($row_MAJPays['ch_pay_mis_jour'])); ?>
                &nbsp;:</small>
                <p>La page du pays <a href="../page-pays.php?ch_pay_id=<?= e($row_MAJPays['ch_pay_id']) ?>"><?= e($row_MAJPays['ch_pay_nom']) ?></a> &agrave; &eacute;t&eacute; mise &agrave; jour</p>
              </div>
            </div>
            <div class="span9"></div>
          </li>
          <?php } while ($row_MAJPays = mysql_fetch_assoc($MAJPays)); ?>
        <?php }?>
        <!-- Si c'est une MAJ de ville
================================================== -->
        <?php if ($row_MAJVilles) {?>
        <h3><img src="../assets/img/IconesBDD/50/Ville1.png" alt="icone ville"> Mise &agrave; jour de villes</h3>
        <?php do { ?>
          <li>
            <div class="span8 alert alert-block">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <div class="span1"><a href="../page-ville.php?ch_pay_id=<?= e($row_MAJVilles['ch_vil_paysID']) ?>&ch_ville_id=<?= e($row_MAJVilles['ch_vil_ID']) ?>"><img src="<?= e($row_MAJVilles['ch_vil_armoiries']) ?>" alt="armoiries"></a></div>
              <div class="span5"> <small>le
                <?php  echo date("d/m/Y à G:i", strtotime($row_MAJVilles['ch_vil_mis_jour'])); ?>
                &nbsp;:</small>
                <p>La page de la ville <a href="../page-ville.php?ch_pay_id=<?= e($row_MAJVilles['ch_vil_paysID']) ?>&ch_ville_id=<?= e($row_MAJVilles['ch_vil_ID']) ?>"><?= e($row_MAJVilles['ch_vil_nom']) ?></a> &agrave; &eacute;t&eacute; mise &agrave; jour</p>
              </div>
            </div>
            <div class="span9"></div>
          </li>
          <?php } while ($row_MAJVilles = mysql_fetch_assoc($MAJVilles)); ?>
        <?php }?>
        <?php if ($row_LastCommuniquePays) {?>
        <h3><img src="../assets/img/IconesBDD/50/Pays1.png" alt="icone pays"> Visites officielles de pays</h3>
        <?php do { ?>
          <li> 
            <!-- Si c'est un commentaire sur un pays
================================================== -->
            <?php if ( $row_LastCommuniquePays['ch_com_categorie'] == "com_pays") {?>
            <div class="span8 alert alert-info">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <div class="span1"><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_use_paysID']) ?>#diplomatie"><img src="<?= e($row_LastCommuniquePays['ch_use_lien_imgpersonnage']) ?>" alt="dirigeant"></a></div>
              <div class="span5"> <small>le
                <?php  echo date("d/m/Y à G:i", strtotime($row_LastCommuniquePays['ch_com_date'])); ?>
                &nbsp;:</small>
                <p><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_use_paysID']) ?>#diplomatie"> <?= e($row_LastCommuniquePays['ch_use_prenom_dirigeant']) ?> <?= e($row_LastCommuniquePays['ch_use_nom_dirigeant']) ?></a> <?= e($row_LastCommuniquePays['ch_use_titre_dirigeant']) ?> &agrave; visit&eacute; le pays <a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_pay_id']) ?>#commentaireID<?= e($row_LastCommuniquePays['ch_com_ID']) ?>"> <?= e($row_LastCommuniquePays['ch_pay_nom']) ?></a></p>
              </div>
              <div class="span1"><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_pay_id']) ?>#commentaireID<?= e($row_LastCommuniquePays['ch_com_ID']) ?>"><img src="<?= e($row_LastCommuniquePays['ch_pay_lien_imgdrapeau']) ?>" alt="pays"></a></div>
            </div>
            <div class="span9"></div>
            <?php } ?>
            <!-- Si c'est un communique emmanant du pays
================================================== -->
            <?php if ( $row_LastCommuniquePays['ch_com_categorie'] == "pays") {?>
            <div class="span8 alert alert-success">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <div class="span1"><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_use_paysID']) ?>#diplomatie"><img src="<?= e($row_LastCommuniquePays['ch_use_lien_imgpersonnage']) ?>" alt="dirigeant"></a></div>
              <div class="span5"> <small>le
                <?php  echo date("d/m/Y à G:i", strtotime($row_LastCommuniquePays['ch_com_date'])); ?>
                &nbsp;:</small>
                <p><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniquePays['ch_use_paysID']) ?>#diplomatie"> <?= e($row_LastCommuniquePays['ch_use_prenom_dirigeant']) ?> <?= e($row_LastCommuniquePays['ch_use_nom_dirigeant']) ?></a> <?= e($row_LastCommuniquePays['ch_use_titre_dirigeant']) ?> &agrave; lan&ccedil;&eacute; un communiqu&eacute; au nom de son pays <a href="../page-communique.php?com_id=<?= e($row_LastCommuniquePays['ch_com_ID']) ?>"> <?= e($row_LastCommuniquePays['ch_pay_nom']) ?></a></p>
              </div>
              <div class="span1"><a href="../page-communique.php?com_id=<?= e($row_LastCommuniquePays['ch_com_ID']) ?>"><img src="<?= e($row_LastCommuniquePays['ch_pay_lien_imgdrapeau']) ?>" alt="pays"></a></div>
            </div>
            <div class="span9"></div>
            <?php } ?>
          </li>
          <?php } while ($row_LastCommuniquePays = mysql_fetch_assoc($LastCommuniquePays)); ?>
        <?php } 
		if ($row_LastCommuniqueVilles) {?>
        <h3><img src="../assets/img/IconesBDD/50/Ville1.png" alt="icone ville"> Visites officielles de villes</h3>
        <?php do { ?>
          <li> 
            <!-- Si c'est un commentaire sur une ville
================================================== -->
            <?php if ( $row_LastCommuniqueVilles['ch_com_categorie'] == "com_ville") {?>
            <div class="span8 alert alert-info">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <div class="span1"><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_use_paysID']) ?>#diplomatie"><img src="<?= e($row_LastCommuniqueVilles['ch_use_lien_imgpersonnage']) ?>" alt="dirigeant"></a></div>
              <div class="span5"> <small>le
                <?php  echo date("d/m/Y à G:i", strtotime($row_LastCommuniqueVilles['ch_com_date'])); ?>
                &nbsp;:</small>
                <p><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_use_paysID']) ?>#diplomatie"> <?= e($row_LastCommuniqueVilles['ch_use_prenom_dirigeant']) ?> <?= e($row_LastCommuniqueVilles['ch_use_nom_dirigeant']) ?></a> <?= e($row_LastCommuniqueVilles['ch_use_titre_dirigeant']) ?> &agrave; visit&eacute; la ville <a href="../page-ville.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_vil_paysID']) ?>&ch_ville_id=<?= e($row_LastCommuniqueVilles['ch_vil_ID']) ?>#commentaireID<?= e($row_LastCommuniqueVilles['ch_com_ID']) ?>"><?= e($row_LastCommuniqueVilles['ch_vil_nom']) ?></a></p>
              </div>
              <div class="span1"><a href="../page-ville.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_vil_paysID']) ?>&ch_ville_id=<?= e($row_LastCommuniqueVilles['ch_vil_ID']) ?>#commentaireID<?= e($row_LastCommuniqueVilles['ch_com_ID']) ?>"><img src="<?= e($row_LastCommuniqueVilles['ch_vil_armoiries']) ?>" alt="armoiries"></a></div>
            </div>
            <div class="span9"></div>
            <?php } ?>
            <!-- Si c'est un communique emmanant d'une ville
================================================== -->
            <?php if ( $row_LastCommuniqueVilles['ch_com_categorie'] == "ville") {?>
            <div class="span8 alert alert-block">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <div class="span1"><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_use_paysID']) ?>#diplomatie"><img src="<?= e($row_LastCommuniqueVilles['ch_use_lien_imgpersonnage']) ?>" alt="dirigeant"></a></div>
              <div class="span5"> <small>le
                <?php  echo date("d/m/Y à G:i", strtotime($row_LastCommuniqueVilles['ch_com_date'])); ?>
                &nbsp;:</small>
                <p><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueVilles['ch_use_paysID']) ?>#diplomatie"> <?= e($row_LastCommuniqueVilles['ch_use_prenom_dirigeant']) ?> <?= e($row_LastCommuniqueVilles['ch_use_nom_dirigeant']) ?></a> <?= e($row_LastCommuniqueVilles['ch_use_titre_dirigeant']) ?> &agrave; lan&ccedil;&eacute; un communiqu&eacute; au nom de la ville <a href="../page-communique.php?com_id=<?= e($row_LastCommuniqueVilles['ch_com_ID']) ?>"> <?= e($row_LastCommuniqueVilles['ch_vil_nom']) ?></a></p>
              </div>
              <div class="span1"><a href="../page-communique.php?com_id=<?= e($row_LastCommuniqueVilles['ch_com_ID']) ?>"><img src="<?= e($row_LastCommuniqueVilles['ch_vil_armoiries']) ?>" alt="armoiries"></a></div>
            </div>
            <div class="span9"></div>
            <?php } ?>
          </li>
          <?php } while ($row_LastCommuniqueVilles = mysql_fetch_assoc($LastCommuniqueVilles)); ?>
        <?php } 
		if ($row_LastCommuniqueReaction) {?>
        <!-- Si c'est une reaction a un communiqué
================================================== -->
        <h3><img src="../assets/img/IconesBDD/50/Ville1.png" alt="icone ville"> R&eacute;actions Officielles</h3>
        <?php do { ?>
          <li>
            <div class="span8 alert alert-info">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <div class="span1"><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueReaction['ch_use_paysID']) ?>#diplomatie"><img src="<?= e($row_LastCommuniqueReaction['ch_use_lien_imgpersonnage']) ?>" alt="dirigeant"></a></div>
              <div class="span5"> <small>le
                <?php  echo date("d/m/Y à G:i", strtotime($row_LastCommuniqueReaction['ch_com_date'])); ?>
                &nbsp;:</small>
                <p><a href="../page-pays.php?ch_pay_id=<?= e($row_LastCommuniqueReaction['ch_use_paysID']) ?>#diplomatie"> <?= e($row_LastCommuniqueReaction['ch_use_prenom_dirigeant']) ?> <?= e($row_LastCommuniqueReaction['ch_use_nom_dirigeant']) ?></a> <?= e($row_LastCommuniqueReaction['ch_use_titre_dirigeant']) ?> &agrave; r&eacute;agit <a href="../page-communique.php?com_id=<?= e($row_LastCommuniqueReaction['ch_com_element_id']) ?>#commentaireID<?= e($row_LastCommuniqueReaction['ch_com_ID']) ?>">&agrave; un communiqu&eacute;</a></p>
              </div>
            </div>
            <div class="span9"></div>
          </li>
          <?php } while ($row_LastCommuniqueReaction = mysql_fetch_assoc($LastCommuniqueReaction)); ?>
        <?php } ?>
      </ul>
    </div>
  </div>
</section>
