<?php

if(count($data['decisionData']) > 1) {
    echo '<small>Second tour : </small><br>';
}

foreach($data['decisionData'] as $data) {
    ?>

    <span style="color: <?= $data['color'] ?>;">
        <?= $data['intitule'] ?>
    </span>
    <br>

    <?php
}