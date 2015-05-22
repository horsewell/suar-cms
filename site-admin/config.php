<?php

/**
 *  cofiguration options
 **/

// Constants
define('FILE_TOKEN', '_tokens.json');

$prepend = file_exists('_content/') ? '' : '../'; // are we in the admin?
define('PATH_CONTENT', $prepend.'_content/');
define('PATH_BACKUP',  PATH_CONTENT.'_backup/');
define('PATH_CACHE',   PATH_CONTENT.'_cache/');
define('PATH_MODULES', $prepend.'_plugins/modules/');
define('PATH_TEMPLATES', $prepend.'_plugins/templates/');

define('PATH_FILES', $prepend.'files/');

unset($prepend);

// Setup for all pages

$page_array			= array();
$tokens					= array();
$plugin_status	= array();
