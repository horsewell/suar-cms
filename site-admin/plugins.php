<?php

include('../_libraries/class.PasswdAuth.inc');
$pass = new PasswdAuth(realpath(getcwd()));
$pass->check();

include_once('config.php');
include_once('functions/forms.php');
include_once('functions/plugins.php');
include_once('functions/core.php');

$plugins_info  = plugins_get_info(PATH_MODULES);
$plugin_status = plugins_get_status(PATH_MODULES);

if ( array_key_exists('plugin', $_GET) && array_key_exists('enable', $_GET) ) {
	$plugin_status[$_GET['plugin']] = $_GET['enable'] == 1 ? TRUE : FALSE;
	plugins_set_status(PATH_MODULES, $plugin_status);
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
		<section>
			<div class="main">
<?php echo plugins_display_page($plugins_info, $plugin_status); ?>
			</div>
		</section>
	<nav><p><a href="./">≪ back to the content administration</a></p></nav>
	</body>
</html>	