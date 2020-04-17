<?php

$logs = $data['logs'];

/** @var \GenCity\Monde\Logger\Log[] $logs */
foreach($logs as $log):

    $thisUser = new \GenCity\Monde\User($log->get('user_id'));

    switch($log->get('target')) {

        case 'infrastructures_officielles':
            $label = __s($thisUser->get('ch_use_login')) . " a apporté des modifications aux infrastructures officielles.";
            break;

        case 'infrastructures_groupes':
            $label = __s($thisUser->get('ch_use_login')) . " a modifié a apporté des modifications aux groupes d'infrastructure.";
            break;

        case 'pages':
            $label = __s($thisUser->get('ch_use_login')) . " a apporté des modifications à une page du site.";
            break;

        default:
            $label = __s($thisUser->get('ch_use_login')) . " a apporté une modification non répertoriée.";
            break;

    }

    $thisData = json_decode($log->get('data_changes'));
    $thisData = json_encode($thisData, JSON_PRETTY_PRINT);
    ?>

    <div class="proposal-active-container well well-light">
    <div class="row-fluid">

        <p><?= $label ?></p>
        <strong>Élément :</strong> <?= __s($log->get('target')) ?> /
        <strong>Type d'action :</strong> <?= __s($log->get('type_action')) ?> /
        <?php if(!empty($log->get('target_id'))): ?>
        <strong>ID de l'élément :</strong> <?= __s($log->get('target_id')) ?> /
        <?php endif; ?>
        <strong>Utilisateur exécutant l'action :</strong> <?= __s($thisUser->get('ch_use_login')) ?>
            (<?= __s($log->get('user_id')) ?>) /
        <strong>Horodateur :</strong> <?= __s(dateFormat($log->get('created'), true)) ?>

        <?php if(!is_null($log->get('data_changes')) && $log->get('data_changes') !== 'null'): ?>
            <br>
            <strong>Données supplémentaires :</strong>
            <pre style="width: 95%; max-height: 120px; overflow-y: auto;"><?= __s($thisData) ?></pre>
        <?php endif; ?>

    </div>
    </div>

    <?php

endforeach;
