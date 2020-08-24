<?php

if(isset($data['pays'])) {
    $pays = $data['pays'];
    $temperance = $data['temperance'];
}

?>

<li>
  <div class="row-fluid">
    <div class="span2"> <a href="page-pays.php?ch_pay_id=<?= $pays['ch_pay_id'] ?>"><img src="<?= __s($pays['ch_pay_lien_imgdrapeau']) ?>" alt="drapeau"></a> </div>
    <div class="span4">
      <h3><?= __s($pays['ch_pay_nom']) ?></h3>
    </div>
    <div class="span4">
      <p><strong>
        <?= number_format($pays['nbhabitant'], 0, ',', ' '); ?>
        </strong> habitants</p>
    </div>
    <div class="span2">
        <a href="page-pays.php?ch_pay_id=<?= $pays['ch_pay_id']; ?>"
           class="btn btn-primary">Visiter</a>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span10 offset2">
        <?php renderElement('temperance/resources_small',
            ['resources' => $temperance]); ?>
    </div>
  </div>
</li>