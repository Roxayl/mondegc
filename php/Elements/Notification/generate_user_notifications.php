<?php

if(!isset($_SESSION['userObject'])) return;

$userNotifications = new \GenCity\Monde\Notification\UserNotifications($_SESSION['userObject']);

$listNotifications = $userNotifications->getNotifications();

$countUnread = $userNotifications->getUnreadCount();

?>

<?php if($countUnread): ?>
<div class="pull-right" style="margin-right: 10px; margin-top: -3px;">
    <form method="POST" action="<?= DEF_URI_PATH ?>back/notifications_manage.php" class="notification-markasread">
        <input type="hidden" name="mark_unread" value="1">
        <button class="btn btn-primary" type="submit">Tout marquer comme lu</button>
    </form>
</div>
<?php endif; ?>

<h4 class="btn-margin-left">Notifications </h4>

<?php if(empty($listNotifications)): ?>
    <div class="well">
        <p>Vous n'avez pas de notifications récentes. :)</p>
    </div>
<?php endif; ?>

<?php
foreach($listNotifications as $notification):

    $continue = false;

    switch($notification->get('type_notif')) {

        case 'infra_juge_accepte':
            $element = new \GenCity\Monde\Temperance\Infrastructure($notification->get('element'));
            $header = "BIEN OUEJ !";
            $text = "Votre infrastructure <strong>" . __s($element->get('nom_infra')) .
                "</strong> a été acceptée par les juges tempérants.";
            $style = "background: linear-gradient(120deg, #ffe300 0%,#ff5c00 72%);";
            $link = DEF_URI_PATH . "back/ville_modifier.php?ville-ID=" . __s($element->get('ch_inf_villeid')) .
                "#mes-infrastructures";
            break;

        case 'infra_juge_refuse':
            $element = new \GenCity\Monde\Temperance\Infrastructure($notification->get('element'));
            $header = "TRY AGAIN...";
            $text = "Votre infrastructure <strong>" . __s($element->get('nom_infra')) .
                "</strong> a été refusée par les juges tempérants pour la raison suivante : <br><i>" .
                __s($element->get('ch_inf_commentaire_juge')) . "</i>";
            $style = "background: linear-gradient(120deg, #ffe300 0%,#ff5c00 72%);";
            $link = DEF_URI_PATH . "back/ville_modifier.php?ville-ID=" . __s($element->get('ch_inf_villeid')) .
                "#mes-infrastructures";
            break;

        case 'nv_pays_bienvenue':
            $element = new \GenCity\Monde\Pays($notification->get('element'));
            $header = "NOUVEAU PAYS";
            $text = "Un nouveau pays, <strong>" . __s($element->get('ch_pay_nom')) . "</strong>, a rejoint le" .
                " concert des nations gécéennes. Souhaitez-lui la bienvenue au sein du Monde GC.";
            $link = DEF_URI_PATH . "page-pays.php?ch_pay_id=" . __s($element->get('ch_pay_id')) . "#commentaires";
            $style = "background-color: #ff4e00;";
            break;

        default:
            $continue = true;

    }

    if($continue) continue;

    ?>

    <li class="<?= $notification->get('unread') ? 'notification-unread' : '' ?>">
        <a href="<?= $link ?>">
            <div class="row-fluid">
                <div class="pull-left">
                    <div class="notification-styler" style="<?= $style ?>"></div>
                </div>
                <div style="margin-left: 5px;">
                    <div class="pull-right">
                        <div class="notification-unread-pastille"></div>
                    </div>
                    <h4><?= $header ?></h4>
                    <p>
                        <small class="inline" style="margin: 0; padding: 0; color: #0a0a0a;">
                            <?= dateFormat($notification->get('created')) ?></small>
                        <?= $text ?>
                    </p>
                </div>
            </div>
        </a>
    </li>

<?php endforeach; ?>

