<?php

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

//recuperation ID element
$ch_temp_id = "-1";
if (isset($_GET['ch_temp_id'])) {
  $ch_temp_id = $_GET['ch_temp_id'];
}

//requete temperance

$query_temperance = sprintf("SELECT * FROM notation_temperance WHERE ch_not_temp_temperance_id=%s", escape_sql( $ch_temp_id, "int"));
$temperance = mysql_query($query_temperance, $maconnexion);
$row_temperance = mysql_fetch_assoc($temperance);
$totalRows_temperance = mysql_num_rows($temperance);

//calcul total pt selon nb de juges
$totalptQuestion = 2 * $totalRows_temperance;

//calcul point pour chaque crit�re
$i=0;
do {
$i++;
$note_Q1 = $note_Q1 + $row_temperance ['ch_not_temp_q1'];
${'commentaireQ1com'.$i}= $row_temperance ['ch_not_temp_q1_com'];
${'commentaireQ1auteurcom'.$i}= $row_temperance ['ch_not_temp_juge'];
$note_Q2 = $note_Q2 + $row_temperance ['ch_not_temp_q2'];
${'commentaireQ2com'.$i}= $row_temperance ['ch_not_temp_q2_com'];
${'commentaireQ2auteurcom'.$i}= $row_temperance ['ch_not_temp_juge'];
$note_Q3 = $note_Q3 + $row_temperance ['ch_not_temp_q3'];
${'commentaireQ3com'.$i}= $row_temperance ['ch_not_temp_q3_com'];
${'commentaireQ3auteurcom'.$i}= $row_temperance ['ch_not_temp_juge'];
$note_Q4 = $note_Q4 + $row_temperance ['ch_not_temp_q4'];
${'commentaireQ4com'.$i}= $row_temperance ['ch_not_temp_q4_com'];
${'commentaireQ4auteurcom'.$i}= $row_temperance ['ch_not_temp_juge'];
$note_Q5 = $note_Q5 + $row_temperance ['ch_not_temp_q5'];
${'commentaireQ5com'.$i}= $row_temperance ['ch_not_temp_q5_com'];
${'commentaireQ5auteurcom'.$i}= $row_temperance ['ch_not_temp_juge'];
$note_Q6 = $note_Q6 + $row_temperance ['ch_not_temp_q6'];
${'commentaireQ6com'.$i}= $row_temperance ['ch_not_temp_q6_com'];
${'commentaireQ6auteurcom'.$i}= $row_temperance ['ch_not_temp_juge'];
$note_Q7 = $note_Q7 + $row_temperance ['ch_not_temp_q7'];
${'commentaireQ7com'.$i}= $row_temperance ['ch_not_temp_q7_com'];
${'commentaireQ7auteurcom'.$i}= $row_temperance ['ch_not_temp_juge'];
$note_Q8 = $note_Q8 + $row_temperance ['ch_not_temp_q8'];
${'commentaireQ8com'.$i}= $row_temperance ['ch_not_temp_q8_com'];
${'commentaireQ8auteurcom'.$i}= $row_temperance ['ch_not_temp_juge'];
$note_Q9 = $note_Q9 + $row_temperance ['ch_not_temp_q9'];
${'commentaireQ9com'.$i}= $row_temperance ['ch_not_temp_q9_com'];
${'commentaireQ9auteurcom'.$i}= $row_temperance ['ch_not_temp_juge'];
$note_Q10 = $note_Q10 + $row_temperance ['ch_not_temp_q10'];
${'commentaireQ10com'.$i}= $row_temperance ['ch_not_temp_q10_com'];
${'commentaireQ10auteurcom'.$i}= $row_temperance ['ch_not_temp_juge'];
} while ($row_temperance = mysql_fetch_assoc($temperance));

//requete pays

$query_pays = sprintf("SELECT ch_pay_id, ch_pay_nom FROM temperance INNER JOIN pays ON ch_temp_element_id = ch_pay_id WHERE ch_temp_id=%s", escape_sql($ch_temp_id, "int"));
$pays = mysql_query($query_pays, $maconnexion);
$row_pays = mysql_fetch_assoc($pays);
$totalRows_pays = mysql_num_rows($pays);
?>

