<?php
/**
 * 404 Not Found template.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main-content" class="gn-destination-page container-shell">
	<p class="gn-destination-page__eyebrow"><?php esc_html_e( 'PAGE NOT FOUND', 'goodnite' ); ?></p>
	<h1><?php esc_html_e( 'This page is still taking shape.', 'goodnite' ); ?></h1>
	<p><?php esc_html_e( 'Return to the Goodnite India homepage to continue exploring comfort and sleep technology.', 'goodnite' ); ?></p>
	<?php goodnite_button( __( 'Return to Homepage', 'goodnite' ), '/', 'primary' ); ?>
</main>
<?php
get_footer();
