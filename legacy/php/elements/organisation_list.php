<?php

use Illuminate\Support\Str;

if(isset($data['organisation']))
    /** @var \Roxayl\MondeGC\Models\Organisation $organisation */
    $organisation = $data['organisation'];

$memberCount = $organisation->members->count();

?>

<li>
<div class="row-fluid">

    <div class="span2">
        <div style="width: 100%; height: 75px; background-size: cover;
                background-position: center;
                background-image: url('<?= e($organisation->flag) ?>');"></div>
    </div>

    <div class="span10">
        <?php if($organisation->hasEconomy()): ?>
        <div class="pull-right" style="position: absolute; right: 0;">
            <?php
            $resources = $organisation->resources();
            renderElement('temperance/resources_small',
                array('resources' => $resources)
            ); ?>
        </div>
        <?php endif; ?>
        <a href="<?= route('organisation.showslug',
            $organisation->showRouteParameter()) ?>">
            <h2><?= e($organisation->name) ?></h2></a>
        <p>
            <span class="badge org-<?= e($organisation->type) ?>">
                <?= __("organisation.types.{$organisation->type}") ?></span>
            <?= $memberCount ?>
            <?= Str::plural('membre', $memberCount) ?>
            <?php foreach($organisation->members as $member): ?>
                <a title="<?= __s($member->pays->ch_pay_nom) ?>"
                   href="<?= url("page-pays.php?ch_pay_id={$member->pays->ch_pay_id}")?>">
                  <img class="img-menu-drapeau" alt="<?= __s($member->pays->ch_pay_nom) ?>, "
                     src="<?= __s($member->pays->ch_pay_lien_imgdrapeau) ?>"></a>
            <?php endforeach; ?>
        </p>
    </div>

</div>
</li>
