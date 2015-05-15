<?php
include('class.PasswdAuth.inc');
$pass = new PasswdAuth(realpath(getcwd()));
$pass->check();

$self = $_SERVER['PHP_SELF'];

include('functions.php');

$tokens = tokens_load('tokens.json');

// remove
if ( $_POST['action'] === "doAction" ) {
	if ( $_POST['submit'] === "Save" ) {
		foreach($_POST as $key => $value ) {
			if ( strrpos($key, "this-") === FALSE && strrpos($key, "new-token") === FALSE ) {
				$token_name = explode("_", $key)[1];
				if ( array_key_exists($token_name, $tokens) ) {
					if ( !empty($_POST["delete_{$token_name}"]) || empty($value) ) {
						unset($tokens[$token_name]);
					} else {
						$tokens[$token_name] = $value;
					}
				}
			}
		}
		if ( !empty($_POST["new-token-name"]) && !empty($_POST["new-token-value"]) ) {
			$tokens[$_POST["new-token-name"]] = $_POST["new-token-value"];
		}
		tokens_save('tokens.json', $tokens);
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

print display_form($tokens);

//print '<pre>$_POST = '. print_r($_POST, TRUE) .'</pre>';
//print '<pre>$tokens = '. print_r($tokens, TRUE) .'</pre>';

?></div></section>
	<nav><p><a href="./">≪ back to the content administration</a></p></nav>
	</body>
</html>