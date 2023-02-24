<?php

if(!isset($accueil)) $accueil = false;
if(!isset($dashboard)) $dashboard = false;
if(!isset($carte)) $carte = false;
if(!isset($menupays)) $menupays = false;
if(!isset($pays)) $pays = false;
if(!isset($institut)) $institut = false;
if(!isset($participer)) $participer = false;
if(!isset($generation_city)) $generation_city = false;

$logoutAction = DEF_URI_PATH . "index.php?doLogout=true&csrf_token=" . csrf_token();
$loginFormAction = DEF_URI_PATH . 'index.php';

// Tri des pays par continent pour le menu deroulant

$query_menu = "SELECT ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, ch_pay_continent FROM pays WHERE ch_pay_publication = 1 ORDER BY ch_pay_nom ASC";
$menu = collect(DB::select(DB::raw($query_menu)))->map(fn(object $row) => (array) $row)->toArray();
$totalRows_menu = count($menu);

$nav_userPays = array();
if(isset($_SESSION['userObject'])) {
    $nav_userPays = $_SESSION['userObject']->getCountries();
}

if(!isset($loginFormAction))
    $loginFormAction = '';

/** Notifications navbar Assemblée générale */
$navbar_proposalList = new \GenCity\Proposal\ProposalList();
$navbar_pendingVotes = $navbar_proposalList->getPendingVotes();
$navbar_userProposalPendingVotes = array();
if(isset($_SESSION['userObject'])) {
    $navbar_userProposalPendingVotes = $navbar_proposalList->userProposalPendingVotes($_SESSION['userObject']);
}

/** Notifications */
if(auth()->check()) {
    $navbar_notifCount = auth()->user()->unreadNotifications->count();
}

/** Organisations */
$navbar_organisationList = \Roxayl\MondeGC\Models\Organisation::allOrdered()->get();

