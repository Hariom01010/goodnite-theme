<?php
/**
 * Template Name: Product Page (Kayler / PDP)
 * Template Post Type: page, product
 *
 * Dedicated mattress product page template with gallery, size/pillow-top selector,
 * interactive custom size calculator, and tech breakdown.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/pdp.php';

$product     = goodnite_get_product_data();
$contact     = goodnite_get_contact_data();
$variant     = $product['variants'][ $product['default_variant'] ] ?? reset( $product['variants'] );
$purchasable = ! empty( $product['variant_prices_approved'] )
	&& ! empty( $product['cart_connected'] )
	&& count( (array) ( $product['approved_prices_inr'] ?? array() ) ) >= count( $product['variants'] ) * 2;

get_header();
?>
<main id="main-content" class="gn-pdp" data-pdp>
	<?php
	require get_template_directory() . '/template-parts/product/hero.php';
	require get_template_directory() . '/template-parts/product/construction.php';
	require get_template_directory() . '/template-parts/product/technologies.php';
	require get_template_directory() . '/template-parts/product/recommendations.php';
	require get_template_directory() . '/template-parts/product/faq.php';
	require get_template_directory() . '/template-parts/product/dialogs.php';
	?>
	<div class="gn-pdp__sticky" data-sticky-bar hidden>
		<button type="button" data-open-store-locator>
			<?php echo goodnite_icon( 'map-pin', 18, 1.8 ); ?>
			<span><?php esc_html_e( 'Locate Nearby Store', 'goodnite' ); ?></span>
		</button>
	</div>
	<script type="application/json" id="gn-pdp-data"><?php
	echo gn_json_for_script(
		array(
			'id'             => $product['id'],
			'name'           => $product['name'],
			'variants'       => $product['variants'],
			'defaultVariant' => $product['default_variant'],
			'gallery'        => array_map(
				static function ( array $img ): array {
					$src        = (string) ( $img['src'] ?? '' );
					$img['src'] = ( 0 === strpos( $src, 'http' ) || 0 === strpos( $src, '//' ) )
						? $src
						: goodnite_asset_url( $src );
					return $img;
				},
				$product['gallery']
			),
			'purchasable'    => $purchasable,
			'custom'         => $product['custom'],
			'pricesApproved' => $product['variant_prices_approved'],
			'approvedPrices' => $product['approved_prices_inr'],
			'whatsapp'       => $contact['whatsapp'],
			'phone'          => $contact['phone'],
		)
	);
	?></script>
</main>
<?php
get_footer();

