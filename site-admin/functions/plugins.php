<?php

/**
 * plugins_display_page function.
 * 
 * @access public
 * @param array $plugins_info
 * @param array $plugin_status
 * @return string
 */
function plugins_display_page($plugins_info, $plugin_status) {
	$form = '<table>';
	$update_status = FALSE;
	
	foreach($plugins_info as $plugin) {
		$form .= '<tr><td>';
		if ( !array_key_exists($plugin['_directory'], $plugin_status) ) {
			$plugin_status[$plugin['_directory']] = FALSE;
			$update_status = TRUE;
		}
		$form .= $plugin_status[$plugin['_directory']] ?
						 '<a href="'.$_SERVER['PHP_SELF'].'?plugin='.$plugin['_directory'].'&amp;enable=0">Disable</a>' : 
						 '<a href="'.$_SERVER['PHP_SELF'].'?plugin='.$plugin['_directory'].'&amp;enable=1">Enable</a>';
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

/**
 * plugins_list function.
 * 
 * @access public
 * @param string $dir
 * @return array
 */
function plugins_list($dir) {
	$directory_contents = directory_list($dir);
	if ( !$directory_contents ) { return FALSE; }
	foreach($directory_contents as $item) {
		if (is_dir($dir.$item)) {
			$file_array[] = $item;
		}
	}
	return $file_array;
}

/**
 * plugins_get_info function.
 * 
 * @access public
 * @param string $dir
 * @return array
 */
function plugins_get_info($dir) {
	$plugins = 	plugins_list($dir);
	$plugins_info = array();
	foreach($plugins as $plugin) {
		if ( file_exists($dir.$plugin.'/'.$plugin.'.json') ) {
			//$plugins_info[] = $dir.$plugin.'/'.$plugin.'.json';
			$plugins_info[] = array_merge(json_decode(txt_load($dir.$plugin.'/'.$plugin.'.json'), TRUE), 
																		array('_directory' => $plugin));
		}
	}
	return $plugins_info;
}

