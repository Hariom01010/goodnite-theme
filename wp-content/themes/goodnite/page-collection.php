<?php
/**
 * Template Name: Collection Page
 * Template Post Type: page
 *
 * Product Listing Page (PLP) for mattress collections with live sorting and filtering.
 * 1:1 Parity with reference prototype (Goodnite Website/Collection Page/)
 * Supports dynamic WooCommerce product querying with complete static dataset fallback.
 * Integrates with Secure Custom Fields (SCF) for configurable hero, banners, and catalog settings.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/collection-data.php';

$collections    = goodnite_get_collections();
$requested_slug = goodnite_get_current_collection_slug();

// If requested slug is not in predefined collections, default to 'all' if valid.
$collection_found = isset( $collections[ $requested_slug ] );
if ( ! $collection_found ) {
	$requested_slug = 'all';
}

$collection = $collections[ $requested_slug ];

// -----------------------------------------------------------------------------
// 1. DYNAMIC WOOCOMMERCE PRODUCT QUERY WITH FULL STATIC FALLBACK
// -----------------------------------------------------------------------------
$dynamic_products = array();

if ( function_exists( 'wc_get_products' ) ) {
	try {
		$wc_args = array(
			'status' => 'publish',
			'limit'  => -1,
		);

		if ( 'all' !== $requested_slug ) {
			$category_term = get_term_by( 'slug', $requested_slug, 'product_cat' );
			if ( $category_term ) {
				$wc_args['category'] = array( $category_term->slug );
			}
		}

		$wc_products = wc_get_products( $wc_args );

		if ( ! empty( $wc_products ) && is_array( $wc_products ) ) {
			$order_index = 1;
			foreach ( $wc_products as $wc_prod ) {
				if ( ! is_object( $wc_prod ) || ! method_exists( $wc_prod, 'get_id' ) ) {
					continue;
				}

				$p_id   = $wc_prod->get_id();
				$p_slug = $wc_prod->get_slug();
				$p_name = $wc_prod->get_name();

				// Pricing
				$p_price = (int) $wc_prod->get_price();
				if ( ! $p_price && method_exists( $wc_prod, 'is_type' ) && $wc_prod->is_type( 'variable' ) ) {
					$p_price = (int) $wc_prod->get_variation_price( 'min' );
				}

				// Featured image
				$image_id  = $wc_prod->get_image_id();
				$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';
				if ( ! $image_url ) {
					$image_url = goodnite_asset_url( 'images/goodnite-product-01.webp' );
				}

				// Category
				$cat_terms = get_the_terms( $p_id, 'product_cat' );
				$col_name  = $collection['title'];
				$col_slug  = $requested_slug;
				if ( ! empty( $cat_terms ) && ! is_wp_error( $cat_terms ) ) {
					$primary_cat = $cat_terms[0];
					$col_name    = $primary_cat->name;
					$col_slug    = $primary_cat->slug;
				}

				// Technology descriptor
				$descriptor = '';
				if ( function_exists( 'get_field' ) ) {
					$descriptor = (string) get_field( 'product_tagline', $p_id );
				}
				if ( empty( $descriptor ) ) {
					$short_desc = wp_strip_all_tags( $wc_prod->get_short_description() );
					$descriptor = ! empty( $short_desc ) ? $short_desc : 'SupportCore Foam';
				}

				// Comfort attribute
				$comfort = $wc_prod->get_attribute( 'pa_comfort' );
				if ( empty( $comfort ) && function_exists( 'get_field' ) ) {
					$comfort = (string) get_field( 'comfort_feel_notes', $p_id );
				}
				if ( empty( $comfort ) ) {
					$comfort = 'Medium';
				}

				// Material attribute
				$material = $wc_prod->get_attribute( 'pa_material' );
				if ( empty( $material ) ) {
					$material = 'Foam';
				}

				// Sizes attribute
				$sizes_attr = $wc_prod->get_attribute( 'pa_size' );
				$sizes      = ! empty( $sizes_attr )
					? array_map( 'trim', explode( ',', $sizes_attr ) )
					: array( 'Single', 'Double', 'Queen', 'King' );

				$dynamic_products[] = array(
					'id'              => (string) $p_id,
					'name'            => $p_name,
					'collection'      => $col_name,
					'collection_slug' => $col_slug,
					'descriptor'      => $descriptor,
					'price'           => $p_price,
					'image'           => '',
					'image_url'       => $image_url,
					'alt'             => sprintf( __( 'Studio image of the %s mattress', 'goodnite' ), $p_name ),
					'url'             => get_permalink( $p_id ),
					'comfort'         => $comfort,
					'material'        => $material,
					'sizes'           => $sizes,
					'featured_order'  => $order_index,
					'sales_rank'      => (int) $wc_prod->get_total_sales() ?: $order_index,
					'newest_order'    => $order_index,
				);
				$order_index++;
			}
		}
	} catch ( Throwable $e ) {
		// In case of any database exception, fall back to static dataset.
		$dynamic_products = array();
	}
}

// Fallback to static catalogue if WooCommerce is unpopulated or offline.
if ( ! empty( $dynamic_products ) ) {
	$products = $dynamic_products;
} else {
	$product_ids = array_flip( $collection['product_ids'] );
	$products    = array_values(
		array_filter(
			$collection['products'],
			static fn( array $product ): bool => isset( $product_ids[ $product['id'] ] )
		)
	);
}

// -----------------------------------------------------------------------------
// 2. SECURE CUSTOM FIELDS (SCF) ENRICHMENT WITH STATIC FALLBACKS
// -----------------------------------------------------------------------------
$scf_context = ( function_exists( 'is_product_category' ) && is_product_category() ) ? get_queried_object() : false;

// Hero section
$scf_hero_title = function_exists( 'get_field' )
	? ( $scf_context ? get_field( 'collection_hero_title', $scf_context ) : get_field( 'collection_hero_title' ) )
	: null;
$hero_title     = ! empty( $scf_hero_title ) ? (string) $scf_hero_title : $collection['title'];

$scf_hero_desc    = function_exists( 'get_field' )
	? ( $scf_context ? get_field( 'collection_hero_description', $scf_context ) : get_field( 'collection_hero_description' ) )
	: null;
$hero_description = ! empty( $scf_hero_desc ) ? (string) $scf_hero_desc : ( $collection['description'] ?? '' );

$scf_hero_img = function_exists( 'get_field' )
	? ( $scf_context ? get_field( 'collection_hero_image', $scf_context ) : get_field( 'collection_hero_image' ) )
	: null;
$hero_image_url = '';
if ( ! empty( $scf_hero_img ) ) {
	$hero_image_url = is_array( $scf_hero_img )
		? ( $scf_hero_img['url'] ?? '' )
		: ( is_numeric( $scf_hero_img ) ? wp_get_attachment_image_url( (int) $scf_hero_img, 'full' ) : (string) $scf_hero_img );
}
if ( empty( $hero_image_url ) ) {
	$hero_image_url = goodnite_asset_url( 'images/' . $collection['hero_image'] );
}

$scf_hero_mob = function_exists( 'get_field' )
	? ( $scf_context ? get_field( 'collection_hero_mobile_image', $scf_context ) : get_field( 'collection_hero_mobile_image' ) )
	: null;
$hero_mobile_image_url = '';
if ( ! empty( $scf_hero_mob ) ) {
	$hero_mobile_image_url = is_array( $scf_hero_mob )
		? ( $scf_hero_mob['url'] ?? '' )
		: ( is_numeric( $scf_hero_mob ) ? wp_get_attachment_image_url( (int) $scf_hero_mob, 'full' ) : (string) $scf_hero_mob );
}
if ( empty( $hero_mobile_image_url ) ) {
	$hero_mobile_image_url = goodnite_asset_url( 'images/' . $collection['hero_mobile_image'] );
}

$scf_hero_alt = function_exists( 'get_field' )
	? ( $scf_context ? get_field( 'collection_hero_alt', $scf_context ) : get_field( 'collection_hero_alt' ) )
	: null;
$hero_alt     = ! empty( $scf_hero_alt ) ? (string) $scf_hero_alt : $collection['hero_alt'];

// Editorial Banners
$scf_banners = function_exists( 'get_field' )
	? ( $scf_context ? get_field( 'collection_banners', $scf_context ) : get_field( 'collection_banners' ) )
	: null;
$banners     = array();

if ( ! empty( $scf_banners ) && is_array( $scf_banners ) ) {
	foreach ( $scf_banners as $b ) {
		$img_url = '';
		if ( ! empty( $b['banner_image'] ) ) {
			$img_url = is_array( $b['banner_image'] )
				? ( $b['banner_image']['url'] ?? '' )
				: ( is_numeric( $b['banner_image'] ) ? wp_get_attachment_image_url( (int) $b['banner_image'], 'full' ) : (string) $b['banner_image'] );
		}
		$banners[] = array(
			'id'           => ! empty( $b['banner_id'] ) ? $b['banner_id'] : uniqid( 'col-banner-' ),
			'title'        => ! empty( $b['banner_title'] ) ? $b['banner_title'] : '',
			'image'        => '',
			'image_url'    => $img_url ?: goodnite_asset_url( 'images/collection-banner-materials.png' ),
			'alt'          => ! empty( $b['banner_alt'] ) ? $b['banner_alt'] : '',
			'after'        => isset( $b['after_desktop'] ) ? (int) $b['after_desktop'] : ( isset( $b['after'] ) ? (int) $b['after'] : 3 ),
			'after_tablet' => isset( $b['after_tablet'] ) ? (int) $b['after_tablet'] : 2,
			'after_mobile' => isset( $b['after_mobile'] ) ? (int) $b['after_mobile'] : 3,
		);
	}
}
if ( empty( $banners ) ) {
	$banners = $collection['banners'];
}

// Settings
$scf_page_size = function_exists( 'get_field' )
	? ( $scf_context ? get_field( 'collection_page_size', $scf_context ) : get_field( 'collection_page_size' ) )
	: null;
$page_size     = ! empty( $scf_page_size ) ? (int) $scf_page_size : 16;

$scf_catalog_note = function_exists( 'get_field' )
	? ( $scf_context ? get_field( 'collection_catalog_note', $scf_context ) : get_field( 'collection_catalog_note' ) )
	: null;
$catalog_note     = ! empty( $scf_catalog_note )
	? (string) $scf_catalog_note
	: __( 'Product imagery and pricing are provisional pending catalogue verification.', 'goodnite' );

// -----------------------------------------------------------------------------
// 3. EXTRACT DYNAMIC FILTER VALUES FOR DRAWER
// -----------------------------------------------------------------------------
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
 * Render an individual product card matching reference prototype 1:1.
 */
