<?php
/**
 * Product recommendations section: related or curated mattresses.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$recommendations = ! empty( $product['recommendations'] )
	? $product['recommendations']
	: ( function_exists( 'goodnite_product_cards' ) ? goodnite_product_cards() : array() );
?>
<section class="gn-home-products section products" data-section="featured-products" aria-labelledby="recommendations-title">
	<div class="container-shell">
		<div class="reveal products-head">
			<div>
				<h2 id="recommendations-title"><?php esc_html_e( 'Comfort Worth Discovering.', 'goodnite' ); ?></h2>
				<p><?php esc_html_e( 'Explore thoughtfully engineered mattresses for different sleep preferences.', 'goodnite' ); ?></p>
			</div>
		</div>
		<div class="gn-home-products__grid product-grid">
			<?php foreach ( $recommendations as $index => $recommendation ) : ?>
				<?php
				$rec_image = $recommendation['image'] ?? '';
				$rec_img_url = '';
				if ( ! empty( $rec_image ) ) {
					$rec_img_url = ( ! empty( $recommendation['is_url'] ) || 0 === strpos( $rec_image, 'http' ) || 0 === strpos( $rec_image, '//' ) )
						? $rec_image
						: goodnite_asset_url( 'images/' . ltrim( $rec_image, '/' ) );
				}
				$rec_url = ! empty( $recommendation['url'] ) ? goodnite_url( $recommendation['url'] ) : '#';
				?>
				<div class="gn-home-product-reveal reveal" style="--reveal-delay:<?php echo (int) ( $index * 70 ); ?>ms">
					<a class="gn-product-card product-card focus-ring" href="<?php echo esc_url( $rec_url ); ?>">
						<div class="gn-product-image product-image">
							<?php if ( $rec_img_url ) : ?>
								<img class="gn-product-image__asset" src="<?php echo esc_url( $rec_img_url ); ?>" alt="<?php echo esc_attr( $recommendation['alt'] ?? $recommendation['name'] ); ?>" width="1100" height="1375" loading="lazy" decoding="async">
							<?php endif; ?>
						</div>
						<span class="gn-product-collection product-collection"><?php echo esc_html( $recommendation['collection'] ?? '' ); ?></span>
						<h3 class="gn-product-name"><?php echo esc_html( $recommendation['name'] ?? '' ); ?></h3>
						<span class="gn-product-status product-status"><?php esc_html_e( 'Thoughtfully engineered comfort.', 'goodnite' ); ?></span>
						<span class="gn-product-link card-link">
							<?php esc_html_e( 'View Mattress', 'goodnite' ); ?>
							<?php echo goodnite_icon( 'arrow-up-right', 17, 1.8 ); ?>
						</span>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="products-carousel-controls" data-products-carousel-controls></div>
	</div>
</section>
