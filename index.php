<!DOCTYPE html>   
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<!--[if IE]><![endif]-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title></title>
	<meta name="description" content="">
	<meta name="keywords" content="" />
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
</head><!-- !Body -->
<body>
	<div id="container">
<?php

require_once 'admin/functions.php';

/**
 *  Filter the text page
 **/

function token_filter($page) {
	global $tokens, $CPATH, $TOKEN_FILE;
	// make sure the $tokens are loaded
	if ( !(isset($tokens) && count($tokens) > 0) ) {
		$tokens = tokens_load($CPATH.$TOKEN_FILE);
		$page .= '<div style="background-color: gray;"><pre>$tokens = '. print_r($tokens, TRUE) .'</pre></div>';
	}
	// pass the page for "[token-text]" style items using regex
	// replace all occurances of each
	// return the text
	return $page;
}

?>		
		<section id="main">
<?php echo token_filter(file_get_contents($CPATH.'home.txt')); ?>
		</section><!-- /main -->
		
		<footer>
		
		</footer><!-- /footer -->
	</div><!--!/#container -->
</body>
</html>