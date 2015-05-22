<?php

/**
 * Core functionality
 *
 */
 

/**
 * directory_list function.
 * 
 * @access public
 * @param string $dir
 * @return mixed array or FALSE
 */
function directory_list($dir) {
	if (is_dir($dir)) {
		$fd = @opendir($dir);
		while (($part = @readdir($fd)) == true) {
			clearstatcache();
			if ($part[0] != "." && $part[0] != "_") { // hidden ("_", ".") to CMS
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

/**
 * file_list function.
 * 
 * @access public
 * @param string $dir
 * @return mixed array or FALSE
 */
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
 * txt_load function.
 * 
 * @access public
 * @param string $file
 * @return string
 */
function txt_load($file) {
	return file_get_contents($file);
}


/**
 * txt_update function.
 * 
 * @access public
 * @param string $file
 * @param string $string
 * @return void
 */
function txt_update($file, $string) {
	if (get_magic_quotes_gpc()) { $string = stripslashes($string); }
	$fp = fopen($file, "w");
	fwrite($fp, $string);
	fclose($fp);
}



/**
 * check_plain function.
 * 
 * @access public
 * @param string $text
 * @return string
 */
function check_plain($text) {
	return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}



/**
 * clean_html function.
 * 
 * @access public
 * @param string $html
 * @param string $allowable_tags (default: '')
 * @return string
 */
function clean_html($html, $allowable_tags = '') {
	if ($allowed_tags !== '') {
		$html = strip_tags($html, $allowable_tags);
	}
	return $html;
}

/**
 * clean_path function.
 *
 * Removes characters from file system path that are dangerous
 * don't start with a slash or jump out of a directory or into a home directory
 * 
 * @access public
 * @param string $path
 * @return string
 */
function clean_path($path) {
	$path = trim($path, "/");
	return str_replace(
	array('../'. '~'),
	array('', '',),
	$path
	);
}

/**
 * message function.
 * 
 * @access public
 * @param string $string
 * @param string $type
 * @return string
 */
function message($string, $type) {
	return '<div class="message '.$type.'">'.$string.'</div>';
}

/**
 * token_filter function.
 *
 * Processes the string looking for tokens and replacing or removing them (if there is no token)
 * 
 * @access public
 * @param string $page_content
 * @return string
 */
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
 * token_load function.
 * 
 * @access public
 * @param string $file (default: 'tokens.json')
 * @return array
 */
function token_load($file = 'tokens.json') {
	$json   = txt_load($file);
	$tokens = json_decode($json, TRUE);
	
	$tokens['this-year'] = date('Y');
	$tokens['this-month'] = date('n');
	$tokens['this-day'] = date('j'); 

	return $tokens;
}

/**
 * plugins_get_status function.
 * 
 * @access public
 * @param string $dir
 * @return array or FALSE (if directory doesn't exist)
 */
function plugins_get_status($dir) {
	if ( file_exists($dir.'status.json') ) {
		return json_decode(txt_load($dir.'status.json'), TRUE);
	}	
	return false;
}

/**
 * plugins_set_status function.
 * 
 * @access public
 * @param string $dir
 * @param array $plugin_status
 * @return void
 */
function plugins_set_status($dir, $plugin_status) {
	txt_update($dir.'status.json', json_encode($plugin_status));
}

/**
 * plugins_load function.
 * 
 * includes the plugin modules code
 *
 * @access public
 * @param string $dir
 * @param array &$plugin_status
 * @return void
 */
function plugins_load($dir, &$plugin_status) {
	$plugin_status = !empty($plugin_status) ? plugins_get_status($dir) : $plugin_status;
	foreach($plugin_status as $plugin => $status) {
		if ($status) {
			require_once($dir.$plugin.'/'.$plugin.'.module');
		}
	}
	// TODO: should execute a default function for each module
}

/**
 * token_save function.
 * 
 * @access public
 * @param string $file (default: 'tokens.json')
 * @param array $tokens (default: array())
 * @return void
 */
function token_save($file = 'tokens.json', $tokens = array()) {
	foreach($tokens as $key => $value ) {
		if ( strrpos($key, "this-") === 0 ) {
			unset($tokens[$key]);
		}
	}
	txt_update($file, json_encode($tokens));
}

/**
 * metadata_link function.
 * 
 * @access public
 * @param string $rel
 * @param string $type
 * @param string $url
 * @return string
 */
function metadata_link($rel, $type, $url) {
	return "<link rel=\"{$rel}\" type=\"{$type}\" href=\"{$url}\" />";
}

/**
 * metadata_script function.
 * 
 * @access public
 * @param string $url
 * @return string
 */
function metadata_script($url) {
	return "<script type=\"application/javascript\" src=\"{$url}\"></script>";
}

/**
 * metadata_meta function.
 * 
 * @access public
 * @param string $name
 * @param string $content
 * @param boolean $httpequiv (default: FALSE)
 * @return string
 */
function metadata_meta($name, $content, $httpequiv = FALSE) {
	$type = !$httpequiv ? 'name' : 'http-equiv';
	return "<meta {$type}=\"{$name}\" content=\"{$content}\" />";
}

/**
 * tag_attributes function.
 * 
 * @access public
 * @param array $options
 * @return string
 */
function tag_attributes($options) {
	if ( !is_array($options) ) { return $options; }
	$attributes = '';
	foreach($options as $name => $value) {
		$attributes .= ' '. check_plain($name) .'="'. check_plain($value) .'"';
	}
	return $attributes;
}
