<?php
/**
 * Store locator showcase.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id       = get_queried_object_id();
$store_title   = function_exists( 'get_field' ) ? (string) get_field( 'store_title', $post_id ) : '';
$store_title   = $store_title ?: goodnite_mod( 'goodnite_store_title', 'Feel the Difference.' );

$store_body    = function_exists( 'get_field' ) ? (string) get_field( 'store_body', $post_id ) : '';
$store_body    = $store_body ?: goodnite_mod( 'goodnite_store_body', 'Find a Goodnite showroom or authorized retail partner near you.' );

$store_button  = function_exists( 'get_field' ) ? (string) get_field( 'store_button', $post_id ) : '';
$store_button  = $store_button ?: goodnite_mod( 'goodnite_store_button', 'Find Dealers' );

$store_url     = function_exists( 'get_field' ) ? (string) get_field( 'store_url', $post_id ) : '';
$store_url     = $store_url ?: goodnite_url( goodnite_mod( 'goodnite_store_url', '/stores' ) );

$store_img_raw = function_exists( 'get_field' ) ? get_field( 'store_image', $post_id ) : null;
$store_img_url = goodnite_resolve_image_url( $store_img_raw, 'images/goodnite-store-locator-4x3.png' );
$store_alt     = goodnite_mod( 'goodnite_store_alt', 'Premium mattress showroom with a calm, curated selection of beds' );
?>
<section class="gn-home-store-locator section locator" data-section="store-locator" aria-labelledby="locator-heading"><div class="container-shell locator-grid">
	<div class="reveal locator-copy">
		<div class="locator-search-state" data-store-search-state>
		<h2 id="locator-heading"><?php echo esc_html( $store_title ); ?></h2>
		<p><?php echo esc_html( $store_body ); ?></p>
		<form action="<?php echo esc_url( goodnite_url( $store_url ) ); ?>" method="get" novalidate data-store-form>
			<div class="search-field"><?php echo goodnite_icon( 'search', 19 ); ?><input id="store-pincode" name="q" inputmode="numeric" maxlength="6" autocomplete="postal-code" placeholder="<?php esc_attr_e( 'Enter your 6-digit pincode.', 'goodnite' ); ?>" aria-describedby="store-help store-error"><button type="submit"><?php echo esc_html( $store_button ); ?></button></div>
			<small id="store-help"><?php esc_html_e( 'Enter your 6-digit pincode.', 'goodnite' ); ?></small>
			<span id="store-error" class="form-error" role="alert" hidden><?php esc_html_e( 'Enter a valid 6-digit pincode.', 'goodnite' ); ?></span>
		</form>
		</div>
		<section class="locator-results" data-store-results aria-live="polite" aria-labelledby="locator-results-title" hidden>
			<div class="locator-results__head"><div><span>NEARBY DEALERS</span><h3 id="locator-results-title">Stores near <strong data-store-pincode></strong></h3></div></div>
			<div class="locator-results__grid" data-store-results-grid></div>
			<button type="button" class="locator-reset" data-store-reset><?php echo goodnite_icon( 'arrow-left', 16, 1.8 ); ?><span>Search Another Pincode</span></button>
			<small class="locator-results__note">Preview dealer data for interface review. Replace with the verified Goodnite dealer directory before publishing.</small>
		</section>
	</div>
	<div class="reveal locator-image" style="--reveal-delay:90ms">
		<picture class="goodnite-picture">
			<source media="(max-width: 768px)" srcset="<?php echo esc_url( $store_img_url ); ?>">
			<img src="<?php echo esc_url( $store_img_url ); ?>" alt="<?php echo esc_attr( $store_alt ); ?>" width="1600" height="1200" loading="lazy" decoding="async">
		</picture>
	</div>
</div></section>
