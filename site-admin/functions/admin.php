<?php

/**
 * admin_menu function.
 * 
 * @access public
 * @return string
 */
function admin_menu() {
	
	$menu  = '<nav><ul id="nav-main">';
	$menu .='<li><a href="./">Content</a> | </li>';
	$menu .='<li><a href="./?page=plugins">Plugins</a> | </li>';
	$menu .= '<li><a href="./?page=tokens">Tokens</a> | </li>';
	$menu .= '<li><a href="./?page=users">Users</a></li>';
	$menu .= '</ul></nav>' ."\n";

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