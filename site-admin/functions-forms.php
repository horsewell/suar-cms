<?php

/**
 *  Functions used to create forms only included when needed (mainly for admin)
 **/

function form_tag_attributes($id, $options) {
	if ( !array_key_exists('name', $options) ) { $options['name'] = $id; } // if no name then give ID
	$attributes = tag_attributes($options);
	return $attributes;
}

// ---- TAGS

function form_form($id, $action, $content, $method = 'post', $options = array()) {
	$attributes = form_tag_attributes($id, $options);
	$form .= "<form id=\"{$id}\" action=\"$action\" method=\"$method\"{$attributes}>\n";
	$form .= $content;
	return $form .'</form>';
}

function form_input($id, $type, $value = '', $options = array()) {
	$attributes = form_tag_attributes($id, $options);
	$attributes .= empty($value) ? '' : " value=\"{$value}\"";
	foreach( $options as $name => $value) {
		$tag .= ' '. $name .'="'. $value .'"';
	}
	return "<input id=\"{$id}\" type=\"{$type}\"{$attributes} />";
}

function form_select($id, $select_title, $select_value, $select_options, $options = array()) {
	$attributes = form_tag_attributes($id, $options);
	$select = "<select id=\"{$id}\"{$attributes}>";
	$select .="<option value=\"\">{$select_title}</option>";
	foreach ($select_options as $key => $value) {
		$select .= "<option value=\"{$value}\" ";
		$select .= $select_value === $value ? 'selected' : '';
		$select .= ' >'. $key .'</option>';
	}
	return $select.'</select>';
}

function form_textarea($id, $text_value, $options) {
	$attributes = form_tag_attributes($id, $options);
	return "<textarea id=\"{$id}\"{$attributes}>{$text_value}</textarea>";
}

function form_html($html) {
	return $html;
}



function form_constructor($form, $post) {
/*

each form item needs: tag, type, id, default value, options
will look for value in $post variable based on name


*/
}

function form_process_post($form, $post) {
/*

cycle through the form
get each post
process based on from (verify)
if error display again with values
if no error pass to the save function

// http://php.net/manual/en/functions.variable-functions.php
// http://php.net/manual/en/function.call-user-func.php

*/
}

