<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="http://www.generation-city.com/forum/images/style.css" rel="stylesheet" type="text/css">
<style>
body {
	position:relative;
	top: 15px;
	left: 30px;
	line-height: 1em;
}
img {
	width: 150px;
	margin: 0px;
	margin-bottom: -20px;
	padding: 0px;
}

input {
	margin-bottom: 3px;
}
</style>

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>

<body>
<form action="http://www.forum-gc.com/login" method="post" name="form_login">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="user_login_form forumline">
          <tr>
          <td class="row1">
          <img src="http://www.generation-city.com/files/logo.png">
          <p>Se connecter sur le Forum</p>
          </td>
          </tr>
              <tr>
                <td class="row1">
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                          <td><span class="genmed">Login:&nbsp;</span> </td>
                          <td><input class="post" type="text" size="10" name="username" value=""/> </td>
                      </tr>
                      <tr>
                          <td><span class="genmed">Mot de passe:&nbsp;</span> </td>
                          <td><input class="post" type="password" size="10" name="password" value="" /> </td>
                          </tr>
                      <tr>    
                          <td><input class="mainoption" type="submit" name="login" value="Connexion" /></td>
                      </tr>
                    </table>
                </td>
              </tr>
          </table>
        </form>
</body>
</html>