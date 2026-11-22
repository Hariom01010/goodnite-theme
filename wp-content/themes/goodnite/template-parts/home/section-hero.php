<?php
/**
 * Hero section.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id          = get_queried_object_id();
$hero_title       = function_exists( 'get_field' ) ? (string) get_field( 'hero_title', $post_id ) : '';
$hero_title       = $hero_title ?: goodnite_mod( 'goodnite_hero_title', 'Sleep Beyond Ordinary.' );

$hero_body        = function_exists( 'get_field' ) ? (string) get_field( 'hero_body', $post_id ) : '';
$hero_body        = $hero_body ?: goodnite_mod( 'goodnite_hero_body', 'Discover a world of comfort shaped by international sleep expertise, thoughtful innovation and the way you sleep.' );

$hero_primary_lbl = function_exists( 'get_field' ) ? (string) get_field( 'hero_primary_cta_label', $post_id ) : '';
$hero_primary_lbl = $hero_primary_lbl ?: goodnite_mod( 'goodnite_hero_primary_label', 'Explore Mattresses' );

$hero_primary_url = function_exists( 'get_field' ) ? (string) get_field( 'hero_primary_cta_url', $post_id ) : '';
$hero_primary_url = $hero_primary_url ?: goodnite_mod( 'goodnite_hero_primary_url', '/mattresses' );

$hero_sec_lbl     = function_exists( 'get_field' ) ? (string) get_field( 'hero_secondary_cta_label', $post_id ) : '';
$hero_sec_lbl     = $hero_sec_lbl ?: goodnite_mod( 'goodnite_hero_secondary_label', 'Find Your Mattress' );

$hero_sec_url     = function_exists( 'get_field' ) ? (string) get_field( 'hero_secondary_cta_url', $post_id ) : '';
$hero_sec_url     = $hero_sec_url ?: goodnite_mod( 'goodnite_hero_secondary_url', '/find-your-mattress' );

$hero_alt         = goodnite_mod( 'goodnite_hero_alt', 'Contemporary bedroom with an ivory upholstered king bed in warm morning light' );
?>
<section class="gn-home-hero hero" data-section="hero" aria-labelledby="hero-heading">
	<div class="hero-media"><?php goodnite_picture( 'goodnite_hero_image_desktop', 'goodnite_hero_image_mobile', 'goodnite-hero-desktop.webp', 'goodnite-hero-mobile.webp', $hero_alt, '', true ); ?></div>
	<div class="container-shell hero-content"><div class="hero-copy">
		<h1 id="hero-heading"><?php echo esc_html( $hero_title ); ?></h1>
		<p><?php echo esc_html( $hero_body ); ?></p>
		<div class="hero-actions"><?php goodnite_button( $hero_primary_lbl, $hero_primary_url ); ?><?php goodnite_button( $hero_sec_lbl, $hero_sec_url, 'secondary-light' ); ?></div>
	</div></div>
	<span class="hero-note"><?php echo esc_html( goodnite_mod( 'goodnite_hero_note', 'Campaign imagery · provisional' ) ); ?></span>
</section>
