<?php

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

