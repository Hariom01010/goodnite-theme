<?php
/**
 * Flagship products carousel showcase.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$products = goodnite_product_cards();
?>
<section class="section products" aria-labelledby="products-heading" data-products-carousel>
	<div class="container-shell">
		<div class="reveal products-head">
			<div>
				<h2 id="products-heading"><?php echo esc_html( goodnite_mod( 'goodnite_products_title', 'Comfort Worth Discovering.' ) ); ?></h2>
			</div>
			<a class="button-link text" href="<?php echo esc_url( goodnite_url( goodnite_mod( 'goodnite_products_cta_url', '/mattresses' ) ) ); ?>"><?php esc_html_e( 'Shop All Mattresses', 'goodnite' ); ?> <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a>
		</div>

		<div class="product-grid" data-product-grid>
			<?php foreach ( $products as $index => $product ) : ?>
				<div class="reveal" style="--reveal-delay:<?php echo esc_attr( (string) ( $index * 70 ) ); ?>ms">
					<article class="product-card focus-ring">
						<a href="<?php echo esc_url( goodnite_url( $product['url'] ) ); ?>" class="product-card__link">
							<div class="product-image">
								<span class="product-collection-overlay"><?php echo esc_html( $product['collection'] ); ?></span>
								<img src="<?php echo esc_url( goodnite_asset_url( 'images/' . $product['image'] ) ); ?>" alt="<?php echo esc_attr( $product['alt'] ); ?>" width="1100" height="1375" loading="lazy" decoding="async">
							</div>
						</a>

						<div class="product-info">
							<a href="<?php echo esc_url( goodnite_url( $product['url'] ) ); ?>">
								<h3><?php echo esc_html( $product['name'] ); ?></h3>
							</a>
							<span class="product-technology"><?php echo esc_html( $product['technology'] ); ?></span>
							<span class="product-price"><?php echo esc_html( $product['price'] ); ?></span>

							<a class="buy-now-link focus-ring" href="<?php echo esc_url( goodnite_url( '/cart?product=' . rawurlencode( $product['name'] ) ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'goodnite' ), $product['name'] ) ); ?>">
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
			<a class="button-link text" href="<?php echo esc_url( goodnite_url( goodnite_mod( 'goodnite_products_cta_url', '/mattresses' ) ) ); ?>"><?php esc_html_e( 'Shop All Mattresses', 'goodnite' ); ?> <?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?></a>
		</div>
	</div>
</section>
