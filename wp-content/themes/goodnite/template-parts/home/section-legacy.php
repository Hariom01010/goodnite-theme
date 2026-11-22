<?php
/**
 * Brand legacy section.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id          = get_queried_object_id();
$legacy_title     = function_exists( 'get_field' ) ? (string) get_field( 'legacy_title', $post_id ) : '';
$legacy_title     = $legacy_title ?: goodnite_mod( 'goodnite_legacy_title', 'A World of Better Sleep.' );

$legacy_body      = function_exists( 'get_field' ) ? (string) get_field( 'legacy_body', $post_id ) : '';
$legacy_body      = $legacy_body ?: goodnite_mod( 'goodnite_legacy_body', 'Born in Malaysia and shaped by decades of sleep expertise, Goodnite brings a global legacy of comfort and innovation to India.' );

$legacy_cta_label = function_exists( 'get_field' ) ? (string) get_field( 'legacy_cta_label', $post_id ) : '';
$legacy_cta_label = $legacy_cta_label ?: goodnite_mod( 'goodnite_legacy_cta_label', 'Discover Our Story' );

$legacy_cta_url   = function_exists( 'get_field' ) ? (string) get_field( 'legacy_cta_url', $post_id ) : '';
$legacy_cta_url   = $legacy_cta_url ?: goodnite_mod( 'goodnite_legacy_cta_url', '/our-story' );

$scf_img          = function_exists( 'get_field' ) ? get_field( 'legacy_image', $post_id ) : null;
$scf_img_url      = is_array( $scf_img ) ? ( $scf_img['url'] ?? '' ) : ( is_string( $scf_img ) ? $scf_img : '' );
?>
<section class="gn-home-legacy section legacy" data-section="brand-legacy" aria-labelledby="legacy-heading">
	<div class="container-shell legacy-grid">
		<div class="reveal legacy-image">
			<?php if ( ! empty( $scf_img_url ) ) : ?>
				<picture class="goodnite-picture"><img src="<?php echo esc_url( $scf_img_url ); ?>" alt="<?php echo esc_attr( goodnite_mod( 'goodnite_legacy_alt', 'Close detail of quilted mattress upholstery and tailored side stitching' ) ); ?>" width="1122" height="1402" loading="lazy" decoding="async"></picture>
			<?php else : ?>
				<?php goodnite_picture( 'goodnite_legacy_image', 'goodnite_legacy_image', 'goodnite-brand-craftsmanship.webp', 'goodnite-brand-craftsmanship.webp', goodnite_mod( 'goodnite_legacy_alt', 'Close detail of quilted mattress upholstery and tailored side stitching' ) ); ?>
			<?php endif; ?>
			<span><?php echo esc_html( goodnite_mod( 'goodnite_legacy_note', 'Material study · provisional' ) ); ?></span>
		</div>
		<div class="reveal legacy-copy" style="--reveal-delay:80ms">
			<h2 id="legacy-heading"><?php echo esc_html( $legacy_title ); ?></h2>
			<p><?php echo esc_html( $legacy_body ); ?></p>
			<?php goodnite_button( $legacy_cta_label, $legacy_cta_url, 'text' ); ?>
		</div>
	</div>
</section>
