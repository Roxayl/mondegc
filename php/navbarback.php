<?php

// Tri des pays par continent pour le menu deroulant
mysql_select_db($database_maconnexion, $maconnexion);
$query_menu = "SELECT ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, ch_pay_continent FROM pays WHERE ch_pay_publication = 1 ORDER BY ch_pay_nom ASC";
$menu = mysql_query($query_menu, $maconnexion) or die(mysql_error());
$totalRows_menu = mysql_num_rows($menu);

$row_menu = mysql_fetch_assoc($menu);
mysql_data_seek($menu, 0);

$nav_userPays = array();
if(isset($_SESSION['userObject'])) {
    $nav_userPays = $_SESSION['userObject']->getCountries();
}

if(!isset($loginFormAction))
    $loginFormAction = '';

?>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> 
      
      <!-- Menu gestion une fois connecté tablet -->
      <div class="visible-tablet navbar-form menu-gestion <?php echo $_SESSION['menu_gestion']?>"> <span class="Nav-pseudo">Bienvenue <?php echo $_SESSION['login_user']?>&nbsp;</span>
        <div><a href="membre-modifier_back.php?userID=<?= $_SESSION['user_ID'] ?>" class="btn btn-primary" type="submit" title="page de gestion du profil"><i class="icon-user-white"></i> Mon profil</a></div>
        <div class="dropdown">
          <a href="dashboard.php" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" type="submit" title="page de gestion du profil"><i class="icon-pays-small-white"></i> Mes pays</a>
          <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
          <?php foreach($nav_userPays as $nav_thisPays): ?>
            <li style="width: 100%;"><a tabindex="-1" href="back/page_pays_back.php?paysID=<?= $nav_thisPays['ch_pay_id'] ?>"><img class="img-menu-drapeau" src="<?= $nav_thisPays['ch_pay_lien_imgdrapeau'] ?>"> <?= $nav_thisPays['ch_pay_nom'] ?></a></li>
          <?php endforeach; ?>
          </ul>
        </div>
        <a href="<?php echo $logoutAction ?>" title="d&eacute;connexion" class="btn btn-small btn-danger">X</a> </div>
      <!-- Logo -->
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="brand" href="../index.php"><img src="../assets/img/2019/logo-navbar.png" alt="Le Monde GC" /></a>
      
      <!-- Collapse -->
      <div class="nav-collapse collapse"> 
        
        <!-- Menu gestion une fois connecté desktop/phone -->
        <div class="hidden-tablet navbar-form <?php echo $_SESSION['menu_gestion']?>">
            <div><a href="membre-modifier_back.php?userID=<?= $_SESSION['user_ID'] ?>" class="btn btn-primary" type="submit" title="page de gestion du profil"><i class="icon-user-white"></i> Mon profil</a></div>
            <div class="dropdown" style="margin-top: -2px;">
              <a href="dashboard.php" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" type="submit" title="page de gestion du profil"><i class="icon-pays-small-white"></i> Mes pays</a>
              <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
              <?php foreach($nav_userPays as $nav_thisPays): ?>
                <li style="width: 100%;"><a tabindex="-1" href="back/page_pays_back.php?paysID=<?= $nav_thisPays['ch_pay_id'] ?>"><img class="img-menu-drapeau" src="<?= $nav_thisPays['ch_pay_lien_imgdrapeau'] ?>"> <?= $nav_thisPays['ch_pay_nom'] ?></a></li>
              <?php endforeach; ?>
              </ul>
            </div>
          <div class="offset"><span class="Nav-pseudo"><span class="bienvenue">Bienvenue </span><?php echo $_SESSION['login_user']?>&nbsp;</span> <a href="<?php echo $logoutAction ?>" title="d&eacute;connexion" class="btn btn-small btn-danger">X</a></div>
        </div>
        
        <!-- Menu -->
        <ul class="nav">

          <li class="<?php if ($accueil) { echo('active');} ?>">
            <center>
              <a href="../index.php"><i class="icon icon-accueil"></i></a>
            </center>
            <a href="../index.php">Accueil</a> </li>

          <li class="<?php if ($dashboard) { echo('active');}  ?>">
            <center>
              <a href="../dashboard.php" title="informations pratiques"><i class="icon icon-evenement"></i></a>
            </center>
            <a href="../dashboard.php" title="informations pratiques">Tableau de bord</a> </li>

          <li class="dropdown <?php if ($carte || $pays) { echo('active');}  ?>">
            <center>
              <a href="../Page-carte.php#liste-pays" title="liste des pays class&eacute;s par continent"><i class="icon icon-pays"></i></a>
            </center>
            <a href="../Page-carte.php" class="dropdown-toggle" data-toggle="dropdown" title="liste des pays class&eacute;s par continent">Carte et pays <b class="caret hidden-phone"></b></a>
            <ul class="dropdown-menu dropdown-double hidden-phone">
              <div class="drop-colonne-gauche">
                <li class="nav-lien-carte" href="../page-pays.php?ch_pay_id=<?php echo $row_menu['ch_pay_id']; ?>"><a href="../Page-carte.php"><div><h3>Explorer la carte</h3></div></a></li>
                <li class="nav-header"><img src="../assets/img/Aurinea.png" class="img-continent"> R&eacute;publique F&eacute;d&eacute;rale de G&eacute;n&eacute;ration City</li>
                <?php 
				do { 
                if ($row_menu['ch_pay_continent'] == 'RFGC') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="../page-pays.php?ch_pay_id=<?php echo $row_menu['ch_pay_id']; ?>"><img src="<?php echo $row_menu['ch_pay_lien_imgdrapeau']; ?>" class="img-menu-drapeau"> <?php echo $row_menu['ch_pay_nom']; ?></a></li>
                <?php }
				} while ($row_menu = mysql_fetch_assoc($menu));
				mysql_data_seek($menu,0); ?>
                <li class="divider"></li>
                <li class="nav-header"><img src="../assets/img/Aurinea.png" class="img-continent"> Continent Aurin&eacute;a</li>
                <?php 
				do { 
                if ($row_menu['ch_pay_continent'] == 'Aurinea') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="../page-pays.php?ch_pay_id=<?php echo $row_menu['ch_pay_id']; ?>"><img src="<?php echo $row_menu['ch_pay_lien_imgdrapeau']; ?>" class="img-menu-drapeau"> <?php echo $row_menu['ch_pay_nom']; ?></a></li>
                <?php }
				} while ($row_menu = mysql_fetch_assoc($menu));
				mysql_data_seek($menu,0); ?>
                <li class="divider"></li>
                <li class="nav-header"><img src="../assets/img/Volcania.png" class="img-continent"> Continent Volcania</li>
                <?php 
				do { 
                if ($row_menu['ch_pay_continent'] == 'Volcania') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="../page-pays.php?ch_pay_id=<?php echo $row_menu['ch_pay_id']; ?>"><img src="<?php echo $row_menu['ch_pay_lien_imgdrapeau']; ?>" class="img-menu-drapeau"> <?php echo $row_menu['ch_pay_nom']; ?></a></li>
                <?php }
				} while ($row_menu = mysql_fetch_assoc($menu));
				mysql_data_seek($menu,0); ?>
              </div>
              <div class="drop-colonne-droite">
                <li class="nav-header"><img src="../assets/img/Aldesyl.png" class="img-continent"> Continent Aldesyl</li>
                <?php 
				do { 
                if ($row_menu['ch_pay_continent'] == 'Aldesyl') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="../page-pays.php?ch_pay_id=<?php echo $row_menu['ch_pay_id']; ?>"><img src="<?php echo $row_menu['ch_pay_lien_imgdrapeau']; ?>" class="img-menu-drapeau"> <?php echo $row_menu['ch_pay_nom']; ?></a></li>
                <?php }
				} while ($row_menu = mysql_fetch_assoc($menu));
				mysql_data_seek($menu,0); ?>
                <li class="divider"></li>
                <li class="nav-header"><img src="../assets/img/Oceania.png" class="img-continent"> Continent Oc&eacute;ania</li>
                <?php 
				do { 
                if ($row_menu['ch_pay_continent'] == 'Oceania') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="../page-pays.php?ch_pay_id=<?php echo $row_menu['ch_pay_id']; ?>"><img src="<?php echo $row_menu['ch_pay_lien_imgdrapeau']; ?>" class="img-menu-drapeau"> <?php echo $row_menu['ch_pay_nom']; ?></a></li>
                <?php }
				} while ($row_menu = mysql_fetch_assoc($menu));
				mysql_data_seek($menu,0); ?>
                <li class="divider"></li>
                <li class="nav-header"><img src="../assets/img/Philicie.png" class="img-continent"> Continent Philicie</li>
                <?php 
				do { 
                if ($row_menu['ch_pay_continent'] == 'Philicie') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="../page-pays.php?ch_pay_id=<?php echo $row_menu['ch_pay_id']; ?>"><img src="<?php echo $row_menu['ch_pay_lien_imgdrapeau']; ?>" class="img-menu-drapeau"> <?php echo $row_menu['ch_pay_nom']; ?></a></li>
                <?php }
				} while ($row_menu = mysql_fetch_assoc($menu));
				mysql_data_seek($menu,0); ?>
              </div>
            </ul>
          </li>

          <li class="dropdown <?php if ($institut) { echo('active');}  ?>">
            <center>
              <a href="../OCGC.php" title="Institutions de r&eacute;gulation du monde GC"><i class="icon icon-institut"></i></a>
            </center>
            <a href="../OCGC.php" class="dropdown-toggle" data-toggle="dropdown" title="Institutions de r&eacute;gulation du monde GC">Les instituts <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li class="nav-header">L'actualité de l'OCGC</li>
              <li><a href="../communiques-ocgc.php">Communiqués publiés</a></li>
              <li class="nav-header">Structure</li>
              <li><a href="../OCGC.php">OCGC</a></li>
              <li><a href="../geographie.php">G&eacute;ographie</a></li>
              <li><a href="../patrimoine.php">Patrimoine</a></li>
              <li><a href="../histoire.php">Histoire</a></li>
              <li><a href="../economie.php">&Eacute;conomie</a></li>
              <li><a href="../politique.php">Politique</a></li>
            </ul>
          </li>

          <li class="<?php if ($participer) { echo('active');}  ?>">
            <center>
              <a href="../participer.php" title="informations pratiques"><i class="icon icon-participer"></i></a>
            </center>
            <a href="../participer.php" title="informations pratiques">Participer</a> </li>

          <li class="dropdown <?php if ($generation_city) { echo('active');}  ?>">
            <center>
              <a title="les autres sites de G&eacute;n&eacute;ration City"><i class="icon icon-generation_city"></i></a>
            </center>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="les autres sites de G&eacute;n&eacute;ration City">G&eacute;n&eacute;ration City <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="http://www.forum-gc.com">Le forum</a></li>
              <li><a href="http://vasel.yt/wiki/index.php?title=Accueil">Le Wiki</a></li>
			  <li><a href="https://squirrel.romukulot.fr/">Squirrel</a></li>
            </ul>
          </li>

        </ul>
      </div>
    </div>
  </div>
</div>
</div>
<?php
mysql_free_result($menu);
?>
