<?php
/**
 * Featured mattresses carousel recreated from Goodnite Website/Homepage/templates/home/products.php
 * Wired to SCF repeater for WooCommerce products with clean fallback.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

// 1. Title & CTA from SCF.
$products_title = function_exists( 'get_field' ) && get_field( 'products_title', $post_id )
	? (string) get_field( 'products_title', $post_id )
	: (string) goodnite_mod( 'goodnite_products_title', 'Comfort Worth Discovering.' );

$products_body = (string) goodnite_mod( 'goodnite_products_body', 'Explore thoughtfully engineered mattresses for different sleep preferences.' );

$products_cta_label = function_exists( 'get_field' ) && get_field( 'products_cta_label', $post_id )
	? (string) get_field( 'products_cta_label', $post_id )
	: (string) __( 'Shop All Mattresses', 'goodnite' );

$products_cta_url = function_exists( 'get_field' ) && get_field( 'products_cta_url', $post_id )
	? (string) get_field( 'products_cta_url', $post_id )
	: (string) goodnite_mod( 'goodnite_products_cta_url', '/mattresses' );

$products = array();

// 2. Resolve Products from SCF Repeater if populated.
if ( function_exists( 'get_field' ) ) {
	$scf_repeater = get_field( 'products_repeater', $post_id );
	if ( ! empty( $scf_repeater ) && is_array( $scf_repeater ) ) {
		foreach ( $scf_repeater as $row ) {
			$prod_id = ! empty( $row['product'] ) ? (int) $row['product'] : 0;
			if ( $prod_id > 0 ) {
				$wc_prod = function_exists( 'wc_get_product' ) ? wc_get_product( $prod_id ) : null;
				if ( $wc_prod ) {
					$cats = array();
					$term_list = get_the_terms( $prod_id, 'product_cat' );
					if ( ! empty( $term_list ) && ! is_wp_error( $term_list ) ) {
						foreach ( $term_list as $term ) {
							$cats[] = $term->name;
						}
					}

					$img_id  = $wc_prod->get_image_id();
					$img_url = $img_id ? (string) wp_get_attachment_image_url( $img_id, 'large' ) : '';

					$price_html = $wc_prod->get_price_html() ? wp_strip_all_tags( $wc_prod->get_price_html() ) : '';
					$status = $price_html ?: ( $wc_prod->get_short_description() ? wp_strip_all_tags( $wc_prod->get_short_description() ) : 'Specifications & pricing pending verification' );

					$products[] = array(
						'name'       => $wc_prod->get_name(),
						'collection' => ! empty( $cats ) ? $cats[0] : 'Goodnite Collection',
						'image_url'  => $img_url,
						'image'      => '',
						'alt'        => $wc_prod->get_name(),
						'url'        => get_permalink( $prod_id ),
						'status'     => $status,
					);
				}
			}
		}
	}
}

// 3. Fallback to default product cards matching prototype.
if ( empty( $products ) ) {
	$products = goodnite_product_cards();
}
?>
<section class="gn-home-products section products" data-section="featured-products" aria-labelledby="products-heading">
	<div class="container-shell">
		<div class="reveal products-head">
			<div>
				<h2 id="products-heading"><?php echo esc_html( $products_title ); ?></h2>
				<p><?php echo esc_html( $products_body ); ?></p>
			</div>
			<?php goodnite_button( $products_cta_label, $products_cta_url, 'text' ); ?>
		</div>

		<div class="gn-home-products__grid product-grid">
			<?php foreach ( $products as $card_index => $product ) : ?>
				<?php
				$product_image = ! empty( $product['image_url'] )
					? $product['image_url']
					: goodnite_asset_url( 'images/' . ( ! empty( $product['image'] ) ? $product['image'] : 'goodnite-product-01.png' ) );

				$product_status = ! empty( $product['status'] )
					? $product['status']
					: 'Specifications & pricing pending verification';
				?>
				<div class="gn-home-product-reveal reveal" style="--reveal-delay:<?php echo esc_attr( (string) ( $card_index * 70 ) ); ?>ms">
					<a href="<?php echo esc_url( goodnite_url( $product['url'] ) ); ?>" class="gn-product-card product-card focus-ring">
						<div class="gn-product-image product-image">
							<img class="gn-product-image__asset" src="<?php echo esc_url( $product_image ); ?>" alt="<?php echo esc_attr( ! empty( $product['alt'] ) ? $product['alt'] : $product['name'] ); ?>" width="1100" height="1375" loading="lazy" decoding="async">
						</div>
						<span class="gn-product-collection product-collection"><?php echo esc_html( $product['collection'] ); ?></span>
						<h3 class="gn-product-name"><?php echo esc_html( $product['name'] ); ?></h3>
						<span class="gn-product-status product-status"><?php echo esc_html( $product_status ); ?></span>
						<span class="gn-product-link card-link">View Mattress <?php echo goodnite_icon( 'arrow-up-right', 17 ); ?></span>
					</a>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="mobile-shop-link">
			<?php goodnite_button( $products_cta_label, $products_cta_url, 'text' ); ?>
		</div>
	</div>
</section>
