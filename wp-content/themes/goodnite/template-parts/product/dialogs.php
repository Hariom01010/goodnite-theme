<?php
/**
 * Product dialog modals: size picker, size chart, store locator, help callback,
 * custom size calculator, pillow top info, and fullscreen gallery zoom.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$zoom_img     = $product['gallery'][0] ?? array( 'src' => '', 'alt' => '' );
$zoom_img_url = ( 0 === strpos( $zoom_img['src'], 'http' ) || 0 === strpos( $zoom_img['src'], '//' ) )
	? $zoom_img['src']
	: goodnite_asset_url( $zoom_img['src'] );
?>
<dialog class="gn-pdp-dialog gn-size-dialog" id="size-picker" aria-labelledby="size-picker-title" data-size-dialog>
	<div class="gn-pdp-dialog__head">
		<h2 id="size-picker-title"><?php esc_html_e( 'Choose a size', 'goodnite' ); ?></h2>
		<button type="button" data-close-dialog aria-label="<?php esc_attr_e( 'Close size selection', 'goodnite' ); ?>">
			<?php echo goodnite_icon( 'x', 22, 1.8 ); ?>
		</button>
	</div>
	<div class="gn-size-dialog__step">
		<h3><?php esc_html_e( 'Bed Type', 'goodnite' ); ?></h3>
		<div class="gn-size-dialog__options" data-size-categories>
			<?php foreach ( array_unique( array_column( $product['variants'], 'category' ) ) as $category ) : ?>
				<button type="button" data-size-category="<?php echo esc_attr( $category ); ?>"><?php echo esc_html( $category ); ?></button>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="gn-size-dialog__step">
		<h3><?php esc_html_e( 'Dimension', 'goodnite' ); ?></h3>
		<div class="gn-size-dialog__options" data-size-dimensions>
			<?php foreach ( $product['variants'] as $option ) : ?>
				<button type="button" data-size-dimension="<?php echo esc_attr( $option['id'] ); ?>" data-size-dimension-category="<?php echo esc_attr( $option['category'] ); ?>">
					<?php echo esc_html( $option['length_in'] . ' × ' . $option['width_in'] . ' in' ); ?>
				</button>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="gn-size-dialog__step">
		<h3><?php esc_html_e( 'Pillow Top', 'goodnite' ); ?></h3>
		<div class="gn-size-dialog__options" data-size-pillow>
			<button type="button" data-size-pillow-option="without"><?php esc_html_e( 'Without Pillow Top', 'goodnite' ); ?></button>
			<button type="button" data-size-pillow-option="with"><?php esc_html_e( 'With Pillow Top', 'goodnite' ); ?></button>
		</div>
	</div>
	<div class="gn-size-dialog__footer">
		<button type="button" class="gn-size-dialog__confirm" data-size-confirm><?php esc_html_e( 'Confirm Size', 'goodnite' ); ?></button>
	</div>
</dialog>

<dialog class="gn-pdp-dialog gn-chart" id="size-chart" aria-labelledby="chart-title" data-chart-dialog>
	<div class="gn-pdp-dialog__head">
		<div><h2 id="chart-title"><?php esc_html_e( 'Complete Size Chart', 'goodnite' ); ?></h2></div>
		<button type="button" data-close-dialog aria-label="<?php esc_attr_e( 'Close size chart', 'goodnite' ); ?>">
			<?php echo goodnite_icon( 'x', 22, 1.8 ); ?>
		</button>
	</div>
	<div class="gn-chart__scroll">
		<table>
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Bed Size', 'goodnite' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Dimensions (inches)', 'goodnite' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Without Pillow Top', 'goodnite' ); ?></th>
					<th scope="col"><?php esc_html_e( 'With Pillow Top', 'goodnite' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( array_unique( array_column( $product['variants'], 'category' ) ) as $category ) : ?>
					<?php
					foreach ( $product['variants'] as $option ) :
						if ( $option['category'] !== $category ) {
							continue;
						}
						$plain_price  = gn_display_price( $product, $option['id'], false );
						$pillow_price = gn_display_price( $product, $option['id'], true );
						?>
						<tr>
							<th scope="row" data-label="<?php esc_attr_e( 'Bed Size', 'goodnite' ); ?>"><?php echo esc_html( $category ); ?></th>
							<td data-label="<?php esc_attr_e( 'Dimensions', 'goodnite' ); ?>"><?php echo esc_html( $option['length_in'] . ' × ' . $option['width_in'] ); ?></td>
							<td data-label="<?php esc_attr_e( 'Without Pillow Top', 'goodnite' ); ?>">
								<button type="button" data-chart-size="<?php echo esc_attr( $option['id'] ); ?>" data-chart-pillow="without" aria-label="<?php echo esc_attr( sprintf( __( 'Select %s without Pillow Top', 'goodnite' ), gn_variant_label( $option ) ) ); ?>">
									<?php echo esc_html( null === $plain_price ? __( 'Unavailable', 'goodnite' ) : gn_money( $plain_price ) ); ?>
								</button>
							</td>
							<td data-label="<?php esc_attr_e( 'With Pillow Top', 'goodnite' ); ?>">
								<button type="button" data-chart-size="<?php echo esc_attr( $option['id'] ); ?>" data-chart-pillow="with" aria-label="<?php echo esc_attr( sprintf( __( 'Select %s with Pillow Top', 'goodnite' ), gn_variant_label( $option ) ) ); ?>">
									<?php echo esc_html( null === $pillow_price ? __( 'Unavailable', 'goodnite' ) : gn_money( $pillow_price ) ); ?>
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</dialog>

<dialog class="gn-pdp-dialog gn-store-locator" aria-labelledby="pdp-store-locator-title" data-store-locator-dialog>
	<div class="gn-pdp-dialog__head">
		<div><h2 id="pdp-store-locator-title"><?php esc_html_e( 'Locate a Nearby Store', 'goodnite' ); ?></h2></div>
		<button type="button" data-close-dialog aria-label="<?php esc_attr_e( 'Close store locator', 'goodnite' ); ?>">
			<?php echo goodnite_icon( 'x', 22, 1.8 ); ?>
		</button>
	</div>
	<div class="locator-copy">
		<div data-store-search-state>
			<p><?php esc_html_e( 'Enter your pincode to find a Goodnite showroom or authorized retail partner near you.', 'goodnite' ); ?></p>
			<form novalidate data-store-form>
				<div class="search-field">
					<div class="store-search-input">
						<?php echo goodnite_icon( 'search', 19, 1.8 ); ?>
						<input id="pdp-store-pincode" name="q" inputmode="numeric" maxlength="6" autocomplete="postal-code" placeholder="<?php esc_attr_e( 'Enter your 6-digit pincode.', 'goodnite' ); ?>" aria-describedby="pdp-store-help pdp-store-error">
					</div>
					<button type="submit"><?php esc_html_e( 'Find Dealers', 'goodnite' ); ?></button>
				</div>
				<small id="pdp-store-help"><?php esc_html_e( 'Enter your 6-digit pincode.', 'goodnite' ); ?></small>
				<span id="pdp-store-error" class="form-error" role="alert" hidden><?php esc_html_e( 'Enter a valid 6-digit pincode.', 'goodnite' ); ?></span>
			</form>
		</div>
		<section class="locator-results" data-store-results aria-live="polite" aria-labelledby="pdp-store-results-title" hidden>
			<div class="locator-results__head">
				<div>
					<span><?php esc_html_e( 'NEARBY DEALERS', 'goodnite' ); ?></span>
					<h3 id="pdp-store-results-title"><?php esc_html_e( 'Stores near', 'goodnite' ); ?> <strong data-store-pincode></strong></h3>
				</div>
			</div>
			<div class="locator-results__grid" data-store-results-grid></div>
			<button type="button" class="locator-reset" data-store-reset>
				<?php echo goodnite_icon( 'arrow-left', 16, 1.8 ); ?>
				<span><?php esc_html_e( 'Search Another Pincode', 'goodnite' ); ?></span>
			</button>
			<small class="locator-results__note"><?php esc_html_e( 'Preview dealer data is shown until live store availability is connected.', 'goodnite' ); ?></small>
		</section>
	</div>
</dialog>

<dialog class="gn-pdp-dialog gn-help-dialog" aria-labelledby="pdp-help-title" data-help-dialog>
	<div class="gn-pdp-dialog__head">
		<div><h2 id="pdp-help-title"><?php esc_html_e( 'Need help choosing?', 'goodnite' ); ?></h2></div>
		<button type="button" data-close-dialog aria-label="<?php esc_attr_e( 'Close help form', 'goodnite' ); ?>">
			<?php echo goodnite_icon( 'x', 22, 1.8 ); ?>
		</button>
	</div>
	<p class="gn-help-dialog__intro"><?php esc_html_e( 'Share your details and the Goodnite team will connect with you to help you find the right mattress.', 'goodnite' ); ?></p>
	<form class="gn-help-form" data-help-form novalidate>
		<label><?php esc_html_e( 'Full Name', 'goodnite' ); ?><input name="name" type="text" autocomplete="name" required></label>
		<label><?php esc_html_e( 'Mobile Number', 'goodnite' ); ?><input name="mobile" type="tel" inputmode="numeric" maxlength="10" autocomplete="tel" required></label>
		<p class="gn-help-form__error" data-help-error role="alert" hidden><?php esc_html_e( 'Enter your name and a valid 10-digit mobile number.', 'goodnite' ); ?></p>
		<button type="submit" class="gn-help-form__submit"><?php esc_html_e( 'Request a Callback', 'goodnite' ); ?></button>
		<p class="gn-help-form__success" data-help-success role="status" hidden><?php esc_html_e( 'Thank you. The Goodnite team will connect with you shortly.', 'goodnite' ); ?></p>
	</form>
</dialog>

<dialog class="gn-pdp-dialog gn-custom" id="custom-size" aria-labelledby="custom-title" data-custom-dialog>
	<div class="gn-pdp-dialog__head">
		<div><h2 id="custom-title"><?php esc_html_e( 'Customize Your Size.', 'goodnite' ); ?></h2></div>
		<button type="button" data-close-dialog aria-label="<?php esc_attr_e( 'Close custom size calculator', 'goodnite' ); ?>">
			<?php echo goodnite_icon( 'x', 22, 1.8 ); ?>
		</button>
	</div>
	<p><?php esc_html_e( 'Enter your dimensions to see an approximate price. Goodnite will confirm the final price and feasibility.', 'goodnite' ); ?></p>
	<form class="gn-custom__form" data-custom-form novalidate>
		<div class="gn-custom__dimensions">
			<label><?php esc_html_e( 'Length (inches)', 'goodnite' ); ?><input name="length" type="number" min="0.01" max="<?php echo esc_attr( (string) ( $product['custom']['preview_max_dimension_in'] ?? 240 ) ); ?>" step="any" inputmode="decimal" required></label>
			<label><?php esc_html_e( 'Width (inches)', 'goodnite' ); ?><input name="width" type="number" min="0.01" max="<?php echo esc_attr( (string) ( $product['custom']['preview_max_dimension_in'] ?? 240 ) ); ?>" step="any" inputmode="decimal" required></label>
		</div>
		<fieldset>
			<legend><?php esc_html_e( 'Pillow Top', 'goodnite' ); ?></legend>
			<label><input type="radio" name="custom_pillow" value="without" checked> <?php esc_html_e( 'Without Pillow Top', 'goodnite' ); ?></label>
			<label><input type="radio" name="custom_pillow" value="with"> <?php esc_html_e( 'With Pillow Top', 'goodnite' ); ?></label>
		</fieldset>
		<div class="gn-custom__estimate" aria-live="polite">
			<span><?php esc_html_e( 'Approximate price', 'goodnite' ); ?></span>
			<strong data-custom-price><?php esc_html_e( 'Enter both dimensions', 'goodnite' ); ?></strong>
			<span class="gn-custom__summary" data-custom-summary></span>
			<small><?php
			echo esc_html(
				sprintf(
					/* translators: 1: rate without PT, 2: rate with PT */
					__( 'Rates: ₹%1$s/sq. in without Pillow Top · ₹%2$s/sq. in with Pillow Top', 'goodnite' ),
					number_format( (float) ( $product['custom']['rate_without_pt'] ?? 12.55 ), 2 ),
					number_format( (float) ( $product['custom']['rate_with_pt'] ?? 13.26 ), 2 )
				)
			);
			?></small>
		</div>
		<p class="gn-custom__error" data-custom-error role="alert" hidden></p>
		<button class="gn-custom__submit" type="submit" data-custom-submit disabled><?php esc_html_e( 'Request Your Custom Mattress', 'goodnite' ); ?></button>
	</form>
