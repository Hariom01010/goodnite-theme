<?php
/**
 * Collection showcase.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id         = get_queried_object_id();
$col_heading     = function_exists( 'get_field' ) ? (string) get_field( 'collections_heading', $post_id ) : '';
$col_heading     = $col_heading ?: goodnite_mod( 'goodnite_collections_title', 'Three Collections. A World of Comfort.' );

$col_subtitle    = function_exists( 'get_field' ) ? (string) get_field( 'collections_subtitle', $post_id ) : '';
$col_subtitle    = $col_subtitle ?: goodnite_mod( 'goodnite_collections_body', 'From essential engineered comfort to advanced sleep technology and our most sophisticated constructions.' );

$collection_cards = goodnite_collection_cards();
?>
<section class="gn-home-collections section collections-section" data-section="collections" aria-labelledby="collections-heading"><div class="container-shell">
	<div class="reveal section-intro">
		<h2 id="collections-heading"><?php echo goodnite_heading( $col_heading, true ); ?></h2>
		<p><?php echo esc_html( $col_subtitle ); ?></p>
	</div>
	<div class="collection-grid">
		<?php foreach ( $collection_cards as $card_index => $collection ) : $collection_image = isset( $collection['image_url'] ) && $collection['image_url'] ? $collection['image_url'] : goodnite_asset_url( 'images/' . $collection['image'] ); ?>
			<div class="reveal" style="--reveal-delay:<?php echo esc_attr( (string) ( $card_index * 80 ) ); ?>ms"><a class="collection-card focus-ring" href="<?php echo esc_url( goodnite_url( $collection['url'] ) ); ?>"><div class="collection-image"><img src="<?php echo esc_url( $collection_image ); ?>" alt="<?php echo esc_attr( $collection['alt'] ? $collection['alt'] : $collection['title'] ); ?>" width="1086" height="1448" loading="lazy" decoding="async"></div><div class="collection-meta"><span><?php echo esc_html( $collection['id'] ); ?></span><div><h3><?php echo esc_html( $collection['title'] ); ?></h3><strong><?php echo esc_html( $collection['subtitle'] ); ?></strong><p><?php echo esc_html( $collection['description'] ); ?></p><span class="card-link">Explore <?php echo esc_html( str_replace( ' Series', '', $collection['title'] ) ); ?> <?php echo goodnite_icon( 'arrow-up-right', 17 ); ?></span></div></div></a></div>
		<?php endforeach; ?>
	</div>
	<div class="products-carousel-dots collection-carousel-dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose collection', 'goodnite' ); ?>"></div>
</div></section>
