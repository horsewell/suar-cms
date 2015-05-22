<?php

include_once('functions/plugins.php');

/**
 * display_page function.
 * 
 * @access public
 * @return string
 */
function display_page_content() {

	$plugins_info  = plugins_get_info(PATH_MODULES);
	$plugin_status = plugins_get_status(PATH_MODULES);

	if ( array_key_exists('plugin', $_GET) && array_key_exists('enable', $_GET) ) {
		$plugin_status[$_GET['plugin']] = $_GET['enable'] == 1 ? TRUE : FALSE;
		plugins_set_status(PATH_MODULES, $plugin_status);
	}

	$form = '<table>';
	$update_status = FALSE;
	
	foreach($plugins_info as $plugin) {
		$form .= '<tr><td>';
		if ( !array_key_exists($plugin['_directory'], $plugin_status) ) {
			$plugin_status[$plugin['_directory']] = FALSE;
			$update_status = TRUE;
		}
		$form .= $plugin_status[$plugin['_directory']] ?
						 '<a href="'.$_SERVER['PHP_SELF'].'?page=plugins&amp;plugin='.$plugin['_directory'].'&amp;enable=0">Disable</a>' : 
						 '<a href="'.$_SERVER['PHP_SELF'].'?page=plugins&amp;plugin='.$plugin['_directory'].'&amp;enable=1">Enable</a>';
		$form .= '</td><td ';
		$form .= $plugin_status[$plugin['_directory']] ? 'style="color: black;"' : 'style="color: gray;"';
		$form .= '>'.$plugin['name'].'</td>';
		$form .= '<td>'.$plugin['description'].'</td>';
		$form .= '</tr>';
  }

  $form .= '</table>';

	if ( $update_status ) { global $path; plugins_set_status($path, $plugin_status); }
  
	return form_form('plugins-form', $_SERVER['PHP_SELF'], $form);
}