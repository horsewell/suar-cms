<?php

include('../_libraries/class.PasswdAuth.inc');

$pass = new PasswdAuth(realpath(getcwd()));
$pass->check();

include_once('config.php');
include_once('functions-forms.php');
include_once('functions-users.php');
include_once('functions.php');

//$self = $_SERVER['PHP_SELF'];
$user = array_key_exists('user', $_POST) ? $_POST['user'] : $_GET['user'];
$pwd  = $_POST['password'];
$action = array_key_exists('action', $_POST) ? $_POST['action'] : $_GET['action'];
$display_users = TRUE;

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Password Administration</title>
<?php
echo metadata_link('stylesheet', 'text/css', 'style.css?t=E0LB').
     metadata_script('js/domready.js').metadata_script('js/script.js');
?>
</head>

<body class="user-admin">
	<header>
		<h1>User Administration</h1>
	</header>
<section><div class="main"><?php

/*
if(empty($_POST['cancel']) && isset($action)) {
	switch($action) {
		case 'change':
			echo editForm($user);
			$display_users = FALSE;
			break;
		case 'add':
			echo addForm();
			$display_users = FALSE;
			break;
		case 'doChange':
			if($pass->changePassword($user, $pwd)) {
				echo message("The user $user's password has been changed.", "notify");
			} else {
				echo message("The user $user's password was not changed.", "notify");
			}
			break;
		case 'doAdd':
			if($pass->addUser($user, $pwd)) {
				echo message("The user $user was added.", "notify");
			} else {
				echo message("The user $user was not added.", "notify");
			}
			break;
		case 'delete':
			if($pass->deleteUser($user)) {
				echo message("The user $user was deleted.", "notify");
			} else {
				echo message("The user $user was not deleted.", "notify");
			}
			unset($user);
	}
}
*/

print users_form_submit($action, $user, $pwd, $display_users);

if ($display_users) { echo display_users_form($user); }

?></div></section>
	<nav><p><a href="./">≪ back to the content administration</a></p></nav>
	</body>
</html>