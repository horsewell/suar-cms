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

$page = !empty($_GET['page']) ? $_GET['page'] : 'edit-content';

$page_array['page-title'] = 'Website Content Administration Area';
$ckeditor_path = '../_libraries/ckeditor/';
$files_version = 'E0LB';
$page_array['meta-data-before'] =
		 ckeditor_includes($ckeditor_path, $files_version).
     metadata_link('stylesheet', 'text/css', 'style.css?t='.$files_version).
     metadata_script('js/ckeditor_config.js?t='.$files_version).
     metadata_script('js/domready.js').metadata_script('js/script.js');

$page_array['page-body-attributes'] = 'class="page-main-admin"';

include_once('page-'. clean_path($page) .'.php');

$page_array['page-body']  = '<header><div id="header"><h1>Website Administration</h1>'. admin_menu() .'</div></header>';
$page_array['page-body'] .= '<section><div id="main-form">'. display_page_content() .'</div></section>';

echo page_template(PATH_TEMPLATES.'page-basic.html', $page_array);
