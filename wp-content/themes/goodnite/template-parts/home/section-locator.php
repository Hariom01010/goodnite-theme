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

$store_url = goodnite_url( goodnite_mod( 'goodnite_store_url', '/stores' ) );
?>
<section class="gn-home-store-locator section locator" data-section="store-locator" aria-labelledby="locator-heading"><div class="container-shell locator-grid">
	<div class="reveal locator-copy">
		<div class="locator-search-state" data-store-search-state>
		<h2 id="locator-heading"><?php echo esc_html( goodnite_mod( 'goodnite_store_title', 'Feel the Difference.' ) ); ?></h2>
		<p><?php echo esc_html( goodnite_mod( 'goodnite_store_body', 'Find a Goodnite showroom or authorized retail partner near you.' ) ); ?></p>
		<form action="<?php echo esc_url( $store_url ); ?>" method="get" novalidate data-store-form>
			<div class="search-field"><?php echo goodnite_icon( 'search', 19 ); ?><input id="store-pincode" name="q" inputmode="numeric" maxlength="6" autocomplete="postal-code" placeholder="Enter your 6-digit pincode." aria-describedby="store-help store-error"><button type="submit"><?php echo esc_html( goodnite_mod( 'goodnite_store_button', 'Find Dealers' ) ); ?></button></div>
			<small id="store-help">Enter your 6-digit pincode.</small>
			<span id="store-error" class="form-error" role="alert" hidden>Enter a valid 6-digit pincode.</span>
		</form>
		</div>
		<section class="locator-results" data-store-results aria-live="polite" aria-labelledby="locator-results-title" hidden>
			<div class="locator-results__head"><div><span>NEARBY DEALERS</span><h3 id="locator-results-title">Stores near <strong data-store-pincode></strong></h3></div></div>
			<div class="locator-results__grid" data-store-results-grid></div>
			<button type="button" class="locator-reset" data-store-reset><?php echo goodnite_icon('arrow-left', 16, 1.8); ?><span>Search Another Pincode</span></button>
			<small class="locator-results__note">Preview dealer data for interface review. Replace with the verified Goodnite dealer directory before publishing.</small>
		</section>
	</div>
	<div class="reveal locator-image" style="--reveal-delay:90ms"><?php goodnite_picture( 'goodnite_store_image', 'goodnite_store_image', 'goodnite-store-locator-4x3.png', 'goodnite-store-locator-4x3.png', goodnite_mod( 'goodnite_store_alt', 'Premium mattress showroom with a calm, curated selection of beds' ) ); ?></div>
</div></section>
