<footer class="footer">
    <div class="container">
        <div class="pull-center">
            <p class="liens-rapides"><a href="#">haut de page</a></p>
        </div>
        <div>
            <ul class="pull-left liens-rapides">
                <li><a href="<?= urlFromLegacy(url('back/Haut-Conseil.php')) ?>">Conseil de l'OCGC</a></li>
                <?php if(auth()->check()): ?>
                    <li><a href="<?= urlFromLegacy(url('index.php?doLogout=true&csrf_token=' . csrf_token())) ?>"
                        >D&eacute;connexion</a></li>
                <?php else: ?>
                    <li><a href="<?= url('connexion.php') ?>">Connexion</a></li>
                <?php endif; ?>

                <li><a href="<?= urlFromLegacy(url('participer.php#charte')) ?>">Charte</a></li>
                <li><a href="https://www.generation-city.com/">G&eacute;n&eacute;ration City</a></li>
                <li><a href="https://www.forum-gc.com/">Forum</a></li>
            </ul>
            <a href="https://www.generation-city.com/">
                <img src="<?= urlFromLegacy(url('assets/img/2019/logoGC-small.png')) ?>" alt="Logo GC">
            </a>
            <div class="copyright">
                <p>Copyright &copy; G&eacute;n&eacute;ration-City - <?= date('Y') ?></p>
                <p>Tous droits r&eacute;serv&eacute;s - Version 2</p>
            </div>
        </div>
    </div>
</footer>
