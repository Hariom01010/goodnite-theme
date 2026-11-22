<?php
/**
 * Template Name: Find Your Mattress
 *
 * Guided Sleep Match recommendation page based on Sleep Finder preferences.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$allowed     = array( 'cooling', 'support', 'pressure_relief', 'motion_isolation', 'natural', 'luxury' );
$raw_pref    = isset( $_GET['preferences'] ) ? (string) sanitize_text_field( wp_unslash( $_GET['preferences'] ) ) : '';
$requested   = '' !== $raw_pref ? explode( ',', $raw_pref ) : array();
$preferences = array_values( array_unique( array_intersect( $allowed, $requested ) ) );

get_header();
?>
<main id="main-content" class="gn-finder-receiving container-shell" style="padding-block: 100px 140px; min-height: 60vh;">
	<p class="gn-sleep-finder__eyebrow"><?php esc_html_e( 'SLEEP IS PERSONAL', 'goodnite' ); ?></p>
	<h1 style="margin: 12px 0 24px; font-size: clamp(2.5rem, 5vw, 4rem); letter-spacing: -0.04em;"><?php esc_html_e( 'Find your sleep match', 'goodnite' ); ?></h1>
	<?php if ( ! empty( $preferences ) ) : ?>
		<p style="font-size: 1.1rem; color: var(--text-secondary);"><?php esc_html_e( 'We’ll begin with what matters to you:', 'goodnite' ); ?></p>
		<ul style="margin: 20px 0 36px; padding-left: 20px; list-style: disc; color: var(--ink); line-height: 2;">
			<?php foreach ( $preferences as $preference ) : ?>
				<li><strong><?php echo esc_html( ucwords( str_replace( '_', ' ', $preference ) ) ); ?></strong></li>
			<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<p style="margin: 16px 0 32px; font-size: 1.1rem; color: var(--text-secondary);"><?php esc_html_e( 'Start by sharing what matters most to your sleep. You can choose your preferences on the homepage.', 'goodnite' ); ?></p>
	<?php endif; ?>
	<p class="gn-finder-receiving__status" style="margin-bottom: 32px; color: var(--text-muted); font-size: 0.95rem;"><?php esc_html_e( 'The guided Mattress Finder is being prepared for Goodnite India.', 'goodnite' ); ?></p>
	<div style="display: flex; gap: 16px; flex-wrap: wrap;">
		<a class="button-link primary" href="<?php echo esc_url( goodnite_url( '/mattresses' ) ); ?>"><?php esc_html_e( 'Browse all mattresses', 'goodnite' ); ?></a>
		<a class="button-link outline" href="<?php echo esc_url( goodnite_url( '/' ) ); ?>"><?php esc_html_e( 'Return to homepage', 'goodnite' ); ?></a>
	</div>
</main>
<?php
get_footer();
