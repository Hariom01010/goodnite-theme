<?php
/**
 * Technology pillars section with dynamic SCF support.
 * Matches Goodnite Website/Homepage with 3 full-bleed visual cards and indicators.
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

// 1. Resolve from SCF repeater if populated (supports tech_cards and legacy tech_pillars).
if ( function_exists( 'get_field' ) ) {
	$scf_cards = get_field( 'tech_cards', $post_id ) ?: get_field( 'tech_pillars', $post_id );
	if ( ! empty( $scf_cards ) && is_array( $scf_cards ) ) {
		foreach ( $scf_cards as $p ) {
			if ( empty( $p['title'] ) ) {
				continue;
			}

			$img_url = '';
			if ( ! empty( $p['image'] ) ) {
				if ( is_array( $p['image'] ) && ! empty( $p['image']['url'] ) ) {
					$img_url = (string) $p['image']['url'];
				} elseif ( is_numeric( $p['image'] ) ) {
					$img_url = (string) wp_get_attachment_image_url( (int) $p['image'], 'full' );
				} elseif ( is_string( $p['image'] ) ) {
					$img_url = (string) $p['image'];
				}
			}

			$subtitle = ! empty( $p['subtitle'] ) ? (string) $p['subtitle'] : ( ! empty( $p['microcopy'] ) ? (string) $p['microcopy'] : '' );

			$technology_pillars[] = array(
				'title'       => (string) $p['title'],
				'subtitle'    => $subtitle,
				'description' => (string) ( $p['description'] ?? '' ),
				'image_url'   => $img_url,
				'alt'         => (string) ( $p['alt_text'] ?? $p['title'] ),
			);
		}
	}
}

// 2. Fallback 3 pillars matching Goodnite Website/Homepage.
if ( empty( $technology_pillars ) ) {
	$technology_pillars = array(
		array(
			'title'       => 'Our Fabric',
			'subtitle'    => 'Includes anti-static, cooling, protective and comfort-focused fabric technologies.',
			'description' => 'The sleep surface is where comfort begins. Goodnite’s fabric technologies are designed around cooling, softness, protection and everyday sleep comfort.',
			'image_url'   => goodnite_asset_url( 'images/goodnite-technology-fabric-v2.png' ),
			'alt'         => 'Close-up of premium quilted mattress fabric',
		),
		array(
			'title'       => 'Our Coil System',
			'subtitle'    => 'From traditional spring support to independently responsive pocketed systems.',
			'description' => 'Beneath the surface, Goodnite’s coil systems are engineered to deliver different kinds of support, responsiveness and movement control.',
			'image_url'   => goodnite_asset_url( 'images/goodnite-technology-coil-v2.png' ),
			'alt'         => 'Premium mattress pocket coil system construction',
		),
		array(
			'title'       => 'Our Material',
			'subtitle'    => 'Materials chosen for comfort, breathability, responsiveness and support.',
			'description' => 'Goodnite combines carefully selected comfort materials to create different sleep sensations, from contouring support to naturally responsive comfort.',
			'image_url'   => goodnite_asset_url( 'images/goodnite-technology-material-v2.png' ),
			'alt'         => 'Layered mattress comfort materials in neutral tones',
		),
	);
}
?>
<section class="gn-home-technology section gn-tech-pillars" data-section="technology-pillars" aria-labelledby="technology-heading">
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
						<?php if ( ! empty( $pillar['subtitle'] ) ) : ?>
							<small><?php echo esc_html( $pillar['subtitle'] ); ?></small>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="gn-tech-pillars__indicators" data-tech-indicators role="tablist" aria-label="<?php esc_attr_e( 'Choose technology pillar', 'goodnite' ); ?>">
			<?php foreach ( $technology_pillars as $index => $pillar ) : ?>
				<button type="button" role="tab" aria-label="<?php echo esc_attr( sprintf( __( 'Show %s', 'goodnite' ), $pillar['title'] ) ); ?>" aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>" class="<?php echo 0 === $index ? 'is-active' : ''; ?>" data-tech-indicator="<?php echo esc_attr( (string) $index ); ?>"></button>
			<?php endforeach; ?>
		</div>

		<a class="button-link text gn-tech-pillars__cta focus-ring" href="<?php echo esc_url( goodnite_url( $tech_cta_url ) ); ?>"><?php echo esc_html( $tech_cta_label ); ?> <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a>
	</div>
</section>
