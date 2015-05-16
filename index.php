<?php

include('admin/functions.php');

$page_file = array_key_exists('page', $_GET) ? $_GET['page'] : 'home';

//$page_content = txt_load($page_file .'.txt');
$page_content = file_get_contents('content/'.$page_file.'.txt');

// preload the page
// parse the tokens and stubstitue
// filter the page for bad things
// display the page

// this will get ready for meta-data changes
// also allow this page to access the tokens

// the page should be ?page=home ?page=features ?page=contact

?><!DOCTYPE html>   
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
</head>
<!-- !Body -->
<body>
	<div id="container">
		
		<section id="main">
<?php echo $page_content; ?>
		</section><!-- /main -->
		
		<footer>
		
		</footer><!-- /footer -->
	</div><!--!/#container -->
</body>
</html>