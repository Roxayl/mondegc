<?php

require_once('../Connections/maconnexion.php');



//requete categories monuments

$colname_liste_mon_cat = "-1";
if (isset($_GET['mon_cat_id'])) {
  $colname_liste_mon_cat = $_GET['mon_cat_id'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mon_cat = sprintf("SELECT * FROM monument_categories WHERE ch_mon_cat_ID = %s ORDER BY ch_mon_cat_mis_jour DESC", GetSQLValueString($colname_liste_mon_cat, "int"));
$liste_mon_cat = mysql_query($query_liste_mon_cat, $maconnexion) or die(mysql_error());
$row_liste_mon_cat = mysql_fetch_assoc($liste_mon_cat);
$totalRows_liste_mon_cat = mysql_num_rows($liste_mon_cat);

?>

<!-- Modal Header-->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
<h3 id="myModalLabel">Supprimer <?php echo $row_liste_mon_cat['ch_mon_cat_nom']; ?></h3>
          </div>
          <div class="modal-body">
          <div class="row-fluid">
          <div class="span9">
          <h1>Attention&nbsp;!</h1>
    <p>Souhaitez-vous r&eacute;ellement supprimer cette cat&eacute;gorie&nbsp;?</p>
    <p><i class="icon-warning-sign"></i> Cette action sera irr&eacute;versible</p>
    </div>
    <div class="span3 icone-categorie"> 
          <img src="<?php echo $row_liste_mon_cat['ch_mon_cat_icon']; ?>" alt="icone cat&eacute;gorie" style="background-color:<?php echo $row_liste_mon_cat['ch_mon_cat_couleur']; ?>;"></div>
    </div>
          </div>
          <div class="modal-footer">
<form action="cat_monument_supprimer.php" name="supprimer-categorie" method="POST" id="supprimer-categorie">
            <input name="ch_mon_cat_ID" type="hidden" value="<?php echo $row_liste_mon_cat['ch_mon_cat_ID']; ?>">
<button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
            <button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</button>
            </form>
          </div>
<?php
mysql_free_result($liste_mon_cat);?>