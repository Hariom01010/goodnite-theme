<?php
/**
 * Goodnite theme bootstrap and initialization.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GOODNITE_THEME_VERSION', '1.0.0' );

// Core modules.
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/search-index.php';
require_once get_template_directory() . '/inc/collection-data.php';
require_once get_template_directory() . '/inc/routing.php';
