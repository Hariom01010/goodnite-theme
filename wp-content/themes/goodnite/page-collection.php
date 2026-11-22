<?php
/**
 * Template Name: Collection Page
 * Template Post Type: page
 *
 * Product Listing Page (PLP) for mattress collections with live sorting and filtering.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/collection-data.php';

$collections      = goodnite_get_collections();
$requested_slug   = goodnite_get_current_collection_slug();
$collection_found = isset( $collections[ $requested_slug ] );

if ( ! $collection_found ) {
	$requested_slug = 'all';
}

$collection  = $collections[ $requested_slug ];
$product_ids = array_flip( $collection['product_ids'] );
$products    = array_values(
	array_filter(
		$collection['products'],
		static fn( array $product ): bool => isset( $product_ids[ $product['id'] ] )
	)
);

// Extract dynamic filter values.
$collection_filters = array();
$comfort_filters    = array();
$material_filters   = array();
$size_filters       = array();

foreach ( $products as $product ) {
	$collection_filters[ $product['collection_slug'] ] = $product['collection'];
	$comfort_filters[ $product['comfort'] ]             = $product['comfort'];
	$material_filters[ $product['material'] ]           = $product['material'];
	foreach ( $product['sizes'] as $size ) {
		$size_filters[ $size ] = $size;
	}
}

asort( $collection_filters );
ksort( $comfort_filters );
ksort( $material_filters );

$size_order = array(
	'Single' => 1,
	'Double' => 2,
	'Queen'  => 3,
	'King'   => 4,
);
uksort(
	$size_filters,
	static fn( string $a, string $b ): int => ( $size_order[ $a ] ?? 99 ) <=> ( $size_order[ $b ] ?? 99 )
);

/**
 * Format currency label.
 */
function goodnite_collection_price_label( int $price ): string {
	return '₹' . number_format( $price );
}

/**
 * Render an individual product card.
 */
function goodnite_render_collection_card( array $product ): void {
	?>
	<article class="product-card" data-product-card data-product-id="<?php echo esc_attr( $product['id'] ); ?>">
		<a class="focus-ring" href="<?php echo esc_url( goodnite_url( $product['url'] ) ); ?>">
			<div class="product-image">
				<img src="<?php echo esc_url( goodnite_asset_url( 'images/' . $product['image'] ) ); ?>" alt="<?php echo esc_attr( $product['alt'] ); ?>" width="1100" height="1375" loading="lazy" decoding="async">
				<span class="product-collection-overlay"><?php echo esc_html( $product['collection'] ); ?></span>
			</div>
		</a>
		<div class="product-info">
			<h3><a href="<?php echo esc_url( goodnite_url( $product['url'] ) ); ?>"><?php echo esc_html( $product['name'] ); ?></a></h3>
			<span class="product-technology"><?php echo esc_html( $product['descriptor'] ); ?></span>
			<span class="product-price"><?php echo esc_html( goodnite_collection_price_label( (int) $product['price'] ) ); ?></span>
			<button class="buy-now-link" type="button" disabled aria-label="<?php echo esc_attr( sprintf( __( 'Shopping action for %s is not yet available', 'goodnite' ), $product['name'] ) ); ?>" title="<?php esc_attr_e( 'Shopping action coming soon', 'goodnite' ); ?>"><?php echo goodnite_icon( 'shopping-bag', 19, 1.7 ); ?></button>
		</div>
	</article>
	<?php
}

/**
 * Render an editorial banner card.
 */
function goodnite_render_editorial_banner( array $banner ): void {
	?>
	<article class="gn-plp-banner" data-editorial-banner>
		<img src="<?php echo esc_url( goodnite_asset_url( 'images/' . $banner['image'] ) ); ?>" alt="<?php echo esc_attr( $banner['alt'] ); ?>" width="1200" height="1500" loading="lazy" decoding="async">
		<div class="gn-plp-banner__overlay"></div>
		<div class="gn-plp-banner__content">
			<h2><?php echo esc_html( $banner['title'] ); ?></h2>
		</div>
	</article>
	<?php
}

