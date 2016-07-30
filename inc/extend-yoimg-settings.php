<?php
if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}
function yoimg_seo_extend_settings($settings) {
	$seo_settings = array (
			'option' => array (
					'page' => 'yoimages-seo',
					'title' => __ ( 'SEO for images', YOIMG_DOMAIN ),
					'option_group' => 'yoimages-seo-group',
					'option_name' => 'yoimg_seo_settings',
					'sanitize_callback' => 'yoimg_seo_settings_sanitize_seo',
					'sections' => array (
							array (
									'id' => 'yoimg_imgseo_options_section',
									'title' => __ ( 'SEO for images', YOIMG_DOMAIN ),
									'callback' => 'yoimg_seo_settings_section_info',
									'fields' => array (
											array (
													'id' => 'imgseo_change_image_title',
													'title' => __ ( 'Change image title', YOIMG_DOMAIN ),
													'callback' => 'yoimg_seo_settings_change_image_title_callback' 
											),
											array (
													'id' => 'imgseo_image_title_expression',
													'title' => __ ( 'Image title expression', YOIMG_DOMAIN ),
													'callback' => 'yoimg_seo_settings_image_title_expression_callback' 
											),
											array (
													'id' => 'imgseo_change_image_alt',
													'title' => __ ( 'Change image alt attribute', YOIMG_DOMAIN ),
													'callback' => 'yoimg_seo_settings_change_image_alt_callback' 
											),
											
											array (
													'id' => 'imgseo_image_alt_expression',
													'title' => __ ( 'Image alt expression', YOIMG_DOMAIN ),
													'callback' => 'yoimg_seo_settings_image_alt_expression_callback' 
											),
											array (
													'id' => 'imgseo_change_image_filename',
													'title' => __ ( 'Change image file name', YOIMG_DOMAIN ),
													'callback' => 'yoimg_seo_settings_image_filename_callback' 
											),
											array (
													'id' => 'imgseo_image_filename_expression',
													'title' => __ ( 'Image file name expression', YOIMG_DOMAIN ),
													'callback' => 'yoimg_seo_settings_image_filename_expression_callback' 
											) 
									) 
							) 
					) 
			) 
	);
	array_push ( $settings, $seo_settings );
	return $settings;
}
add_filter ( 'yoimg_settings', 'yoimg_seo_extend_settings', 10, 1 );
function yoimg_seo_settings_change_image_title_callback() {
	$seo_options = get_option ( 'yoimg_seo_settings' );
	printf ( '<input type="checkbox" id="imgseo_change_image_title" name="yoimg_seo_settings[imgseo_change_image_title]" value="TRUE" %s />
				<p class="description">' . __ ( 'If checked title will be replaced with the expression here below', YOIMG_DOMAIN ) . '</p>', $seo_options ['imgseo_change_image_title'] ? 'checked="checked"' : (YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_TITLE && ! isset ( $seo_options ['imgseo_change_image_title'] ) ? 'checked="checked"' : '') );
}
function yoimg_seo_settings_image_title_expression_callback() {
	$seo_options = get_option ( 'yoimg_seo_settings' );
	printf ( '<input type="text" id="imgseo_image_title_expression" name="yoimg_seo_settings[imgseo_image_title_expression]" value="%s" class="imgseo_change_image_title-dep" />
				<p class="description">' . __ ( 'expression used to replace the title, accepted values are:', YOIMG_DOMAIN ) . ' ' . implode ( ', ', apply_filters ( 'yoimg_supported_expressions', array () ) ) . '</p>', ! empty ( $seo_options ['imgseo_image_title_expression'] ) ? esc_attr ( $seo_options ['imgseo_image_title_expression'] ) : YOIMG_IMGSEO_IMAGE_TITLE_EXPRESSION );
}
function yoimg_seo_settings_change_image_alt_callback() {
	$seo_options = get_option ( 'yoimg_seo_settings' );
	printf ( '<input type="checkbox" id="imgseo_change_image_alt" name="yoimg_seo_settings[imgseo_change_image_alt]" value="TRUE" %s />
				<p class="description">' . __ ( 'If checked alt will be replaced with the expression here below', YOIMG_DOMAIN ) . '</p>', $seo_options ['imgseo_change_image_alt'] ? 'checked="checked"' : (YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_ALT && ! isset ( $seo_options ['imgseo_change_image_alt'] ) ? 'checked="checked"' : '') );
}
function yoimg_seo_settings_image_alt_expression_callback() {
	$seo_options = get_option ( 'yoimg_seo_settings' );
	printf ( '<input type="text" id="imgseo_image_alt_expression" name="yoimg_seo_settings[imgseo_image_alt_expression]" value="%s" class="imgseo_change_image_alt-dep" />
				<p class="description">' . __ ( 'expression used to replace the alt, accepted values are:', YOIMG_DOMAIN ) . ' ' . implode ( ', ', apply_filters ( 'yoimg_supported_expressions', array () ) ) . '</p>', ! empty ( $seo_options ['imgseo_image_alt_expression'] ) ? esc_attr ( $seo_options ['imgseo_image_alt_expression'] ) : YOIMG_IMGSEO_IMAGE_ALT_EXPRESSION );
}
function yoimg_seo_settings_image_filename_callback() {
	$seo_options = get_option ( 'yoimg_seo_settings' );
	printf ( '<input type="checkbox" id="imgseo_change_image_filename" name="yoimg_seo_settings[imgseo_change_image_filename]" value="TRUE" %s />
				<p class="description">' . __ ( 'If checked the filename will be replaced with the expression here below', YOIMG_DOMAIN ) . '</p>', $seo_options ['imgseo_change_image_filename'] ? 'checked="checked"' : (YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_FILENAME && ! isset ( $seo_options ['imgseo_change_image_filename'] ) ? 'checked="checked"' : '') );
}
function yoimg_seo_settings_image_filename_expression_callback() {
	$seo_options = get_option ( 'yoimg_seo_settings' );
	printf ( '<input type="text" id="imgseo_image_filename_expression" name="yoimg_seo_settings[imgseo_image_filename_expression]" value="%s" class="imgseo_change_image_filename-dep" />
				<p class="description">' . __ ( 'expression used to replace the filename, accepted values are:', YOIMG_DOMAIN ) . ' ' . implode ( ', ', apply_filters ( 'yoimg_supported_expressions', array () ) ) . '</p>', ! empty ( $seo_options ['imgseo_image_filename_expression'] ) ? esc_attr ( $seo_options ['imgseo_image_filename_expression'] ) : YOIMG_IMGSEO_IMAGE_FILENAME_EXPRESSION );
}
function yoimg_seo_settings_section_info() {
	print __ ( 'Enter your images SEO settings here below', YOIMG_DOMAIN );
	printf ( '<p>' . __ ( 'Supported expressions:', YOIMG_DOMAIN ) . ' ' . implode ( ', ', apply_filters ( 'yoimg_supported_expressions', array () ) ) . '</p>' );
}
function yoimg_seo_settings_sanitize_seo($input) {
	$new_input = array ();
	if (isset ( $input ['imgseo_change_image_title'] ) && ($input ['imgseo_change_image_title'] === 'TRUE' || $input ['imgseo_change_image_title'] === TRUE)) {
		$new_input ['imgseo_change_image_title'] = TRUE;
	} else {
		$new_input ['imgseo_change_image_title'] = FALSE;
	}
	if (isset ( $input ['imgseo_change_image_alt'] ) && ($input ['imgseo_change_image_alt'] === 'TRUE' || $input ['imgseo_change_image_alt'] === TRUE)) {
		$new_input ['imgseo_change_image_alt'] = TRUE;
	} else {
		$new_input ['imgseo_change_image_alt'] = FALSE;
	}
	if (isset ( $input ['imgseo_change_image_filename'] ) && ($input ['imgseo_change_image_filename'] === 'TRUE' || $input ['imgseo_change_image_filename'] === TRUE)) {
		$new_input ['imgseo_change_image_filename'] = TRUE;
	} else {
		$new_input ['imgseo_change_image_filename'] = FALSE;
	}
	if (isset ( $input ['imgseo_image_title_expression'] ) && ! empty ( $input ['imgseo_image_title_expression'] )) {
		$new_input ['imgseo_image_title_expression'] = sanitize_text_field ( $input ['imgseo_image_title_expression'] );
	} else {
		add_settings_error ( 'yoimg_seo_options_group', 'imgseo_image_title_expression', __ ( 'title expression is not valid, using default:', YOIMG_DOMAIN ) . ' ' . YOIMG_DEFAULT_IMGSEO_IMAGE_TITLE_EXPRESSION, 'error' );
		$new_input ['imgseo_image_title_expression'] = YOIMG_DEFAULT_IMGSEO_IMAGE_TITLE_EXPRESSION;
	}
	if (isset ( $input ['imgseo_image_alt_expression'] ) && ! empty ( $input ['imgseo_image_alt_expression'] )) {
		$new_input ['imgseo_image_alt_expression'] = sanitize_text_field ( $input ['imgseo_image_alt_expression'] );
	} else {
		add_settings_error ( 'yoimg_seo_options_group', 'imgseo_image_alt_expression', __ ( 'alt expression is not valid, using default:', YOIMG_DOMAIN ) . ' ' . YOIMG_DEFAULT_IMGSEO_IMAGE_ALT_EXPRESSION, 'error' );
		$new_input ['imgseo_image_alt_expression'] = YOIMG_DEFAULT_IMGSEO_IMAGE_ALT_EXPRESSION;
	}
	if (isset ( $input ['imgseo_image_filename_expression'] ) && ! empty ( $input ['imgseo_image_filename_expression'] )) {
		$new_input ['imgseo_image_filename_expression'] = sanitize_text_field ( $input ['imgseo_image_filename_expression'] );
	} else {
		add_settings_error ( 'yoimg_seo_options_group', 'imgseo_image_filename_expression', __ ( 'filename expression is not valid, using default:', YOIMG_DOMAIN ) . ' ' . YOIMG_DEFAULT_IMGSEO_IMAGE_FILENAME_EXPRESSION, 'error' );
		$new_input ['imgseo_image_filename_expression'] = YOIMG_DEFAULT_IMGSEO_IMAGE_FILENAME_EXPRESSION;
	}
	return $new_input;
}