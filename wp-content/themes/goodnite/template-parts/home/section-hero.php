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

$hero_alt = goodnite_mod( 'goodnite_hero_alt', 'Contemporary bedroom with an ivory upholstered king bed in warm morning light' );
?>
<section class="gn-home-hero hero" data-section="hero" aria-labelledby="hero-heading">
	<div class="hero-media"><?php goodnite_picture( 'goodnite_hero_image_desktop', 'goodnite_hero_image_mobile', 'goodnite-hero-desktop.webp', 'goodnite-hero-mobile.webp', $hero_alt, '', true ); ?></div>
	<div class="container-shell hero-content"><div class="hero-copy">
		<h1 id="hero-heading"><?php echo esc_html( goodnite_mod( 'goodnite_hero_title', 'Sleep Beyond Ordinary.' ) ); ?></h1>
		<p><?php echo esc_html( goodnite_mod( 'goodnite_hero_body', 'Discover a world of comfort shaped by international sleep expertise, thoughtful innovation and the way you sleep.' ) ); ?></p>
		<div class="hero-actions"><?php goodnite_button( goodnite_mod( 'goodnite_hero_primary_label', 'Explore Mattresses' ), goodnite_mod( 'goodnite_hero_primary_url', '/mattresses' ) ); ?><?php goodnite_button( goodnite_mod( 'goodnite_hero_secondary_label', 'Find Your Mattress' ), goodnite_mod( 'goodnite_hero_secondary_url', '/find-your-mattress' ), 'secondary-light' ); ?></div>
	</div></div>
	<span class="hero-note"><?php echo esc_html( goodnite_mod( 'goodnite_hero_note', 'Campaign imagery · provisional' ) ); ?></span>
</section>