function goodnite_render_collection_card( array $product ): void {
	$image_url   = ! empty( $product['image_url'] ) ? $product['image_url'] : goodnite_asset_url( 'images/' . $product['image'] );
	$product_url = ! empty( $product['url'] ) ? ( 0 === strpos( $product['url'], '/' ) ? goodnite_url( $product['url'] ) : $product['url'] ) : '#';
	$alt_text    = ! empty( $product['alt'] ) ? $product['alt'] : sprintf( __( 'Studio image of the %s mattress', 'goodnite' ), $product['name'] );
	?>
	<article class="gn-collection-product-card product-card" data-product-card data-product-id="<?php echo esc_attr( (string) $product['id'] ); ?>">
		<a class="gn-product-image-link focus-ring" href="<?php echo esc_url( $product_url ); ?>">
			<div class="gn-product-image product-image">
				<img class="gn-product-image__asset" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" width="1100" height="1375" loading="lazy" decoding="async">
				<span class="gn-product-collection product-collection-overlay"><?php echo esc_html( $product['collection'] ); ?></span>
			</div>
		</a>
		<div class="gn-product-info product-info">
			<h3 class="gn-product-name"><a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product['name'] ); ?></a></h3>
			<span class="gn-product-technology product-technology"><?php echo esc_html( $product['descriptor'] ); ?></span>
			<span class="gn-product-price product-price">Starting from <?php echo esc_html( goodnite_collection_price_label( (int) $product['price'] ) ); ?></span>
		</div>
	</article>
	<?php
}

