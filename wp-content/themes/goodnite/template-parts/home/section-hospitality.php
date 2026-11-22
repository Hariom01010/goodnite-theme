<?php
/**
 * Goodnite Hospitality showcase with split layout and infinite logo marquee.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

$eyebrow   = function_exists( 'get_field' ) && get_field( 'hospitality_eyebrow', $post_id ) ? (string) get_field( 'hospitality_eyebrow', $post_id ) : (string) goodnite_mod( 'goodnite_hospitality_eyebrow', 'GOODNITE HOSPITALITY' );
$title     = function_exists( 'get_field' ) && get_field( 'hospitality_title', $post_id ) ? (string) get_field( 'hospitality_title', $post_id ) : (string) goodnite_mod( 'goodnite_hospitality_title', 'Exceptional Sleep for Exceptional Stays.' );
$body      = function_exists( 'get_field' ) && get_field( 'hospitality_body', $post_id ) ? (string) get_field( 'hospitality_body', $post_id ) : (string) goodnite_mod( 'goodnite_hospitality_body', 'For hotels, resorts and hospitality destinations, Goodnite brings decades of international mattress expertise to the guest experience, delivering thoughtfully engineered comfort where expectations are high.' );
$cta_label = function_exists( 'get_field' ) && get_field( 'hospitality_cta_label', $post_id ) ? (string) get_field( 'hospitality_cta_label', $post_id ) : (string) goodnite_mod( 'goodnite_hospitality_cta_label', 'Explore Hospitality Solutions' );
$cta_url   = function_exists( 'get_field' ) && get_field( 'hospitality_cta_url', $post_id ) ? (string) get_field( 'hospitality_cta_url', $post_id ) : (string) goodnite_mod( 'goodnite_hospitality_cta_url', '/hospitality' );

$img_url = '';
if ( function_exists( 'get_field' ) ) {
	$img_field = get_field( 'hospitality_image', $post_id );
	if ( ! empty( $img_field ) ) {
		$img_url = is_array( $img_field ) ? (string) ( $img_field['url'] ?? '' ) : (string) wp_get_attachment_image_url( (int) $img_field, 'full' );
	}
}
if ( empty( $img_url ) ) {
	$img_url = goodnite_asset_url( 'images/goodnite-hospitality-desktop.webp' );
}

$hospitality_clients = array(
	'Taj', 'Grand Hyatt', 'Radisson', 'Wyndham Hotels & Resorts', 'ITC Hotels',
	'Marriott', 'IHG', 'Westin', 'Ascott', 'Sayaji', 'The Fern', 'Lemon Tree Hotels',
	'Sarovar', 'The Park', 'Lords', 'Mayfair', 'Atmantan', 'ibis',
);
?>
<section class="gn-hospitality" aria-labelledby="hospitality-heading">
	<div class="gn-hospitality__visual">
		<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" width="1400" height="1600" loading="lazy" decoding="async">
	</div>

	<div class="gn-hospitality__content reveal">
		<div class="gn-hospitality__eyebrow eyebrow">
			<?php echo esc_html( $eyebrow ); ?>
		</div>

		<h2 id="hospitality-heading">
			<?php echo esc_html( $title ); ?>
		</h2>

		<p class="gn-hospitality__description">
			<?php echo esc_html( $body ); ?>
		</p>

		<a class="button-link primary gn-hospitality__cta focus-ring" href="<?php echo esc_url( goodnite_url( $cta_url ) ); ?>"><?php echo esc_html( $cta_label ); ?> <span aria-hidden="true">→</span></a>

		<div class="gn-hospitality__clients">
			<div class="gn-hospitality__clients-label">
				<?php esc_html_e( 'OUR HOSPITALITY CLIENT PORTFOLIO', 'goodnite' ); ?>
			</div>

			<div class="gn-hospitality__marquee" data-hospitality-marquee tabindex="0" aria-label="<?php esc_attr_e( 'Hospitality client portfolio brand names', 'goodnite' ); ?>">
				<div class="gn-hospitality__marquee-track">
					<div class="gn-hospitality__logo-set">
						<?php foreach ( $hospitality_clients as $client ) : ?>
							<span class="gn-hospitality__logo" role="img" aria-label="<?php echo esc_attr( $client ); ?>">
								<?php echo esc_html( $client ); ?>
							</span>
						<?php endforeach; ?>
					</div>

					<div class="gn-hospitality__logo-set" aria-hidden="true">
						<?php foreach ( $hospitality_clients as $client ) : ?>
							<span class="gn-hospitality__logo">
								<?php echo esc_html( $client ); ?>
							</span>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