get_header();
?>
<main id="main-content" class="gn-collection">
	<?php if ( ! $collection_found ) : ?>
		<section class="gn-collection__not-found container-shell" style="padding-block: 100px; text-align: center;">
			<h1><?php esc_html_e( 'Collection not found.', 'goodnite' ); ?></h1>
			<p style="margin: 16px 0 28px; color: var(--text-secondary);"><?php esc_html_e( 'The requested collection is unavailable.', 'goodnite' ); ?></p>
			<a class="button-link primary" href="<?php echo esc_url( goodnite_url( '/mattresses' ) ); ?>"><?php esc_html_e( 'View all mattresses', 'goodnite' ); ?></a>
		</section>
	<?php else : ?>
		<section class="gn-collection-hero" data-hero-mode="static" aria-labelledby="collection-title">
			<picture>
				<source media="(max-width: 767px)" srcset="<?php echo esc_url( goodnite_asset_url( 'images/' . $collection['hero_mobile_image'] ) ); ?>">
				<img src="<?php echo esc_url( goodnite_asset_url( 'images/' . $collection['hero_image'] ) ); ?>" alt="<?php echo esc_attr( $collection['hero_alt'] ); ?>" width="1672" height="941" fetchpriority="high" decoding="async">
			</picture>
			<div class="gn-collection-hero__shade"></div>
			<div class="container-shell gn-collection-hero__content">
				<h1 id="collection-title"><?php echo esc_html( $collection['title'] ); ?></h1>
			</div>
		</section>

		<div class="container-shell gn-collection-topbar">
			<nav class="gn-collection-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'goodnite' ); ?>">
				<a href="<?php echo esc_url( goodnite_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'goodnite' ); ?></a>
				<span aria-hidden="true">/</span>
				<span><?php echo esc_html( $collection['title'] ); ?></span>
			</nav>

			<div class="gn-plp-toolbar">
				<div class="gn-plp-toolbar__actions">
					<?php if ( ! empty( $products ) ) : ?>
						<button class="gn-plp-toolbar__button" type="button" data-filter-open aria-expanded="false" aria-controls="collection-filters">
							<?php esc_html_e( 'Sort & Filter', 'goodnite' ); ?> <?php echo goodnite_icon( 'chevron-down', 14, 1.8 ); ?>
						</button>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<section class="gn-plp" aria-labelledby="products-title" data-collection-listing>
			<div class="container-shell">
				<h2 class="screen-reader-text" id="products-title"><?php echo esc_html( sprintf( __( 'Products in %s', 'goodnite' ), $collection['title'] ) ); ?></h2>
				<div class="gn-plp-grid" data-product-grid>
					<?php
					$rendered_products = 0;
					foreach ( $products as $product ) {
						goodnite_render_collection_card( $product );
						$rendered_products++;
						foreach ( $collection['banners'] as $banner ) {
							if ( (int) $banner['after'] === $rendered_products ) {
								goodnite_render_editorial_banner( $banner );
							}
						}
					}
					?>
				</div>
				<p class="gn-plp-catalog-note"><?php esc_html_e( 'Product imagery and pricing are provisional pending catalogue verification.', 'goodnite' ); ?></p>
				<p class="gn-plp-empty" data-product-empty hidden><?php esc_html_e( 'No products match the selected filters.', 'goodnite' ); ?></p>
				<div class="gn-plp-load-more" data-load-more-wrap hidden>
					<button class="button-link outline" type="button" data-load-more><?php esc_html_e( 'Load More', 'goodnite' ); ?></button>
				</div>
			</div>
		</section>

		<?php if ( ! empty( $products ) ) : ?>
			<div class="gn-plp-filter" id="collection-filters" data-filter-panel hidden>
				<button class="gn-plp-filter__backdrop" type="button" data-filter-close aria-label="<?php esc_attr_e( 'Close filters', 'goodnite' ); ?>"></button>
				<aside class="gn-plp-filter__drawer" role="dialog" aria-modal="true" aria-labelledby="filter-title">
					<header>
						<h2 id="filter-title"><?php esc_html_e( 'Filter products', 'goodnite' ); ?></h2>
						<button type="button" data-filter-close aria-label="<?php esc_attr_e( 'Close filters', 'goodnite' ); ?>">×</button>
					</header>
					<form data-filter-form>
						<fieldset class="gn-plp-filter__sort-fieldset">
							<legend><?php esc_html_e( 'Sort by', 'goodnite' ); ?></legend>
							<div class="gn-plp-filter__sort-options" role="radiogroup" aria-label="<?php esc_attr_e( 'Sort products', 'goodnite' ); ?>">
								<label><input type="radio" name="sort" value="featured" checked> <span><?php esc_html_e( 'Featured', 'goodnite' ); ?></span></label>
								<label><input type="radio" name="sort" value="best-selling"> <span><?php esc_html_e( 'Best Selling', 'goodnite' ); ?></span></label>
								<label><input type="radio" name="sort" value="newest"> <span><?php esc_html_e( 'Newest', 'goodnite' ); ?></span></label>
								<label><input type="radio" name="sort" value="price-asc"> <span><?php esc_html_e( 'Price: Low to High', 'goodnite' ); ?></span></label>
								<label><input type="radio" name="sort" value="price-desc"> <span><?php esc_html_e( 'Price: High to Low', 'goodnite' ); ?></span></label>
								<label><input type="radio" name="sort" value="name-asc"> <span><?php esc_html_e( 'Name: A-Z', 'goodnite' ); ?></span></label>
							</div>
						</fieldset>
						<fieldset>
							<legend><?php esc_html_e( 'Collection', 'goodnite' ); ?></legend>
							<?php foreach ( $collection_filters as $slug => $label ) : ?>
								<label><input type="checkbox" name="collection" value="<?php echo esc_attr( $slug ); ?>"> <span><?php echo esc_html( $label ); ?></span></label>
							<?php endforeach; ?>
						</fieldset>
						<fieldset>
							<legend><?php esc_html_e( 'Comfort', 'goodnite' ); ?></legend>
							<?php foreach ( $comfort_filters as $value ) : ?>
								<label><input type="checkbox" name="comfort" value="<?php echo esc_attr( $value ); ?>"> <span><?php echo esc_html( $value ); ?></span></label>
							<?php endforeach; ?>
						</fieldset>
						<fieldset>
							<legend><?php esc_html_e( 'Material', 'goodnite' ); ?></legend>
							<?php foreach ( $material_filters as $value ) : ?>
								<label><input type="checkbox" name="material" value="<?php echo esc_attr( $value ); ?>"> <span><?php echo esc_html( $value ); ?></span></label>
							<?php endforeach; ?>
						</fieldset>
						<fieldset>
							<legend><?php esc_html_e( 'Mattress Size', 'goodnite' ); ?></legend>
							<?php foreach ( $size_filters as $value ) : ?>
								<label><input type="checkbox" name="size" value="<?php echo esc_attr( $value ); ?>"> <span><?php echo esc_html( $value ); ?></span></label>
							<?php endforeach; ?>
						</fieldset>
						<fieldset>
							<legend><?php esc_html_e( 'Price', 'goodnite' ); ?></legend>
							<label><input type="radio" name="price" value="under-50000"> <span><?php esc_html_e( 'Under ₹50,000', 'goodnite' ); ?></span></label>
							<label><input type="radio" name="price" value="50000-69999"> <span><?php esc_html_e( '₹50,000–₹69,999', 'goodnite' ); ?></span></label>
							<label><input type="radio" name="price" value="70000-plus"> <span><?php esc_html_e( '₹70,000 and above', 'goodnite' ); ?></span></label>
						</fieldset>
						<div class="gn-plp-filter__actions">
							<button class="button-link outline" type="reset" data-filter-reset><?php esc_html_e( 'Reset', 'goodnite' ); ?></button>
							<button class="button-link primary" type="button" data-filter-apply><?php esc_html_e( 'Apply', 'goodnite' ); ?></button>
						</div>
					</form>
				</aside>
			</div>
		<?php endif; ?>

		<script type="application/json" id="gn-collection-data"><?php
		echo json_encode(
			array(
				'slug'      => $collection['slug'],
				'products'  => array_map(
					static function ( array $product ): array {
						$product['url']         = goodnite_url( $product['url'] );
						$product['image_url']   = goodnite_asset_url( 'images/' . $product['image'] );
						$product['price_label'] = goodnite_collection_price_label( (int) $product['price'] );
						return $product;
					},
					$products
				),
				'banners'   => array_map(
					static function ( array $banner ): array {
						$banner['image_url'] = goodnite_asset_url( 'images/' . $banner['image'] );
						return $banner;
					},
					$collection['banners']
				),
				'page_size' => 16,
			),
			JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
		);
		?></script>
	<?php endif; ?>
</main>
<?php
get_footer();
