<?php 

include('../_libraries/class.PasswdAuth.inc');

$auth = new PasswdAuth(realpath(getcwd()));
$auth->check(); // $auth->check('admin'); // <-- only for the admin user

include_once('config.php');
include_once('functions/bootstrap.php');
include_once('functions/core.php');
include_once('functions/templates.php');
include_once('functions/forms.php');
include_once('functions/admin.php');

$page_array['page-title'] = 'Website Content Administration Area';
$ckeditor_path = '../_libraries/ckeditor/';
$files_version = 'E0LB';
$page_array['meta-data-before'] = metadata_link('stylesheet', 'text/css', 'style.css?t='.$files_version).
		 metadata_link('stylesheet', 'text/css', $ckeditor_path.'skins/moono/editor.css?t='.$files_version).
		 metadata_link('stylesheet', 'text/css', $ckeditor_path.'plugins/uicolor/yui/assets/yui.css?t='.$files_version).
     metadata_script($ckeditor_path.'ckeditor.js').'<style>.cke{visibility:hidden;}</style>'.
     metadata_script('js/ckeditor_config.js?t='.$files_version).
     metadata_script($ckeditor_path.'lang/en.js?t='.$files_version).
     metadata_script($ckeditor_path.'styles.js?t='.$files_version).
     metadata_script($ckeditor_path.'plugins/uicolor/yui/yui.js?t='.$files_version).
     metadata_script('js/domready.js').metadata_script('js/script.js');

$page_array['page-body-attributes'] = 'class="page-main-admin"';

include_once('page-edit-content.php');

$page_array['page-body']  = '<header><div id="header"><h1>Website Administration</h1>'. admin_menu() .'</div></header>';
$page_array['page-body'] .= '<section><div id="main-form">'. display_page_content() .'</div></section>';

echo page_template(PATH_TEMPLATES.'page-basic.html', $page_array);
