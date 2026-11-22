<?php
/**
 * Recreated from Goodnite Website/Homepage/templates/home/technology-pillars.php
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

// Section Title & CTA (with SCF support if set, otherwise exact prototype defaults).
$tech_title = function_exists( 'get_field' ) && get_field( 'tech_title', $post_id )
	? (string) get_field( 'tech_title', $post_id )
	: 'Inside Goodnite Technology.';

$tech_cta_label = function_exists( 'get_field' ) && get_field( 'tech_cta_label', $post_id )
	? (string) get_field( 'tech_cta_label', $post_id )
	: 'Explore Technologies';

$tech_cta_url = function_exists( 'get_field' ) && get_field( 'tech_cta_url', $post_id )
	? (string) get_field( 'tech_cta_url', $post_id )
	: '/sleep-technology';

// Exact pillars data from Goodnite Website/Homepage/templates/home/technology-pillars.php.
$technology_pillars = array(
	array(
		'title'       => 'Our Fabric',
		'description' => 'The sleep surface is where comfort begins. Goodnite’s fabric technologies are designed around cooling, softness, protection and everyday sleep comfort.',
		'microcopy'   => 'Includes anti-static, cooling, protective and comfort-focused fabric technologies.',
		'image'       => 'goodnite-technology-fabric-v2.png',
		'alt'         => 'Close-up of premium quilted mattress fabric',
		'items'       => array( 'StatFree®', 'IceSleep™', 'IcyYarn™', 'MosFree®', 'Anti-Bed Bug', 'Fire Retardant', 'Knitted Fabric', 'Damask Fabric' ),
	),
	array(
		'title'       => 'Our Coil System',
		'description' => 'Beneath the surface, Goodnite’s coil systems are engineered to deliver different kinds of support, responsiveness and movement control.',
		'microcopy'   => 'From traditional spring support to independently responsive pocketed systems.',
		'image'       => 'goodnite-technology-coil-v2.png',
		'alt'         => 'Premium mattress pocket coil system construction',
		'items'       => array( 'Dual Layer Pocketed Coil System', 'Double Posture Coil® (DPC®)', 'Pocketed Spring', 'Posture Coil' ),
	),
	array(
		'title'       => 'Our Material',
		'description' => 'Goodnite combines carefully selected comfort materials to create different sleep sensations, from contouring support to naturally responsive comfort.',
		'microcopy'   => 'Materials chosen for comfort, breathability, responsiveness and support.',
		'image'       => 'goodnite-technology-material-v2.png',
		'alt'         => 'Layered mattress comfort materials in neutral tones',
		'items'       => array( 'Natural Latex', 'Memory Foam', 'Coconut Fibre', 'Palm Oil Foam' ),
	),
);

// If custom cards are provided via SCF, override them.
if ( function_exists( 'get_field' ) ) {
	$scf_cards = get_field( 'tech_cards', $post_id );
	if ( ! empty( $scf_cards ) && is_array( $scf_cards ) ) {
		$custom_pillars = array();
		foreach ( $scf_cards as $c ) {
			if ( empty( $c['title'] ) ) {
				continue;
			}
			$img_src = '';
			if ( ! empty( $c['image'] ) ) {
				if ( is_array( $c['image'] ) && ! empty( $c['image']['url'] ) ) {
					$img_src = (string) $c['image']['url'];
				} elseif ( is_numeric( $c['image'] ) ) {
					$img_src = (string) wp_get_attachment_image_url( (int) $c['image'], 'full' );
				} elseif ( is_string( $c['image'] ) ) {
					$img_src = (string) $c['image'];
				}
			}
			$custom_pillars[] = array(
				'title'       => (string) $c['title'],
				'description' => (string) ( $c['description'] ?? '' ),
				'microcopy'   => (string) ( $c['subtitle'] ?? '' ),
				'image'       => '',
				'image_url'   => $img_src,
				'alt'         => (string) $c['title'],
			);
		}
		if ( ! empty( $custom_pillars ) ) {
			$technology_pillars = $custom_pillars;
		}
	}
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
				<?php
				$img_url = ! empty( $pillar['image_url'] ) ? $pillar['image_url'] : goodnite_asset_url( 'images/' . $pillar['image'] );
				?>
				<article class="gn-tech-pillars__card reveal">
					<div class="gn-tech-pillars__image">
						<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $pillar['alt'] ); ?>" loading="lazy" decoding="async" width="1600" height="760">
					</div>
					<div class="gn-tech-pillars__content">
						<h3><?php echo esc_html( $pillar['title'] ); ?></h3>
						<p><?php echo esc_html( $pillar['description'] ); ?></p>
						<?php if ( ! empty( $pillar['microcopy'] ) ) : ?>
							<small><?php echo esc_html( $pillar['microcopy'] ); ?></small>
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
