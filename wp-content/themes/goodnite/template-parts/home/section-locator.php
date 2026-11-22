<?php
/**
 * Interactive Store and Showroom locator section with SCF support.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

$store_title = function_exists( 'get_field' ) && get_field( 'store_title', $post_id )
	? (string) get_field( 'store_title', $post_id )
	: (string) goodnite_mod( 'goodnite_store_title', 'Feel the Difference.' );

$store_body = function_exists( 'get_field' ) && get_field( 'store_body', $post_id )
	? (string) get_field( 'store_body', $post_id )
	: (string) goodnite_mod( 'goodnite_store_body', 'Find a Goodnite showroom or authorized retail partner near you.' );

$store_label = function_exists( 'get_field' ) && get_field( 'store_label', $post_id )
	? (string) get_field( 'store_label', $post_id )
	: (string) goodnite_mod( 'goodnite_store_label', 'Enter your pincode' );

$store_button = function_exists( 'get_field' ) && get_field( 'store_button', $post_id )
	? (string) get_field( 'store_button', $post_id )
	: (string) goodnite_mod( 'goodnite_store_button', 'Find Dealers' );

$store_url = goodnite_url( goodnite_mod( 'goodnite_store_url', '/stores' ) );

$store_img_url = '';
if ( function_exists( 'get_field' ) ) {
	$img_field = get_field( 'store_image', $post_id );
	if ( ! empty( $img_field ) ) {
		$store_img_url = is_array( $img_field ) ? (string) ( $img_field['url'] ?? '' ) : (string) wp_get_attachment_image_url( (int) $img_field, 'full' );
	}
}
if ( empty( $store_img_url ) ) {
	$store_img_url = goodnite_asset_url( 'images/goodnite-showroom.webp' );
}
?>
<section class="section locator" aria-labelledby="locator-heading">
	<div class="container-shell locator-grid">
		<div class="reveal locator-copy">
			<div class="locator-search-state" data-store-search-state>
				<h2 id="locator-heading">
					<?php echo esc_html( $store_title ); ?>
				</h2>

				<p>
					<?php echo esc_html( $store_body ); ?>
				</p>

				<form action="<?php echo esc_url( $store_url ); ?>" method="get" novalidate data-store-form>
					<label for="store-query">
						<?php echo esc_html( $store_label ); ?>
					</label>

					<div class="search-field">
						<?php echo goodnite_icon( 'search', 19 ); ?>
						<input id="store-query" name="q" inputmode="numeric" maxlength="6" autocomplete="postal-code" placeholder="<?php echo esc_attr( goodnite_mod( 'goodnite_store_placeholder', '6-digit pincode' ) ); ?>" aria-describedby="store-help store-error">
						<button type="submit" class="focus-ring">
							<?php echo esc_html( $store_button ); ?>
						</button>
					</div>

					<small id="store-help"><?php esc_html_e( 'Enter a valid 6-digit Indian pincode to see nearby dealer previews.', 'goodnite' ); ?></small>
					<span id="store-error" class="form-error" role="alert" hidden><?php esc_html_e( 'Enter a valid 6-digit pincode.', 'goodnite' ); ?></span>
				</form>
			</div>

			<section class="locator-results" data-store-results aria-live="polite" aria-labelledby="locator-results-title" hidden>
				<div class="locator-results__head">
					<div>
						<span><?php esc_html_e( 'NEARBY DEALERS', 'goodnite' ); ?></span>
						<h3 id="locator-results-title"><?php esc_html_e( 'Stores near', 'goodnite' ); ?> <strong data-store-pincode></strong></h3>
					</div>
				</div>

				<div class="locator-results__grid" data-store-results-grid></div>
				<button type="button" class="locator-reset focus-ring" data-store-reset>
					<?php esc_html_e( 'Search Another Pincode', 'goodnite' ); ?>
				</button>
			</section>
		</div>

		<div class="reveal locator-image" style="--reveal-delay:90ms">
			<picture class="goodnite-picture">
				<source media="(max-width: 600px)" srcset="<?php echo esc_url( $store_img_url ); ?>">
				<img src="<?php echo esc_url( $store_img_url ); ?>" alt="<?php echo esc_attr( $store_title ); ?>" width="1122" height="1402" loading="lazy" decoding="async">
			</picture>
		</div>
	</div>
</section>
