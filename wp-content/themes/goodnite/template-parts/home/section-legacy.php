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
?>
<section class="gn-home-legacy section legacy" data-section="brand-legacy" aria-labelledby="legacy-heading">
	<div class="container-shell legacy-grid">
		<div class="reveal legacy-image"><?php goodnite_picture( 'goodnite_legacy_image', 'goodnite_legacy_image', 'goodnite-brand-craftsmanship.webp', 'goodnite-brand-craftsmanship.webp', goodnite_mod( 'goodnite_legacy_alt', 'Close detail of quilted mattress upholstery and tailored side stitching' ) ); ?><span><?php echo esc_html( goodnite_mod( 'goodnite_legacy_note', 'Material study · provisional' ) ); ?></span></div>
		<div class="reveal legacy-copy" style="--reveal-delay:80ms"><h2 id="legacy-heading"><?php echo esc_html( goodnite_mod( 'goodnite_legacy_title', 'A World of Better Sleep.' ) ); ?></h2><p><?php echo esc_html( goodnite_mod( 'goodnite_legacy_body', 'Born in Malaysia and shaped by decades of sleep expertise, Goodnite brings a global legacy of comfort and innovation to India.' ) ); ?></p><?php goodnite_button( goodnite_mod( 'goodnite_legacy_cta_label', 'Discover Our Story' ), goodnite_mod( 'goodnite_legacy_cta_url', '/our-story' ), 'text' ); ?></div>
	</div>
</section>
