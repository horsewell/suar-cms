<?php

include('../_libraries/class.PasswdAuth.inc');

$pass = new PasswdAuth(realpath(getcwd()));
$pass->check();

include_once('config.php');
include_once('functions-forms.php');
include_once('functions.php');

//$self = $_SERVER['PHP_SELF'];
$user = array_key_exists('user', $_POST) ? $_POST['user'] : $_GET['user'];
$pwd  = $_POST['password'];
$action = array_key_exists('action', $_POST) ? $_POST['action'] : $_GET['action'];
$display_users = TRUE;

function editForm($user) {
  $form = 'Please enter a new password for '. $user .':<br>';
  $form .= form_input('password', 'Text') .'<br>';
  
  $form .= form_input('user', 'Hidden', $user);
  $form .= form_input('action', 'Hidden', 'doChange');
  $form .= form_input('submit', 'Submit', 'Save');
  $form .= ' '. form_input('cancel', 'Submit', 'Cancel');
  
	return form_form('form-user-password-change', $_SERVER['PHP_SELF'], $form);
}  

function addForm() {
  $form = 'Please enter a new user and password:<br>';
  $form .= 'User: '. form_input('user', 'Text') .'<br>';
  $form .= 'Password: '. form_input('password', 'Text') .'<br>';
  
  $form .= form_input('action', 'Hidden', 'doAdd');
  $form .= form_input('submit', 'Submit', 'Add');
  $form .= ' '. form_input('cancel', 'Submit', 'Cancel');
  
	return form_form('form-user-password-change', $_SERVER['PHP_SELF'], $form);
}    

function users($user) {
    global $pass, $self;
    $users   = $pass->getUsers();
    if (!empty($user) && !in_array($user, $users)) { $users[] = $user; }
    $string  = "<p><a href=\"$self?action=add\">Add a new user</a><p>";
    $string .= '<table class="list-users"><thead><tr><td>Username</td><td></td><td></td></tr></thead>';
    $i = 1;
    foreach($users as $user) {
    	$string .= '<tr class="row ';
    	if ( $i++ % 2 === 0 ) { $string .= "row-even"; } else { $string .= "row-odd"; }
    	$string .= '">';
        $string .= "<td>$user</td>";
        $string .= "<td>[<a href=\"$self?action=change&user=$user\" class=\"user-password-change\">Change password</a>]</td>";
        $string .= "<td>[<a href=\"$self?action=delete&user=$user\" class=\"user-delete\">Delete</a>]</td>";
        $string .= '</tr>';
    }
    $string .= '</table>';
    return $string;
}

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

if ($display_users) { echo users($user); }

?></div></section>
	<nav><p><a href="./">≪ back to the content administration</a></p></nav>
	</body>
</html>