/**
 * Render an editorial banner card matching reference prototype 1:1.
 */
function goodnite_render_editorial_banner( array $banner ): void {
	$image_url = ! empty( $banner['image_url'] ) ? $banner['image_url'] : goodnite_asset_url( 'images/' . $banner['image'] );
	$alt_text  = ! empty( $banner['alt'] ) ? $banner['alt'] : '';
	?>
	<article class="gn-collection-banner gn-plp-banner" data-editorial-banner>
		<img class="gn-collection-banner__image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" width="1200" height="1500" loading="lazy" decoding="async">
		<div class="gn-plp-banner__overlay"></div>
		<div class="gn-plp-banner__content">
			<h2 class="gn-collection-banner__title"><?php echo esc_html( $banner['title'] ); ?></h2>
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
		<section class="gn-collection-hero" data-section="collection-hero" data-hero-mode="static" aria-labelledby="collection-title">
			<picture>
				<source media="(max-width: 767px)" srcset="<?php echo esc_url( $hero_mobile_image_url ); ?>">
				<img src="<?php echo esc_url( $hero_image_url ); ?>" alt="<?php echo esc_attr( $hero_alt ); ?>" width="1672" height="941" fetchpriority="high" decoding="async">
			</picture>
			<div class="gn-collection-hero__shade"></div>
			<div class="container-shell gn-collection-hero__content">
				<h1 id="collection-title"><?php echo esc_html( $hero_title ); ?></h1>
			</div>
		</section>

		<div class="container-shell gn-collection-topbar">
			<nav class="gn-collection-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'goodnite' ); ?>">
				<a href="<?php echo esc_url( goodnite_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'goodnite' ); ?></a>
				<span aria-hidden="true">/</span>
				<span><?php echo esc_html( $hero_title ); ?></span>
			</nav>

			<div class="gn-plp-toolbar" aria-label="<?php esc_attr_e( 'Product listing controls', 'goodnite' ); ?>">
				<div class="gn-plp-toolbar__actions">
					<?php if ( ! empty( $products ) ) : ?>
						<button class="gn-plp-toolbar__button" type="button" data-filter-open aria-expanded="false" aria-controls="collection-filters">
							<?php esc_html_e( 'Sort & Filter', 'goodnite' ); ?> <?php echo goodnite_icon( 'chevron-down', 14, 1.8 ); ?>
						</button>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<section class="gn-collection-products gn-plp" data-section="collection-products" aria-labelledby="products-title" data-collection-listing>
			<div class="container-shell gn-collection-products__container">
				<h2 class="screen-reader-text" id="products-title"><?php echo esc_html( sprintf( __( 'Products in %s', 'goodnite' ), $hero_title ) ); ?></h2>
				<div class="gn-collection-products__grid gn-plp-grid" data-product-grid>
					<?php
					$rendered_products = 0;
					foreach ( $products as $product ) {
						goodnite_render_collection_card( $product );
						$rendered_products++;
						foreach ( $banners as $banner ) {
							if ( (int) $banner['after'] === $rendered_products ) {
								goodnite_render_editorial_banner( $banner );
							}
						}
					}
					?>
				</div>
				<p class="gn-collection-products__note gn-plp-catalog-note"><?php echo esc_html( $catalog_note ); ?></p>
				<p class="gn-collection-products__empty gn-plp-empty" data-product-empty hidden><?php esc_html_e( 'No products match the selected filters.', 'goodnite' ); ?></p>
				<div class="gn-collection-products__load-more gn-plp-load-more" data-load-more-wrap hidden>
					<button class="button-link outline" type="button" data-load-more><?php esc_html_e( 'Load More', 'goodnite' ); ?> <span aria-hidden="true">↓</span></button>
				</div>
			</div>
		</section>

		<?php if ( ! empty( $products ) ) : ?>
			<div class="gn-collection-filter gn-plp-filter" id="collection-filters" data-filter-panel hidden>
				<button class="gn-plp-filter__backdrop" type="button" data-filter-close aria-label="<?php esc_attr_e( 'Close filters', 'goodnite' ); ?>"></button>
				<aside class="gn-collection-filter__drawer gn-plp-filter__drawer" role="dialog" aria-modal="true" aria-labelledby="filter-title">
					<header class="gn-collection-filter__header">
						<h2 id="filter-title"><?php esc_html_e( 'Filter products', 'goodnite' ); ?></h2>
						<button class="gn-collection-filter__close" type="button" data-filter-close aria-label="<?php esc_attr_e( 'Close filters', 'goodnite' ); ?>">×</button>
					</header>
					<form class="gn-collection-filter__form" data-filter-form>
						<fieldset class="gn-collection-filter__fieldset gn-plp-filter__sort-fieldset">
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
						<fieldset class="gn-collection-filter__fieldset">
							<legend><?php esc_html_e( 'Collection', 'goodnite' ); ?></legend>
							<?php foreach ( $collection_filters as $slug => $label ) : ?>
								<label><input type="checkbox" name="collection" value="<?php echo esc_attr( $slug ); ?>"> <span><?php echo esc_html( $label ); ?></span></label>
							<?php endforeach; ?>
						</fieldset>
						<fieldset class="gn-collection-filter__fieldset">
							<legend><?php esc_html_e( 'Comfort', 'goodnite' ); ?></legend>
							<?php foreach ( $comfort_filters as $value ) : ?>
								<label><input type="checkbox" name="comfort" value="<?php echo esc_attr( $value ); ?>"> <span><?php echo esc_html( $value ); ?></span></label>
							<?php endforeach; ?>
						</fieldset>
						<fieldset class="gn-collection-filter__fieldset">
							<legend><?php esc_html_e( 'Material', 'goodnite' ); ?></legend>
							<?php foreach ( $material_filters as $value ) : ?>
								<label><input type="checkbox" name="material" value="<?php echo esc_attr( $value ); ?>"> <span><?php echo esc_html( $value ); ?></span></label>
							<?php endforeach; ?>
						</fieldset>
						<fieldset class="gn-collection-filter__fieldset">
							<legend><?php esc_html_e( 'Mattress Size', 'goodnite' ); ?></legend>
							<?php foreach ( $size_filters as $value ) : ?>
								<label><input type="checkbox" name="size" value="<?php echo esc_attr( $value ); ?>"> <span><?php echo esc_html( $value ); ?></span></label>
							<?php endforeach; ?>
						</fieldset>
						<fieldset class="gn-collection-filter__fieldset">
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
						if ( empty( $product['url'] ) || 0 === strpos( $product['url'], '/' ) ) {
							$product['url'] = goodnite_url( $product['url'] ?? '' );
						}
						if ( empty( $product['image_url'] ) ) {
							$product['image_url'] = goodnite_asset_url( 'images/' . $product['image'] );
						}
						$product['price_label'] = goodnite_collection_price_label( (int) $product['price'] );
						return $product;
					},
					$products
				),
				'banners'   => array_map(
					static function ( array $banner ): array {
						if ( empty( $banner['image_url'] ) ) {
							$banner['image_url'] = goodnite_asset_url( 'images/' . $banner['image'] );
						}
						return $banner;
					},
					$banners
				),
				'page_size' => $page_size,
			),
			JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
		);
		?></script>
	<?php endif; ?>
</main>
<?php
get_footer();
