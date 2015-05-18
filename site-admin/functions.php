<?php

/**
 *  Basic functionality  
 */
 
/**
 *  Get the list of files  
 */

function directory_list($dir) {
	if (is_dir($dir)) {
		$fd = @opendir($dir);
		while (($part = @readdir($fd)) == true) {
			clearstatcache();
			if ($part[0] != "." && $part[0] != "_") {
				$file_array[] = $part;
			}
		}
		if ($fd == true) {
			closedir($fd);
		}
		if (is_array($file_array)) {
			natsort($file_array);
			return $file_array;
		} else {
			return $file_array = NULL;
		}
	} else {
		return FALSE;
	}
}

function file_list($dir) {
	$directory_contents = directory_list($dir);
	if ( !$directory_contents ) { return FALSE; }
	foreach($directory_contents as $item) {
		if (!is_dir($dir.$item)) { // only files
			$file_array[] = $item;
		}
	}
	return $file_array;
}

/**
 *  Load the file
 */

function txt_load($file) {
	return file_get_contents($file);
}

/**
 *  Update the file
 */

function txt_update($file, $string) {
	if (get_magic_quotes_gpc()) { $string = stripslashes($string); }
	$fp = fopen($file, "w");
	fwrite($fp, $string);
	fclose($fp);
}

/**
 *  save the file in the backup file
 */

function txt_backup($file, $string) {
	$fp = fopen($file, "w");
	fwrite($fp, $string);
	fclose($fp);
}

/**
 *  
 */

function txt_restore($file, $string) {
	return file_get_contents($file);
}

/**
 *  Make sure the text is plain
 */

function check_plain($text) {
	$text = strip_tags($text); // remove all tags
	return htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); // From Drupal 7
}

/**
 *  Make sure the html doesn't have any nasty code
 */

function clean_html($html, $allowable_tags = '') {
	if ($allowed_tags !== '') {
		$html = strip_tags($html, $allowable_tags);
	}
	return $html;
}

function message($string, $type) {
	return '<div class="message '.$type.'">'.$string.'</div>';
}

/**
 *  Filter the text page
 **/

function token_filter($page_content) {
	global $tokens;
	// make sure the $tokens are loaded
	if ( !(isset($tokens) && count($tokens) > 0) ) {
		$tokens = token_load(PATH_CONTENT.FILE_TOKEN);
	}
	
	preg_match_all('/\[[a-zA-z0-9\-]+\]/', $page_content, $matches);
	$matches = array_unique($matches[0]);

	//$page_content .= '<div style="background-color: yellow;"><pre>found tokens = '. print_r($matches, TRUE) .'</pre></div>';
	//$page_content .= '<div style="background-color: gray;"><pre>$tokens = '. print_r($tokens, TRUE) .'</pre></div>';
	
	foreach($matches as $match) {
		$page_content = str_replace($match, $tokens[trim($match, '[]')], $page_content);
	}
	
	return $page_content;
}

/**
 *  load token variables
 */

function token_load($file = 'tokens.json') {
	$json   = txt_load($file);
	$tokens = json_decode($json, TRUE);
	
	$tokens['this-year'] = date('Y');
	$tokens['this-month'] = date('n');
	$tokens['this-day'] = date('j'); 
	// $tokens['current-user'] = $GLOBALS[''];

	return $tokens;
}

/**
 *  plugins get, set and load.
 **/

function plugins_get_status($dir) {
	if ( file_exists($dir.'status.json') ) {
		return json_decode(txt_load($dir.'status.json'), TRUE);
	}	
	return false;
}

function plugins_set_status($dir, $plugin_status) {
	txt_update($dir.'status.json', json_encode($plugin_status));
}

function plugins_load($dir, &$plugin_status) {
	$plugin_status = !empty($plugin_status) ? plugins_get_status($dir) : $plugin_status;
	foreach($plugin_status as $plugin => $status) {
		if ($status) {
			require_once($dir.$plugin.'/'.$plugin.'.module');
		}
	}
}

/**
 *  save token variables.
 **/

function token_save($file = 'tokens.json', $tokens = array()) {
	foreach($tokens as $key => $value ) {
		if ( strrpos($key, "this-") === 0 ) {
			unset($tokens[$key]);
		}
	}
	txt_update($file, json_encode($tokens));
}

/**
 *  functions for meta-data
 **/

function metadata_link($rel, $type, $url) {
	return "<link rel=\"{$rel}\" type=\"{$type}\" href=\"{$url}\" />";
}

function metadata_script($url) {
	return "<script type=\"application/javascript\" src=\"{$url}\"></script>";
}

function metadata_meta($name, $content, $httpequiv = FALSE) {
	$type = !$httpequiv ? 'name' : 'http-equiv';
	return "<meta {$type}=\"{$name}\" content=\"{$content}\" />";
}

function tag_attributes($options) {
	if ( !is_array($options) ) { return $options; }
	$attributes = '';
	foreach($options as $name => $value) {
		$attributes .= ' '. check_plain($name) .'="'. check_plain($value) .'"';
	}
	return $attributes;
}

function clean_path($path) {
	$path = trim($path, "/"); // don't start in root
	return str_replace(
	array('../'. '~'), // prevent changing down directories but can go up
	array('', '',),
	$path
	);
}
