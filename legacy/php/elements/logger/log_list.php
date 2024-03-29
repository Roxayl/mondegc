<?php

$logs = $data['logs'];

/** @var \GenCity\Monde\Logger\Log[] $logs */
foreach($logs as $log):

    $userId = $log->get('user_id');
    $thisUser = null;
    $username = 'Un utilisateur';

    if(! is_null($userId)) {
        $thisUser = new \GenCity\Monde\User($userId);
        $username = $thisUser->get('ch_use_login');
    }

    $label = e($username);

    switch($log->get('type_action')) {

        case 'insert':
            $label .= ' a ajouté ';
            $color = '#ACD9AB';
            break;

        case 'update':
            $label .= ' a modifié ';
            $color = '#D9D38D';
            break;

        case 'delete':
            $label .= ' a supprimé ';
            $color = '#D9AB9B';
            break;

        default:
            $label .= ' a effectué une action sur ';
            $color = '#B4BCD9';

    }

    switch($log->get('target')) {

        case 'infrastructures_officielles':
            $label .= " une infrastructure officielle."; break;

        case 'infrastructures_groupes':
            $label .= " un groupe d'infrastructure."; break;

        case 'pages':
            $label .= " une page du site."; break;

        case 'pays':
            $label .= " un pays."; break;

        case 'instituts':
            $label .= " un comité."; break;

        default:
            $label = e($username) . " a effectué une action sur un élément ("
                . e($log->get('target')) . ") du site.";

    }

    $thisData = json_decode($log->get('data_changes'));
    $thisData = json_encode($thisData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    ?>

    <div class="proposal-active-container well well-light"
         style="border-left: 12px solid <?= $color ?>; border-radius: 5px; margin-bottom: 10px;">
    <div class="row-fluid">

        <h4><?= $label ?></h4>

        <p><br>
        <strong>Élément :</strong> <?= e($log->get('target')) ?> /
        <strong>Type d'action :</strong> <?= e($log->get('type_action')) ?> /
        <?php if(!empty($log->get('target_id'))): ?>
        <strong>ID de l'élément :</strong> <?= e($log->get('target_id')) ?> /
        <?php endif; ?>
        <strong>Utilisateur exécutant l'action :</strong> <?= e($username) ?>
            (<?= e($log->get('user_id')) ?>) /
        <strong>Horodateur :</strong> <?= e(dateFormat($log->get('created'), true)) ?>
        </p>

        <?php if(!is_null($log->get('data_changes')) && $log->get('data_changes') !== 'null'): ?>
            <p><strong>Données supplémentaires :</strong></p>
            <pre style="width: 95%; max-height: 120px; overflow-y: auto;"><?= e($thisData) ?></pre>
        <?php endif; ?>

    </div>
    </div>

    <?php

endforeach;
