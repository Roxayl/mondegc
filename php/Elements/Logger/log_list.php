<?php

$logs = $data['logs'];

/** @var \GenCity\Monde\Logger\Log[] $logs */
foreach($logs as $log):

    $thisUser = new \GenCity\Monde\User($log->get('user_id'));

    switch($log->get('type_action')) {

        case 'insert':
            $label_action = ' a ajouté '; break;

        case 'update':
            $label_action = ' a modifié '; break;

        case 'delete':
            $label_action = ' a supprimé '; break;

    }

    switch($log->get('target')) {

        case 'infrastructures_officielles':
            $label = __s($thisUser->get('ch_use_login')) . " $label_action une infrastructure officielle.";
            break;

        case 'infrastructures_groupes':
            $label = __s($thisUser->get('ch_use_login')) . " $label_action un groupe d'infrastructure.";
            break;

        case 'pages':
            $label = __s($thisUser->get('ch_use_login')) . " $label_action une page du site.";
            break;

        case 'pays':
            $label = __s($thisUser->get('ch_use_login')) . " $label_action un pays.";
            break;

        default:
            $label = __s($thisUser->get('ch_use_login')) . " $label_action un élément (" . __s($log->get('target'))
                    . ") du site.";
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
