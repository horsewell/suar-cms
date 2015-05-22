<?php

/**
 * admin_menu function.
 * 
 * @access public
 * @return string
 */
function admin_menu() {
	
	$file = clean_path($_POST["page-file"]);
	
	$menu_start = '<nav><ul id="nav-main"><li>';
	$menu_finish = '</li><li><a href="plugins.php">Plugins</a> | </li><li><a href="tokens.php">Tokens</a> | </li><li><a href="user.php">Users</a></li></ul></nav>';

	$FL=file_list(PATH_CONTENT);
	$file_form_options = array();
	foreach( $FL as $value ) {
		$file_form_options[substr($value, 0, -5)] = $value;
	}
	
	$file_form  = form_label('page-file', 'Select a file to edit:').' ';
	$file_form .= form_select('page-file', '- Select a File -', $file, $file_form_options, array('class' => 'select-file'));
	$file_form .= ' '.form_input('button-load', 'submit', 'Load', array('name' => 'submit')) .' | ';

	return $menu_start . form_form('form-file', $_SERVER['PHP_SELF'], $file_form) . $menu_finish;
	
}