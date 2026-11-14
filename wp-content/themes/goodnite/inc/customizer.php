<?php
/**
 * Native WordPress Customizer controls and sanitization.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Goodnite Customizer panels, sections, and controls.
 *
 * @param WP_Customize_Manager $wp_customize Manager instance.
 */
function goodnite_customize_register( WP_Customize_Manager $wp_customize ): void {
	// Sections.
	$wp_customize->add_section( 'goodnite_brand', array( 'title' => __( 'Goodnite — Announcement & Header', 'goodnite' ), 'priority' => 30 ) );
	$wp_customize->add_section( 'goodnite_hero', array( 'title' => __( 'Goodnite — Hero Section', 'goodnite' ), 'priority' => 31 ) );
	$wp_customize->add_section( 'goodnite_legacy', array( 'title' => __( 'Goodnite — Legacy Story & Metrics', 'goodnite' ), 'priority' => 32 ) );
	$wp_customize->add_section( 'goodnite_sections', array( 'title' => __( 'Goodnite — Collections & Finder', 'goodnite' ), 'priority' => 33 ) );
	$wp_customize->add_section( 'goodnite_hospitality', array( 'title' => __( 'Goodnite — Hospitality', 'goodnite' ), 'priority' => 34 ) );
	$wp_customize->add_section( 'goodnite_store', array( 'title' => __( 'Goodnite — Store Locator', 'goodnite' ), 'priority' => 35 ) );
	$wp_customize->add_section( 'goodnite_footer', array( 'title' => __( 'Goodnite — Footer', 'goodnite' ), 'priority' => 36 ) );

	$fields = array(
		// Announcement.
		array( 'goodnite_announcement', 'goodnite_brand', 'Announcement Bar Text', 'GLOBAL LUXURY SLEEP · EST. 1989' ),

		// Hero.
		array( 'goodnite_hero_title', 'goodnite_hero', 'Hero Heading', 'Sleep Beyond Ordinary.' ),
		array( 'goodnite_hero_body', 'goodnite_hero', 'Hero Body Copy', 'Discover a world of comfort shaped by international sleep expertise, thoughtful innovation and the way you sleep.', 'textarea' ),
		array( 'goodnite_hero_primary_label', 'goodnite_hero', 'Primary Button Label', 'Explore Mattresses' ),
		array( 'goodnite_hero_primary_url', 'goodnite_hero', 'Primary Button URL', '/mattresses', 'url' ),
		array( 'goodnite_hero_secondary_label', 'goodnite_hero', 'Secondary Button Label', 'Find Your Mattress' ),
		array( 'goodnite_hero_secondary_url', 'goodnite_hero', 'Secondary Button URL', '/find-your-mattress', 'url' ),
		array( 'goodnite_hero_alt', 'goodnite_hero', 'Hero Image Alt Text', 'Contemporary bedroom with an ivory upholstered king bed in warm morning light' ),

		// Legacy.
		array( 'goodnite_legacy_title', 'goodnite_legacy', 'Legacy Heading', 'A Legacy of Better Sleep, Since 1989' ),
		array( 'goodnite_legacy_body', 'goodnite_legacy', 'Legacy Paragraph', 'For decades, Goodnite has brought together sleep expertise, material innovation and manufacturing craftsmanship to create comfort for people around the world.<br><br>Today, that global heritage begins a new chapter in India bringing thoughtfully engineered sleep solutions to a market that deserves more than one definition of perfect sleep.', 'textarea' ),
		array( 'goodnite_legacy_cta_label', 'goodnite_legacy', 'Legacy CTA Label', 'Discover Our Story' ),
		array( 'goodnite_legacy_cta_url', 'goodnite_legacy', 'Legacy CTA URL', '/our-story', 'url' ),
		array( 'goodnite_metric_1_val', 'goodnite_legacy', 'Metric 1 Value', '1500' ),
		array( 'goodnite_metric_1_label', 'goodnite_legacy', 'Metric 1 Label', 'Mattresses per day manufacturing capacity' ),
		array( 'goodnite_metric_2_val', 'goodnite_legacy', 'Metric 2 Value', '5M+' ),
		array( 'goodnite_metric_2_label', 'goodnite_legacy', 'Metric 2 Label', 'Mattresses Sold Worldwide' ),
		array( 'goodnite_metric_3_val', 'goodnite_legacy', 'Metric 3 Value', '15+' ),
		array( 'goodnite_metric_3_label', 'goodnite_legacy', 'Metric 3 Label', 'Countries' ),

		// Collections & Finder.
		array( 'goodnite_collections_title', 'goodnite_sections', 'Collections Heading', 'Three Collections. A World of Comfort.' ),
		array( 'goodnite_collections_body', 'goodnite_sections', 'Collections Body', 'From essential engineered comfort to advanced sleep technology and our most sophisticated constructions.', 'textarea' ),
		array( 'goodnite_finder_title', 'goodnite_sections', 'Sleep Finder Heading', 'Your Sleep. Your Way.' ),
		array( 'goodnite_finder_body', 'goodnite_sections', 'Sleep Finder Intro', 'Some prefer a cooler night. Others seek firmer support, softer comfort or more natural materials. Tell us what matters to you, and discover a better place to begin.', 'textarea' ),

		// Hospitality.
		array( 'goodnite_hospitality_eyebrow', 'goodnite_hospitality', 'Hospitality Eyebrow', 'GOODNITE HOSPITALITY' ),
		array( 'goodnite_hospitality_title', 'goodnite_hospitality', 'Hospitality Heading', 'Exceptional Sleep for Exceptional Stays.' ),
		array( 'goodnite_hospitality_body', 'goodnite_hospitality', 'Hospitality Body', 'For hotels, resorts and hospitality destinations, Goodnite brings decades of international mattress expertise to the guest experience, delivering thoughtfully engineered comfort where expectations are high.', 'textarea' ),
		array( 'goodnite_hospitality_cta_label', 'goodnite_hospitality', 'Hospitality CTA Label', 'Explore Hospitality Solutions' ),
		array( 'goodnite_hospitality_cta_url', 'goodnite_hospitality', 'Hospitality CTA URL', '/hospitality', 'url' ),

		// Store locator.
		array( 'goodnite_store_title', 'goodnite_store', 'Store Locator Heading', 'Feel the Difference.' ),
		array( 'goodnite_store_body', 'goodnite_store', 'Store Locator Body', 'Find a Goodnite showroom or authorized retail partner near you.', 'textarea' ),
		array( 'goodnite_store_label', 'goodnite_store', 'Field Label', 'Enter your pincode' ),
		array( 'goodnite_store_placeholder', 'goodnite_store', 'Field Placeholder', '6-digit pincode' ),
		array( 'goodnite_store_button', 'goodnite_store', 'Button Label', 'Find Dealers' ),

		// Footer.
		array( 'goodnite_footer_tagline', 'goodnite_footer', 'Footer Brand Tagline', 'International sleep expertise. Thoughtful innovation. Comfort made personal.', 'textarea' ),
		array( 'goodnite_footer_slogan', 'goodnite_footer', 'Footer Bottom Slogan', 'GLOBAL LUXURY SLEEP' ),
	);

	foreach ( $fields as $field ) {
		goodnite_add_customizer_field( $wp_customize, $field );
	}

	// Media settings.
	$media_items = array(
		array( 'goodnite_hero_image_desktop', 'goodnite_hero', 'Hero Image — Desktop' ),
		array( 'goodnite_hero_image_mobile', 'goodnite_hero', 'Hero Image — Mobile' ),
		array( 'goodnite_legacy_image', 'goodnite_legacy', 'Brand Craftsmanship Image' ),
		array( 'goodnite_store_image', 'goodnite_store', 'Showroom Image' ),
	);

	foreach ( $media_items as $media ) {
		$wp_customize->add_setting(
			$media[0],
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				$media[0],
				array(
					'label'     => $media[2],
					'section'   => $media[1],
					'mime_type' => 'image',
				)
			)
		);
	}
}
add_action( 'customize_register', 'goodnite_customize_register' );

/**
 * Register a single customizer setting and control.
 *
 * @param WP_Customize_Manager $wp_customize Manager instance.
 * @param array                $field Field definition tuple.
 */
function goodnite_add_customizer_field( WP_Customize_Manager $wp_customize, array $field ): void {
	$id       = $field[0];
	$section  = $field[1];
	$label    = $field[2];
	$default  = $field[3];
	$type     = $field[4] ?? 'text';
	$sanitize = 'sanitize_text_field';

	if ( 'textarea' === $type ) {
		$sanitize = 'wp_kses_post';
	} elseif ( 'url' === $type ) {
		$sanitize = 'esc_url_raw';
	}

	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => $sanitize,
		)
	);

	$wp_customize->add_control(
		$id,
		array(
			'label'   => $label,
			'section' => $section,
			'type'    => $type,
		)
	);
}
