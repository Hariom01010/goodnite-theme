<?php
/**
 * Flagship products carousel showcase with dynamic SCF and WooCommerce integration.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

$products_title = function_exists( 'get_field' ) && get_field( 'products_title', $post_id )
	? (string) get_field( 'products_title', $post_id )
	: (string) goodnite_mod( 'goodnite_products_title', 'Comfort Worth Discovering.' );

$products_cta_label = function_exists( 'get_field' ) && get_field( 'products_cta_label', $post_id )
	? (string) get_field( 'products_cta_label', $post_id )
	: 'Shop All Mattresses';

$products_cta_url = function_exists( 'get_field' ) && get_field( 'products_cta_url', $post_id )
	? (string) get_field( 'products_cta_url', $post_id )
	: (string) goodnite_mod( 'goodnite_products_cta_url', '/mattresses' );

$products = array();

// 1. Try fetching WooCommerce products if active.
if ( class_exists( 'WooCommerce' ) ) {
	$selected_ids = function_exists( 'get_field' ) ? get_field( 'products_selection', $post_id ) : null;
	$query_args   = array(
		'status' => 'publish',
		'limit'  => 8,
	);

	if ( ! empty( $selected_ids ) && is_array( $selected_ids ) ) {
		$query_args['include'] = array_map( 'intval', $selected_ids );
		$query_args['orderby'] = 'post__in';
	}

	$wc_products = wc_get_products( $query_args );

	if ( ! empty( $wc_products ) ) {
		foreach ( $wc_products as $wc_product ) {
			if ( ! $wc_product instanceof WC_Product ) {
				continue;
			}

			$cat_names = array();
			$term_list = get_the_terms( $wc_product->get_id(), 'product_cat' );
			if ( ! empty( $term_list ) && ! is_wp_error( $term_list ) ) {
				foreach ( $term_list as $term ) {
					$cat_names[] = $term->name;
				}
			}

			$img_id  = $wc_product->get_image_id();
			$img_url = $img_id ? (string) wp_get_attachment_image_url( $img_id, 'large' ) : goodnite_asset_url( 'images/goodnite-product-01.png' );

			$products[] = array(
				'id'          => $wc_product->get_id(),
				'name'        => $wc_product->get_name(),
				'collection'  => ! empty( $cat_names ) ? $cat_names[0] : 'Goodnite Collection',
				'technology'  => $wc_product->get_short_description() ? wp_strip_all_tags( $wc_product->get_short_description() ) : 'Engineered Sleep Comfort',
				'price'       => $wc_product->get_price_html() ?: '₹' . number_format( (float) $wc_product->get_price(), 0 ),
				'url'         => get_permalink( $wc_product->get_id() ),
				'image_url'   => $img_url,
				'alt'         => $wc_product->get_name(),
				'cart_url'    => $wc_product->add_to_cart_url(),
				'is_wc'       => true,
			);
		}
	}
}

// 2. Fallback to hardcoded mock products.
if ( empty( $products ) ) {
	$raw_mock = goodnite_product_cards();
	foreach ( $raw_mock as $mock ) {
		$products[] = array(
			'id'         => 0,
			'name'       => $mock['name'],
			'collection' => $mock['collection'],
			'technology' => $mock['technology'],
			'price'      => $mock['price'],
			'url'        => goodnite_url( $mock['url'] ),
			'image_url'  => goodnite_asset_url( 'images/' . $mock['image'] ),
			'alt'        => $mock['alt'],
			'cart_url'   => goodnite_url( '/cart?product=' . rawurlencode( $mock['name'] ) ),
			'is_wc'      => false,
		);
	}
}
?>
<section class="section products" aria-labelledby="products-heading" data-products-carousel>
	<div class="container-shell">
		<div class="reveal products-head">
			<div>
				<h2 id="products-heading"><?php echo esc_html( $products_title ); ?></h2>
			</div>
			<a class="button-link text" href="<?php echo esc_url( goodnite_url( $products_cta_url ) ); ?>"><?php echo esc_html( $products_cta_label ); ?> <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a>
		</div>

		<div class="product-grid" data-product-grid>
			<?php foreach ( $products as $index => $product ) : ?>
				<div class="reveal" style="--reveal-delay:<?php echo esc_attr( (string) ( $index * 70 ) ); ?>ms">
					<article class="product-card focus-ring">
						<a href="<?php echo esc_url( $product['url'] ); ?>" class="product-card__link">
							<div class="product-image">
								<span class="product-collection-overlay"><?php echo esc_html( $product['collection'] ); ?></span>
								<img src="<?php echo esc_url( $product['image_url'] ); ?>" alt="<?php echo esc_attr( $product['alt'] ); ?>" width="1100" height="1375" loading="lazy" decoding="async">
							</div>
						</a>

						<div class="product-info">
							<a href="<?php echo esc_url( $product['url'] ); ?>">
								<h3><?php echo esc_html( $product['name'] ); ?></h3>
							</a>
							<span class="product-technology"><?php echo esc_html( $product['technology'] ); ?></span>
							<span class="product-price"><?php echo wp_kses_post( $product['price'] ); ?></span>

							<a class="buy-now-link focus-ring" href="<?php echo esc_url( $product['cart_url'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'goodnite' ), $product['name'] ) ); ?>">
								<?php echo goodnite_icon( 'cart-simple', 19, 1.8 ); ?>
							</a>
						</div>
					</article>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="products-carousel-controls">
			<button class="products-carousel-arrow products-carousel-prev focus-ring" type="button" aria-label="<?php esc_attr_e( 'Previous product', 'goodnite' ); ?>" data-product-prev>
				<?php echo goodnite_icon( 'chevron-left', 20 ); ?>
			</button>
			<div class="products-carousel-dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose product', 'goodnite' ); ?>" data-product-dots>
				<?php foreach ( $products as $index => $product ) : ?>
					<button class="products-carousel-dot<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" aria-label="<?php echo esc_attr( sprintf( __( 'Show product %d', 'goodnite' ), $index + 1 ) ); ?>" data-product-dot="<?php echo esc_attr( (string) $index ); ?>"></button>
				<?php endforeach; ?>
			</div>
			<button class="products-carousel-arrow products-carousel-next focus-ring" type="button" aria-label="<?php esc_attr_e( 'Next product', 'goodnite' ); ?>" data-product-next>
				<?php echo goodnite_icon( 'chevron-right', 20 ); ?>
			</button>
		</div>

		<div class="mobile-shop-link">
			<a class="button-link text" href="<?php echo esc_url( goodnite_url( $products_cta_url ) ); ?>"><?php echo esc_html( $products_cta_label ); ?> <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a>
		</div>
	</div>
</section>
