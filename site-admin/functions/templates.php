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
	$page_template = token_filter(txt_load($template_file));
	
	preg_match_all('/\[template\:([a-zA-z0-9\-]+)\]/', $page_template, $sections);
	//print '<div style="background-color: yellow;"><pre>found tokens = '. print_r($page_array, TRUE) .'</pre></div>';
	foreach($sections[1] as $section) {
		$page_template = str_replace('[template:'.$section.']', $page_array[$section], $page_template);
	}
	return $page_template;
}

/**
 * page_template_list function.
 * 
 * @access public
 * @param string $dir (default: '')
 * @return array
 */
function page_template_list($dir = '') {
	$templates = file_list(PATH_TEMPLATES);
	//print '<pre>'. print_r($templates, TRUE).'</pre>';
	$template_options = array();
	foreach($templates as $template) {
		$template_options[substr($template, 0, -5)] = $template;
	}
	return $template_options;
}