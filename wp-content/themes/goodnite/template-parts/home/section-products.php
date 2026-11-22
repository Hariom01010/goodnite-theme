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

$product_cards = goodnite_product_cards();
?>
<section class="gn-home-products section products" data-section="featured-products" aria-labelledby="products-heading"><div class="container-shell">
	<div class="reveal products-head"><div><h2 id="products-heading"><?php echo esc_html( goodnite_mod( 'goodnite_products_title', 'Comfort Worth Discovering.' ) ); ?></h2><p><?php echo esc_html( goodnite_mod( 'goodnite_products_body', 'Explore thoughtfully engineered mattresses for different sleep preferences.' ) ); ?></p></div><?php goodnite_button( __( 'Shop All Mattresses', 'goodnite' ), goodnite_mod( 'goodnite_products_cta_url', '/mattresses' ), 'text' ); ?></div>
	<div class="gn-home-products__grid product-grid">
		<?php foreach ( $product_cards as $card_index => $product ) : $product_image = isset( $product['image_url'] ) && $product['image_url'] ? $product['image_url'] : goodnite_asset_url( 'images/' . $product['image'] ); $product_status = isset( $product['status'] ) && $product['status'] ? $product['status'] : 'Specifications & pricing pending verification'; ?>
			<div class="gn-home-product-reveal reveal" style="--reveal-delay:<?php echo esc_attr( (string) ( $card_index * 70 ) ); ?>ms"><a href="<?php echo esc_url( goodnite_url( $product['url'] ) ); ?>" class="gn-product-card product-card focus-ring"><div class="gn-product-image product-image"><img class="gn-product-image__asset" src="<?php echo esc_url( $product_image ); ?>" alt="<?php echo esc_attr( $product['alt'] ? $product['alt'] : $product['name'] ); ?>" width="1100" height="1375" loading="lazy" decoding="async"></div><span class="gn-product-collection product-collection"><?php echo esc_html( $product['collection'] ); ?></span><h3 class="gn-product-name"><?php echo esc_html( $product['name'] ); ?></h3><span class="gn-product-status product-status"><?php echo esc_html( $product_status ); ?></span><span class="gn-product-link card-link">View Mattress <?php echo goodnite_icon( 'arrow-up-right', 17 ); ?></span></a></div>
		<?php endforeach; ?>
	</div>
	<div class="mobile-shop-link"><?php goodnite_button( __( 'Shop All Mattresses', 'goodnite' ), goodnite_mod( 'goodnite_products_cta_url', '/mattresses' ), 'text' ); ?></div>
</div></section>