?>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <!-- Formulaire connexion Tablettes -->
      <form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" name="connexion" class="navbar-form pull-right visible-tablet <?= e($_SESSION['menu_connexion']) ?>">
        <input type="hidden" name="__csrf_magic" value="<?= csrf_get_tokens() ?>">
        <input type="hidden" name="_token"
                 value="<?= \Illuminate\Support\Facades\Session::token() ?>">
        <input class="span2" type="text" placeholder="Identifiant" name="identifiant"  id="identifiant">
        <input class="span2" type="password" placeholder="Mot de passe" name="mot_de_passe" id="mot_de_passe">
        <button type="submit" class="btn btn-connexion">connexion</button>
      </form>
      
      <!-- Menu gestion une fois connecté tablet -->
      <div class="visible-tablet navbar-form menu-gestion menu-gestion-front <?php echo isset($_SESSION['menu_gestion']) ? $_SESSION['menu_gestion'] : '' ?>"><span class="Nav-pseudo"><?php echo isset($_SESSION['login_user']) ? $_SESSION['login_user'] : '' ?>&nbsp;</span>
        <div class="dropdown">
          <a href="<?= DEF_URI_PATH ?>dashboard.php" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" type="submit" title="page de gestion du profil"><i class="icon-pays-small-white"></i> Mes pays</a>
          <ul class="dropdown-menu dropdown-mes-pays" role="menu" aria-labelledby="dLabel">
          <?php foreach($nav_userPays as $nav_thisPays): ?>
            <li style="width: 100%;"><a tabindex="-1" href="back/page_pays_back.php?paysID=<?= e($nav_thisPays['ch_pay_id']) ?>"><img class="img-menu-drapeau" src="<?= e($nav_thisPays['ch_pay_lien_imgdrapeau']) ?>"> <?= e($nav_thisPays['ch_pay_nom']) ?></a></li>
          <?php endforeach; ?>
          </ul>
        </div>
        <a href="<?php echo $logoutAction ?>" title="d&eacute;connexion" class="btn btn-small btn-danger">X</a> </div>
      <!-- Logo -->
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="brand" href="<?= DEF_URI_PATH ?>index.php"><img src="<?= DEF_URI_PATH ?>assets/img/2019/logo-navbar.png" alt="Le Monde GC" /></a>
      <!-- Collapse -->
      <div class="nav-collapse collapse"> 
        
        <!-- Formulaire connexion desktop / mobile -->
        <form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" name="connexion" class="navbar-form hidden-tablet <?= e($_SESSION['menu_connexion']) ?>" style="margin-top: 4px;">
          <input type="hidden" name="__csrf_magic" value="<?= csrf_get_tokens() ?>">
          <input type="hidden" name="_token"
                 value="<?= \Illuminate\Support\Facades\Session::token() ?>">
          <div>
            <input class="span2" type="text" placeholder="Identifiant" name="identifiant"  id="identifiant">
          </div>
          <div>
            <input class="span2" type="password" placeholder="Mot de passe" name="mot_de_passe" id="mot_de_passe">
          </div>
          <div style="margin-top: 8px;">
            <button type="submit" class="span2 btn btn-connexion">Connexion</button>
          </div>
        </form>
        
        <!-- Menu gestion une fois connecté desktop/phone -->
        <div class="hidden-tablet navbar-form <?php echo isset($_SESSION['menu_gestion']) ? $_SESSION['menu_gestion'] : '' ?>">
            <div><a href="<?= DEF_URI_PATH ?>back/membre-modifier_back.php?userID=<?= isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : '' ?>" class="btn btn-primary" type="submit" title="page de gestion du profil" style="visibility: hidden;"><i class="icon-user-white"></i> Mon profil</a></div>

        <?php if(isset($_SESSION['userObject'])):

            $navbar_intitulePays = count($nav_userPays) > 1 ? 'Mes pays' : 'Mon pays';
            ?>
            <div class="offset" style="margin-top: 38px;">

                <div class="dropdown pull-right">
                  <a href="<?= DEF_URI_PATH ?>dashboard.php" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" type="submit" title="Gérer mes pays"><i class="icon-pays-small-white"></i> <?= $navbar_intitulePays ?></a>
                  <ul class="dropdown-menu dropdown-mes-pays" role="menu" aria-labelledby="dLabel">
                      <li class="nav-header">Gérer <?= \Illuminate\Support\Str::lower($navbar_intitulePays) ?></li>
                      <?php foreach($nav_userPays as $nav_thisPays): ?>
                      <li><a href="<?= DEF_URI_PATH ?>back/page_pays_back.php?paysID=<?= e($nav_thisPays['ch_pay_id']) ?>" style="padding-left: 5px;">
                        <img class="img-menu-drapeau" src="<?= e($nav_thisPays['ch_pay_lien_imgdrapeau']) ?>"> <?= e($nav_thisPays['ch_pay_nom']) ?>
                         </a></li>
                      <?php endforeach; ?>
                      <li class="divider"></li>
                      <li class="nav-header">Mon compte</li>
                      <li><div style="margin: 5px;"><small>Connecté en tant que <?= __s($_SESSION['userObject']->get('ch_use_login')) ?></small></div></li>
                      <li><a href="<?= DEF_URI_PATH ?>back/membre-modifier_back.php?userID=<?= $_SESSION['userObject']->get('ch_use_id') ?>">Gérer mon compte</a></li>
                      <li><a href="<?= $logoutAction ?>">Se déconnecter</a></li>
                  </ul>
                </div>

                <div class="dropdown pull-right dropdown-notification hidden-phone"
                     style="margin-right: 4px;">
                  <a href="#" class="btn notification-toggle-btn
                     <?= !$navbar_notifCount ? 'btn-transparent' : 'btn-primary' ?>" type="submit"
                     title="Notifications" data-toggle="dropdown">
                      <i class="icon-bell icon-white"></i>
                      <span class="notification-count"><?= $navbar_notifCount > 0 ? $navbar_notifCount : '' ?></span>
                  </a>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">

                  </ul>
                </div>

                <div class="dropdown pull-right hidden-phone"
                     style="margin-right: 4px;">
                  <a href="#" class="btn btn-transparent" type="submit"
                     title="Recherche" data-toggle="dropdown">
                      <i class="icon-search icon-white"></i>
                  </a>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <li>
                      <form action="<?= DEF_URI_PATH ?>search" method="GET">

                      <div class="well"
                         style="text-align: center;
                         background: rgb(249,249,249);
                         background: linear-gradient(90deg, rgba(249,249,249,1) 0%, rgba(241,241,241,1) 10%, rgba(241,241,241,1) 90%, rgb(231, 231, 231) 100%);
                         padding: 10px; margin-left: -10px;">
                        <div class="control-group">
                            <label class="control-label" for="query">Termes de recherche</label>
                            <div class="controls">
                                <input class="input-xlarge" name="query" type="text" id="query"
                                       value="" maxlength="50">
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Rechercher...">
                      </div>

                      </form>
                    </li>
                  </ul>
                </div>
            </div>
            <?php endif; ?>

        </div>
        
        <!-- Menu -->
        <ul class="nav">

        <?php if(isset($_SESSION['userObject'])): ?>
          <li class="<?php if ($dashboard) { echo('active');}  ?>">
            <center>
              <a href="<?= DEF_URI_PATH ?>dashboard.php" title="informations pratiques"><i class="icon icon-evenement"></i></a>
            </center>
            <a href="<?= DEF_URI_PATH ?>dashboard.php" title="informations pratiques">Tableau de bord</a>
          <ul class="dropdown-menu">            
              <li><a href="https://www.forum-gc.com">Le forum</a></li>
              <li><a href="http://vasel.yt/wiki/index.php?title=Accueil">Le Wiki</a></li>
        <li><a href="https://squirrel.roxayl.fr/">Squirrel</a></li>
            </ul></li>
        <?php endif; ?>

          <li class="dropdown <?php if ($carte || $menupays) { echo('active');}  ?>">
            <center>
              <a href="<?= DEF_URI_PATH ?>Page-carte.php#liste-pays" title="liste des pays class&eacute;s par continent"><i class="icon icon-pays"></i></a>
            </center>
            <a href="<?= DEF_URI_PATH ?>Page-carte.php" class="dropdown-toggle" data-toggle="dropdown" title="liste des pays class&eacute;s par continent">Carte et pays <b class="caret hidden-phone"></b></a>
            <ul class="dropdown-menu dropdown-double hidden-phone">
              <div class="drop-colonne-gauche">
                <li class="nav-lien-carte"><a href="<?= DEF_URI_PATH ?>map"><div><h3>Explorer la carte</h3></div></a></li>
                <li><a href="<?= DEF_URI_PATH ?>Page-carte.php"><i class="icon-list"></i> Voir la liste des pays</a></li>
                <li class="nav-header"><img src="<?= DEF_URI_PATH ?>assets/img/Aurinea.png" class="img-continent"> R&eacute;publique F&eacute;d&eacute;rale de G&eacute;n&eacute;ration City</li>
                <?php
                foreach($menu as $row_menu) {
                if (is_array($row_menu) && $row_menu['ch_pay_continent'] == 'RFGC') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="<?= DEF_URI_PATH ?>page-pays.php?ch_pay_id=<?= e($row_menu['ch_pay_id']) ?>"><img src="<?= e($row_menu['ch_pay_lien_imgdrapeau']) ?>" class="img-menu-drapeau"> <?= e($row_menu['ch_pay_nom']) ?></a></li>
                <?php }
				}
                ?>
                <li class="divider"></li>
                <li class="nav-header"><img src="<?= DEF_URI_PATH ?>assets/img/Aurinea.png" class="img-continent"> Continent Aurin&eacute;a</li>
                <?php 
                foreach($menu as $row_menu) {
                if (is_array($row_menu) && $row_menu['ch_pay_continent'] == 'Aurinea') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="<?= DEF_URI_PATH ?>page-pays.php?ch_pay_id=<?= e($row_menu['ch_pay_id']) ?>"><img src="<?= e($row_menu['ch_pay_lien_imgdrapeau']) ?>" class="img-menu-drapeau"> <?= e($row_menu['ch_pay_nom']) ?></a></li>
                <?php }
				} ?>
                <li class="divider"></li>
                <li class="nav-header"><img src="<?= DEF_URI_PATH ?>assets/img/Volcania.png" class="img-continent"> Continent Volcania</li>
                <?php 
                foreach($menu as $row_menu) {
                if (is_array($row_menu) && $row_menu['ch_pay_continent'] == 'Volcania') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="<?= DEF_URI_PATH ?>page-pays.php?ch_pay_id=<?= e($row_menu['ch_pay_id']) ?>"><img src="<?= e($row_menu['ch_pay_lien_imgdrapeau']) ?>" class="img-menu-drapeau"> <?= e($row_menu['ch_pay_nom']) ?></a></li>
                <?php }
				} ?>
              </div>
              <div class="drop-colonne-droite">
                <li class="nav-header"><img src="<?= DEF_URI_PATH ?>assets/img/Aldesyl.png" class="img-continent"> Continent Aldesyl</li>
                <?php 
                foreach($menu as $row_menu) {
                if (is_array($row_menu) && $row_menu['ch_pay_continent'] == 'Aldesyl') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="<?= DEF_URI_PATH ?>page-pays.php?ch_pay_id=<?= e($row_menu['ch_pay_id']) ?>"><img src="<?= e($row_menu['ch_pay_lien_imgdrapeau']) ?>" class="img-menu-drapeau"> <?= e($row_menu['ch_pay_nom']) ?></a></li>
                <?php }
				} ?>
                <li class="divider"></li>
                <li class="nav-header"><img src="<?= DEF_URI_PATH ?>assets/img/Oceania.png" class="img-continent"> Continent Oc&eacute;ania</li>
                <?php 
                foreach($menu as $row_menu) {
                if (is_array($row_menu) && $row_menu['ch_pay_continent'] == 'Oceania') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="<?= DEF_URI_PATH ?>page-pays.php?ch_pay_id=<?= e($row_menu['ch_pay_id']) ?>"><img src="<?= e($row_menu['ch_pay_lien_imgdrapeau']) ?>" class="img-menu-drapeau"> <?= e($row_menu['ch_pay_nom']) ?></a></li>
                <?php }
                } ?>
                <li class="divider"></li>
                <li class="nav-header"><img src="<?= DEF_URI_PATH ?>assets/img/Philicie.png" class="img-continent"> Continent Philicie</li>
                <?php
                foreach($menu as $row_menu) {
                if (is_array($row_menu) && $row_menu['ch_pay_continent'] == 'Philicie') {
                	if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_menu['ch_pay_lien_imgdrapeau']))
					{
					$row_menu['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/SmallThumb/$1', $row_menu['ch_pay_lien_imgdrapeau']);
					} ?>
                <li><a href="<?= DEF_URI_PATH ?>page-pays.php?ch_pay_id=<?= e($row_menu['ch_pay_id']) ?>"><img src="<?= e($row_menu['ch_pay_lien_imgdrapeau']) ?>" class="img-menu-drapeau"> <?= e($row_menu['ch_pay_nom']) ?></a></li>
                <?php }
                } ?>
              </div>
            </ul>
          </li>

          <li class="dropdown <?= isset($organisation) ? 'active' : '' ?>">
            <center>
              <a href="<?= DEF_URI_PATH . 'politique.php#organisations' ?>"
                 title="Organisations du Monde GC"><i class="icon icon-institut"></i></a>
            </center>
            <a href="<?= DEF_URI_PATH . 'politique.php' ?>" class="dropdown-toggle"
               data-toggle="dropdown" title="Organisations du Monde GC"
                >Organisations <b class="caret"></b></a>
            <ul class="dropdown-menu dropdown-double hidden-phone">
              <div class="drop-colonne-gauche">
                  <li><a href="<?= DEF_URI_PATH . 'politique.php#organisations' ?>">
                          <i class="icon-list"></i> Voir la liste des organisations
                      </a></li>
                  <?php
                  $org_type_actuel = '';
                  foreach($navbar_organisationList as $thisOrganisation):

                      if($org_type_actuel !== $thisOrganisation->type):
                          $org_type_actuel = $thisOrganisation->type;

                          if($org_type_actuel === \Roxayl\MondeGC\Models\Organisation::TYPE_ORGANISATION): ?>
                              </div>
                              <div class="drop-colonne-droite">
                          <?php endif; ?>
                          <li class="nav-header" style="text-transform: none;">
                              <span class="badge org-<?= $thisOrganisation->type ?>">
                              <?= __("organisation.types.{$thisOrganisation->type}") ?>
                              </span>
                          </li>
                      <?php
                      endif;
                      ?>

                      <li><a href="<?= DEF_URI_PATH . 'organisation/' . $thisOrganisation->id ?>">
                              <img src="<?= e($thisOrganisation->flag) ?>"
                                   class="img-menu-drapeau">
                              <?= e($thisOrganisation->name) ?>
                          </a></li>
                  <?php endforeach; ?>
              </div>
            </ul>
          </li>

          <li class="dropdown <?php if ($institut) { echo('active');}  ?>">
            <center>
              <a href="<?= DEF_URI_PATH ?>OCGC.php" title="Institutions de r&eacute;gulation du monde GC"><i class="icon icon-institut"></i></a>
            </center>
            <a href="<?= DEF_URI_PATH ?>OCGC.php" class="dropdown-toggle" data-toggle="dropdown" title="L'Organisation des Cités Gécéennes">OCGC
            <?php if(count($navbar_userProposalPendingVotes)): ?>
                <span class="navbar-circle-notification"><img src="<?= DEF_URI_PATH ?>assets/img/2019/AGicon.png"></span>
            <?php endif; ?>
                <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li class="nav-header">À propos de l'OCGC</li>
              <li><a href="<?= DEF_URI_PATH ?>OCGC.php">Présentation de l'OCGC</a></li>
              <li><a href="<?= DEF_URI_PATH ?>communiques-ocgc.php">Communiqués publiés</a></li>
              <li class="nav-header">Organes de l'OCGC</li>
              <li><a href="http://vasel.yt/wiki/index.php?title=Conseil_de_l%27OCGC">Conseil de l'OCGC</a></li>
              <li><a href="<?= DEF_URI_PATH ?>assemblee.php">
                  Assemblée générale
                  <?php if(count($navbar_pendingVotes)): ?><br>
                      <span class="btn-small"><strong><img src="<?= DEF_URI_PATH ?>assets/img/2019/AGicon.png"> Session en cours</strong><br>
                          <?= count($navbar_pendingVotes) ?>
                          proposition<?= count($navbar_pendingVotes) > 1 ? 's' : '' ?>
                          actuellement soumis<?= count($navbar_pendingVotes) > 1 ? 'es' : 'e' ?> au vote
                          <?php if(count($navbar_userProposalPendingVotes)): ?>
                            <br><span style="color: #ff4e00;">
                              (dont <?= count($navbar_userProposalPendingVotes) ?> en attente de votre vote)
                              </span>
                          <?php endif; ?>
                      </span>
                  <?php endif; ?>
                  </a></li>
              <li class="nav-header">Les comités</li>
              <li><a href="<?= DEF_URI_PATH ?>geographie.php">G&eacute;ographie</a></li>
              <li><a href="<?= DEF_URI_PATH ?>patrimoine.php">Culture</a></li>
              <li><a href="<?= DEF_URI_PATH ?>histoire.php">Histoire</a></li>
              <li><a href="<?= DEF_URI_PATH ?>economie.php">&Eacute;conomie</a></li>
              <li><a href="<?= DEF_URI_PATH ?>politique.php">Politique</a></li>
            </ul>
          </li>

          <li class="<?php if ($participer) { echo('active');}  ?>">
            <center>
              <a href="<?= DEF_URI_PATH ?>participer.php" title="informations pratiques"><i class="icon icon-participer"></i></a>
            </center>
            <a href="<?= DEF_URI_PATH ?>participer.php" title="informations pratiques">Participer</a> </li>

          <li class="dropdown <?php if ($generation_city) { echo('active');}  ?>">
            <center>
              <a title="les autres sites de G&eacute;n&eacute;ration City"><i class="icon icon-generation_city"></i></a>
            </center>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="les autres sites de G&eacute;n&eacute;ration City">G&eacute;n&eacute;ration City <b class="caret"></b></a>
            <ul class="dropdown-menu">            
              <li><a href="https://www.forum-gc.com">Le forum</a></li>
              <li><a href="http://vasel.yt/wiki/index.php?title=Accueil">Le Wiki</a></li>
			  <li><a href="https://squirrel.roxayl.fr/">Squirrel</a></li>
            </ul>
          </li>

        </ul>
      </div>
    </div>
  </div>
</div>
