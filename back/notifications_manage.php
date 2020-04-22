<?php

require_once('../Connections/maconnexion.php');
//deconnexion
include('../php/logout.php');

if (!isset($_SESSION['userObject'])) {
    // Redirection vers connexion
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ../connexion.php');
    exit();
}

if(isset($_GET['fetch'])) {

    renderElement('Notification/generate_user_notifications');

}

elseif(isset($_POST['mark_unread'])) {

    $userNotifications = new \GenCity\Monde\Notification\UserNotifications($_SESSION['userObject']);
    $userNotifications->markAsRead();

    echo json_encode(array('status' => 'success'));

}