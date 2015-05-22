<?php

$page_array['page-title'] = 'Users | Website Content Administration Area';
$page_array['meta-data-before'] = metadata_link('stylesheet', 'text/css', 'style.css?t=E0LB').
     															metadata_script('js/domready.js').metadata_script('js/script.js');
$page_array['page-body-attributes'] = 'class="page-main-admin user-admin"';

include_once('functions/users.php');
$display_users = TRUE;

$pass = new PasswdAuth(realpath(getcwd()));
$pass->check();

/**
 * display_page function.
 * 
 * @access public
 * @return string
 */
function display_page_content() {
	global $pass, $display_users;

	$self = $_SERVER['PHP_SELF'];
	$user = array_key_exists('user', $_POST) ? $_POST['user'] : $_GET['user'];
	
	$string = users_form_submit(
		array_key_exists('action', $_POST) ? $_POST['action'] : $_GET['action'],
		$user,
		$_POST['password'],
		$display_users
	);
	
	if ($display_users) {
		$users   = $pass->getUsers();
		if (!empty($user) && !in_array($user, $users)) { $users[] = $user; }
			$string  = "<p><a href=\"$self?page=users&amp;action=add\">Add a new user</a><p>";
			$string .= '<table class="list-users"><thead><tr><td>Username</td><td></td><td></td></tr></thead>';
			$i = 1;
			foreach($users as $user) {
				$string .= '<tr class="row ';
				if ( $i++ % 2 === 0 ) { $string .= "row-even"; } else { $string .= "row-odd"; }
				$string .= '">';
				$string .= "<td>$user</td>";
				$string .= "<td>[<a href=\"$self?page=users&amp;action=change&user=$user\" class=\"user-password-change\">Change password</a>]</td>";
				$string .= "<td>[<a href=\"$self?page=users&amp;action=delete&user=$user\" class=\"user-delete\">Delete</a>]</td>";
				$string .= '</tr>';
			}
		$string .= '</table>';
	}
	return $string;
}