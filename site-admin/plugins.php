<?php

include('../_libraries/class.PasswdAuth.inc');
$pass = new PasswdAuth(realpath(getcwd()));
$pass->check();

include_once('config.php');
include_once('functions-forms.php');
include_once('functions.php');

$path = '../'.PATH_MODULES;

$plugins_info  = plugins_get_info($path);
$plugin_status = plugins_get_status($path);

if ( array_key_exists('plugin', $_GET) && array_key_exists('enable', $_GET) ) {
	$plugin_status[$_GET['plugin']] = $_GET['enable'] == 1 ? TRUE : FALSE;
	plugins_set_status($path, $plugin_status);
}

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

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Plugin Administration</title>
<?php
echo metadata_link('stylesheet', 'text/css', 'style.css?t=E0LB').
     metadata_script('js/domready.js').metadata_script('js/script.js');
?>
</head>

<body class="user-admin">
	<header>
		<h1>Plugin Administration</h1>
	</header>
<section><div class="main"><?php

// echo plugins_display_form($_POST);

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

echo plugins_display_page($plugins_info, $plugin_status);
// echo '<pre>'. print_r($_GET, TRUE) .'</pre>';

?></div></section>
	<nav><p><a href="./">≪ back to the content administration</a></p></nav>
	</body>
</html>	