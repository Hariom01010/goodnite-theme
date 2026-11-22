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

$technology_pillars = array(
	array('title'=>'Our Fabric','description'=>'The sleep surface is where comfort begins. Goodnite’s fabric technologies are designed around cooling, softness, protection and everyday sleep comfort.','microcopy'=>'Includes anti-static, cooling, protective and comfort-focused fabric technologies.','image'=>'goodnite-technology-fabric-v2.png','alt'=>'Close-up of premium quilted mattress fabric','items'=>array('StatFree®','IceSleep™','IcyYarn™','MosFree®','Anti-Bed Bug','Fire Retardant','Knitted Fabric','Damask Fabric')),
	array('title'=>'Our Coil System','description'=>'Beneath the surface, Goodnite’s coil systems are engineered to deliver different kinds of support, responsiveness and movement control.','microcopy'=>'From traditional spring support to independently responsive pocketed systems.','image'=>'goodnite-technology-coil-v2.png','alt'=>'Premium mattress pocket coil system construction','items'=>array('Dual Layer Pocketed Coil System','Double Posture Coil® (DPC®)','Pocketed Spring','Posture Coil')),
	array('title'=>'Our Material','description'=>'Goodnite combines carefully selected comfort materials to create different sleep sensations, from contouring support to naturally responsive comfort.','microcopy'=>'Materials chosen for comfort, breathability, responsiveness and support.','image'=>'goodnite-technology-material-v2.png','alt'=>'Layered mattress comfort materials in neutral tones','items'=>array('Natural Latex','Memory Foam','Coconut Fibre','Palm Oil Foam')),
);
?>
<section class="gn-home-technology section gn-tech-pillars" data-section="technology-pillars" aria-labelledby="technology-heading"><div class="container-shell">
	<div class="gn-tech-pillars__intro reveal"><div><h2 id="technology-heading">Inside Goodnite Technology.</h2></div><a class="button-link text gn-tech-pillars__intro-cta" href="<?php echo esc_url( goodnite_url( '/sleep-technology' ) ); ?>">Explore Technologies <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a></div>
	<div class="gn-tech-pillars__grid" data-tech-grid><?php foreach ( $technology_pillars as $pillar ) : ?><article class="gn-tech-pillars__card reveal"><div class="gn-tech-pillars__image"><img src="<?php echo esc_url( goodnite_asset_url( 'images/' . $pillar['image'] ) ); ?>" alt="<?php echo esc_attr( $pillar['alt'] ); ?>" loading="lazy" decoding="async" width="1600" height="760"></div><div class="gn-tech-pillars__content"><h3><?php echo esc_html( $pillar['title'] ); ?></h3><p><?php echo esc_html( $pillar['description'] ); ?></p><small><?php echo esc_html( $pillar['microcopy'] ); ?></small></div></article><?php endforeach; ?></div>
	<div class="gn-tech-pillars__indicators" data-tech-indicators role="tablist" aria-label="<?php esc_attr_e( 'Choose technology pillar', 'goodnite' ); ?>"><?php foreach ( $technology_pillars as $index => $pillar ) : ?><button type="button" role="tab" aria-label="Show <?php echo esc_attr( $pillar['title'] ); ?>" aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>" class="<?php echo $index === 0 ? 'is-active' : ''; ?>" data-tech-indicator="<?php echo esc_attr( (string) $index ); ?>"></button><?php endforeach; ?></div>
	<a class="button-link text gn-tech-pillars__cta" href="<?php echo esc_url( goodnite_url( '/sleep-technology' ) ); ?>">Explore Technologies <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a>
</div></section>
