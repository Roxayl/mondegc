<?php
require_once('Connections/maconnexion.php');

// Connexions pour live stats
mysql_select_db($database_maconnexion, $maconnexion);
$query_nb_pays = "SELECT ch_pay_id FROM pays WHERE ch_pay_publication = 1";
$nb_pays = mysql_query($query_nb_pays, $maconnexion) or die(mysql_error());
$row_nb_pays = mysql_fetch_assoc($nb_pays);
$totalRows_nb_pays = mysql_num_rows($nb_pays);

mysql_select_db($database_maconnexion, $maconnexion);
$query_nb_villes = "SELECT ch_vil_ID FROM villes WHERE ch_vil_capitale != 3";
$nb_villes = mysql_query($query_nb_villes, $maconnexion) or die(mysql_error());
$row_nb_villes = mysql_fetch_assoc($nb_villes);
$totalRows_nb_villes = mysql_num_rows($nb_villes);

mysql_select_db($database_maconnexion, $maconnexion);
$query_population = "SELECT SUM(ch_pay_population_carte) + (SELECT SUM(ch_vil_population) FROM villes INNER JOIN pays ON ch_vil_paysID = ch_pay_id WHERE ch_pay_publication = 1 AND ch_vil_capitale != 3) AS population_mondiale FROM pays WHERE ch_pay_publication = 1";
$population = mysql_query($query_population, $maconnexion) or die(mysql_error());
$row_population = mysql_fetch_assoc($population);
$totalRows_population = mysql_num_rows($population);
$population_mondiale = $row_population['population_mondiale'];
$population_mondiale_francais = number_format($population_mondiale, 0, ',', ' ');
 ?>

<div class="bandeau-stat">
  <div class="container"> <span class="live-stat"><em>Statistiques en direct</em></span>
    <ul>
      <li><span class="label"><?php echo $totalRows_nb_pays ?></span> pays </li>
      <li><span class="label"><?php echo $totalRows_nb_villes ?></span> villes </li>
      <li><span class="label"><?php echo $population_mondiale_francais ?></span> habitants</li>
    </ul>
  </div>
</div>
<?php
mysql_free_result($nb_pays);

mysql_free_result($nb_villes);

mysql_free_result($population);
 ?>
