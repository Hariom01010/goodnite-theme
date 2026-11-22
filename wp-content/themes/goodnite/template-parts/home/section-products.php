<?php
/**
 * Featured mattresses.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id          = get_queried_object_id();
$products_title   = function_exists( 'get_field' ) ? (string) get_field( 'products_title', $post_id ) : '';
$products_title   = $products_title ?: goodnite_mod( 'goodnite_products_title', 'Comfort Worth Discovering.' );

$products_body    = function_exists( 'get_field' ) ? (string) get_field( 'products_body', $post_id ) : '';
$products_body    = $products_body ?: goodnite_mod( 'goodnite_products_body', 'Explore thoughtfully engineered mattresses for different sleep preferences.' );

$products_cta_lbl = function_exists( 'get_field' ) ? (string) get_field( 'products_cta_label', $post_id ) : '';
$products_cta_lbl = $products_cta_lbl ?: __( 'Shop All Mattresses', 'goodnite' );

$products_cta_url = function_exists( 'get_field' ) ? (string) get_field( 'products_cta_url', $post_id ) : '';
$products_cta_url = $products_cta_url ?: goodnite_mod( 'goodnite_products_cta_url', '/mattresses' );

$product_cards    = goodnite_product_cards();

if ( function_exists( 'get_field' ) && function_exists( 'wc_get_product' ) ) {
	$scf_repeater = get_field( 'products_repeater', $post_id );
	if ( ! empty( $scf_repeater ) && is_array( $scf_repeater ) ) {
		$custom_cards = array();
		foreach ( $scf_repeater as $row ) {
			$prod_id = $row['product'] ?? 0;
			if ( is_object( $prod_id ) && isset( $prod_id->ID ) ) {
				$prod_id = $prod_id->ID;
			}
			$prod_id = absint( $prod_id );
			if ( $prod_id > 0 ) {
				$wc_product = wc_get_product( $prod_id );
				if ( $wc_product ) {
					$img_id  = $wc_product->get_image_id();
					$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'full' ) : '';
					$cats    = wc_get_product_category_list( $prod_id );
					$custom_cards[] = array(
						'name'       => $wc_product->get_name(),
						'collection' => wp_strip_all_tags( $cats ) ?: 'Goodnite Series',
						'technology' => '',
						'price'      => $wc_product->get_price_html() ?: '',
						'image'      => '',
						'image_url'  => $img_url,
						'alt'        => $wc_product->get_name(),
						'url'        => get_permalink( $prod_id ),
					);
				}
			}
		}
		if ( ! empty( $custom_cards ) ) {
			$product_cards = $custom_cards;
		}
	}
}
?>
<section class="gn-home-products section products" data-section="featured-products" aria-labelledby="products-heading"><div class="container-shell">
	<div class="reveal products-head"><div><h2 id="products-heading"><?php echo esc_html( $products_title ); ?></h2><p><?php echo esc_html( $products_body ); ?></p></div><?php goodnite_button( $products_cta_lbl, $products_cta_url, 'text' ); ?></div>
	<div class="gn-home-products__grid product-grid">
		<?php foreach ( $product_cards as $card_index => $product ) : $product_image = isset( $product['image_url'] ) && $product['image_url'] ? $product['image_url'] : goodnite_asset_url( 'images/' . $product['image'] ); $product_status = isset( $product['status'] ) && $product['status'] ? $product['status'] : 'Specifications & pricing pending verification'; ?>
			<div class="gn-home-product-reveal reveal" style="--reveal-delay:<?php echo esc_attr( (string) ( $card_index * 70 ) ); ?>ms"><a href="<?php echo esc_url( goodnite_url( $product['url'] ) ); ?>" class="gn-product-card product-card focus-ring"><div class="gn-product-image product-image"><img class="gn-product-image__asset" src="<?php echo esc_url( $product_image ); ?>" alt="<?php echo esc_attr( $product['alt'] ? $product['alt'] : $product['name'] ); ?>" width="1100" height="1375" loading="lazy" decoding="async"></div><span class="gn-product-collection product-collection"><?php echo esc_html( $product['collection'] ); ?></span><h3 class="gn-product-name"><?php echo esc_html( $product['name'] ); ?></h3><span class="gn-product-status product-status"><?php echo esc_html( $product_status ); ?></span><span class="gn-product-link card-link">View Mattress <?php echo goodnite_icon( 'arrow-up-right', 17 ); ?></span></a></div>
		<?php endforeach; ?>
	</div>
	<div class="mobile-shop-link"><?php goodnite_button( $products_cta_lbl, $products_cta_url, 'text' ); ?></div>
</div></section>
