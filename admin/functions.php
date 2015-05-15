<?php

/**
 *  Basic functionality  
 */

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
	global $CPATH;
	return file_get_contents($CPATH.$file);
}

/**
 *  Update the file
 */

function txt_update($file, $string) {
	global $CPATH;
	if (get_magic_quotes_gpc()) { $string = stripslashes($string); }
	$fp = fopen($CPATH.$file, "w");
	fwrite($fp, $string);
	fclose($fp);
}

/**
 *  save the file in the backup file
 */

function txt_backup($file, $string) {
	global $BPATH;
	$fp = fopen($BPATH.$file, "w");
	fwrite($fp, $string);
	fclose($fp);
}

/**
 *  
 */

function txt_restore($file, $string) {
	global $BPATH;
	return file_get_contents($BPATH.$file);
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
 *  
 */






