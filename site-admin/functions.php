<?php

/**
 *  Basic functionality  
 */

$CPATH="_content/";
$BPATH="_content/backup/";
$TOKEN_FILE = 'tokens.json';
$tokens = array();

/**
 *  Get the list of files  
 */

function file_list($dir) {
	if (is_dir($dir)) {
		$fd = @opendir($dir);
		while (($part = @readdir($fd)) == true) {
			clearstatcache();
			if ($part != "." && $part != ".." && $part != ".htaccess") {
				if (!is_dir($part)) {
					$file_array[] = $part;
				}
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
		return false;
	}
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
	$text = strip_tags($text);
	return $text;
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

/**
 *  Filter the text page
 **/

function token_filter($page_content) {
	global $tokens, $CPATH, $TOKEN_FILE;
	// make sure the $tokens are loaded
	if ( !(isset($tokens) && count($tokens) > 0) ) {
		$tokens = tokens_load($CPATH.$TOKEN_FILE);
	}
	
	preg_match_all('/\[[a-zA-z0-9\-]+\]/', $page_content, $matches);
	$matches = array_unique($matches)[0];
	
	foreach($matches as $value) {
		$match = trim($value, "[]");
		$page_content = str_replace('['.$match.']', $tokens[$match], $page_content);
	}
	
	//$page_content .= '<div style="background-color: yellow;"><pre>found tokens = '. print_r($matches, TRUE) .'</pre></div>';
	//$page_content .= '<div style="background-color: gray;"><pre>$tokens = '. print_r($tokens, TRUE) .'</pre></div>';
	
	return $page_content;
}

/**
 *  load token variables
 */

function tokens_load($file = 'tokens.json') {
	$json   = txt_load($file);
	$tokens = json_decode($json, TRUE);
	
	$tokens['this-year'] = date('Y');
	$tokens['this-month'] = date('n');
	$tokens['this-day'] = date('j'); 
	// $tokens['current-user'] = $GLOBALS[''];

	return $tokens;
}

/**
 *  save token variables.
 **/

function tokens_save($file = 'tokens.json', $tokens = array()) {
	foreach($tokens as $key => $value ) {
		if ( strrpos($key, "this-") === 0 ) {
			unset($tokens[$key]);
		}
	}
	txt_update($file, json_encode($tokens));
}

/**
 *  parse JSON
 **/



/**
 *  Display variable form
 **/

function display_form($post_values, $tokens) {
	global $self;
	$form  ="";
	$form .= "<form action=\"{$self}\" method=\"post\">\n";

	$form .= "<table>";
	
	ksort($tokens);
	foreach($tokens as $key => $value) {
		$form .="<tr><td>[$key]</td><td>";
		if ( strrpos($key, "this-") !== 0 ) {
			$form .="<input type=\"Text\" size=\"20\" name=\"token_{$key}\" value=\"{$value}\">";
		} else {
			$form .= " {$value} ";
		}
		$form .= "</td><td>";
		if ( strrpos($key, "this-") !== 0 ) {
			$form .="<input type=\"checkbox\" name=\"delete_{$key}\" value=\"$key\">";
		} else {
			$form .= "&nbsp;";
		}
		$form .= "</td></tr>";
  }
  
  $form .="<tr>";
	$form .="<td><input type=\"Text\" size=\"20\" name=\"new-token-name\"></td>";
	$form .="<td><input type=\"Text\" size=\"20\" name=\"new-token-value\"></td>";
	$form .="<td>&nbsp;</td>";
	$form .="</tr>";

  $form .= "</table>";

  $form .= "<input type=\"Hidden\" name=\"action\" value=\"doAction\">";
  $form .= "<input type=\"Submit\" name=\"submit\" value=\"Save\">";
	$form .= "</form>";
	return $form;
}


/**
 *  functions for meta-data forms
 **/

function create_metadata_form() {
	
}












