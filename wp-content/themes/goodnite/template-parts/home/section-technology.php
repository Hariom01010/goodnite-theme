<?php
/**
 * Technology pillars section with dynamic SCF support.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

$tech_title = function_exists( 'get_field' ) && get_field( 'tech_title', $post_id )
	? (string) get_field( 'tech_title', $post_id )
	: 'Inside Goodnite Technology.';

$tech_cta_label = function_exists( 'get_field' ) && get_field( 'tech_cta_label', $post_id )
	? (string) get_field( 'tech_cta_label', $post_id )
	: 'Explore Technologies';

$tech_cta_url = function_exists( 'get_field' ) && get_field( 'tech_cta_url', $post_id )
	? (string) get_field( 'tech_cta_url', $post_id )
	: '/sleep-technology';

$technology_pillars = array();
if ( function_exists( 'get_field' ) ) {
	$scf_pillars = get_field( 'tech_pillars', $post_id );
	if ( ! empty( $scf_pillars ) && is_array( $scf_pillars ) ) {
		foreach ( $scf_pillars as $idx => $p ) {
			$img_url = '';
			if ( is_array( $p['image'] ?? null ) && ! empty( $p['image']['url'] ) ) {
				$img_url = (string) $p['image']['url'];
			} elseif ( is_numeric( $p['image'] ?? null ) ) {
				$img_url = (string) wp_get_attachment_image_url( (int) $p['image'], 'full' );
			} elseif ( is_string( $p['image'] ?? null ) ) {
				$img_url = (string) $p['image'];
			}

			if ( ! empty( $p['title'] ) ) {
				$technology_pillars[] = array(
					'eyebrow'     => sprintf( '%02d', $idx + 1 ),
					'title'       => (string) $p['title'],
					'description' => (string) ( $p['description'] ?? '' ),
					'image_url'   => $img_url,
					'alt'         => (string) ( $p['alt_text'] ?? $p['title'] ),
				);
			}
		}
	}
}

// Fallback pillars.
if ( empty( $technology_pillars ) ) {
	$technology_pillars = array(
		array(
			'eyebrow'     => '01',
			'title'       => 'Our Fabric',
			'description' => 'The sleep surface is where comfort begins. Goodnite’s fabric technologies are designed around cooling, softness, protection and everyday sleep comfort.',
			'image_url'   => goodnite_asset_url( 'images/goodnite-technology-fabric.webp' ),
			'alt'         => 'Close-up of premium quilted mattress fabric',
		),
		array(
			'eyebrow'     => '02',
			'title'       => 'Our Coil System',
			'description' => 'Beneath the surface, Goodnite’s coil systems are engineered to deliver different kinds of support, responsiveness and movement control.',
			'image_url'   => goodnite_asset_url( 'images/goodnite-technology-coil.webp' ),
			'alt'         => 'Premium mattress pocket coil system construction',
		),
		array(
			'eyebrow'     => '03',
			'title'       => 'Our Material',
			'description' => 'Goodnite combines carefully selected comfort materials to create different sleep sensations, from contouring support to naturally responsive comfort.',
			'image_url'   => goodnite_asset_url( 'images/goodnite-technology-material.webp' ),
			'alt'         => 'Layered mattress comfort materials in neutral tones',
		),
	);
}
?>
<section class="section gn-tech-pillars" aria-labelledby="technology-heading">
	<div class="container-shell">
		<div class="gn-tech-pillars__intro reveal">
			<div>
				<h2 id="technology-heading"><?php echo esc_html( $tech_title ); ?></h2>
			</div>
			<a class="button-link text gn-tech-pillars__intro-cta focus-ring" href="<?php echo esc_url( goodnite_url( $tech_cta_url ) ); ?>"><?php echo esc_html( $tech_cta_label ); ?> <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a>
		</div>

		<div class="gn-tech-pillars__grid" data-tech-grid>
			<?php foreach ( $technology_pillars as $pillar ) : ?>
				<article class="gn-tech-pillars__card reveal">
					<div class="gn-tech-pillars__image">
						<img src="<?php echo esc_url( $pillar['image_url'] ); ?>" alt="<?php echo esc_attr( $pillar['alt'] ); ?>" loading="lazy" decoding="async" width="1600" height="760">
					</div>
					<div class="gn-tech-pillars__content">
						<h3><?php echo esc_html( $pillar['title'] ); ?></h3>
						<p><?php echo esc_html( $pillar['description'] ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<a class="button-link text gn-tech-pillars__cta focus-ring" href="<?php echo esc_url( goodnite_url( $tech_cta_url ) ); ?>"><?php echo esc_html( $tech_cta_label ); ?> <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a>
	</div>
</section>
