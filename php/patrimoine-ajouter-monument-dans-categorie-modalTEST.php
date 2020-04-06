<?php

require_once('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=utf-8');


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-mon_categorie")) {
  $insertSQL = sprintf("INSERT INTO dispatch_mon_cat (ch_disp_cat_id, ch_disp_mon_label, ch_disp_mon_id, ch_disp_date) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_disp_cat_id'], "int"),
                       GetSQLValueString($_POST['ch_disp_mon_label'], "text"),
                       GetSQLValueString($_POST['ch_disp_mon_id'], "int"),
                       GetSQLValueString($_POST['ch_disp_date'], "date"));					   
					   
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = '../back/institut_patrimoine.php?mon_cat_ID = %s' .$row_mon_cat['ch_mon_cat_ID'].'';
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  $adresse = $insertGoTo .'#classer-monument';
  header(sprintf("Location: %s", $adresse));
}

//requete listes monuments
$colname_classer_mon = "-1";
if (isset($_GET['mon_cat_ID'])) {
  $colname_classer_mon = $_GET['mon_cat_ID'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mon_cat = sprintf("SELECT ch_pat_id, ch_pat_nom FROM patrimoine WHERE ch_pat_id NOT IN (SELECT ch_disp_mon_id FROM dispatch_mon_cat WHERE ch_disp_cat_id = %s)  ORDER BY ch_pat_mis_jour DESC", GetSQLValueString($colname_classer_mon, ""));
$liste_mon_cat = mysql_query($query_liste_mon_cat, $maconnexion) or die(mysql_error());
$row_liste_mon_cat = mysql_fetch_assoc($liste_mon_cat);
$totalRows_liste_mon_cat = mysql_num_rows($liste_mon_cat);

//calcul ressources de la ville
$ville_id = $row_infrastructure['ch_inf_villeid'];
mysql_select_db($database_maconnexion, $maconnexion);
$query_ressources_ville = sprintf("SELECT 
SUM(ch_mon_cat_budget) AS sum_ville_budget,
SUM(ch_mon_cat_Industrie) AS sum_ville_industrie,
SUM(ch_mon_cat_Commerce) AS sum_ville_commerce,
SUM(ch_mon_cat_Agriculture) AS sum_ville_agriculture,
SUM(ch_mon_cat_Tourisme) AS sum_ville_tourisme,
SUM(ch_mon_cat_Recherche) AS sum_ville_recherche,
SUM(ch_mon_cat_Environnement) AS sum_ville_environnement,
SUM(ch_mon_cat_Education) AS sum_ville_education
FROM monument_categories INNER JOIN  monuments ON monument_categories.ch_pat_id = monuments.ch_pat_id WHERE ch_inf_villeid = $ville_id");
$ressources_ville = mysql_query($query_ressources_ville, $maconnexion) or die(mysql_error());
$row_ressources_ville = mysql_fetch_assoc($ressources_ville);
$totalRows_ressources_ville = mysql_num_rows($ressources_ville);

//calcul ressources du pays 
$ch_pay_id = $row_infrastructure['ch_pay_id'];
mysql_select_db($database_maconnexion, $maconnexion);
$query_ressources_pays = sprintf("SELECT 
SUM(ch_mon_cat_budget) AS sum_pays_budget,
SUM(ch_mon_cat_Industrie) AS sum_pays_industrie,
SUM(ch_mon_cat_Commerce) AS sum_pays_commerce,
SUM(ch_mon_cat_Agriculture) AS sum_pays_agriculture,
SUM(ch_mon_cat_Tourisme) AS sum_pays_tourisme,
SUM(ch_mon_cat_Recherche) AS sum_pays_recherche,
SUM(ch_mon_cat_Environnement) AS sum_pays_environnement,
SUM(ch_mon_cat_Education) AS sum_pays_education
FROM monument_categories INNER JOIN  monuments ON monument_categories.ch_pat_id = monuments.ch_pat_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = $ch_pay_id");
$ressources_pays = mysql_query($query_ressources_pays, $maconnexion) or die(mysql_error());
$row_ressources_pays = mysql_fetch_assoc($ressources_pays);
$totalRows_ressources_pays = mysql_num_rows($ressources_pays);
?>

//requete info catégorie
mysql_select_db($database_maconnexion, $maconnexion);
$query_mon_cat = sprintf("SELECT ch_mon_cat_ID, ch_mon_cat_nom FROM monument_categories WHERE ch_mon_cat_ID = %s", GetSQLValueString($colname_classer_mon, "int"));
$mon_cat = mysql_query($query_mon_cat, $maconnexion) or die(mysql_error());
$row_mon_cat = mysql_fetch_assoc($mon_cat);
$totalRows_mon_cat = mysql_num_rows($mon_cat);
?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="ajout-mon_categorie" method="POST" class="form-horizontal" id="ajout-mon_categorie">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Ajouter un monument dans la cat&eacute;gorie <?php echo $row_mon_cat['ch_mon_cat_nom']; ?></h3>
  </div>
  <div class="modal-body"> 
    <!-- Boutons cachés -->
    <?php 
				  $now= date("Y-m-d G:i:s");?>
    <input name="ch_disp_cat_id" type="hidden" value="<?php echo $row_mon_cat['ch_mon_cat_ID']; ?>">
    <input name="ch_disp_mon_label" type="hidden" value="disp_mon">
    <input name="ch_disp_date" type="hidden" value="<?php echo $now; ?>">
    <select name="ch_disp_mon_id" id="ch_disp_mon_id">
      <?php do { ?>
      <option value="<?php echo $row_liste_mon_cat['ch_pat_id']; ?>"><?php echo $row_liste_mon_cat['ch_pat_nom']; ?></option>
      <?php } while ($row_liste_mon_cat = mysql_fetch_assoc($liste_mon_cat)); ?>
    </select>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_insert" value="ajout-mon_categorie">
</form>
<?php
mysql_free_result($liste_mon_cat);
mysql_free_result($mon_cat);?>
