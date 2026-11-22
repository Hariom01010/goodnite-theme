<?php
/**
 * WooCommerce Product Archive / Shop template override.
 *
 * Delegates cleanly to the Goodnite Collection Page template.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require get_template_directory() . '/page-collection.php';

