<?php
/**
 * Goodnite technology pillars section.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id    = get_queried_object_id();
$tech_title = function_exists( 'get_field' ) ? (string) get_field( 'tech_title', $post_id ) : '';
$tech_title = $tech_title ?: 'Inside Goodnite Technology.';

$tech_cta_lbl = function_exists( 'get_field' ) ? (string) get_field( 'tech_cta_label', $post_id ) : '';
$tech_cta_lbl = $tech_cta_lbl ?: 'Explore Technologies';

$tech_cta_url = function_exists( 'get_field' ) ? (string) get_field( 'tech_cta_url', $post_id ) : '';
$tech_cta_url = $tech_cta_url ?: '/sleep-technology';

$scf_cards = function_exists( 'get_field' ) ? get_field( 'tech_cards', $post_id ) : null;
$pillars   = array();

if ( ! empty( $scf_cards ) && is_array( $scf_cards ) ) {
	foreach ( $scf_cards as $c ) {
		$img_url = goodnite_resolve_image_url( $c['image'] ?? null );
		if ( ! empty( $c['title'] ) ) {
			$pillars[] = array(
				'title'       => (string) $c['title'],
				'description' => (string) ( $c['description'] ?? '' ),
				'microcopy'   => (string) ( $c['subtitle'] ?? '' ),
				'image_url'   => $img_url,
				'alt'         => (string) ( $c['title'] ?? '' ),
			);
		}
	}
}

if ( empty( $pillars ) ) {
	$pillars = array(
		array(
			'title'       => 'Our Fabric',
			'description' => 'The sleep surface is where comfort begins. Goodnite’s fabric technologies are designed around cooling, softness, protection and everyday sleep comfort.',
			'microcopy'   => 'Includes anti-static, cooling, protective and comfort-focused fabric technologies.',
			'image_url'   => goodnite_asset_url( 'images/goodnite-technology-fabric-v2.png' ),
			'alt'         => 'Close-up of premium quilted mattress fabric',
		),
		array(
			'title'       => 'Our Coil System',
			'description' => 'Beneath the surface, Goodnite’s coil systems are engineered to deliver different kinds of support, responsiveness and movement control.',
			'microcopy'   => 'From traditional spring support to independently responsive pocketed systems.',
			'image_url'   => goodnite_asset_url( 'images/goodnite-technology-coil-v2.png' ),
			'alt'         => 'Premium mattress pocket coil system construction',
		),
		array(
			'title'       => 'Our Material',
			'description' => 'Goodnite combines carefully selected comfort materials to create different sleep sensations, from contouring support to naturally responsive comfort.',
			'microcopy'   => 'Materials chosen for comfort, breathability, responsiveness and support.',
			'image_url'   => goodnite_asset_url( 'images/goodnite-technology-material-v2.png' ),
			'alt'         => 'Layered mattress comfort materials in neutral tones',
		),
	);
}
?>
<section class="gn-home-technology section gn-tech-pillars" data-section="technology-pillars" aria-labelledby="technology-heading"><div class="container-shell">
	<div class="gn-tech-pillars__intro reveal"><div><h2 id="technology-heading"><?php echo esc_html( $tech_title ); ?></h2></div><a class="button-link text gn-tech-pillars__intro-cta" href="<?php echo esc_url( goodnite_url( $tech_cta_url ) ); ?>"><?php echo esc_html( $tech_cta_lbl ); ?> <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a></div>
	<div class="gn-tech-pillars__grid" data-tech-grid><?php foreach ( $pillars as $pillar ) : ?><article class="gn-tech-pillars__card reveal"><div class="gn-tech-pillars__image"><img src="<?php echo esc_url( $pillar['image_url'] ); ?>" alt="<?php echo esc_attr( $pillar['alt'] ); ?>" loading="lazy" decoding="async" width="1600" height="760"></div><div class="gn-tech-pillars__content"><h3><?php echo esc_html( $pillar['title'] ); ?></h3><p><?php echo esc_html( $pillar['description'] ); ?></p><small><?php echo esc_html( $pillar['microcopy'] ); ?></small></div></article><?php endforeach; ?></div>
	<div class="gn-tech-pillars__indicators" data-tech-indicators role="tablist" aria-label="<?php esc_attr_e( 'Choose technology pillar', 'goodnite' ); ?>"><?php foreach ( $pillars as $index => $pillar ) : ?><button type="button" role="tab" aria-label="Show <?php echo esc_attr( $pillar['title'] ); ?>" aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>" class="<?php echo $index === 0 ? 'is-active' : ''; ?>" data-tech-indicator="<?php echo esc_attr( (string) $index ); ?>"></button><?php endforeach; ?></div>
	<a class="button-link text gn-tech-pillars__cta" href="<?php echo esc_url( goodnite_url( $tech_cta_url ) ); ?>"><?php echo esc_html( $tech_cta_lbl ); ?> <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a>
</div></section>
