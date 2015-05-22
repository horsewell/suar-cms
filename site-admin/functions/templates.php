<?php

/**
 * page_template function.
 * 
 * @access public
 * @param string $template_file
 * @param array $page_array
 * @return string
 */
function page_template($template_file, $page_array) {
	$page_template = txt_load($template_file);
	
	preg_match_all('/\[template\:([a-zA-z0-9\-]+)\]/', $page_template, $sections);
	foreach($sections[1] as $section) {
		$page_template = str_replace('[template:'.$section.']', $page_array[$section], $page_template);
	}
	
	return token_filter($page_template);
}