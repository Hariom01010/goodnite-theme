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

// Collect slides from SCF repeater or single image, fallback to default prototype images
$slides = array();
if ( function_exists( 'get_field' ) ) {
	$scf_slides = get_field( 'hero_slides', $post_id );
	if ( ! empty( $scf_slides ) && is_array( $scf_slides ) ) {
		foreach ( $scf_slides as $s ) {
			$url = goodnite_resolve_image_url( $s['image'] ?? null );
			if ( $url ) {
				$slides[] = array(
					'url' => $url,
					'alt' => ! empty( $s['alt_text'] ) ? (string) $s['alt_text'] : $hero_title,
				);
			}
		}
	}
	if ( empty( $slides ) ) {
		$single_img = goodnite_resolve_image_url( get_field( 'hero_image', $post_id ) );
		if ( $single_img ) {
			$slides[] = array(
				'url' => $single_img,
				'alt' => $hero_title,
			);
		}
	}
}

if ( empty( $slides ) ) {
	$slides = array(
		array(
			'url' => goodnite_asset_url( 'images/goodnite-hero-desktop.webp' ),
			'alt' => $hero_alt,
		),
		array(
			'url' => goodnite_asset_url( 'images/goodnite-collection-dreamer.webp' ),
			'alt' => 'Dreamer Collection Mattress',
		),
		array(
			'url' => goodnite_asset_url( 'images/goodnite-collection-love.webp' ),
			'alt' => 'Love Collection Mattress',
		),
	);
}
?>
<section class="gn-home-hero hero" data-section="hero" aria-labelledby="hero-heading">
	<div class="hero-image-slider" aria-label="<?php esc_attr_e( 'Hero images', 'goodnite' ); ?>">
		<?php foreach ( $slides as $i => $slide ) : ?>
			<div class="hero-image-slider__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" style="background-image: url('<?php echo esc_url( $slide['url'] ); ?>');" role="img" aria-label="<?php echo esc_attr( $slide['alt'] ); ?>"></div>
		<?php endforeach; ?>
	</div>
	<div class="container-shell hero-content"><div class="hero-copy">
		<h1 id="hero-heading"><?php echo esc_html( $hero_title ); ?></h1>
		<p><?php echo esc_html( $hero_body ); ?></p>
		<div class="hero-actions"><?php goodnite_button( $hero_primary_lbl, $hero_primary_url ); ?><?php goodnite_button( $hero_sec_lbl, $hero_sec_url, 'secondary-light' ); ?></div>
	</div></div>
	<span class="hero-note"><?php echo esc_html( goodnite_mod( 'goodnite_hero_note', 'Campaign imagery · provisional' ) ); ?></span>
</section>
