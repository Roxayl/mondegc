<?php

$path = '';
if(isset($data['path']))
    $path = $data['path'];
$resources = $data['resources'];

?>

<div class="resource-small-container">
    <?php foreach($resources as $key => $value): ?>
    <div style="display: inline-block; font-weight: bold; margin-right: 5px;"
         class="token-<?= __s($key) ?>" title="<?= \Illuminate\Support\Str::ucfirst($key) ?>">
        <img style="width: 16px; height: 16px; margin-top: -2px;"
             src="<?= $path ?>assets/img/ressources/<?= __s($key) ?>.png" alt="<?= __s($key) ?>">
        <?= __s(formatNum($value)) ?>
    </div>
    <?php endforeach; ?>
</div>