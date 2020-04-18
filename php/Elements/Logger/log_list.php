<?php

$logs = $data['logs'];

/** @var \GenCity\Monde\Logger\Log[] $logs */
foreach($logs as $log):

    $thisUser = new \GenCity\Monde\User($log->get('user_id'));

    switch($log->get('type_action')) {

        case 'insert':
            $label_action = ' a ajouté ';
            $color = '#ACD9AB';
            break;

        case 'update':
            $label_action = ' a modifié ';
            $color = '#D9D38D';
            break;

        case 'delete':
            $label_action = ' a supprimé ';
            $color = '#D9AB9B';
            break;

        default:
            $label_action = ' a effectué une action sur ';
            $color = '#B4BCD9';

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

    <div class="proposal-active-container well well-light"
         style="border-left: 12px solid <?= $color ?>; border-radius: 5px; margin-bottom: 10px;">
    <div class="row-fluid">

        <h4><?= $label ?></h4>

        <p><br>
        <strong>Élément :</strong> <?= __s($log->get('target')) ?> /
        <strong>Type d'action :</strong> <?= __s($log->get('type_action')) ?> /
        <?php if(!empty($log->get('target_id'))): ?>
        <strong>ID de l'élément :</strong> <?= __s($log->get('target_id')) ?> /
        <?php endif; ?>
        <strong>Utilisateur exécutant l'action :</strong> <?= __s($thisUser->get('ch_use_login')) ?>
            (<?= __s($log->get('user_id')) ?>) /
        <strong>Horodateur :</strong> <?= __s(dateFormat($log->get('created'), true)) ?>
        </p>

        <?php if(!is_null($log->get('data_changes')) && $log->get('data_changes') !== 'null'): ?>
            <p><strong>Données supplémentaires :</strong></p>
            <pre style="width: 95%; max-height: 120px; overflow-y: auto;"><?= __s($thisData) ?></pre>
        <?php endif; ?>

    </div>
    </div>

    <?php

endforeach;
