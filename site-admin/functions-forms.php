<?php

/**
 *  Functions used to create forms only included when needed (mainly for admin)
 **/

function form_tag_attributes($id, $options) {
	$attributes = '';
	$attributes .= array_key_exists('name', $options) ? '' : ' name="'.check_plain($id).'"';
	foreach( $options as $name => $value) {
		$attributes .= ' '. check_plain($name) .'="'. check_plain($value) .'"';
	}
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