</dialog>

<?php if ( ! empty( $product['pillow_top_explanation'] ) ) : ?>
	<dialog class="gn-pdp-dialog gn-pillow-info" id="pillow-info" aria-labelledby="pillow-info-title" data-pillow-dialog>
		<div class="gn-pdp-dialog__head">
			<h2 id="pillow-info-title"><?php esc_html_e( 'What is Pillow Top?', 'goodnite' ); ?></h2>
			<button type="button" data-close-dialog aria-label="<?php esc_attr_e( 'Close Pillow Top information', 'goodnite' ); ?>">
				<?php echo goodnite_icon( 'x', 22, 1.8 ); ?>
			</button>
		</div>
		<p><?php echo esc_html( $product['pillow_top_explanation'] ); ?></p>
	</dialog>
<?php endif; ?>

<script>
(() => {
	const dialog = document.querySelector('[data-custom-dialog]');
	const form = dialog?.querySelector('[data-custom-form]');
	if (!form) return;
	const lengthInput = form.querySelector('[name="length"]');
	const widthInput = form.querySelector('[name="width"]');
	const priceOutput = form.querySelector('[data-custom-price]');
	const summaryOutput = form.querySelector('[data-custom-summary]');
	const rates = { without: <?php echo json_encode( (float) ( $product['custom']['rate_without_pt'] ?? 12.55 ) ); ?>, with: <?php echo json_encode( (float) ( $product['custom']['rate_with_pt'] ?? 13.26 ) ); ?> };
	const formatPrice = (value) => new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
	const updateEstimate = () => {
		const length = Number(lengthInput.value);
		const width = Number(widthInput.value);
		const pillow = form.querySelector('[name="custom_pillow"]:checked')?.value || 'without';
		if (!lengthInput.value.trim() || !widthInput.value.trim() || !Number.isFinite(length) || !Number.isFinite(width) || length <= 0 || width <= 0) {
			priceOutput.textContent = 'Enter both dimensions';
			summaryOutput.textContent = '';
			return;
		}
		priceOutput.textContent = formatPrice(length * width * rates[pillow]);
		summaryOutput.textContent = `${length} × ${width} in · ${pillow === 'with' ? 'With' : 'Without'} Pillow Top`;
	};
	form.addEventListener('input', updateEstimate);
	form.addEventListener('change', updateEstimate);
	updateEstimate();
})();
</script>

<dialog class="gn-pdp-dialog gn-zoom" id="gallery-zoom" aria-label="<?php echo esc_attr( sprintf( __( 'Enlarged %s product image', 'goodnite' ), $product['name'] ?? 'Goodnite' ) ); ?>" data-zoom-dialog>
	<button type="button" data-close-dialog aria-label="<?php esc_attr_e( 'Close enlarged image', 'goodnite' ); ?>">
		<?php echo goodnite_icon( 'x', 22, 1.8 ); ?>
	</button>
	<img src="<?php echo esc_url( $zoom_img_url ); ?>" alt="<?php echo esc_attr( $zoom_img['alt'] ?? '' ); ?>" width="1100" height="1375" data-zoom-image>
</dialog>
