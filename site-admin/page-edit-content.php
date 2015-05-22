<?php

$file = clean_path($_POST["page-file"]);

if($_POST["page-file"] && $_POST["submit"]=="Load") {
	$json = txt_load(PATH_CONTENT.$file);
	$page_content = json_decode($json, TRUE);
	$page_content['page-body'] = html_entity_decode($page_content['page-body'], ENT_QUOTES);
}

$nofile = array();
if ( empty($file) ) {
	$nofile['disabled'] = 'disabled';
	$nofile['title'] = 'No file has been selected';
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
	'page-description' => array(
		'title' => 'Path',
		'type' => 'text',
		'value' => $page_content['page-path'],
	),
	'page-html-2' => array(
		'type' => 'html',
		'html' => !empty($file) ? '' : '<h2> Please select a file to edit.</h2>',
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
	'page-file' => array(
		'type' => 'hidden',
		'value' => $file,
		'save'  => FALSE,
		'attributes' => array(
			'name' => 'page-file'
		),
	),
	'html-json-encoding' => array(
		'type' => 'hidden',
		'value' => 'htmlentities',
		'save'  => FALSE,
		'attributes' => array(
			'name' => 'encoding'
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
 * @return boolean
 */
function admin_form_page_post($form) {
	if ( !empty($_POST) && $_POST['form-name'] === $page_metadata['id'] ) { return; }

	$page_content = array();
	
	foreach( $form as $key => $value ) {
		$name = $key;
		//echo '<br>'.$value.'<br>';
		if ( is_array($value) && array_key_exists('attributes', $value) ) {
			if ( array_key_exists('name', $value['attributes']) ) {
				$name  = $value['attributes']['name'];
			}
		}
		if ( !empty($_POST[$name]) && $value['type'] !== 'submit' && $value['save'] !== FALSE ) {
			$page_content[$name] = $_POST[$name];
			if ( $value['type'] === 'textarea' ) {
				$page_content[$name] = htmlentities(stripslashes(utf8_encode($page_content[$name])), ENT_QUOTES);
			}
		}
	}
	
	$json = json_encode($page_content, JSON_PRETTY_PRINT);
	
	switch($_POST['submit']) {
		case 'Update':
			txt_update(PATH_CONTENT.$form['page-file']['value'], $json);
			break;
		case 'Backup':
			txt_update(PATH_BACKUP.$form['page-file']['value'], $json);
			break;
		case 'Restore':
			$json = txt_load(PATH_BACKUP.$form['page-file']['value']);
			$page_content = json_decode($json, TRUE);
			$page_content['page-body'] = html_entity_decode($page_content['page-body'], ENT_QUOTES);
			foreach($page_content as $key => $value) {
				$_POST[$key] = $value;
			}
			break;
	}
	
	return TRUE; //'<div style="background-color: gray;"><pre>'. print_r($GLOBALS['BPATH'].$file, TRUE) .'</pre></div>';
}

/**
 * display_page function.
 * 
 * @access public
 * @return string
 */
function display_page_content() {
	global $page_metadata;
	call_user_func($page_metadata['post_call'], $page_metadata);
	return form_constructor($page_metadata);
}
