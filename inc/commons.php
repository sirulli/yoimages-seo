<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function yoimg_imgseo_explode_expression( $expression, $attachment, $parent ) {
	$result = $expression;
	$result = apply_filters( 'yoimg_seo_expressions', $result, $attachment, $parent );
	if ( empty( $result ) ) {
		return $parent->post_title;
	} else {
		return $result;
	}
}

function yoimg_get_all_translations( $input ) {
	global $yoimg_mos;
	if ( ! $yoimg_mos ) {
		$yoimg_mos = array();
	}
	$result = array();
	$supported_locales = explode( ' ', YOIMG_SUPPORTED_LOCALES );
	foreach ( $supported_locales as $supported_locale ) {
		if ( $supported_locale === get_locale() ) {
			$translation = __( $input, YOIMG_DOMAIN );
		} else {
			$mo = isset( $yoimg_mos[$supported_locale] ) ? $yoimg_mos[$supported_locale] : null;
			if ( ! $mo ) {
				$mo_file = WP_PLUGIN_DIR . '/' . trim( YOIMG_LANG_REL_PATH, '/' ) . '/' . YOIMG_DOMAIN . '-' . $supported_locale . '.mo';
				yoimg_log( 'loading mo: ' . $mo_file );
				$mo = new MO();
				$mo->import_from_file( $mo_file );
				$yoimg_mos[$supported_locale] = $mo;
			}
			$translation = $mo->translate( $input );
		}
		array_push( $result, $translation );
	}
	return $result;
}

function yoimg_seo_expressions_author( $result, $attachment, $parent ) {
	$post_author_id = $parent->post_author;
	$post_author_username = get_the_author_meta( 'nickname', $post_author_id );
	$expressions = yoimg_get_all_translations( YOIMG_AUTHOR_USERNAME_EXPRESSION_EN_US );
	foreach ( $expressions as $expression ) {
		if ( strpos( $result, $expression ) !== FALSE ) {
			$result = str_replace( $expression, $post_author_username, $result );
		}
	}
	$post_author_firstname = get_the_author_meta( 'first_name', $post_author_id );
	if ( empty( $post_author_firstname ) ) {
		$post_author_firstname = '';
	}
	$expressions = yoimg_get_all_translations( YOIMG_AUTHOR_FIRSTNAME_EXPRESSION_EN_US );
	foreach ( $expressions as $expression ) {
		if ( strpos( $result, $expression ) !== FALSE ) {
			$result = str_replace( $expression, $post_author_firstname, $result );
		}
	}
	$post_author_lastname = get_the_author_meta( 'last_name', $post_author_id );
	if ( empty( $post_author_lastname ) ) {
		$post_author_lastname = '';
	}
	$expressions = yoimg_get_all_translations( YOIMG_AUTHOR_LASTNAME_EXPRESSION_EN_US );
	foreach ( $expressions as $expression ) {
		if ( strpos( $result, $expression ) !== FALSE ) {
			$result = str_replace( $expression, $post_author_lastname, $result );
		}
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expressions_author', 10, 3);

function yoimg_seo_expression_title( $result, $attachment, $parent ) {
	$expressions = yoimg_get_all_translations( YOIMG_TITLE_EXPRESSION_EN_US );
	foreach ( $expressions as $expression ) {
		if ( strpos( $result, $expression ) !== FALSE ) {
			$result = str_replace( $expression, $parent->post_title, $result );
		}
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expression_title', 10, 3);

function yoimg_seo_expression_post_type( $result, $attachment, $parent ) {
	$expressions = yoimg_get_all_translations( YOIMG_POST_TYPE_EXPRESSION_EN_US );
	foreach ( $expressions as $expression ) {
		if ( strpos( $result, $expression ) !== FALSE ) {
			$result = str_replace( $expression, $parent->post_type, $result );
		}
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expression_post_type', 10, 3);

function yoimg_seo_expression_site_name( $result, $attachment, $parent ) {
	$expressions = yoimg_get_all_translations( YOIMG_SITE_NAME_EXPRESSION_EN_US );
	foreach ( $expressions as $expression ) {
		if ( strpos( $result, $expression ) !== FALSE ) {
			$result = str_replace( $expression, get_bloginfo( 'name' ), $result );
		}
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expression_site_name', 10, 3);

function yoimg_seo_expression_tags( $result, $attachment, $parent ) {
	$expressions = yoimg_get_all_translations( YOIMG_TAGS_EXPRESSION_EN_US );
	foreach ( $expressions as $expression ) {
		if ( strpos( $result, $expression ) !== FALSE ) {
			$tags_str = '';
			$posttags = get_the_tags( $parent->ID );
			if ( $posttags ) {
				foreach( $posttags as $tag ) {
					$tags_str = $tags_str . $tag->name . ' ';
				}
				$tags_str = trim( $tags_str );
			}
			$result = str_replace( $expression, $tags_str, $result );
		}
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expression_tags', 10, 3);

function yoimg_seo_expression_categories( $result, $attachment, $parent ) {
	$expressions = yoimg_get_all_translations( YOIMG_CATEGORIES_EXPRESSION_EN_US );
	foreach ( $expressions as $expression ) {
		if ( strpos( $result, $expression ) !== FALSE ) {
			$cats_str = '';
			$cats = get_the_category( $parent->ID );
			if ( $cats ) {
				foreach( $cats as $cat ) {
					$cats_str = $cats_str . $cat->cat_name . ' ';
				}
				$cats_str = trim( $cats_str );
			}
			$result = str_replace( $expression, $cats_str, $result );
		}
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expression_categories', 10, 3);

function yoimg_imgseo_get_image_title( $attachment, $parent ) {
	$base_title = yoimg_imgseo_explode_expression( YOIMG_IMGSEO_IMAGE_TITLE_EXPRESSION, $attachment, $parent );
	$title = $base_title;
	$count = 1;
	$other = get_page_by_title( $title, 'OBJECT', 'attachment' );
	while ( $other ) {
		$title = $base_title . ' ' . $count;
		$other = get_page_by_title( $title, 'OBJECT', 'attachment' );
		$count++;
	}
	return $title;
}

function yoimg_imgseo_get_image_alt( $attachment, $parent ) {
	$base_alt = yoimg_imgseo_explode_expression( YOIMG_IMGSEO_IMAGE_ALT_EXPRESSION, $attachment, $parent );
	$alt = $base_alt;
	$count = 1;
	$args = array(
		'post_type' => 'attachment',
		'post_status' => 'any',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key' => '_wp_attachment_image_alt',
				'value' => $alt
			)
		)
	);
	$query = new WP_Query( $args );
	while ( $query->post_count > 0 ) {
		$alt = $base_alt . ' ' . $count;
		$args['meta_query'][0]['value'] = $alt;
		$query = new WP_Query( $args );
		$count++;
	}	
	return $alt;
}

function yoimg_imgseo_get_image_filename( $attachment, $parent ) {
	$filename = yoimg_imgseo_explode_expression( YOIMG_IMGSEO_IMAGE_FILENAME_EXPRESSION, $attachment, $parent );
	return $filename;
}
