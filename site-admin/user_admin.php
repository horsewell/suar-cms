<?php

include('../_libraries/class.PasswdAuth.inc');

$pass = new PasswdAuth(realpath(getcwd()));
$pass->check();

$self = $_SERVER['PHP_SELF'];
$user = $_GET['user'];
$pwd  = $_GET['password'];

function editForm($user) {
    global $self;
    $form = <<< END
<form action="$self" method="get">
    Please enter a new password for $user:<br>
    <input type="Text" size="20" name="password"><br>
    <input type="Hidden" name="user" value="$user">
    <input type="Hidden" name="action" value="doChange">
    <input type="Submit" value="Change"> <input type="Submit" name="cancel" value="Cancel">
</form>
END;
	return $form;
}  

function addForm() {
    global $self;
		$form = <<< END
<form action="$self" method="get">
    Please enter a new user and password:<br>
    User: <input type="Text" size="20" name="user"><br>
    Password: <input type="Text" size="20" name="password"><br>
    <input type="Hidden" name="action" value="doAdd">
    <input type="Submit" value="Add"> <input type="Submit" name="cancel" value="Cancel">
</form>
END;
	return $form;
}    

function users() {
    global $pass, $self;
    $users   = $pass->getUsers();
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

function message($string, $type) {
	return '<div class="message '.$type.'">'.$string.'</div>';
}

function back_to_admin () {
	global $self;
	return '<p><a href="'.$self.'">≪ back to the main user administration</a></p>';
}

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Password Administration</title>
	<link rel="stylesheet" type="text/css" href="style.css?t=E0LB">
	
	<script src="js/domready.js" type="application/javascript"></script>
	<script src="js/script.js" type="application/javascript"></script>
</head>

<body class="user-admin">
	<header>
		<h1>User Administration</h1>
	</header>
<section><div class="main"><?php

if(empty($_GET['cancel']) && isset($_GET['action'])) {
    switch($_GET['action']) {
    
        case 'change':
            echo editForm($user);
            break;
            
        case 'add':
            echo addForm();
            break;
            
        case 'doChange':
            if($pass->changePassword($user, $pwd)) {
                echo message("The user $user's password has been changed.", "notify");
            } else {
                echo message("The user $user's password was not changed.", "notify");
            }
            echo users();
            break;
            
        case 'doAdd':
            if($pass->addUser($user, $pwd)) {
                echo message("The user $user was added.", "notify");
            } else {
                echo message("The user $user was not added.", "notify");
            }
            echo back_to_admin();
            break;
            
        case 'delete':
            if($pass->deleteUser($user)) {
                echo message("The user $user was deleted.", "notify");
            } else {
                echo message("The user $user was not deleted.", "notify");
            }
            echo users();
            break;
            
        default:
            echo users();
            break;
            
    }
} else {
    echo users();
}

// phpinfo();

?></div></section>
	<nav><p><a href="./">≪ back to the content administration</a></p></nav>
	</body>
</html>