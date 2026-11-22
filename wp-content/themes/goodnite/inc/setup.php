<?php
/**
 * Theme setup, supports, and navigation menus.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Configure theme defaults and register support for WordPress features.
 */
function goodnite_theme_setup(): void {
	load_theme_textdomain( 'goodnite', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'woocommerce' );

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 260,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary'         => __( 'Primary Navigation', 'goodnite' ),
			'mobile_primary'  => __( 'Mobile Navigation', 'goodnite' ),
			'footer_shop'     => __( 'Footer — Shop', 'goodnite' ),
			'footer_discover' => __( 'Footer — Discover', 'goodnite' ),
			'footer_policies' => __( 'Footer — Policies', 'goodnite' ),
			'footer_social'   => __( 'Footer — Social Links', 'goodnite' ),
		)
	);

	add_image_size( 'goodnite-collection', 900, 1200, true );
	add_image_size( 'goodnite-product', 800, 1000, true );
}
add_action( 'after_setup_theme', 'goodnite_theme_setup' );

/**
 * Filter the document title separator.
 *
 * @param string $sep Default separator.
 * @return string
 */
function goodnite_document_title_separator( string $sep ): string {
	return '|';
}
add_filter( 'document_title_separator', 'goodnite_document_title_separator' );


