<footer class="footer">
  <div class="container">
  <div class="pull-center">
    <p class="liens-rapides"><a href="#">haut de page</a></p>
  </div>
  <div>
    <ul class="pull-left liens-rapides">
      <li><a href="Haut-Conseil.php">Haut-Conseil</a></li>
      <?php if ($_SESSION['connect']) {?>
      <li><a href="<?php echo $logoutAction ?>">D&eacute;connexion</a></li>
      <?php } else { ?>
      <li><a href="../connexion.php">Connexion</a></li>
      <?php }?>
      <li><a href="../participer.php#charte">Charte</a></li>
      <li><a href="http://www.generation-city.com/">G&eacute;n&eacute;ration City</a></li>
      <li><a href="http://www.forum-gc.com/">Forum</a></li>
    </ul>
    <a href="http://www.generation-city.com/"><img src="http://www.generation-city.com/monde/assets/img/logoGC-small.png"></a>
    <div class="copyright">
      <p>Copyright &copy; G&eacute;n&eacute;ration-City - 2013 </p>
      <p>Tous droits r&eacute;serv&eacute;s - Version 1</p>
    </div>
  </div>
</footer>
