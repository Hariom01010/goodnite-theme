<?php
/**
 * Mega menu dropdown for mattresses matching Goodnite Website/Homepage.
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
		<a class="mega-collection-link" href="<?php echo esc_url( goodnite_url( '/mattresses' ) ); ?>">
			<img class="mega-collection-icon" src="<?php echo esc_url( goodnite_asset_url( 'Megamenu Icons/Shop All.png' ) ); ?>" alt="" width="80" height="80">
			<small aria-hidden="true">&nbsp;</small>
			<strong><?php esc_html_e( 'Shop All Mattresses', 'goodnite' ); ?></strong>
			<span><?php esc_html_e( 'Explore the full collection ↗', 'goodnite' ); ?></span>
		</a>
		<a class="mega-collection-link" href="<?php echo esc_url( goodnite_url( '/mattresses/dreamer' ) ); ?>">
			<img class="mega-collection-icon" src="<?php echo esc_url( goodnite_asset_url( 'Megamenu Icons/Dreamer Series.png' ) ); ?>" alt="" width="80" height="80">
			<small>01</small>
			<strong><?php esc_html_e( 'Dreamer Series', 'goodnite' ); ?></strong>
			<span><?php esc_html_e( 'Essential Engineered Comfort', 'goodnite' ); ?></span>
		</a>
		<a class="mega-collection-link" href="<?php echo esc_url( goodnite_url( '/mattresses/love' ) ); ?>">
			<img class="mega-collection-icon" src="<?php echo esc_url( goodnite_asset_url( 'Megamenu Icons/Lover Series.png' ) ); ?>" alt="" width="80" height="80">
			<small>02</small>
			<strong><?php esc_html_e( 'Love Series', 'goodnite' ); ?></strong>
			<span><?php esc_html_e( 'Advanced Sleep Technology', 'goodnite' ); ?></span>
		</a>
		<a class="mega-collection-link" href="<?php echo esc_url( goodnite_url( '/mattresses/signature' ) ); ?>">
			<img class="mega-collection-icon" src="<?php echo esc_url( goodnite_asset_url( 'Megamenu Icons/Signature Series.png' ) ); ?>" alt="" width="80" height="80">
			<small>03</small>
			<strong><?php esc_html_e( 'Signature Series', 'goodnite' ); ?></strong>
			<span><?php esc_html_e( 'Engineered Beyond Comfort', 'goodnite' ); ?></span>
		</a>
	</div>
</div>
