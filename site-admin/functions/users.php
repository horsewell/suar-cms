<?php

/**
 * editForm function.
 * 
 * @access public
 * @param string $user
 * @return string
 */
function editForm($user) {
  $form = 'Please enter a new password for '. $user .':<br>';
  $form .= form_input('password', 'Text') .'<br>';
  
  $form .= form_input('user', 'Hidden', $user);
  $form .= form_input('action', 'Hidden', 'doChange');
  $form .= form_input('submit', 'Submit', 'Save');
  $form .= ' '. form_input('cancel', 'Submit', 'Cancel');
  
	return form_form('form-user-password-change', $_SERVER['PHP_SELF'] .'?page=users', $form);
}  

/**
 * addForm function.
 * 
 * @access public
 * @return string
 */
function addForm() {
  $form = 'Please enter a new user and password:<br>';
  $form .= 'User: '. form_input('user', 'Text') .'<br>';
  $form .= 'Password: '. form_input('password', 'Text') .'<br>';
  
  $form .= form_input('action', 'Hidden', 'doAdd');
  $form .= form_input('submit', 'Submit', 'Add');
  $form .= ' '. form_input('cancel', 'Submit', 'Cancel');
  
	return form_form('form-user-password-change', $_SERVER['PHP_SELF'] .'?page=users', $form);
}    

/**
 * users function.
 * 
 * @access public
 * @param string $user
 * @return string
 */
function display_users_form($user) {
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

/**
 * users_form_submit function.
 * 
 * @access public
 * @param string &$user
 * @param string $pwd
 * @param boolean &$display_users
 * @return void
 */
function users_form_submit($action, &$user, $pwd, &$display_users) {
	global $pass;
	if(empty($_POST['cancel']) && isset($action)) {
		switch($action) {
			case 'change':
				$value = editForm($user);
				$display_users = FALSE;
				break;
			case 'add':
				$value = addForm();
				$display_users = FALSE;
				break;
			case 'doChange':
				if($pass->changePassword($user, $pwd)) {
					$value = message("The user $user's password has been changed.", "notify");
				} else {
					$value = message("The user $user's password was not changed.", "notify");
				}
				break;
			case 'doAdd':
				if($pass->addUser($user, $pwd)) {
					$value = message("The user $user was added.", "notify");
				} else {
					$value = message("The user $user was not added.", "notify");
				}
				break;
			case 'delete':
				if($pass->deleteUser($user)) {
					$value = message("The user $user was deleted.", "notify");
				} else {
					$value = message("The user $user was not deleted.", "notify");
				}
				unset($user);
		}
	}
	return $value;
}

