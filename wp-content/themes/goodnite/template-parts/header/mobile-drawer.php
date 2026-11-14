<?php
/**
 * Accessible mobile navigation drawer.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="mobile-overlay" hidden data-mobile-overlay>
	<div class="mobile-sheet" id="mobile-navigation" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Site navigation', 'goodnite' ); ?>">
		<div class="mobile-sheet-head">
			<?php goodnite_logo(); ?>
			<button type="button" data-mobile-close aria-label="<?php esc_attr_e( 'Close navigation', 'goodnite' ); ?>">×</button>
		</div>

		<nav class="mobile-nav" aria-label="<?php esc_attr_e( 'Mobile navigation', 'goodnite' ); ?>">
			<details>
				<summary><?php esc_html_e( 'Mattresses', 'goodnite' ); ?> <?php echo goodnite_icon( 'chevron-down', 16 ); ?></summary>
				<div class="mobile-subnav">
					<a href="<?php echo esc_url( goodnite_url( '/mattresses' ) ); ?>"><?php esc_html_e( 'All Mattresses', 'goodnite' ); ?></a>
					<a href="<?php echo esc_url( goodnite_url( '/mattresses/dreamer' ) ); ?>"><?php esc_html_e( 'Dreamer Series', 'goodnite' ); ?></a>
					<a href="<?php echo esc_url( goodnite_url( '/mattresses/love' ) ); ?>"><?php esc_html_e( 'Love Series', 'goodnite' ); ?></a>
					<a href="<?php echo esc_url( goodnite_url( '/mattresses/signature' ) ); ?>"><?php esc_html_e( 'Signature Series', 'goodnite' ); ?></a>
				</div>
			</details>

			<?php
			if ( has_nav_menu( 'mobile_primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'mobile_primary',
						'container'      => false,
						'menu_class'     => 'mobile-primary-menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			} else {
				goodnite_fallback_menu(
					array(
						'Sleep Technology' => '/sleep-technology',
						'Our Story'        => '/our-story',
						'Hospitality'      => '/hospitality',
					),
					'mobile-primary-menu'
				);
			}
			?>
		</nav>
	</div>
</div>
