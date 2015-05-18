<?php
include('../_libraries/class.PasswdAuth.inc');
$pass = new PasswdAuth(realpath(getcwd()));
$pass->check();

include_once('config.php');
include_once('functions-forms.php');
include_once('functions.php');

$CPATH = '../'.PATH_CONTENT;

$tokens = token_load($CPATH.FILE_TOKEN);

// remove
if ( $_POST['action'] === "doAction" ) {
	if ( $_POST['submit'] === "Save" ) {
		foreach($_POST as $key => $value ) {
			if ( strrpos($key, "this-") !== 0 && strrpos($key, "new-token") !== 0 ) {
				$token_name = explode("_", $key)[1];
				if ( array_key_exists($token_name, $tokens) ) {
					if ( array_key_exists("delete_{$token_name}", $_POST) || empty($value) ) {
						unset($tokens[$token_name]);
					} else {
						$tokens[$token_name] = $value;
					}
				}
			}
			// TODO: if removing tokens then warn before removing
		}
		if ( !empty($_POST["new-token-name"]) && !empty($_POST["new-token-value"]) ) {
			if ( strrpos($_POST["new-token-name"], "this") === FALSE && strrpos($_POST["new-token-name"], "this") !== 0 ) {
				$tokens[str_replace(' ', '-', $_POST["new-token-name"])] = $_POST["new-token-value"];
			}
			// TODO: if the token name has "this-" at the start then display a message
		}
		token_save($CPATH.FILE_TOKEN, $tokens);
	}
}


/**
 * token_display_form function.
 * 
 * @access public
 * @param array $post_values
 * @param array $tokens
 * @return string
 */
function token_display_form($post_values, $tokens) {

	$form = '<table>';
	
	ksort($tokens);
	foreach($tokens as $key => $value) {
		$form .="<tr><td>[$key]</td><td>";
		if ( strrpos($key, "this-") !== 0 ) {
			$form .= form_input('token_'.$key, 'Text', $value, array('size' => 20,));
		} else {
			$form .= " {$value} ";
		}
		$form .= "</td><td>";
		if ( strrpos($key, "this-") !== 0 ) {
			$form .= form_input('delete_'.$key, 'checkbox', $key);
		} else {
			$form .= "&nbsp;";
		}
		$form .= "</td></tr>";
  }
  
  $form .="<tr>";
	$form .="<td>".form_input('new-token-name', 'Text', '', array('size' => 20,))."</td>";
	$form .="<td>".form_input('new-token-value', 'Text', '', array('size' => 20,))."</td>";
	$form .="<td>&nbsp;</td>";
	$form .="</tr>";

  $form .= '</table>';

  $form .= form_input('action', 'Hidden', 'doAction');
  $form .= form_input('submit', 'Submit', 'Save');
  
	return form_form('token-form', $_SERVER['PHP_SELF'], $form);
}

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Token Administration</title>
<?php
echo metadata_link('stylesheet', 'text/css', 'style.css?t=E0LB').
     metadata_script('js/domready.js').metadata_script('js/script.js');
?>
</head>

<body class="user-admin">
	<header>
		<h1>Token Administration</h1>
	</header>
<section><div class="main"><?php

echo token_display_form($_POST, $tokens);

?></div></section>
	<nav><p><a href="./">≪ back to the content administration</a></p></nav>
	</body>
</html>