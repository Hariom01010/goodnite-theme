<?php
/**
 * Brand legacy story, manufacturing metrics with count-up, and story CTA.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

// 1. Resolve Dynamic Content from SCF (with clean fallbacks).
$legacy_title = '';
$legacy_body  = '';
$legacy_image_url = '';
$legacy_image_alt = '';

if ( function_exists( 'get_field' ) ) {
	$legacy_title = (string) get_field( 'legacy_title', $post_id );
	$legacy_body  = (string) get_field( 'legacy_body', $post_id );

	$img_field = get_field( 'legacy_image', $post_id );
	if ( ! empty( $img_field ) ) {
		if ( is_array( $img_field ) && ! empty( $img_field['url'] ) ) {
			$legacy_image_url = (string) $img_field['url'];
			$legacy_image_alt = (string) ( $img_field['alt'] ?? '' );
		} elseif ( is_numeric( $img_field ) ) {
			$legacy_image_url = (string) wp_get_attachment_image_url( (int) $img_field, 'full' );
			$legacy_image_alt = (string) get_post_meta( (int) $img_field, '_wp_attachment_image_alt', true );
		} elseif ( is_string( $img_field ) ) {
			$legacy_image_url = (string) $img_field;
		}
	}
}

if ( empty( $legacy_title ) ) {
	$legacy_title = (string) goodnite_mod( 'goodnite_legacy_title', 'A Legacy of Better Sleep, Since 1989' );
}

if ( empty( $legacy_body ) ) {
	$default_body = "For decades, Goodnite has brought together sleep expertise, material innovation and manufacturing craftsmanship to create comfort for people around the world.\n\nToday, that global heritage begins a new chapter in India bringing thoughtfully engineered sleep solutions to a market that deserves more than one definition of perfect sleep.";
	$legacy_body  = (string) goodnite_mod( 'goodnite_legacy_body', $default_body );
}

$cta_label = function_exists( 'get_field' ) && get_field( 'legacy_cta_label', $post_id )
	? (string) get_field( 'legacy_cta_label', $post_id )
	: (string) goodnite_mod( 'goodnite_legacy_cta_label', 'Discover Our Story' );

$cta_url = function_exists( 'get_field' ) && get_field( 'legacy_cta_url', $post_id )
	? (string) get_field( 'legacy_cta_url', $post_id )
	: (string) goodnite_mod( 'goodnite_legacy_cta_url', '/our-story' );

$m1_val   = function_exists( 'get_field' ) && get_field( 'legacy_m1_val', $post_id ) ? (string) get_field( 'legacy_m1_val', $post_id ) : '1500';
$m1_label = function_exists( 'get_field' ) && get_field( 'legacy_m1_label', $post_id ) ? (string) get_field( 'legacy_m1_label', $post_id ) : (string) goodnite_mod( 'goodnite_metric_1_label', 'Mattresses per day manufacturing capacity' );

$m2_val   = function_exists( 'get_field' ) && get_field( 'legacy_m2_val', $post_id ) ? (string) get_field( 'legacy_m2_val', $post_id ) : (string) goodnite_mod( 'goodnite_metric_2_val', '5 M+' );
$m2_label = function_exists( 'get_field' ) && get_field( 'legacy_m2_label', $post_id ) ? (string) get_field( 'legacy_m2_label', $post_id ) : (string) goodnite_mod( 'goodnite_metric_2_label', 'Mattresses Sold Worldwide' );

$m3_val   = function_exists( 'get_field' ) && get_field( 'legacy_m3_val', $post_id ) ? (string) get_field( 'legacy_m3_val', $post_id ) : (string) goodnite_mod( 'goodnite_metric_3_val', '15+' );
$m3_label = function_exists( 'get_field' ) && get_field( 'legacy_m3_label', $post_id ) ? (string) get_field( 'legacy_m3_label', $post_id ) : (string) goodnite_mod( 'goodnite_metric_3_label', 'Countries' );

$m1_numeric = preg_replace( '/[^0-9]/', '', $m1_val );
$m1_numeric = ! empty( $m1_numeric ) ? $m1_numeric : '1500';

if ( empty( $legacy_image_url ) ) {
	$legacy_image_url = goodnite_asset_url( 'images/goodnite-brand-craftsmanship.webp' );
	$legacy_image_alt = 'Close detail of quilted mattress upholstery and tailored side stitching';
}
?>
<section class="section legacy" aria-labelledby="legacy-heading" data-section="legacy">
	<div class="container-shell legacy-grid">
		<div class="reveal legacy-image">
			<picture class="goodnite-picture">
				<source media="(max-width: 600px)" srcset="<?php echo esc_url( $legacy_image_url ); ?>">
				<img src="<?php echo esc_url( $legacy_image_url ); ?>" alt="<?php echo esc_attr( ! empty( $legacy_image_alt ) ? $legacy_image_alt : $legacy_title ); ?>" width="1122" height="1402" loading="lazy" decoding="async">
			</picture>
		</div>

		<div class="reveal legacy-copy" style="--reveal-delay:80ms">
			<h2 id="legacy-heading">
				<?php echo esc_html( $legacy_title ); ?>
			</h2>

			<p>
				<?php echo nl2br( esc_html( $legacy_body ) ); ?>
			</p>

			<a class="button-link text legacy-cta focus-ring" href="<?php echo esc_url( goodnite_url( $cta_url ) ); ?>">
				<?php echo esc_html( $cta_label ); ?> <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?>
			</a>

			<div class="metrics" data-metrics-block>
				<div class="metric-item">
					<strong data-counter-target="<?php echo esc_attr( $m1_numeric ); ?>" data-counter-suffix="">0</strong>
					<span class="metric-label"><?php echo esc_html( $m1_label ); ?></span>
				</div>
				<div class="metric-item">
					<strong><?php echo esc_html( $m2_val ); ?></strong>
					<span class="metric-label"><?php echo esc_html( $m2_label ); ?></span>
				</div>
				<div class="metric-item">
					<strong><?php echo esc_html( $m3_val ); ?></strong>
					<span class="metric-label"><?php echo esc_html( $m3_label ); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>
