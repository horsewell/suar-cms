<?php 

include('../_libraries/class.PasswdAuth.inc');

$auth = new PasswdAuth(realpath(getcwd()));
$auth->check(); // $auth->check('admin'); // <-- only for the admin user

include_once('config.php');
include_once('functions-forms.php');
include_once('functions.php');

$text   = $_POST["textfield"];
$file   = clean_path($_POST["select"]);
//$file   = $_POST["editing"];
$action = $_POST["submit"];


$CPATH = '../'.PATH_CONTENT;
$BPATH = '../'.PATH_BACKUP;

$nofile = array();
if ( empty($file) ) {
	$nofile['disabled'] = 'disabled';
	$nofile['title'] = 'No file has been selected';
}

if($_POST["select"] && $action=="Load") {
	$json = txt_load($CPATH.$file);
	$page_content = json_decode($json, TRUE);
	$page_content['page-body'] = base64_decode($page_content['page-body']);
}

$page_metadata = array(
	'page-title' => array(
		'title' => 'Page Title',
		'type' => 'text',
		'value' => $page_content['page-title'],
		
	),
	'page-keywords' => array(
		'title' => 'Keywords',
		'type' => 'text',
		'value' => $page_content['page-keywords'],
	),
	'page-description' => array(
		'title' => 'Description',
		'type' => 'text',
		'value' => $page_content['page-description'],
	),
	'page-template' => array(
		'title' => 'Template',
		'type' => 'select',
		'select-options' => 'page_template_list',
		'default_value' => 'page-plain.html',
		'value' => $page_content['page-template'],
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
		'value' => $page_content['page-body'],
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
	'page-html-6' => array(
		'type' => 'html',
		'html' => ' ',
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
		'save'  => FALSE,
		'attributes' => array(
			'name' => 'select'
		),
	),
	'action' => $_SERVER['PHP_SELF'],
	'id'     => 'page-from',
	'post_call' => 'admin_form_page_post'
);

/**
 * admin_form_page_post function.
 * 
 * @access public
 * @param array $form
 * @param array $post
 * @return void
 */
function admin_form_page_post($form, $post) {
	if ( $_POST['form-name'] === $page_metadata['id'] ) { return; }

	$page_content = array();
	
	foreach( $form as $key => $value ) {
		$name = $key;
		//echo '<br>'.$value.'<br>';
		if ( is_array($value) && array_key_exists('attributes', $value) ) {
			if ( array_key_exists('name', $value['attributes']) ) {
				$name  = $value['attributes']['name'];
			}
		}
		if ( !empty($post[$name]) && $value['type'] !== 'submit' && $value['save'] !== FALSE ) {
			$page_content[$name] = $post[$name];
			if ( $value['type'] === 'textarea' ) {
					$page_content[$name] = base64_encode($page_content[$name]);
			}
		}
	}
	
	$json = json_encode($page_content, JSON_PRETTY_PRINT);
	
	switch($post['submit']) {
		case 'Update':
			$_POST['doing'] = "update";
			txt_update($GLOBALS['CPATH'].$form['file-name']['value'], $json);
			break;
		case 'Backup':
			$_POST['doing'] = "backup";
			txt_update($GLOBALS['BPATH'].$form['file-name']['value'], $json);
			break;
		case 'Restore':
			$_POST['doing'] = "restore";
			$json = txt_load($GLOBALS['BPATH'].$form['file-name']['value']);
			$page_content = json_decode($json, TRUE);
			$page_content['page-body'] = base64_decode($page_content['page-body']);
			foreach($page_content as $key => $value) {
				$_POST[$key] = $value;
			}
			break;
	}
	
	return ''; //'<div style="background-color: gray;"><pre>'. print_r($GLOBALS['BPATH'].$file, TRUE) .'</pre></div>';
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
	if ( $_POST['form-name'] === $page_metadata['id'] ) {
		echo call_user_func($page_metadata['post_call'], $page_metadata, $_POST);
	}
}

echo form_constructor($page_metadata, $_POST);
?>	</div>
    </section>
</body>
</html>
