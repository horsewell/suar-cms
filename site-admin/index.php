<?php 

include('../_libraries/class.PasswdAuth.inc');

$auth = new PasswdAuth(realpath(getcwd()));
$auth->check(); // $auth->check('admin'); // <-- only for the admin user

$text   = $_POST["textfield"];
$file   = $_POST["select"];
//$file   = $_POST["editing"];
$action = $_POST["submit"];

include('config.php');
include('functions.php');

$CPATH = '../'.$CPATH;
$BPATH = '../'.$BPATH;

if($_POST["select"] && $action=="Load") {
	$text = txt_load($CPATH.$_POST["select"]);
	// TODO: load meta-data as well
} else if ($file) {
	if ($action=="Restore") {
		$text = txt_restore($BPATH.$file, $text);
	} else if($text && $action=="Update") {
		txt_update($CPATH.$file, $text);
		// TODO: save meta-data as well
	} else if($text && $action=="Backup") {
		txt_backup($BPATH.$file, $text);
	}
}

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Website Content Administration Area</title>
	
	<link rel="stylesheet" type="text/css" href="style.css?t=E0LB">
	<link rel="stylesheet" type="text/css" href="../_libraries/ckeditor/skins/moono/editor.css?t=E0LB">
	<link rel="stylesheet" type="text/css" href="../_libraries/ckeditor/plugins/uicolor/yui/assets/yui.css?t=E0LB">
	
	<script type="text/javascript" src="../_libraries/ckeditor/ckeditor.js"></script><style>.cke{visibility:hidden;}</style>
	
	<script src="js/domready.js" type="application/javascript"></script>
	<script src="js/script.js" type="application/javascript"></script>
	<!-- created a custom file outside the ckeditor folder so when upgrading ckeditor will not lose config (but may have to update it) -->
	<script src="js/ckeditor_config.js?t=E0LB" type="text/javascript"></script>
	
	<script type="text/javascript" src="../_libraries/ckeditor/lang/en.js?t=E0LB"></script>
	<script type="text/javascript" src="../_libraries/ckeditor/styles.js?t=E0LB"></script>
	<script type="text/javascript" src="../_libraries/ckeditor/plugins/uicolor/yui/yui.js?t=E0LB"></script>
</head>

<body class="page-main-admin">
	<header>
		<div id="header">
		<h1>Website Administration</h1>
			<nav>
				<ul id="nav-main">
					<li>Select a file to edit: <?php 

$FL=file_list($CPATH);
$file_form_options = array();
foreach( $FL as $value ) {
	$file_form_options[substr($value, 0, -4)] = $value;
}

$file_form  = form_select('select', '- Select a File Here -', $file, $file_form_options, array('class' => 'select-file'));
$file_form .= ' '.form_input('button-load', 'submit', 'Load', array('name' => 'submit')) .' | ';

echo form_form('form-file', $_SERVER['PHP_SELF'], $file_form);

?>				</li>
					<li><a href="tokens.php">Tokens</a> | </li>
					<li><a href="user_admin.php">Manage Users</a></li>
				</ul>
			</nav>
		</div>
	</header>

	<section>
	<div id="main-form">
<?php 

$nofile = array();
if ( empty($file) ) {
	$nofile['disabled'] = 'disabled';
	$nofile['title'] = 'No file has been selected';
}

$content_form  = '<h2>Currently editing: ';
$content_form .= !empty($file) ? $file : 'None - Please select a file to edit.';
$content_form .= '</h2>'. form_textarea(textfield, $text, array('class' => 'ckeditor', 'cols' => 60, 'rows' => 20));
$content_form .= '<p class="buttons">Backup: ';
$content_form .= (!is_writable($BPATH) || empty($file)) ? 'unavailable' : 
									form_input('button-backup', 'submit', 'Backup', array('name' => 'submit')) .' '. 
									form_input('button-restore', 'submit', 'Restore', array('name' => 'submit'));
$content_form .= ' | Live: '. form_input('button-update', 'submit', 'Update', array_merge(array('name' => 'submit'), $nofile));
$content_form .= form_input('file-name', 'hidden', $file, array('name' => 'select'));

/* TODO: This is where we add the meta-data */	

echo form_form('form-content', $_SERVER['PHP_SELF'], $content_form);
?>	</div>
    </section>
</body>
</html>
