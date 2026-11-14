<?php
/**
 * Site header template.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo esc_url( goodnite_asset_url( 'images/Faviicon.png' ) ); ?>" type="image/png">
	<link rel="shortcut icon" href="<?php echo esc_url( goodnite_asset_url( 'images/Faviicon.png' ) ); ?>" type="image/png">
	<link rel="apple-touch-icon" href="<?php echo esc_url( goodnite_asset_url( 'images/Faviicon.png' ) ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content">
	<?php esc_html_e( 'Skip to content', 'goodnite' ); ?>
</a>

<div class="announcement">
	<?php echo esc_html( goodnite_mod( 'goodnite_announcement', 'GLOBAL LUXURY SLEEP · EST. 1989' ) ); ?>
</div>

<header class="site-header" data-site-header>
	<div class="container-shell header-row">
		<button class="icon-button mobile-menu-button focus-ring" type="button" aria-label="<?php esc_attr_e( 'Open navigation menu', 'goodnite' ); ?>" aria-expanded="false" aria-controls="mobile-navigation" data-mobile-open>
			<?php echo goodnite_icon( 'menu', 22 ); ?>
		</button>

		<?php goodnite_logo(); ?>

		<?php get_template_part( 'template-parts/header/navigation' ); ?>

		<div class="header-utilities">
			<button type="button" class="icon-button search-button focus-ring" aria-label="<?php esc_attr_e( 'Search mattresses, collections, and technologies', 'goodnite' ); ?>" aria-expanded="false" aria-controls="site-search-panel" data-search-open>
				<?php echo goodnite_icon( 'search', 20, 1.7 ); ?>
			</button>
			<a href="<?php echo esc_url( goodnite_url( '/account' ) ); ?>" class="icon-button account-button focus-ring" aria-label="<?php esc_attr_e( 'Customer Account', 'goodnite' ); ?>">
				<?php echo goodnite_icon( 'user', 20, 1.7 ); ?>
			</a>
			<a href="<?php echo esc_url( goodnite_url( '/cart' ) ); ?>" class="icon-button cart-button focus-ring" aria-label="<?php esc_attr_e( 'Shopping Cart', 'goodnite' ); ?>">
				<?php echo goodnite_icon( 'shopping-bag', 20, 1.7 ); ?>
			</a>
		</div>
	</div>

	<?php get_template_part( 'template-parts/header/mega-menu' ); ?>
</header>

<?php get_template_part( 'template-parts/header/search-modal' ); ?>
<?php get_template_part( 'template-parts/header/mobile-drawer' ); ?>
