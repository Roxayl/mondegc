<div class="navbar-haut-conseil">

  <a class="btn btn-primary" href="<?= urlFromLegacy(url('back/Haut-Conseil.php')) ?>" title="Page de gestion du Conseil de l'OCGC">
    <i class="icon-edit" style="visibility: hidden;"></i> Conseil de l'OCGC
  </a>

  <div class="dropdown">
    <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" title="Les différents organes de l'OCGC">
      <i class="icon-edit icon-white"></i> Les comités <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
      <li><a href="<?= urlFromLegacy(url('back/institut_OCGC.php')) ?>">OCGC</a></li>
      <li><a href="<?= urlFromLegacy(url('back/institut_geographie.php')) ?>">Géographie</a></li>
      <li><a href="<?= urlFromLegacy(url('back/institut_patrimoine.php')) ?>">Culture</a></li>
      <li><a href="<?= urlFromLegacy(url('back/institut_histoire.php')) ?>">Histoire</a></li>
      <li><a href="<?= urlFromLegacy(url('back/institut_politique.php')) ?>">Politique</a></li>
      <li><a href="<?= urlFromLegacy(url('back/institut_economie.php')) ?>">Économie</a></li>
    </ul>
  </div>

  <a class="btn btn-primary" href="<?= urlFromLegacy(url('back/liste-pays.php')) ?>" title="Liste des pays du monde GC">
    <i class="icon-list icon-white"></i> Pays
  </a>

  <a class="btn btn-primary" href="<?= urlFromLegacy(url('back/liste-villes.php')) ?>" title="Liste des villes du monde GC">
    <i class="icon-list icon-white"></i> Villes
  </a>

  <a class="btn btn-primary" href="<?= urlFromLegacy(url('back/liste-membres.php')) ?>" title="Liste des membres">
    <i class="icon-list icon-white"></i> Membres
  </a>

  <a class="btn btn-primary" href="<?= urlFromLegacy(url('back/liste-communiques.php')) ?>" title="Liste des communiqués">
    <i class="icon-list icon-white"></i> Communiqués
  </a>

  <a class="btn btn-primary" href="<?= urlFromLegacy(url('back/gestion-pages.php')) ?>" title="Gestion des contenus du site">
    <i class="icon-list icon-white"></i> Pages
  </a>

  <div class="dropdown">
    <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" title="Ajouter un membre ou un pays et gérer les paramètres avancés">
      <i class="icon-plus icon-white"></i> <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
      <li class="nav-header">Nouveau...</li>
      <li><a href="<?= urlFromLegacy(url('back/membre-ajouter.php')) ?>">Nouveau membre</a></li>
      <li><a href="<?= urlFromLegacy(url('back/page_pays_ajouter.php')) ?>">Nouveau pays</a></li>
      <li class="divider"></li>
      <li class="nav-header">Avancé</li>
      <li><a href="<?= urlFromLegacy(route('back-office.advanced-parameters')) ?>">Paramètres avancés</a></li>
      <li><a href="<?= urlFromLegacy(url('back/logger.php')) ?>">Journalisation</a></li>
    </ul>
  </div>

</div>
