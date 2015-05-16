<?php

/*
TODO:

We need to add some code to be able to add meta tags.
We also need to have some cutom items.

- One file for the HTML and another for the meta-data: page.html, page.meta.json

Base metadata
- title [meta:title]
- inpage title [meta:title-inpage]
- template? if we create template files name the template in the meta-data (select from dropdown)
- description [meta:description]
- author [meta:author]
- keywords
- other meta-data
- would be good to be able to have plugins here where people can add additional meta-data	
- which menu? have a plugin for menu system
	
 */

include('site-admin/config.php');
include('site-admin/functions.php');

$page_file = array_key_exists('page', $_GET) ? $_GET['page'] : 'home';

$page_content = txt_load($CPATH.$page_file .'.txt');
// TODO: load meta-data as well
$page_content = token_filter($page_content);

// filter the page for bad things

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
<!-- TODO: display meta-data info -->
	<title></title>
	<meta name="description" content="">
	<meta name="keywords" content="" />
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
</head><!-- !Body -->
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