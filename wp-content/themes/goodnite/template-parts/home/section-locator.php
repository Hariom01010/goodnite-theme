<?php
/**
 * Interactive Store and Showroom locator section.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$store_url = goodnite_url( goodnite_mod( 'goodnite_store_url', '/stores' ) );
?>
<section class="section locator" aria-labelledby="locator-heading">
	<div class="container-shell locator-grid">
		<div class="reveal locator-copy">
			<div class="locator-search-state" data-store-search-state>
				<h2 id="locator-heading">
					<?php echo esc_html( goodnite_mod( 'goodnite_store_title', 'Feel the Difference.' ) ); ?>
				</h2>

				<p>
					<?php echo esc_html( goodnite_mod( 'goodnite_store_body', 'Find a Goodnite showroom or authorized retail partner near you.' ) ); ?>
				</p>

				<form action="<?php echo esc_url( $store_url ); ?>" method="get" novalidate data-store-form>
					<label for="store-query">
						<?php echo esc_html( goodnite_mod( 'goodnite_store_label', 'Enter your pincode' ) ); ?>
					</label>

					<div class="search-field">
						<?php echo goodnite_icon( 'search', 19 ); ?>
						<input id="store-query" name="q" inputmode="numeric" maxlength="6" autocomplete="postal-code" placeholder="<?php echo esc_attr( goodnite_mod( 'goodnite_store_placeholder', '6-digit pincode' ) ); ?>" aria-describedby="store-help store-error">
						<button type="submit" class="focus-ring">
							<?php echo esc_html( goodnite_mod( 'goodnite_store_button', 'Find Dealers' ) ); ?>
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

				<small class="locator-results__note">
					<?php esc_html_e( 'Preview dealer data for interface review. Replace with the verified Goodnite dealer directory before publishing.', 'goodnite' ); ?>
				</small>
			</section>
		</div>

		<div class="reveal locator-image" style="--reveal-delay:90ms">
			<?php
			goodnite_picture(
				'goodnite_store_image',
				'goodnite_store_image',
				'goodnite-showroom.webp',
				'goodnite-showroom.webp',
				goodnite_mod( 'goodnite_store_alt', 'Conceptual premium mattress studio with a restrained selection of beds' )
			);
			?>
		</div>
	</div>
</section>