<!-- Modal Header-->
<!-- Boutons cachés -->
<div class="modal-header">
<div class="pull-left"></div>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel">Rapport des juges temp&eacute;rants sur le pays : <a href="../page-pays.php?ch_pay_id=<?= e($row_pays['ch_pay_id']) ?>" target="_blank"><?= e($row_pays['ch_pay_nom']) ?></a></h3>
</div>
<div class="modal-body">
<div class="alert alert-tips"><p>Ce rapport a &eacute;t&eacute; r&eacute;dig&eacute; dans le cadre du <a href="http://www.generation-city.com/monde/economie.php#temperance" title="en savoir plus sur le porjet temp&eacute;rance"> projet Temp&eacute;rance de l'Institut Economique</a> qui vise &agrave; appr&eacute;cier la coh&eacute;rence globale d'un pays.</p>
<h4>Nombre de juges votants&nbsp;: <?php echo $totalRows_temperance ?></h4></div>
  <ul class="listes">
    <div class="titre-gris">
      <h3>Appr&eacute;ciation g&eacute;n&eacute;rale - Coh&eacute;rence visuelle</h3>
    </div>
    <li class="row-fluid">
      <div class="span11">
        <p><strong>1-</strong> Nombre d'images propos&eacute;es pour la capitale</p>
      </div>
      <div class="span1">
        <p><strong><?php echo $note_Q1 ?>/<?php echo $totalptQuestion; ?></strong></p>
      </div>
      <div class="span11">
        <?php 
		$i = 0;
		do {
    		$i++; ?>
        <p><strong><?php echo ${'commentaireQ1auteurcom'.$i} ?>&nbsp;: </strong><em><?php echo ${'commentaireQ1com'.$i} ?></em></p>
        <?php } while ($i < $totalRows_temperance);?>
      </div>
    </li>
    <li class="row-fluid">
      <div class="span11">
        <p><strong>2-</strong> Nombre d'images propos&eacute;es pour chacune des villes provinciales</p>
      </div>
      <div class="span1">
        <p><strong><?php echo $note_Q2 ?>/<?php echo $totalptQuestion; ?></strong></p>
      </div>
      <div class="span11">
        <?php 
		$i = 0;
		do {
    		$i++; ?>
        <p><strong><?php echo ${'commentaireQ2auteurcom'.$i} ?>&nbsp;: </strong><em><?php echo ${'commentaireQ2com'.$i} ?></em></p>
        <?php } while ($i < $totalRows_temperance);?>
      </div>
    </li>
    <li class="row-fluid">
      <div class="span11">
        <p><strong>3-</strong> Nombre d'informations et de donn&eacute;es transmises</p>
      </div>
      <div class="span1">
        <p><strong><?php echo $note_Q3 ?>/<?php echo $totalptQuestion; ?></strong></p>
      </div>
      <div class="span11">
        <?php 
		$i = 0;
		do {
    		$i++; ?>
        <p><strong><?php echo ${'commentaireQ3auteurcom'.$i} ?>&nbsp;: </strong><em><?php echo ${'commentaireQ3com'.$i} ?></em></p>
        <?php } while ($i < $totalRows_temperance);?>
      </div>
    </li>
    <p>&nbsp;</p>
    <div class="titre-gris">
      <h3>Appr&eacute;ciation g&eacute;n&eacute;rale - Coh&eacute;rence des chiffres par rapport aux visuels</h3>
    </div>
    <li class="row-fluid">
      <div class="span11">
        <p><strong>4-</strong> Coh&eacute;rence du chiffre indiqu&eacute; pour la population de la capitale</p>
      </div>
      <div class="span1">
        <p><strong><?php echo $note_Q4 ?>/<?php echo $totalptQuestion; ?></strong></p>
      </div>
      <div class="span11">
        <?php 
		$i = 0;
		do {
    		$i++; ?>
        <p><strong><?php echo ${'commentaireQ4auteurcom'.$i} ?>&nbsp;: </strong><em><?php echo ${'commentaireQ4com'.$i} ?></em></p>
        <?php } while ($i < $totalRows_temperance);?>
      </div>
    </li>
    <li class="row-fluid">
      <div class="span11">
        <p><strong>5-</strong> Coh&eacute;rence du chiffre indiqu&eacute; pour la population des villes provinciales</p>
      </div>
      <div class="span1">
        <p><strong><?php echo $note_Q5 ?>/<?php echo $totalptQuestion; ?></strong></p>
      </div>
      <div class="span11">
        <?php 
		$i = 0;
		do {
    		$i++; ?>
        <p><strong><?php echo ${'commentaireQ5auteurcom'.$i} ?>&nbsp;: </strong><em><?php echo ${'commentaireQ5com'.$i} ?></em></p>
        <?php } while ($i < $totalRows_temperance);?>
      </div>
    </li>
    <p>&nbsp;</p>
    <div class="titre-gris">
      <h3>Exploitation des donn&eacute;es chiffrables</h3>
    </div>
    <li class="row-fluid">
      <div class="span11">
        <p><strong>6- </strong>Etude de la ressource Budget</p>
      </div>
      <div class="span1">
        <p><strong><?php echo $note_Q6 ?>/<?php echo $totalptQuestion; ?></strong></p>
      </div>
      <div class="span11">
        <?php 
		$i = 0;
		do {
    		$i++; ?>
        <p><strong><?php echo ${'commentaireQ6auteurcom'.$i} ?>&nbsp;: </strong><em><?php echo ${'commentaireQ6com'.$i} ?></em></p>
        <?php } while ($i < $totalRows_temperance);?>
      </div>
    </li>
    <li class="row-fluid">
      <div class="span11">
        <p><strong>7-</strong> Etude de la ressource Commerce</p>
      </div>
      <div class="span1">
        <p><strong><?php echo $note_Q7 ?>/<?php echo $totalptQuestion; ?></strong></p>
      </div>
      <div class="span11">
        <?php 
		$i = 0;
		do {
    		$i++; ?>
        <p><strong><?php echo ${'commentaireQ7auteurcom'.$i} ?>&nbsp;: </strong><em><?php echo ${'commentaireQ7com'.$i} ?></em></p>
        <?php } while ($i < $totalRows_temperance);?>
      </div>
    </li>
    <li class="row-fluid">
      <div class="span11">
        <p><strong>8- </strong>Etude du nombre d'infrastructures propos&eacute;es, valid&eacute;es ou en cours de validation</p>
      </div>
      <div class="span1">
        <p><strong><?php echo $note_Q8 ?>/<?php echo $totalptQuestion; ?></strong></p>
      </div>
      <div class="span11">
        <?php 
		$i = 0;
		do {
    		$i++; ?>
        <p><strong><?php echo ${'commentaireQ8auteurcom'.$i} ?>&nbsp;: </strong><em><?php echo ${'commentaireQ8com'.$i} ?></em></p>
        <?php } while ($i < $totalRows_temperance);?>
      </div>
    </li>
    <p>&nbsp;</p>
    <div class="titre-gris">
      <h3>Evaluation comportementale</h3>
    </div>
    <li class="row-fluid">
      <div class="span11">
        <p><strong>9- </strong>Homog&eacute;n&eacute;it&eacute; de l'histoire du pays avec le Monde GC</p>
      </div>
      <div class="span1">
        <p><strong><?php echo $note_Q9 ?>/<?php echo $totalptQuestion; ?></strong></p>
      </div>
      <div class="span11">
        <?php 
		$i = 0;
		do {
    		$i++; ?>
        <p><strong><?php echo ${'commentaireQ9auteurcom'.$i} ?>&nbsp;: </strong><em><?php echo ${'commentaireQ9com'.$i} ?></em></p>
        <?php } while ($i < $totalRows_temperance);?>
      </div>
    </li>
    <li class="row-fluid">
      <div class="span11">
        <p><strong>10- </strong>Participation au d&eacute;veloppement du Monde et &agrave; sa p&eacute;rennit&eacute;</p>
      </div>
      <div class="span1">
        <p><strong><?php echo $note_Q10 ?>/<?php echo $totalptQuestion; ?></strong></p>
      </div>
      <div class="span11">
        <?php 
		$i = 0;
		do {
    		$i++; ?>
        <p><strong><?php echo ${'commentaireQ10auteurcom'.$i} ?>&nbsp;: </strong><em><?php echo ${'commentaireQ10com'.$i} ?></em></p>
        <?php } while ($i < $totalRows_temperance);?>
      </div>
    </li>
    <p>&nbsp;</p>
    <div class="row-fluid">
      <div class="titre-gris">
        <h3><span style="text-align:left;">Total : </span><?php echo ($note_Q1+$note_Q2+$note_Q3+$note_Q4+$note_Q5+$note_Q6+$note_Q7+$note_Q8+$note_Q9+$note_Q10) ?>/<?php echo (10 * $totalptQuestion); ?></h3>
      </div>
    </div>
  </ul>
  <p>&nbsp;</p>
</div>
<div class="modal-footer">
  <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
</div>