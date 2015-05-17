<?php

/**
 *  cofiguration options
 **/

// Constants

define('FILE_TOKEN', '_tokens.json');
define('PATH_CONTENT', '_content/');
define('PATH_BACKUP',  PATH_CONTENT.'_backup/');
define('PATH_CACHE',   PATH_CONTENT.'_cache/');
define('PATH_MODULES', '_plugins/modules/');
define('PATH_TEMPLATES', '_plugins/templates/');

define('PATH_FILES', 'files/');

// Setup for all pages

$tokens = array();
$plugin_status = array();

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
	'action' => $_SERVER['PHP_SELF'],
	'id'     => 'page-from',
);