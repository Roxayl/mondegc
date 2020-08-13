<?php

//requete categories monuments

$colname_ch_disp_id = "-1";
if (isset($_GET['ch_disp_id'])) {
  $colname_ch_disp_id = $_GET['ch_disp_id'];
}

$query_mon = sprintf("SELECT ch_disp_id, ch_disp_cat_id, ch_disp_mon_id, ch_pat_nom, ch_pat_lien_img1, ch_mon_cat_nom, ch_mon_cat_icon,  	ch_mon_cat_couleur FROM dispatch_mon_cat INNER JOIN patrimoine ON ch_disp_mon_id = ch_pat_id INNER JOIN monument_categories ON ch_disp_cat_id = ch_mon_cat_ID WHERE ch_disp_id = %s", GetSQLValueString($colname_ch_disp_id, "int"));
$mon = mysql_query($query_mon, $maconnexion) or die(mysql_error());
$row_mon = mysql_fetch_assoc($mon);
$totalRows_mon = mysql_num_rows($mon);

?>

<!-- Modal Header-->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
<h3 id="myModalLabel">Enlever <?= e($row_mon['ch_pat_nom']) ?> de la cat&eacute;gorie <?= e($row_mon['ch_mon_cat_nom']) ?> </h3>
          </div>
          <div class="modal-body">
          <div class="row-fluid">
          <div class="span9">
          <h1>Attention&nbsp;!</h1>
    <p>Souhaitez-vous r&eacute;ellement enlever <?= e($row_mon['ch_pat_nom']) ?> de cette cat&eacute;gorie&nbsp;?</p>
    <p><i class="icon-warning-sign"></i> Vous pourrez remettre plus tard ce monument dans cette cat&eacute;gorie.</p>
    </div>
    <div class="span3 icone-categorie"> 
          <img src="<?= e($row_mon['ch_mon_cat_icon']) ?>" alt="icone cat&eacute;gorie" style="background-color:<?= e($row_mon['ch_mon_cat_couleur']) ?>;">
          <img src="<?php echo $row_mon['ch_pat_lien_img1']; ?>" alt="image monument"></div>
    </div>
          </div>
          <div class="modal-footer">
<form action="disp_monument_supprimer.php" name="supprimer-categorie" method="POST" id="supprimer-categorie">
            <input name="ch_disp_id" type="hidden" value="<?= e($row_mon['ch_disp_id']) ?>">
            <input name="ch_disp_cat_id" type="hidden" value="<?= e($row_mon['ch_disp_cat_id']) ?>">
<button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
            <button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</button>
            </form>
          </div>