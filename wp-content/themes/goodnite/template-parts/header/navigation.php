<?php
/**
 * Desktop primary navigation.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="desktop-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'goodnite' ); ?>">
	<button class="nav-trigger focus-ring" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="mattress-mega-menu" data-mega-trigger><?php esc_html_e( 'Mattresses', 'goodnite' ); ?> <?php echo goodnite_icon( 'chevron-down', 14 ); ?></button>

	<?php
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'primary-menu',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
	} else {
		goodnite_fallback_menu(
			array(
				'Our Story'   => '/our-story',
				'Hospitality' => '/hospitality',
			),
			'primary-menu'
		);
	}
	?>
</nav>
