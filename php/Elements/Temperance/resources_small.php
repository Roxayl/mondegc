<?php

$path = '';
if(isset($data['path']))
    $path = $data['path'];
$resources = $data['resources'];

?>

<div class="resource-small-container">
    <?php
    $i = 0;
    foreach($resources as $key => $value):?>
    <div class="resource-small-inline-block token-<?= __s($key) ?>"
         title="<?= \Illuminate\Support\Str::ucfirst($key) ?>">
        <img style=""
             src="<?= $path ?>assets/img/ressources/<?= __s($key) ?>.png" alt="<?= __s($key) ?>">
        <?= formatNum($value) ?>
    </div>
    <?php if($i++ > 2): $i = 0; ?><br><?php endif; ?>
    <?php endforeach; ?>
</div>