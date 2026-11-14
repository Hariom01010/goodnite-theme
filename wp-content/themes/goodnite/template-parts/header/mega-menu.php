<?php
/**
 * Mega menu dropdown for mattresses.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="mega-menu" id="mattress-mega-menu" hidden data-mega-menu>
	<div class="container-shell mega-grid">
		<div>
			<span class="mega-kicker"><?php esc_html_e( 'Shop mattresses', 'goodnite' ); ?></span>
			<h2><?php esc_html_e( 'Find comfort made for you.', 'goodnite' ); ?></h2>
			<a class="mega-all focus-ring" href="<?php echo esc_url( goodnite_url( '/mattresses' ) ); ?>"><?php esc_html_e( 'Shop All Mattresses', 'goodnite' ); ?> <span>↗</span></a>
		</div>
		<a href="<?php echo esc_url( goodnite_url( '/mattresses/dreamer' ) ); ?>">
			<small>01</small>
			<strong><?php esc_html_e( 'Dreamer Series', 'goodnite' ); ?></strong>
			<span><?php esc_html_e( 'Essential Engineered Comfort', 'goodnite' ); ?></span>
		</a>
		<a href="<?php echo esc_url( goodnite_url( '/mattresses/love' ) ); ?>">
			<small>02</small>
			<strong><?php esc_html_e( 'Love Series', 'goodnite' ); ?></strong>
			<span><?php esc_html_e( 'Advanced Sleep Technology', 'goodnite' ); ?></span>
		</a>
		<a href="<?php echo esc_url( goodnite_url( '/mattresses/signature' ) ); ?>">
			<small>03</small>
			<strong><?php esc_html_e( 'Signature Series', 'goodnite' ); ?></strong>
			<span><?php esc_html_e( 'Engineered Beyond Comfort', 'goodnite' ); ?></span>
		</a>
	</div>
</div>
