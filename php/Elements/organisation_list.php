<?php

if(isset($data['organisation']))
    /** @var \App\Models\Organisation $organisation */
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
        <?php if($organisation->allow_temperance): ?>
        <div class="pull-right" style="position: absolute; right: 0;">
            <?php
            $temperance = $organisation->temperance()->get()->first();
            renderElement('Temperance/resources_small',
                array('resources' => $temperance)
            ); ?>
        </div>
        <?php endif; ?>
        <a href="<?= route('organisation.showslug',
            $organisation->showRouteParameter()) ?>">
            <h2><?= e($organisation->name) ?></h2></a>
        <p>
            <?= $memberCount ?>
            <?= \Illuminate\Support\Str::plural('membre', $memberCount) ?>
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