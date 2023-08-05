<?php
/******* Affichage des messages d'erreur *******/

echo '<div class="well">';

echo '<noscript>';
showErrorMessage('ban_error', 'Il semblerait que JavaScript n\'est pas activé. Certains éléments sont susceptibles de ne pas fonctionner correctement.');
echo '</noscript>';

if(isset($_SESSION['errmsgs']) && count($_SESSION['errmsgs']) > 0) {
	foreach($_SESSION['errmsgs'] as $key=>$error) {
		showErrorMessage($error['err_type'], $error['msg']);
	}
	unset($_SESSION['errmsgs']);
}

echo '</div>';