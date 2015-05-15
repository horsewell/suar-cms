<?php 

include('class.PasswdAuth.inc');
$auth = new PasswdAuth(realpath(getcwd()));
$auth->check(); // $auth->check('admin'); // <-- only for the admin user

$text   = $_POST["textfield"];
$file   = $_POST["select"];
//$file   = $_POST["editing"];
$action = $_POST["Submit"];

include('functions.php');

$FL=file_list($CPATH);

if($_POST["select"] && $action=="Load") {
	$text=txt_load($_POST["select"]);
} else if ($file) {
	if ($action=="Restore") {
		$text = txt_restore($file, $text);
	} else if($text && $action=="Update") {
		txt_update($file, $text);
	} else if($text && $action=="Backup") {
		txt_backup($file, $text);
	}
}

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Website Content Administration Area</title>
	
	<link rel="stylesheet" type="text/css" href="style.css?t=E0LB">
	<link rel="stylesheet" type="text/css" href="../libraries/ckeditor/skins/moono/editor.css?t=E0LB">
	<link rel="stylesheet" type="text/css" href="../libraries/ckeditor/plugins/uicolor/yui/assets/yui.css?t=E0LB">
	
	<script type="text/javascript" src="../libraries/ckeditor/ckeditor.js"></script><style>.cke{visibility:hidden;}</style>
	
	<script src="js/domready.js" type="application/javascript"></script>
	<script src="js/script.js" type="application/javascript"></script>
	<!-- created a custom file outside the ckeditor folder so when upgrading ckeditor will not lose config (but may have to update it) -->
	<script src="js/ckeditor_config.js?t=E0LB" type="text/javascript"></script>
	
	<script type="text/javascript" src="../libraries/ckeditor/lang/en.js?t=E0LB"></script>
	<script type="text/javascript" src="../libraries/ckeditor/styles.js?t=E0LB"></script>
	<script type="text/javascript" src="../libraries/ckeditor/plugins/uicolor/yui/yui.js?t=E0LB"></script>
</head>

<body class="page-main-admin">
	<header>
		<div id="header">
		<h1>Website Administration</h1>
			<nav>
				<ul id="nav-main">
					<li>Select a file to edit:
<form id="form-file" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
	<select name="select" class="select-file">
		<option value="">- Select a File Here -</option>
		<?php foreach ($FL as $key) { if ($TOKEN_FILE !== $key) { ?>
		<option value="<?php echo $key ?>" <?php if ($_POST["select"]==$key) {echo "selected";} ?>>
			<?php if (!is_writable($CPATH.$key)) {echo "*";} ?><?php echo $key ?></option>
		<?php } } ?>
	</select> <input type="submit" name="Submit" value="Load" /> |
</form>						
					</li>
					<li><a href="user_admin.php">Manage Users</a></li>
				</ul>
			</nav>
		</div>
	</header>

	<section>
	<div id="main-form">
    <form id="form-content" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<h2>Currently editing: <?php echo !empty($file) ? $file : 'None - Please select a file to edit.';  ?></h2>
		<textarea name="textfield" cols="60" rows="20" class="ckeditor"><?php echo $text ?></textarea>
		<p class="buttons"><a href="tokens.php">Tokens</a> | Backup: <?php if (!is_writable($BPATH) || empty($file)): echo "unavailable"; else: ?><input name="Submit" type="submit" id="backup" value="Backup" /> <input name="Submit" type="submit" id="restore" value="Restore" /><?php endif; ?> | Live: <input name="Submit" type="submit" id="update" value="Update" <?php if (empty($file)) { echo 'disabled="disabled"'; } ?> /></p>
		<input name="select" type="hidden" value="<?php echo $file; ?>" />
    </form>
	</div>
    </section>
</body>
</html>
