<?php

/**
 *  Functions used to create forms only included when needed (mainly for admin)
 **/

/**
 * form_tag_attributes function.
 * 
 * @access public
 * @param string $id
 * @param array $options
 * @return string
 */
function form_tag_attributes($id, $options) {
	if ( !is_array($options) ) { return $options; }
	if ( empty($options['name']) ) { $options['name'] = $id; } // if no name then give ID
	return tag_attributes($options);
}


/**
 * form_form function.
 * 
 * @access public
 * @param string $id
 * @param string $action
 * @param string $content
 * @param string $method (default: 'post')
 * @param array $options (default: array())
 * @return string
 */
function form_form($id, $action, $content, $method = 'post', $options = array()) {
	$attributes = form_tag_attributes($id, $options);
	$form .= "<form id=\"{$id}\" name=\"{$id}\" action=\"{$action}\" method=\"$method\"{$attributes}>\n";
	$form .= $content;
	$form .= form_input($id.'-name', 'hidden', $id, array('name' => 'form-name'));
	return $form .'</form>';
}

/**
 * form_input function.
 * 
 * @access public
 * @param string $id
 * @param string $type
 * @param string $value (default: '')
 * @param array $options (default: array())
 * @return void
 */
function form_input($id, $type, $value = '', $options = array()) {
	if ( !empty($value) ) { $options['value'] = $value; }
	$attributes = form_tag_attributes($id, $options);
	return "<input id=\"{$id}\" type=\"{$type}\"{$attributes} />";
}

/**
 * form_select function.
 * 
 * @access public
 * @param string $id
 * @param string $select_title
 * @param string $select_value
 * @param array $select_options
 * @param array $options (default: array())
 * @return string
 */
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

/**
 * form_textarea function.
 * 
 * @access public
 * @param string $id
 * @param string $text_value
 * @param array $options (default: array())
 * @return string
 */
function form_textarea($id, $text_value, $options = array()) {
	$attributes = form_tag_attributes($id, $options);
	return "<textarea id=\"{$id}\"{$attributes}>{$text_value}</textarea>";
}

/**
 * form_html function.
 * 
 * @access public
 * @param string $html
 * @return string
 */
function form_html($html) {
	return $html;
}



/**
 * form_constructor function.
 * 
 * @access public
 * @param array $form_array
 * @param array $post
 * @return string
 */
function form_constructor($form_array, $post) {
	if ( $post['form-name'] !== $form_array['id'] ) { $post = array(); } // not our form not our business
	$form = '';
	$input_types = array(
		'text', 'password', 'submit', 'radio', 'checkbox',
		'button', 'color', 'date', 'email', 'month',
		'range', 'tel', 'time', 'url', 'week', 'hidden',
	);
	foreach($form_array as $id => $options) {
		if ( !is_array($options) || !array_key_exists('type', $options) ) { continue; }
		$type = $options['type'];
		$options['attributes']['name'] = empty($options['attributes']['name']) ? $id : $options['attributes']['name'];
		
		$value = $options['value'];
		if (!in_array($type, array('submit','button'))) {
			$value = array_key_exists($options['attributes']['name'], $post) ? $post[$options['attributes']['name']] : $value;
			$value = empty($value) ? $options['default_value'] : $value;
		}
		if ( in_array($type, $input_types) ) {
			$input_item = form_input($id, $type, $value, $options['attributes']);
			if ( empty($options['title']) ) {
				$form .= $input_item;
			} else {
				$form .= '<div><label for="'.$id.'">'.$options['title'].'</label> '.$input_item.'</div>';
			}
		} else if ( $type == 'textarea' ) {
			$form .= '<div><label for="'.$id.'">'.$options['title'].'</label><br/>';
			$form .= form_textarea($id, $value, $options['attributes']) .'</div>';
		} else if ( $type == 'select' ) {
			if ( is_string($options['select-options']) ) {
				$options['select-options'] = call_user_func($options['select-options'], $value);
			}
			$form .= '<div><label for="'.$id.'">'.$options['title'].'</label>';
			$form .= form_select($id, '', $value, $options['select-options'], $options['attributes']) .'</div>';
		} else if ( $type == 'html' || empty($type) ) {
			$form .= form_html($options['html']);
		}
	}
	return form_form($form_array['id'], $form_array['action'], $form);
}

/**
 * form_process_post function.
 * 
 * @access public
 * @param array $form
 * @param array $post
 * @return void
 */
function form_process_post($form, $post) {
	if ( $post['form-name'] !== $form_array['id'] ) { return; } // not our form not our business




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

/**
 * page_template_list function.
 * 
 * @access public
 * @param string $dir (default: '')
 * @return array
 */
function page_template_list($dir = '') {
	$templates = file_list('../'.PATH_TEMPLATES);
	//print '<pre>'. print_r($templates, TRUE).'</pre>';
	$template_options = array('- Select a template -' => '');
	foreach($templates as $template) {
		$template_options[substr($template, 0, -5)] = $template;
	}
	return $template_options;
}
