<?php

/**
 *  Functions used to create forms only included when needed (mainly for admin)
 **/

function form_tag_attributes($id, $options) {
	if ( !is_array($options) ) { return $options; }
	if ( !array_key_exists('name', $options) ) { $options['name'] = $id; } // if no name then give ID
	$attributes = tag_attributes($options);
	return $attributes;
}

// ---- TAGS

function form_form($id, $action, $content, $method = 'post', $options = array()) {
	$attributes = form_tag_attributes($id, $options);
	$form .= "<form id=\"{$id}\" action=\"{$action}\" method=\"$method\"{$attributes}>\n";
	$form .= $content;
	return $form .'</form>';
}

function form_input($id, $type, $value = '', $options = array()) {
	if ( !empty($value) ) { $options['value'] = $value; }
	$attributes = form_tag_attributes($id, $options);
	return "<input id=\"{$id}\" type=\"{$type}\"{$attributes} />";
}

function form_select($id, $select_title, $select_value, $select_options, $options = array()) {
	$attributes = form_tag_attributes($id, $options);
	$select = "<select id=\"{$id}\"{$attributes}>";
	$select .= empty($select_title) ? '' : "<option value=\"\">{$select_title}</option>";
	foreach ($select_options as $key => $value) {
		$select .= "<option value=\"{$value}\" ";
		$select .= $select_value === $value ? 'selected' : '';
		$select .= ' >'. $key .'</option>';
	}
	return $select.'</select>';
}

function form_textarea($id, $text_value, $options = array()) {
	$attributes = form_tag_attributes($id, $options);
	return "<textarea id=\"{$id}\"{$attributes}>{$text_value}</textarea>";
}

function form_html($html) {
	return $html;
}



function form_constructor($form_array, $post) {
	$form = '';
	$input_types = array(
		'text', 'password', 'submit', 'radio', 'checkbox',
		'button', 'color', 'date', 'email', 'month',
		'range', 'tel', 'time', 'url', 'week',
	);
	foreach($form_array as $id => $options) {
		if ( !is_array($options) || !array_key_exists('type', $options) ) { continue; }
		$type = $options['type']; unset($options['type']);
		$name = array_key_exists('name', $options) ? $options['name'] : $id;
		
		if ( in_array($type, $input_types) ) {
			$form .= '<div><label for="'.$id.'">'.$options['title'].'</label> ';
			$form .= form_input($id, $type, $post[$name], $options['attributes']) .'</div>';
		} else if ( $type == 'textarea' ) {
			$form .= '<div><label for="'.$id.'">'.$options['title'].'</label><br/>';
			$form .= form_textarea($id, $post[$name], $options['attributes']) .'</div>';
		} else if ( $type == 'select' ) {
			if ( is_string($options['select-options']) ) {
				$options['select-options'] = call_user_func($options['select-options']);
			}
			$form .= '<div><label for="'.$id.'">'.$options['title'].'</label>';
			$form .= form_select($id, '', $post[$name], $options['select-options'], $options['attributes']) .'</div>';
		} else if ( $type == 'html' ) {
			$form .= form_html($options['html']);
		}
	}
	return form_form($form_array['id'], $form_array['action'], $form);
}

function form_process_post($form, $post) {
	
// make sure items validate
// construct json and html
// save the two files or just one?	

	
/*

cycle through the form
get each post
process based on from (verify)
if error display again with values
if no error pass to the save function

// http://php.net/manual/en/functions.variable-functions.php
// http://php.net/manual/en/function.call-user-func.php
// http://php.net/manual/en/function.html-entity-decode.php
// http://php.net/manual/en/function.htmlspecialchars.php

*/
}

function page_template_list($dir = '') {
	$templates = file_list('../'.PATH_TEMPLATES);
	//print '<pre>'. print_r($templates, TRUE).'</pre>';
	$template_options = array('- Select a template -' => '');
	foreach($templates as $template) {
		$template_options[substr($template, 0, -5)] = $template;
	}
	return $template_options;
}
