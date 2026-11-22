<?php
/**
 * Script and style enqueuing.
 * Modular, clean, production-level architecture.
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
	$css_ver  = file_exists( $css_file ) ? (string) filemtime( $css_file ) : '1.2.0';
	$js_ver   = file_exists( $js_file ) ? (string) filemtime( $js_file ) : '1.2.0';

	// 1. Primary theme stylesheet (Globals, Reset, Header, Navigation, Footer).
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

	// 2. Modular Home Sections (Enqueued conditionally on the front page or homepage template).
	$is_home_screen = is_front_page()
		|| is_home()
		|| is_page_template( 'page-templates/template-home.php' )
		|| is_page( 'home' );

	if ( $is_home_screen ) {
		$home_sections = array(
			'hero'        => 'section-hero.css',
			'usp'         => 'section-usp-strip.css',
			'legacy'      => 'section-legacy.css',
			'collections' => 'section-collections.css',
			'finder'      => 'section-finder.css',
			'technology'  => 'section-technology.css',
			'products'    => 'section-products.css',
			'hospitality' => 'section-hospitality.css',
			'locator'     => 'section-locator.css',
		);

		foreach ( $home_sections as $sec_key => $sec_file ) {
			$file_path = get_template_directory() . '/assets/css/home/' . $sec_file;
			if ( file_exists( $file_path ) ) {
				wp_enqueue_style(
					'goodnite-home-' . $sec_key,
					get_template_directory_uri() . '/assets/css/home/' . $sec_file,
					array( 'goodnite-theme' ),
					(string) filemtime( $file_path )
				);
			}
		}
	}

	// 3. Modular Product Page Sections (Enqueued conditionally only on PDP & product preview).
	$is_pdp = is_singular( 'product' )
		|| is_page_template( 'page-templates/template-product.php' )
		|| is_page( 'kayler' )
		|| ( function_exists( 'is_product' ) && is_product() );

	if ( $is_pdp ) {
		$pdp_sections = array(
			'hero'            => 'hero.css',
			'construction'    => 'construction.css',
			'technologies'    => 'technologies.css',
			'recommendations' => 'recommendations.css',
			'faq'             => 'faq.css',
			'dialogs'         => 'dialogs.css',
		);

		foreach ( $pdp_sections as $pdp_key => $pdp_file ) {
			$file_path = get_template_directory() . '/assets/css/product/' . $pdp_file;
			if ( file_exists( $file_path ) ) {
				wp_enqueue_style(
					'goodnite-pdp-' . $pdp_key,
					get_template_directory_uri() . '/assets/css/product/' . $pdp_file,
					array( 'goodnite-theme' ),
					(string) filemtime( $file_path )
				);
			}
		}

		$prod_js = get_template_directory() . '/assets/js/product.js';
		if ( file_exists( $prod_js ) ) {
			wp_enqueue_script(
				'goodnite-product',
				get_template_directory_uri() . '/assets/js/product.js',
				array( 'goodnite-theme' ),
				(string) filemtime( $prod_js ),
				true
			);
			wp_script_add_data( 'goodnite-product', 'strategy', 'defer' );
		}
	}

	// 4. Collection Page (PLP) & WooCommerce Catalog Archives.
	$is_col = ( function_exists( 'goodnite_is_collection_page' ) && goodnite_is_collection_page() )
		|| ( function_exists( 'is_shop' ) && is_shop() )
		|| ( function_exists( 'is_product_category' ) && is_product_category() )
		|| ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() );

	if ( $is_col ) {
		$col_css = get_template_directory() . '/assets/css/collection.css';
		$col_js  = get_template_directory() . '/assets/js/collection.js';

		if ( file_exists( $col_css ) ) {
			wp_enqueue_style(
				'goodnite-collection',
				get_template_directory_uri() . '/assets/css/collection.css',
				array( 'goodnite-theme' ),
				(string) filemtime( $col_css )
			);
		}

		if ( file_exists( $col_js ) ) {
			wp_enqueue_script(
				'goodnite-collection',
				get_template_directory_uri() . '/assets/js/collection.js',
				array( 'goodnite-theme' ),
				(string) filemtime( $col_js ),
				true
			);
			wp_script_add_data( 'goodnite-collection', 'strategy', 'defer' );
		}
	}

	// 5. Policy Page Template.
	$is_policy = is_page_template( 'page-templates/template-policy.php' )
		|| is_page_template( 'page-policy.php' )
		|| is_page( array( 'terms', 'privacy', 'privacy-policy', 'shipping-delivery', 'returns-refunds', 'warranty' ) );

	if ( $is_policy ) {
		$pol_css = get_template_directory() . '/assets/css/policy.css';
		$pol_js  = get_template_directory() . '/assets/js/policy.js';

		if ( file_exists( $pol_css ) ) {
			wp_enqueue_style(
				'goodnite-policy',
				get_template_directory_uri() . '/assets/css/policy.css',
				array( 'goodnite-theme' ),
				(string) filemtime( $pol_css )
			);
		}

		if ( file_exists( $pol_js ) ) {
			wp_enqueue_script(
				'goodnite-policy',
				get_template_directory_uri() . '/assets/js/policy.js',
				array( 'goodnite-theme' ),
				(string) filemtime( $pol_js ),
				true
			);
			wp_script_add_data( 'goodnite-policy', 'strategy', 'defer' );
		}
	}

	// 6. Contact Us Page.
	$is_contact = is_page_template( 'page-templates/template-contact.php' )
		|| is_page_template( 'page-contact.php' )
		|| is_page( array( 'contact', 'contact-us' ) );

	if ( $is_contact ) {
		$cnt_css = get_template_directory() . '/assets/css/contact.css';
		$cnt_js  = get_template_directory() . '/assets/js/contact.js';

		if ( file_exists( $cnt_css ) ) {
			wp_enqueue_style(
				'goodnite-contact',
				get_template_directory_uri() . '/assets/css/contact.css',
				array( 'goodnite-theme' ),
				(string) filemtime( $cnt_css )
			);
		}

		if ( file_exists( $cnt_js ) ) {
			wp_enqueue_script(
				'goodnite-contact',
				get_template_directory_uri() . '/assets/js/contact.js',
				array( 'goodnite-theme' ),
				(string) filemtime( $cnt_js ),
				true
			);
			wp_script_add_data( 'goodnite-contact', 'strategy', 'defer' );

			wp_localize_script(
				'goodnite-contact',
				'goodnite_contact_params',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'goodnite_contact_action' ),
				)
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'goodnite_enqueue_assets' );
