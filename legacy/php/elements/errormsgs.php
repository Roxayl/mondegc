<?php
/******* Affichage des messages d'erreur *******/

echo '<div class="well">';

echo '<noscript>';
showErrorMessage('ban_error', 'Il semble que JavaScript n\'est pas activé... Z\'êtes méchants :(<br />Si le site déconne c\'est pas ma faute hein *siffle*');
echo '</noscript>';

if(isset($_SESSION['errmsgs']) && count($_SESSION['errmsgs']) > 0) {
	foreach($_SESSION['errmsgs'] as $key=>$error) {
		showErrorMessage($error['err_type'], $error['msg']);
	}
	unset($_SESSION['errmsgs']);
}

echo '</div>';