<?php
/**
 * Mattress Collections showcase section recreated from Goodnite Website/Homepage/templates/home/collections.php
 * Supports dynamic WooCommerce category selection and SCF with exact prototype markup.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

// 1. Heading & Subtitle.
$heading = function_exists( 'get_field' ) && get_field( 'collections_heading', $post_id )
	? (string) get_field( 'collections_heading', $post_id )
	: (string) goodnite_mod( 'goodnite_collections_title', 'Three Collections. A World of Comfort.' );

$subtitle = function_exists( 'get_field' ) && get_field( 'collections_subtitle', $post_id )
	? (string) get_field( 'collections_subtitle', $post_id )
	: (string) goodnite_mod( 'goodnite_collections_body', 'From essential engineered comfort to advanced sleep technology and our most sophisticated constructions.' );

// 2. Build the 3 Collections.
$collections   = array();
$default_cards = goodnite_collection_cards();

for ( $i = 1; $i <= 3; $i++ ) {
	$card_data = null;
	$cat_id    = function_exists( 'get_field' ) ? get_field( "col_{$i}_cat", $post_id ) : null;

	// Check if a WooCommerce Category is selected.
	if ( ! empty( $cat_id ) && taxonomy_exists( 'product_cat' ) ) {
		$term = get_term( (int) $cat_id, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$term_link = get_term_link( $term );
			$thumb_id  = get_term_meta( $term->term_id, 'thumbnail_id', true );
			$img_url   = $thumb_id ? (string) wp_get_attachment_image_url( (int) $thumb_id, 'full' ) : '';

			if ( empty( $img_url ) ) {
				$fallback_img = get_field( "col_{$i}_image", $post_id );
				if ( is_array( $fallback_img ) && ! empty( $fallback_img['url'] ) ) {
					$img_url = (string) $fallback_img['url'];
				}
			}

			$card_data = array(
				'id'          => sprintf( '%02d', $i ),
				'title'       => $term->name,
				'subtitle'    => (string) ( get_field( "col_{$i}_subtitle", $post_id ) ?: '' ),
				'description' => (string) ( $term->description ?: get_field( "col_{$i}_desc", $post_id ) ),
				'image_url'   => $img_url,
				'alt'         => $term->name,
				'cta_text'    => (string) ( get_field( "col_{$i}_cta", $post_id ) ?: '' ),
				'url'         => ! is_wp_error( $term_link ) ? $term_link : goodnite_url( "/mattresses/{$term->slug}" ),
			);
		}
	}

	// Manual SCF settings if no category is picked.
	if ( null === $card_data && function_exists( 'get_field' ) ) {
		$name = get_field( "col_{$i}_name", $post_id );
		if ( ! empty( $name ) ) {
			$img_field = get_field( "col_{$i}_image", $post_id );
			$img_url   = '';
			if ( is_array( $img_field ) && ! empty( $img_field['url'] ) ) {
				$img_url = (string) $img_field['url'];
			} elseif ( is_numeric( $img_field ) ) {
				$img_url = (string) wp_get_attachment_image_url( (int) $img_field, 'full' );
			}

			$card_data = array(
				'id'          => sprintf( '%02d', $i ),
				'title'       => (string) $name,
				'subtitle'    => (string) ( get_field( "col_{$i}_subtitle", $post_id ) ?: '' ),
				'description' => (string) ( get_field( "col_{$i}_desc", $post_id ) ?: '' ),
				'image_url'   => $img_url,
				'alt'         => (string) $name,
				'cta_text'    => (string) ( get_field( "col_{$i}_cta", $post_id ) ?: '' ),
				'url'         => (string) ( get_field( "col_{$i}_url", $post_id ) ?: '/mattresses' ),
			);
		}
	}

	// Ultimate fallback to default cards.
	if ( null === $card_data || empty( $card_data['image_url'] ) ) {
		$fallback_card = $default_cards[ $i - 1 ] ?? $default_cards[0];
		if ( null === $card_data ) {
			$card_data = array(
				'id'          => (string) ( $fallback_card['id'] ?? sprintf( '%02d', $i ) ),
				'title'       => $fallback_card['title'],
				'subtitle'    => $fallback_card['subtitle'],
				'description' => $fallback_card['description'],
				'image_url'   => goodnite_asset_url( 'images/' . $fallback_card['image'] ),
				'alt'         => $fallback_card['alt'],
				'cta_text'    => '',
				'url'         => goodnite_url( $fallback_card['url'] ),
			);
		} elseif ( empty( $card_data['image_url'] ) ) {
			$card_data['image_url'] = goodnite_asset_url( 'images/' . $fallback_card['image'] );
		}
	}

	if ( empty( $card_data['cta_text'] ) ) {
		$card_data['cta_text'] = 'Explore ' . str_replace( ' Series', '', $card_data['title'] );
	}

	$collections[] = $card_data;
}
?>
<section class="gn-home-collections section collections-section" data-section="collections" aria-labelledby="collections-heading">
	<div class="container-shell">
		<div class="reveal section-intro">
			<h2 id="collections-heading"><?php echo goodnite_heading( goodnite_mod( 'goodnite_collections_title', $heading ), true ); ?></h2>
			<p><?php echo esc_html( goodnite_mod( 'goodnite_collections_body', $subtitle ) ); ?></p>
		</div>

		<div class="collection-grid">
			<?php foreach ( $collections as $card_index => $collection ) : ?>
				<div class="reveal" style="--reveal-delay:<?php echo esc_attr( (string) ( $card_index * 80 ) ); ?>ms">
					<a class="collection-card focus-ring" href="<?php echo esc_url( goodnite_url( $collection['url'] ) ); ?>">
						<div class="collection-image">
							<img src="<?php echo esc_url( $collection['image_url'] ); ?>" alt="<?php echo esc_attr( $collection['alt'] ? $collection['alt'] : $collection['title'] ); ?>" width="1086" height="1448" loading="lazy" decoding="async">
						</div>
						<div class="collection-meta">
							<span><?php echo esc_html( $collection['id'] ); ?></span>
							<div>
								<h3><?php echo esc_html( $collection['title'] ); ?></h3>
								<strong><?php echo esc_html( $collection['subtitle'] ); ?></strong>
								<p><?php echo esc_html( $collection['description'] ); ?></p>
								<span class="card-link"><?php echo esc_html( $collection['cta_text'] ); ?> <?php echo goodnite_icon( 'arrow-up-right', 17 ); ?></span>
							</div>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="products-carousel-dots collection-carousel-dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose collection', 'goodnite' ); ?>"></div>
	</div>
</section>
