<?php

require_once('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=iso-8859-1');


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//recuperation ID temperance

$colname_temperance = "-1";
if (isset($_GET['ch_temp_id'])) {
  $colname_temperance = $_GET['ch_temp_id'];
}

$juge = $_SESSION['login_user'];

//requete pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_pays = sprintf("SELECT ch_pay_id, ch_pay_nom FROM temperance INNER JOIN pays ON ch_temp_element_id = ch_pay_id WHERE ch_temp_id=%s", GetSQLValueString( $colname_temperance, "int"));
$pays = mysql_query($query_pays, $maconnexion) or die(mysql_error());
$row_pays = mysql_fetch_assoc($pays);
$totalRows_pays = mysql_num_rows($pays);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "notation")) {
  $insertSQL = sprintf("INSERT INTO notation_temperance (ch_not_temp_label, ch_not_temp_date, ch_not_temp_juge, ch_not_temp_temperance_id, ch_not_temp_q1, ch_not_temp_q1_com, ch_not_temp_q2, ch_not_temp_q2_com, ch_not_temp_q3, ch_not_temp_q3_com, ch_not_temp_q4, ch_not_temp_q4_com, ch_not_temp_q5, ch_not_temp_q5_com, ch_not_temp_q6, ch_not_temp_q6_com, ch_not_temp_q7, ch_not_temp_q7_com, ch_not_temp_q8, ch_not_temp_q8_com, ch_not_temp_q9, ch_not_temp_q9_com, ch_not_temp_q10, ch_not_temp_q10_com) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_not_temp_label'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_date'], "date"),
                       GetSQLValueString($_POST['ch_not_temp_juge'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_temperance_id'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q1'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q1_com'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_q2'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q2_com'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_q3'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q3_com'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_q4'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q4_com'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_q5'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q5_com'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_q6'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q6_com'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_q7'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q7_com'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_q8'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q8_com'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_q9'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q9_com'], "text"),
                       GetSQLValueString($_POST['ch_not_temp_q10'], "int"),
                       GetSQLValueString($_POST['ch_not_temp_q10_com'], "text"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = "../back/Temperance_jugement.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  $adresse = $insertGoTo .'#liste-temperance';
  header(sprintf("Location: %s", $adresse));
} ?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="notation" method="POST" class="form-horizontal" id="notation">
  <!-- Boutons cach�s -->
  <?php $now= date("Y-m-d G:i:s");?>
  <input name="ch_not_temp_label" type="hidden" value="notation">
  <input name="ch_not_temp_date" type="hidden" value="<?php echo $now; ?>">
  <input name="ch_not_temp_juge" type="hidden" value="<?php echo $juge; ?>">
  <input name="ch_not_temp_temperance_id" type="hidden" value="<?php echo $colname_temperance; ?>">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
    <h3 id="myModalLabel"><?php echo $juges; ?>Quetionnaire de notation temp&eacute;rance : pays</h3>
  </div>
  <div class="modal-body">
    <div class="well">
      <h4>Vous notez le pays <a href="../page-pays.php?ch_pay_id=<?php echo $row_pays['ch_pay_id']; ?>" target="_blank"><?php echo $row_pays['ch_pay_nom']; ?></a></h4>
    </div>
    <ul class="listes">
      <div class="titre-gris">
        <h3>Premi&egrave;re partie : Appr&eacute;ciation g&eacute;n&eacute;rale - Coh&eacute;rence visuelle</h3>
      </div>
      <div class="alert alert-success">
        <p>Attribuer 0 point pour un crit&egrave;re non satisfaisant</p>
        <p>Attribuer 1 point pour un crit&egrave;re &agrave; d&eacute;velopper ou &agrave; prouver</p>
        <p>Attribuer 2 points pour un crit&egrave;re satisfaisant et coh&eacute;rent</p>
      </div>
      <li class="row-fluid">
        <div class="span9">
          <p><strong>1-</strong> Jugez-vous satisfaisant le nombre d'images propos&eacute;es pour la capitale&nbsp;?</p>
        </div>
        <div id="sprytextfield1" class="span2">
          <input class="span8" type="text" name="ch_not_temp_q1" id="ch_not_temp_q1" value="" />
          <br />
          <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">1 seul chiffre est autoris&eacute;.</span></div>
        <div id="sprytextfield11" class="span11"> <em>commentaire</em>
          <input class="span11" type="text" name="ch_not_temp_q1_com" id="ch_not_temp_q1_com" value="" />
          <span class="textfieldMaxCharsMsg">maximum 250 caract�res.</span></div>
      </li>
      <li class="row-fluid">
        <div class="span9">
          <p><strong>2-</strong> Jugez-vous satisfaisant le nombre d'images propos&eacute;es pour chacune des villes provinciales&nbsp;?</p>
        </div>
        <div id="sprytextfield2" class="span2">
          <input class="span8" type="text" name="ch_not_temp_q2" id="ch_not_temp_q2" value="" />
          <br />
          <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">1 seul chiffre est autoris&eacute;.</span></div>
        <div id="sprytextfield12" class="span11"> <em>commentaire</em>
          <input class="span11" type="text" name="ch_not_temp_q2_com" id="ch_not_temp_q2_com" value="" />
          <span class="textfieldMaxCharsMsg">maximum 250 caract�res.</span></div>
      </li>
      <li class="row-fluid">
        <div class="span9">
          <p><strong>3-</strong> Jugez-vous satisfaisant le nombre d'informations et de donn&eacute;es transmises&nbsp;?</p>
        </div>
        <div id="sprytextfield3" class="span2">
          <input class="span8" type="text" name="ch_not_temp_q3" id="ch_not_temp_q3" value="" />
          <br />
          <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">1 seul chiffre est autoris&eacute;.</span></div>
        <div id="sprytextfield13" class="span11"> <em>commentaire</em>
          <input class="span11" type="text" name="ch_not_temp_q3_com" id="ch_not_temp_q3_com" value="" />
          <span class="textfieldMaxCharsMsg">maximum 250 caract�res.</span></div>
      </li>
      <p>&nbsp;</p>
      <div class="titre-gris">
        <h3>Deuxi&egrave;me partie : Appr&eacute;ciation g&eacute;n&eacute;rale - Coh&eacute;rence des chiffres par rapport aux visuels</h3>
      </div>
      <div class="alert alert-success">
        <p>Attribuer 0 point pour un crit&egrave;re non satisfaisant</p>
        <p>Attribuer 1 point pour un crit&egrave;re &agrave; d&eacute;velopper ou &agrave; prouver</p>
        <p>Attribuer 2 points pour un crit&egrave;re satisfaisant et coh&eacute;rent</p>
      </div>
      <li class="row-fluid">
        <div class="span9">
          <p><strong>4-</strong> Trouvez-vous coh&eacute;rent le chiffre indiqu&eacute; pour la population de la capitale&nbsp;?</p>
        </div>
        <div id="sprytextfield4" class="span2">
          <input class="span8" type="text" name="ch_not_temp_q4" id="ch_not_temp_q4" value="" />
          <br />
          <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">1 seul chiffre est autoris&eacute;.</span></div>
        <div id="sprytextfield14" class="span11"> <em>commentaire</em>
          <input class="span11" type="text" name="ch_not_temp_q4_com" id="ch_not_temp_q5_com" value="" />
          <span class="textfieldMaxCharsMsg">maximum 250 caract�res.</span></div>
      </li>
      <li class="row-fluid">
        <div class="span9">
          <p><strong>5-</strong> Trouvez-vous coh&eacute;rent le chiffre indiqu&eacute; pour la population des villes provinciales&nbsp;?</p>
        </div>
        <div id="sprytextfield5" class="span2">
          <input class="span8" type="text" name="ch_not_temp_q5" id="ch_not_temp_q5" value="" />
          <br />
          <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">1 seul chiffre est autoris&eacute;.</span></div>
        <div id="sprytextfield15" class="span11"> <em>commentaire</em>
          <input class="span11" type="text" name="ch_not_temp_q5_com" id="ch_not_temp_q5_com" value="" />
          <span class="textfieldMaxCharsMsg">maximum 250 caract�res.</span></div>
      </li>
      <p>&nbsp;</p>
      <div class="titre-gris">
        <h3>Troisi&egrave;me partie : Exploitation des donn&eacute;es chiffrables</h3>
      </div>
      <div class="alert alert-success">
        <p>Attribuer 0 point si donn&eacute;es inf&eacute;rieures, &eacute;gales &agrave; 0 ou NC</p>
        <p>Attribuer 1 point si donn&eacute;es sup&eacute;rieures &agrave; 0</p>
        <p>Attribuer 2 points si le pays est dans le top 10 / poss&egrave;de au moins 10 entit&eacute;s</p>
      </div>
      <li class="row-fluid">
        <div class="span9">
          <p><strong>6-</strong> Etude de la ressource Budget</p>
        </div>
        <div id="sprytextfield6" class="span2">
          <input class="span8" type="text" name="ch_not_temp_q6" id="ch_not_temp_q6" value="" />
          <br />
          <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">1 seul chiffre est autoris&eacute;.</span></div>
        <div id="sprytextfield16" class="span11">
          <p>commentaire</p>
          <input class="span11" type="text" name="ch_not_temp_q6_com" id="ch_not_temp_q6_com" value="" />
          <span class="textfieldMaxCharsMsg">maximum 250 caract�res.</span></div>
      </li>
      <li class="row-fluid">
        <div class="span9">
          <p><strong>7-</strong> Etude de la ressource Commerce</p>
        </div>
        <div id="sprytextfield7" class="span2">
          <input class="span8" type="text" name="ch_not_temp_q7" id="ch_not_temp_q7" value="" />
          <br />
          <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">1 seul chiffre est autoris&eacute;.</span></div>
        <div id="sprytextfield17" class="span11"> <em>commentaire</em>
          <input class="span11" type="text" name="ch_not_temp_q7_com" id="ch_not_temp_q7_com" value="" />
          <span class="textfieldMaxCharsMsg">maximum 250 caract�res.</span></div>
      </li>
      <li class="row-fluid">
        <div class="span9">
          <p><strong>8-</strong> Etude du nombre d'infrastructures propos&eacute;es, valid&eacute;es ou en cours de validation</p>
        </div>
        <div id="sprytextfield8" class="span2">
          <input class="span8" type="text" name="ch_not_temp_q8" id="ch_not_temp_q8" value="" />
          <br />
          <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">1 seul chiffre est autoris&eacute;.</span></div>
        <div id="sprytextfield18" class="span11"> <em>commentaire</em>
          <input class="span11" type="text" name="ch_not_temp_q8_com" id="ch_not_temp_q8_com" value="" />
          <span class="textfieldMaxCharsMsg">maximum 250 caract�res.</span></div>
      </li>
      <p>&nbsp;</p>
      <div class="titre-gris">
        <h3>Quatri&egrave;me partie : Evaluation comportementale</h3>
      </div>
      <div class="alert alert-success">
        <p>Attribuer 0 point pour un crit&egrave;re non satisfaisant</p>
        <p>Attribuer 1 point pour un crit&egrave;re &agrave; d&eacute;velopper ou &agrave; prouver</p>
        <p>Attribuer 2 points pour un crit&egrave;re satisfaisant et coh&eacute;rent</p>
      </div>
      <li class="row-fluid">
        <div class="span9">
          <p><strong>9-</strong> Le pays propose-t-il une homog&eacute;n&eacute;it&eacute; de son histoire avec le Monde GC&nbsp;?</p>
        </div>
        <div id="sprytextfield9" class="span2">
          <input class="span8" type="text" name="ch_not_temp_q9" id="ch_not_temp_q9" value="" />
          <br />
          <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">1 seul chiffre est autoris&eacute;.</span></div>
        <div id="sprytextfield19" class="span11"> <em>commentaire</em>
          <input class="span11" type="text" name="ch_not_temp_q9_com" id="ch_not_temp_q9_com" value="" />
          <span class="textfieldMaxCharsMsg">maximum 250 caract�res.</span></div>
      </li>
      <li class="row-fluid">
        <div class="span9">
          <p><strong>10-</strong> Participe-t-il au d&eacute;veloppement du Monde et &agrave; sa p&eacute;rennit&eacute; de par sa politique g&eacute;n&eacute;rale&nbsp;? </p>
        </div>
        <div id="sprytextfield10" class="span2">
          <input class="span8" type="text" name="ch_not_temp_q10" id="ch_not_temp_q10" value="" />
          <br />
          <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">1 seul chiffre est autoris&eacute;.</span></div>
        <div id="sprytextfield20" class="span11"> <em>commentaire</em>
          <input class="span11" type="text" name="ch_not_temp_q10_com" id="ch_not_temp_q10_com" value="" />
          <span class="textfieldMaxCharsMsg">maximum 250 caract�res.</span></div>
      </li>
    </ul>
    <p>&nbsp;</p>
    <div class="alert alert-danger">
      <h4>Attention</h4>
      Vous ne pourrez pas modifier votre vote apr&egrave;s l'avoir valid&eacute;. </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary"><i class="icon-jugement"></i> Valider</button>
  </div>
  <input type="hidden" name="MM_insert" value="notation">
</form>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {validateOn:["blur", "change"], useCharacterMasking:true, maxChars:1});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "integer", {validateOn:["change"], useCharacterMasking:true, maxChars:1});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {validateOn:["change"], useCharacterMasking:true, maxChars:1});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "integer", {validateOn:["change"], useCharacterMasking:true, maxChars:1});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "integer", {validateOn:["change"], useCharacterMasking:true, maxChars:1});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "integer", {validateOn:["change"], useCharacterMasking:true, maxChars:1});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "integer", {validateOn:["change"], useCharacterMasking:true, maxChars:1});
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "integer", {validateOn:["change"], useCharacterMasking:true, maxChars:1});
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9", "integer", {validateOn:["change"], useCharacterMasking:true, maxChars:1});
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "integer", {validateOn:["change"], useCharacterMasking:true, maxChars:1});
var sprytextfield11 = new Spry.Widget.ValidationTextField("sprytextfield11", "none", {validateOn:["change"], maxChars:250, isRequired:false});
var sprytextfield12 = new Spry.Widget.ValidationTextField("sprytextfield12", "none", {validateOn:["change"], maxChars:250, isRequired:false});
var sprytextfield13 = new Spry.Widget.ValidationTextField("sprytextfield13", "none", {validateOn:["change"], maxChars:250, isRequired:false});
var sprytextfield14 = new Spry.Widget.ValidationTextField("sprytextfield14", "none", {validateOn:["change"], maxChars:250, isRequired:false});
var sprytextfield15 = new Spry.Widget.ValidationTextField("sprytextfield15", "none", {validateOn:["change"], maxChars:250, isRequired:false});
var sprytextfield16 = new Spry.Widget.ValidationTextField("sprytextfield16", "none", {validateOn:["change"], maxChars:250, isRequired:false});
var sprytextfield17 = new Spry.Widget.ValidationTextField("sprytextfield17", "none", {validateOn:["change"], maxChars:250, isRequired:false});
var sprytextfield18 = new Spry.Widget.ValidationTextField("sprytextfield18", "none", {validateOn:["change"], maxChars:250, isRequired:false});
var sprytextfield19 = new Spry.Widget.ValidationTextField("sprytextfield19", "none", {validateOn:["change"], maxChars:250, isRequired:false});
var sprytextfield20 = new Spry.Widget.ValidationTextField("sprytextfield20", "none", {validateOn:["change"], maxChars:250, isRequired:false});
</script>