<?php

use Roxayl\MondeGC\Models\Enums\Resource;

$path = '';
if(isset($data['path']))
    $path = $data['path'];
$resources = $data['resources'];

?>

<div class="resource-small-container">
    <?php
    $i = 0;
    foreach($resources as $key => $value):
        if(!in_array(strtolower($key), array_column(Resource::cases(), 'value'), true))
            continue;
        ?>
    <div class="resource-small-inline-block token-<?= htmlspecialchars($key) ?>"
         title="<?= \Illuminate\Support\Str::ucfirst($key) ?>">
        <img src="<?= url("assets/img/ressources/" . htmlspecialchars($key) . ".png") ?>"
             alt="<?= htmlspecialchars($key) ?>">
        <?= number_format((float)$value, 0, ',', '&#160;') ?>
    </div>
    <?php endforeach; ?>
</div>