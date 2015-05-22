<?php

/**
 * admin_menu function.
 * 
 * @access public
 * @return string
 */
function admin_menu() {
	
	$menu  = '<nav><ul id="nav-main"><li><a href="plugins.php">Plugins</a> | ';
	$menu .= '</li><li><a href="tokens.php">Tokens</a> | ';
	$menu .= '</li><li><a href="user.php">Users</a></li></ul></nav>';

	return $menu;
}

function ckeditor_includes($ckeditor_path, $files_version) {
	return metadata_link('stylesheet', 'text/css', $ckeditor_path.'skins/moono/editor.css?t='.$files_version).
	metadata_link('stylesheet', 'text/css', $ckeditor_path.'plugins/uicolor/yui/assets/yui.css?t='.$files_version).
	metadata_script($ckeditor_path.'ckeditor.js').'<style>.cke{visibility:hidden;}</style>'.
	metadata_script($ckeditor_path.'lang/en.js?t='.$files_version).
	metadata_script($ckeditor_path.'styles.js?t='.$files_version).
	metadata_script($ckeditor_path.'plugins/uicolor/yui/yui.js?t='.$files_version);
}