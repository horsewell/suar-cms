<?php 

include('../_libraries/class.PasswdAuth.inc');

$auth = new PasswdAuth(realpath(getcwd()));
$auth->check(); // $auth->check('admin'); // <-- only for the admin user

$text   = $_POST["textfield"];
$file   = $_POST["select"];
//$file   = $_POST["editing"];
$action = $_POST["submit"];

include_once('config.php');
include_once('functions-forms.php');
include_once('functions.php');

$CPATH = '../'.PATH_CONTENT;
$BPATH = '../'.PATH_BACKUP;

$nofile = array();
if ( empty($file) ) {
	$nofile['disabled'] = 'disabled';
	$nofile['title'] = 'No file has been selected';
}

$page_metadata = array(
	'page-title' => array(
		'title' => 'Page Title',
		'type' => 'text',
	),
	'page-keywords' => array(
		'title' => 'Keywords',
		'type' => 'text',
	),
	'page-description' => array(
		'title' => 'Description',
		'type' => 'text',
	),
	'page-template' => array(
		'title' => 'Template',
		'type' => 'select',
		'select-options' => 'page_template_list'
	),
	'page-html-1' => array(
		'type' => 'html',
		'html' => '<h2>Currently editing: ',
	),
	'page-html-2' => array(
		'type' => 'html',
		'html' => !empty($file) ? $file : 'None - Please select a file to edit.',
	),
	'page-html-3' => array(
		'type' => 'html',
		'html' => '</h2>',
	),
	'page-body' => array(
		'title' => 'Body',
		'type' => 'textarea',
		'attributes' => array('class' => 'ckeditor', 'cols' => 60, 'rows' => 20),
	),
	'page-html-4' => array(
		'type' => 'html',
		'html' => '<p class="buttons">Backup: ',
	),
	'button-backup' => array(
		'type' => 'submit',
		'value' => 'Backup',
		'attributes' => array_merge(array(
			'name' => 'submit'
		), $nofile),
	),
	'button-restore' => array(
		'type' => 'submit',
		'value' => 'Restore',
		'attributes' => array_merge(array(
			'name' => 'submit'
		), $nofile),
	),
	'page-html-5' => array(
		'type' => 'html',
		'html' => ' | Live: ',
	),
	'button-update' => array(
		'type' => 'submit',
		'value' => 'Update',
		'attributes' => array_merge(array(
			'name' => 'submit'
		), $nofile),
	),
	'file-name' => array(
		'type' => 'hidden',
		'value' => $file,
		'attributes' => array(
			'name' => 'select'
		),
	),
	'action' => $_SERVER['PHP_SELF'],
	'id'     => 'page-from',
	'post_call' => 'admin_form_page_post'
);

if($_POST["select"] && $action=="Load") {
	$_POST['page-body'] = txt_load($CPATH.$_POST["select"]);
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

function admin_form_page_post($form, $post) {
	return '<div style="background-color: gray;"><pre>$tokens = '. print_r($post, TRUE) .'</pre></div>';
}

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Website Content Administration Area</title>
<?php
$ckeditor_path = '../_libraries/ckeditor/';
echo metadata_link('stylesheet', 'text/css', 'style.css?t=E0LB').
		 metadata_link('stylesheet', 'text/css', $ckeditor_path.'skins/moono/editor.css?t=E0LB').
		 metadata_link('stylesheet', 'text/css', $ckeditor_path.'plugins/uicolor/yui/assets/yui.css?t=E0LB').
     metadata_script($ckeditor_path.'ckeditor.js').'<style>.cke{visibility:hidden;}</style>'.
     metadata_script('js/domready.js').metadata_script('js/script.js').
     metadata_script('js/ckeditor_config.js?t=E0LB').
     metadata_script($ckeditor_path.'lang/en.js?t=E0LB').
     metadata_script($ckeditor_path.'styles.js?t=E0LB').
     metadata_script($ckeditor_path.'plugins/uicolor/yui/yui.js?t=E0LB');
?>
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

$file_form  = form_select('select', '- Select a File -', $file, $file_form_options, array('class' => 'select-file'));
$file_form .= ' '.form_input('button-load', 'submit', 'Load', array('name' => 'submit')) .' | ';

echo form_form('form-file', $_SERVER['PHP_SELF'], $file_form);

?>				</li>
					<li><a href="plugins.php">Plugins</a> | </li>
					<li><a href="tokens.php">Tokens</a> | </li>
					<li><a href="user.php">Users</a></li>
				</ul>
			</nav>
		</div>
	</header>

	<section>
	<div id="main-form"><?php 

if ( !empty($_POST) ) {
	print call_user_func($page_metadata['post_call'], $page_metadata, $_POST);
}

//$content_form  = '<h2>Currently editing: ';
//$content_form .= !empty($file) ? $file : 'None - Please select a file to edit.';
//$content_form .= '</h2>'. form_textarea('textfield', $text, array('class' => 'ckeditor', 'cols' => 60, 'rows' => 20));
//$content_form .= '<p class="buttons">Backup: ';
//$content_form .= (!is_writable($BPATH) || empty($file)) ? 'unavailable' : 
//									form_input('button-backup', 'submit', 'Backup', array('name' => 'submit')) .' '. 
//									form_input('button-restore', 'submit', 'Restore', array('name' => 'submit'));
//$content_form .= ' | Live: '. form_input('button-update', 'submit', 'Update', array_merge(array('name' => 'submit'), $nofile));
//$content_form .= form_input('file-name', 'hidden', $file, array('name' => 'select'));

//echo form_form('form-content', $_SERVER['PHP_SELF'], $content_form);

echo form_constructor($page_metadata, $_POST);
?>	</div>
    </section>
</body>
</html>
