<?php include('../Connections/maconnexion.php'); ?>
<?php 
//requete liste communiqués

$maxRows_Liste_communiques = 10;
$pageNum_Liste_communiques = 0;
if (isset($_GET['pageNum_Liste_communiques'])) {
  $pageNum_Liste_communiques = $_GET['pageNum_Liste_communiques'];
}
$startRow_Liste_communiques = $pageNum_Liste_communiques * $maxRows_Liste_communiques;

mysql_select_db($database_maconnexion, $maconnexion);
$query_Liste_communiques = sprintf("SELECT * FROM communiques WHERE communiques.ch_com_categorie = '$com_cat'  AND communiques.ch_com_element_id = %s ORDER BY ch_com_date_mis_jour DESC", GetSQLValueString($com_element_id, "int"));
$query_limit_Liste_communiques = sprintf("%s LIMIT %d, %d", $query_Liste_communiques, $startRow_Liste_communiques, $maxRows_Liste_communiques);
$Liste_communiques = mysql_query($query_limit_Liste_communiques, $maconnexion) or die(mysql_error());
$row_Liste_communiques = mysql_fetch_assoc($Liste_communiques);

if (isset($_GET['totalRows_Liste_communiques'])) {
  $totalRows_Liste_communiques = $_GET['totalRows_Liste_communiques'];
} else {
  $all_Liste_communiques = mysql_query($query_Liste_communiques);
  $totalRows_Liste_communiques = mysql_num_rows($all_Liste_communiques);
}
$totalPages_Liste_communiques = ceil($totalRows_Liste_communiques/$maxRows_Liste_communiques)-1;

$queryString_Liste_communiques = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Liste_communiques") == false && 
        stristr($param, "totalRows_Liste_communiques") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Liste_communiques = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Liste_communiques = sprintf("&totalRows_Liste_communiques=%d%s", $totalRows_Liste_communiques, $queryString_Liste_communiques);

?>
<!-- Liste des Communiqués
        ================================================== -->
        <?php if ($row_Liste_communiques) { ?>
        <table width="539" class="table table-hover">
          <thead>
            <tr class="tablehead">
              <th width="5%" scope="col"><a href="#" rel="clickover" title="Statut de votre communiqu&eacute;" data-content="le communiqu&eacute; peut-&ecirc;tre publi&eacute; ou masqu&eacute;."><i class="icon-globe"></i></a></th>
              <th width="64%" scope="col">Titre</th>
              <th width="23%" scope="col">Date</th>
              <th width="4%" scope="col">&nbsp;</th>
              <th width="4%" scope="col">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <?php do { ?>
              <tr>
                <td><img src="../assets/img/statutpays<?php echo $row_Liste_communiques['ch_com_statut']; ?>.png" alt="Statut"></td>
                <td><?php echo $row_Liste_communiques['ch_com_titre']; ?></td>
                <td>Le <?php echo date("d/m/Y", strtotime($row_Liste_communiques['ch_com_date_mis_jour'])); ?>
                &agrave; <?php echo date("G:i", strtotime($row_Liste_communiques['ch_com_date_mis_jour'])); ?>
                </td>
                <td><form action="communique_modifier.php" method="post">
                    <?php if(isset($colname_paysID)): ?>
                      <input name="paysID" type="hidden" value="<?php echo $colname_paysID; ?>">
                    <?php endif; ?>
                    <input name="userID" type="hidden" value="<?php echo $userID; ?>">
                    <input name="com_id" type="hidden" value="<?php echo $row_Liste_communiques['ch_com_ID']; ?>">
                    <button class="btn" type="submit" title="modifier le communiqu&eacute;"><i class="icon-pencil"></i></button>
                  </form></td>
                <td><form action="communique_confirmation_supprimer.php" method="post">
                    <input name="userID" type="hidden" value="<?php echo $userID; ?>">
                    <input name="communique_ID" type="hidden" value="<?php echo $row_Liste_communiques['ch_com_ID']; ?>">
                    <button class="btn" type="submit" title="supprimer le communiqu&eacute;"><i class="icon-trash"></i></button>
                  </form></td>
              </tr>
              <?php } while ($row_Liste_communiques = mysql_fetch_assoc($Liste_communiques)); ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5"><p class="pull-right">de <?php echo ($startRow_Liste_communiques + 1) ?> &agrave; <?php echo min($startRow_Liste_communiques + $maxRows_Liste_communiques, $totalRows_Liste_communiques) ?> sur <?php echo $totalRows_Liste_communiques ?>
            <?php if ($pageNum_Liste_communiques > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_Liste_communiques=%d%s#mes-communiques", $currentPage, max(0, $pageNum_Liste_communiques - 1), $queryString_Liste_communiques); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_Liste_communiques < $totalPages_Liste_communiques) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_Liste_communiques=%d%s#mes-communiques", $currentPage, min($totalPages_Liste_communiques, $pageNum_Liste_communiques + 1), $queryString_Liste_communiques); ?>"> <i class="icon-forward"></i></a>
            <?php } // Show if not last page ?></p>
                
                <!-- ajouter un commentaire -->
                <form action="communique_ajouter.php" method="post">
                <?php if(isset($colname_paysID)): ?>
                  <input name="paysID" type="hidden" value="<?php echo $colname_paysID; ?>">
                <?php endif; ?>
                  <input name="userID" type="hidden" value="<?php echo $userID; ?>">
                  <input name="cat" type="hidden" value="<?php echo $com_cat; ?>">
                  <input name="com_element_id" type="hidden" value="<?php echo $com_element_id; ?>">
                  <button class="btn btn-primary" type="submit">Ajouter un communiqu&eacute;</button>
                </form></td>
            </tr>
          </tfoot>
        </table>
        <?php } else { ?>
        <form action="communique_ajouter.php" method="post">
                <?php if(isset($colname_paysID)): ?>
                  <input name="paysID" type="hidden" value="<?php echo $colname_paysID; ?>">
                <?php endif; ?>
                 <input name="userID" type="hidden" value="<?php echo $userID; ?>">
                  <input name="cat" type="hidden" value="<?php echo $com_cat; ?>">
          <input name="com_element_id" type="hidden" value="<?php echo $com_element_id; ?>">
          <button class="btn btn-primary btn-margin-left" type="submit">Ajouter un communiqu&eacute;</button>
        </form>
        <?php } ?>
		<?php
mysql_free_result($Liste_communiques);?>