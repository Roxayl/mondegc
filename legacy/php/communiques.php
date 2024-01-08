<?php  ?>
<?php 
//requete liste communiqués

$maxRows_communiques = 10;
$pageNum_communiques = 0;
if (isset($_GET['pageNum_communiques'])) {
  $pageNum_communiques = $_GET['pageNum_communiques'];
}
$startRow_communiques = $pageNum_communiques * $maxRows_communiques;


$query_communiques = sprintf("SELECT * FROM communiques WHERE communiques.ch_com_categorie = '$ch_com_categorie'  AND communiques.ch_com_element_id = %s AND communiques.ch_com_statut = '1' ORDER BY ch_com_date DESC", escape_sql($ch_com_element_id, "int"));
$query_limit_communiques = sprintf("%s LIMIT %d, %d", $query_communiques, $startRow_communiques, $maxRows_communiques);
$communiques = mysql_query($query_limit_communiques, $maconnexion);
$row_communiques = mysql_fetch_assoc($communiques);

if (isset($_GET['totalRows_communiques'])) {
  $totalRows_communiques = $_GET['totalRows_communiques'];
} else {
  $all_communiques = mysql_query($query_communiques);
  $totalRows_communiques = mysql_num_rows($all_communiques);
}
$totalPages_communiques = ceil($totalRows_communiques/$maxRows_communiques)-1;

$queryString_communiques = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_communiques") == false && 
        stristr($param, "totalRows_communiques") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_communiques = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_communiques = sprintf("&totalRows_communiques=%d%s", $totalRows_communiques, $queryString_communiques);
?>
<?php if ($row_communiques) { ?>
<!-- Communiqués
        ================================================== -->

<div>
  <table width="100%" class="table table-hover">
    <thead>
      <tr class="tablehead">
        <th width="60%" scope="col">Titre</th>
        <th width="25%" scope="col">Date</th>
        <th width="15%" scope="col">&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php do { ?>
      <tr id="communiqueID<?= e($row_communiques['ch_com_ID']) ?>">
        <td><?= e($row_communiques['ch_com_titre']) ?></td>
        <td>Le <?php echo date("d/m/Y", strtotime($row_communiques['ch_com_date'])); ?></td>
        <td><!-- Button to trigger modal -->
          
          <div class="text-center">
          <?php if (isset($_SESSION['user_id']) && $row_communiques['ch_com_user_id'] == $_SESSION['user_ID']) { ?>
  <a class="btn btn-primary pull-right" href="php/partage-communique.php?com_id=<?= e($row_communiques['ch_com_ID']) ?>" data-toggle="modal" data-target="#myModal" title="Poster sur le forum"><i class="icon-share icon-white"></i></a>
  <?php } ?>
          <a class="btn btn-primary" href="php/communique-modal.php?com_id=<?= e($row_communiques['ch_com_ID']) ?>" data-toggle="modal" data-target="#myModal">Lire</a>
          </div></td>
      </tr>
      <?php } while ($row_communiques = mysql_fetch_assoc($communiques)); ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3" class="table-foot"><p class="pull-right">de <?php echo ($startRow_communiques + 1) ?> &agrave; <?php echo min($startRow_communiques + $maxRows_communiques, $totalRows_communiques) ?> sur <?php echo $totalRows_communiques ?>
            <?php if ($pageNum_communiques > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_communiques=%d%s#communiques", $currentPage, max(0, $pageNum_communiques - 1), $queryString_communiques); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_communiques < $totalPages_communiques) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_communiques=%d%s#communiques", $currentPage, min($totalPages_communiques, $pageNum_communiques + 1), $queryString_communiques); ?>"> <i class="icon-forward"></i></a>
            <?php } // Show if not last page ?></p>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
<div class="modal container fade" id="myModal"></div>
<script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#myModal').modal('hide');
});
</script>
<?php } else { ?>
<div class="well">
    <div class="alert alert-tips">
        <p>Aucun communiqu&eacute; n'a encore &eacute;t&eacute; &eacute;crit.</p>
    </div>
</div>
<?php } ?>
<?php 
mysql_free_result($communiques);
?>
