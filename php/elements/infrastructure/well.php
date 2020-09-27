<?php
if(!isset($data['type']))
    $data['type'] = 'infra';
switch($data['type']) {
    case 'infra':
        $url = url('php/infrastructure-modal.php?ch_inf_id=' . $data['id']);
        break;
    case 'patrimoine':
        $url = url( 'php/patrimoine-modal.php?ch_pat_id=' . $data['id']);
        break;
    default:
        $url = '';
}
?>

<div class="infra-well infra-type-<?= e($data['type']) ?>">
    <?php if(isset($data['overlay_text']) || isset($data['overlay_image'])): ?>
    <div class="infra-overlay" style="<?= isset($data['overlay_image']) ? 'padding-left: 32px;' : '' ?>">
        <?php if(isset($data['overlay_image'])): ?>
            <img src="<?= e($data['overlay_image']) ?>">
        <?php endif; ?>
        <?= isset($data['overlay_text']) ? $data['overlay_text'] : '' ?>
    </div>
    <?php endif; ?>
    <div class="infra-image" style="background-image: url('<?= $data['image'] ?>');"></div>
    <div class="infra-text">
        <a href="<?= e($url) ?>" data-toggle="modal" data-target="#Modal-Monument"><h4><?= e($data['nom']) ?></h4></a>
        <p><?= !isset($data['unescape_description']) || !$data['unescape_description'] ?
                e($data['description']) : $data['description'] ?></p>
    </div>
</div>
