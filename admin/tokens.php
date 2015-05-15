<?php
include('class.PasswdAuth.inc');
$pass = new PasswdAuth(realpath(getcwd()));
$pass->check();

$self = $_SERVER['PHP_SELF'];

include('functions.php');

$tokens = tokens_load($TOKEN_FILE);

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
		tokens_save($TOKEN_FILE, $tokens);
	}
}

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Token Administration</title>
	<link rel="stylesheet" type="text/css" href="style.css?t=E0LB">
	
	<script src="js/domready.js" type="application/javascript"></script>
	<script src="js/script.js" type="application/javascript"></script>
</head>

<body class="user-admin">
	<header>
		<h1>Token Administration</h1>
	</header>
<section><div class="main"><?php

// if remove then give a chance to cancel delete

print display_form($_POST, $tokens);

//print '<pre>$_POST = '. print_r($_POST, TRUE) .'</pre>';
//print '<pre>$tokens = '. print_r($tokens, TRUE) .'</pre>';

?></div></section>
	<nav><p><a href="./">≪ back to the content administration</a></p></nav>
	</body>
</html>