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

$page_array = array();

$json = txt_load(PATH_CONTENT.clean_path($page_file) .'.json');
$page_array = json_decode($json, TRUE);
$page_array['page-body'] = base64_decode($page_array['page-body']);
//$page_array['page-body'] = token_filter($page_array['page-body']);

// filter the page for bad things

// this will get ready for meta-data changes
// also allow this page to access the tokens

// the page should be ?page=home ?page=features ?page=contact

function page_template($template_file, $page_array) {
	$page_template = txt_load($template_file);
	
	preg_match_all('/\[template\:([a-zA-z0-9\-]+)\]/', $page_template, $sections);
	foreach($sections[1] as $section) {
		$page_template = str_replace('[template:'.$section.']', $page_array[$section], $page_template);
	}
	
	return $page_template;
}

print token_filter(page_template(PATH_TEMPLATES.clean_path($page_array['page-template']), $page_array));

//print_r($page_array);

?>