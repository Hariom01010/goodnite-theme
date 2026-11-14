<?php
/**
 * Script and style enqueuing.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue styles and scripts for front-end.
 */
function goodnite_enqueue_assets(): void {
	$css_file = get_template_directory() . '/assets/css/theme.css';
	$js_file  = get_template_directory() . '/assets/js/theme.js';
	$css_ver  = file_exists( $css_file ) ? (string) filemtime( $css_file ) : '1.1.0';
	$js_ver   = file_exists( $js_file ) ? (string) filemtime( $js_file ) : '1.1.0';

	// Primary theme stylesheet.
	wp_enqueue_style(
		'goodnite-theme',
		get_template_directory_uri() . '/assets/css/theme.css',
		array(),
		$css_ver
	);

	// Primary modular JavaScript.
	wp_enqueue_script(
		'goodnite-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		$js_ver,
		true
	);
	wp_script_add_data( 'goodnite-theme', 'strategy', 'defer' );

	// Enqueue collection assets on collection pages.
	if ( function_exists( 'goodnite_is_collection_page' ) && goodnite_is_collection_page() ) {
		$col_css = get_template_directory() . '/assets/css/collection.css';
		$col_js  = get_template_directory() . '/assets/js/collection.js';

		wp_enqueue_style(
			'goodnite-collection',
			get_template_directory_uri() . '/assets/css/collection.css',
			array( 'goodnite-theme' ),
			file_exists( $col_css ) ? (string) filemtime( $col_css ) : '1.0.0'
		);

		wp_enqueue_script(
			'goodnite-collection',
			get_template_directory_uri() . '/assets/js/collection.js',
			array( 'goodnite-theme' ),
			file_exists( $col_js ) ? (string) filemtime( $col_js ) : '1.0.0',
			true
		);
		wp_script_add_data( 'goodnite-collection', 'strategy', 'defer' );
	}
}
add_action( 'wp_enqueue_scripts', 'goodnite_enqueue_assets' );
