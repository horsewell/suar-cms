<?php

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
	if ( $_POST['submit'] === 'Load' ) { return; }
	
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
			break;
	}
	
	$page_content['page-body'] = html_entity_decode($page_content['page-body'], ENT_QUOTES);
	foreach($page_content as $key => $value) {
		$_POST[$key] = $value;
	}
	
	return $page_content; //'<div style="background-color: gray;"><pre>'. print_r($GLOBALS['BPATH'].$file, TRUE) .'</pre></div>';
}

/**
 * admin_page_form_pagelist function.
 * 
 * @access public
 * @return array
 */
function admin_page_form_pagelist() {
	$FL=file_list(PATH_CONTENT);
	$options = array();
	foreach( $FL as $value ) {
		$options[substr($value, 0, -5)] = $value;
	}
	return $options;
}

/**
 * display_page function.
 * 
 * @access public
 * @return string
 */
function display_page_content() {

	// handle redirect too
	
	$nofile = array();
	if ( empty($_POST['page-file']) ) {
		$nofile['disabled'] = 'disabled';
		$nofile['title'] = 'No file has been selected';
	}

	if($_POST['page-file'] && $_POST['submit'] === 'Load') {
		$json = txt_load(PATH_CONTENT.clean_path($_POST['page-file']));
		$page_content = json_decode($json, TRUE);
		$page_content['page-body'] = html_entity_decode($page_content['page-body'], ENT_QUOTES);
		//echo '<div>loading file: '.$page_content['page-body'].'</div>';
	}
	
	$page_metadata = array(
	
		'page-file' => array(
			'title' => 'Select a file to edit:',
			'type' => 'select',
			'default_value' => '- Select a File -',
			'value' => clean_path($_POST['page-file']),
			'select-options' => 'admin_page_form_pagelist',
			array('class' => 'page-file')
		),
		'button-load' => array(
			'type' => 'submit',
			'value' => 'Load',
			'attributes' => array(
				'name' => 'submit'
			),
		),
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
			'html' => !empty($_POST['page-file']) ? '' : '<h2> Please select a file to edit.</h2>',
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
	
	
	$page_content = call_user_func($page_metadata['post_call'], $page_metadata);
	//$output = '<pre>'.print_r($page_metadata, TRUE).'</pre>';
	if ( !empty($page_content) ) {
		foreach($page_content as $key => $value) {
			$page_metadata[$key]['value'] = $value;
		}
	}
	
	if ( !file_exists(PATH_BACKUP.$page_metadata['page-file']['value'])) {
		$page_metadata['button-restore']['attributes'] = array_merge($page_metadata['button-restore']['attributes'], array(
			'disabled' => 'disabled',
			'title' => 'No backup to restore',
		));
	}

	return $output.form_constructor($page_metadata);
}
