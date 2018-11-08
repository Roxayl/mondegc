<?php
session_start();
include('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=iso-8859-1');

//requete categories monuments

$colname_membres_groupes = "-1";
if (isset($_GET['mem_group_ID'])) {
  $colname_membres_groupes = $_GET['mem_group_ID'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_membres_groupes = sprintf("SELECT * FROM membres_groupes WHERE ch_mem_group_ID = %s ORDER BY ch_mem_group_mis_jour DESC", GetSQLValueString($colname_membres_groupes, "int"));
$membres_groupes = mysql_query($query_membres_groupes, $maconnexion) or die(mysql_error());
$row_membres_groupes = mysql_fetch_assoc($membres_groupes);
$totalRows_membres_groupes = mysql_num_rows($membres_groupes);

?>

<!-- Modal Header-->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">Supprimer <?php echo $row_membres_groupes['ch_mem_group_nom']; ?></h3>
          </div>
          <div class="modal-body">
          <div class="row-fluid">
          <div class="span9">
          <h1>Attention&nbsp;!</h1>
    <p>Souhaitez-vous r&eacute;ellement supprimer ce groupe&nbsp;?</p>
    <p><i class="icon-warning-sign"></i> Cette action sera irr&eacute;versible</p>
    </div>
    <div class="span3 icone-categorie"> 
          <img src="<?php echo $row_membres_groupes['ch_mem_group_icon']; ?>" alt="icone cat&eacute;gorie" style="background-color:<?php echo $row_membres_groupes['ch_mem_group_couleur']; ?>;"></div>
    </div>
          </div>
          <div class="modal-footer">
<form action="groupe_supprimer.php" name="supprimer-categorie" method="POST" id="supprimer-categorie">
            <input name="ch_mem_group_ID" type="hidden" value="<?php echo $row_membres_groupes['ch_mem_group_ID']; ?>">
<button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
            <button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</button>
            </form>
          </div>
<?php
mysql_free_result($membres_groupes);?>