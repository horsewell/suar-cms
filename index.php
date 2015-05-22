<?php

/*
TODO:

We need to add some code to be able to add meta tags.
We also need to have some custom items.

- One file for the HTML and another for the meta-data: page.html, page.meta.json

Base metadata
* title [meta:title]
* inpage title [meta:title-inpage]
* template? if we create template files name the template in the meta-data (select from dropdown)
* description [meta:description]
- author [meta:author]
* keywords
- other meta-data
- would be good to be able to have plugins here where people can add additional meta-data	
- which menu? have a plugin for menu system


have a file that keeps the URLs 
"file": "URL path" then every page can have it's own URL (automatically created but editable).

file system flat but URL can have structure.

have one file that keeps everything upto date (index) and the URL also in the file (which is always the correct URL)

must search for page URL in index file then load that file.
cache file will be the same.

*/

include('site-admin/config.php');
include('site-admin/functions/bootstrap.php');

// see if we can load the cached file
// if caching is off, the page is not cached or the page needs to regenerated start loading the rest

include('site-admin/functions/core.php');
include('site-admin/functions/templates.php');

$page_file = array_key_exists('page', $_GET) ? $_GET['page'] : 'home';

$page_array = array();

$json = txt_load(PATH_CONTENT.clean_path($page_file) .'.json');
$page_array = json_decode($json, TRUE);
$page_array['page-body'] = token_filter(html_entity_decode($page_array['page-body'], ENT_QUOTES));
//$page_array['page-body'] = token_filter($page_array['page-body']);


//print '<div style="background-color: yellow;"><pre>'. print_r($page_array, TRUE) .'</pre></div>';
print page_template(PATH_TEMPLATES.clean_path($page_array['page-template']), $page_array);

?>