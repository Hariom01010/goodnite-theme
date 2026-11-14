<?php
/**
 * Flagship Hero section with dynamic SCF repeater slides and copy.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Resolve Dynamic Content from SCF (Secure Custom Fields).
$post_id     = get_queried_object_id();
$hero_title  = '';
$hero_body   = '';
$hero_slides = array();

if ( function_exists( 'get_field' ) ) {
	$hero_title = (string) get_field( 'hero_title', $post_id );
	$hero_body  = (string) get_field( 'hero_body', $post_id );

	// Fetch Repeater Slides.
	$acf_slides = get_field( 'hero_slides', $post_id );
	if ( ! empty( $acf_slides ) && is_array( $acf_slides ) ) {
		foreach ( $acf_slides as $slide ) {
			$img_url = '';
			if ( is_array( $slide['image'] ?? null ) && ! empty( $slide['image']['url'] ) ) {
				$img_url = (string) $slide['image']['url'];
			} elseif ( is_numeric( $slide['image'] ?? null ) ) {
				$img_url = (string) wp_get_attachment_image_url( (int) $slide['image'], 'full' );
			} elseif ( is_string( $slide['image'] ?? null ) ) {
				$img_url = (string) $slide['image'];
			}

			if ( ! empty( $img_url ) ) {
				$alt_text = '';
				if ( ! empty( $slide['alt_text'] ) ) {
					$alt_text = (string) $slide['alt_text'];
				} elseif ( is_array( $slide['image'] ?? null ) && ! empty( $slide['image']['alt'] ) ) {
					$alt_text = (string) $slide['image']['alt'];
				}

				$hero_slides[] = array(
					'image' => $img_url,
					'alt'   => ! empty( $alt_text ) ? $alt_text : $hero_title,
				);
			}
		}
	}
}

// 2. Safe Fallbacks if fields are empty.
if ( empty( $hero_title ) ) {
	$hero_title = (string) goodnite_mod( 'goodnite_hero_title', 'Sleep Beyond Ordinary.' );
}

if ( empty( $hero_body ) ) {
	$hero_body = (string) goodnite_mod( 'goodnite_hero_body', 'Discover a world of comfort shaped by international sleep expertise, thoughtful innovation and the way you sleep.' );
}

$primary_label = function_exists( 'get_field' ) && get_field( 'hero_primary_cta_label', $post_id )
	? (string) get_field( 'hero_primary_cta_label', $post_id )
	: (string) goodnite_mod( 'goodnite_hero_primary_label', 'Explore Mattresses' );

$primary_url = function_exists( 'get_field' ) && get_field( 'hero_primary_cta_url', $post_id )
	? (string) get_field( 'hero_primary_cta_url', $post_id )
	: (string) goodnite_mod( 'goodnite_hero_primary_url', '/mattresses' );

$secondary_label = function_exists( 'get_field' ) && get_field( 'hero_secondary_cta_label', $post_id )
	? (string) get_field( 'hero_secondary_cta_label', $post_id )
	: (string) goodnite_mod( 'goodnite_hero_secondary_label', 'Find Your Mattress' );

$secondary_url = function_exists( 'get_field' ) && get_field( 'hero_secondary_cta_url', $post_id )
	? (string) get_field( 'hero_secondary_cta_url', $post_id )
	: (string) goodnite_mod( 'goodnite_hero_secondary_url', '/find-your-mattress' );

if ( empty( $hero_slides ) ) {
	$hero_slides = array(
		array(
			'image' => goodnite_asset_url( 'images/goodnite-hero-desktop.webp' ),
			'alt'   => $hero_title,
		),
	);
}

$has_multiple_slides = count( $hero_slides ) > 1;
?>
<section class="hero" aria-labelledby="hero-heading" data-hero-slider>
	<div class="hero-image-slider" aria-label="<?php esc_attr_e( 'Hero gallery slides', 'goodnite' ); ?>">
		<?php foreach ( $hero_slides as $index => $slide ) : ?>
			<div class="hero-image-slider__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" style="background-image: url('<?php echo esc_url( $slide['image'] ); ?>');" role="img" aria-label="<?php echo esc_attr( $slide['alt'] ); ?>"></div>
		<?php endforeach; ?>
	</div>

	<div class="container-shell hero-content">
		<div class="hero-copy">
			<h1 id="hero-heading"><?php echo esc_html( $hero_title ); ?></h1>
			<p><?php echo esc_html( $hero_body ); ?></p>
			<div class="hero-actions">
				<?php
				goodnite_button( $primary_label, $primary_url, 'primary' );
				goodnite_button( $secondary_label, $secondary_url, 'secondary-light' );
				?>
			</div>
		</div>
	</div>

	<?php if ( $has_multiple_slides ) : ?>
		<div class="hero-slider-controls">
			<button class="hero-slider-arrow hero-slider-prev focus-ring" type="button" aria-label="<?php esc_attr_e( 'Previous hero image', 'goodnite' ); ?>" data-hero-prev>
				<?php echo goodnite_icon( 'chevron-left', 20 ); ?>
			</button>
			<div class="hero-slider-dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose hero image', 'goodnite' ); ?>">
				<?php foreach ( $hero_slides as $index => $slide ) : ?>
					<button class="hero-slider-dot<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" role="tab" aria-label="<?php echo esc_attr( sprintf( __( 'Show image %d', 'goodnite' ), $index + 1 ) ); ?>" aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>" data-slide-index="<?php echo esc_attr( (string) $index ); ?>"></button>
				<?php endforeach; ?>
			</div>
			<button class="hero-slider-arrow hero-slider-next focus-ring" type="button" aria-label="<?php esc_attr_e( 'Next hero image', 'goodnite' ); ?>" data-hero-next>
				<?php echo goodnite_icon( 'chevron-right', 20 ); ?>
			</button>
		</div>
	<?php endif; ?>
</section>
