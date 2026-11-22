<?php
/**
 * Mattress Collections showcase section with dynamic SCF support.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

$collections_title = function_exists( 'get_field' ) && get_field( 'collections_title', $post_id )
	? (string) get_field( 'collections_title', $post_id )
	: (string) goodnite_mod( 'goodnite_collections_title', 'Three Collections. A World of Comfort.' );

$collections_body = function_exists( 'get_field' ) && get_field( 'collections_body', $post_id )
	? (string) get_field( 'collections_body', $post_id )
	: (string) goodnite_mod( 'goodnite_collections_body', 'From essential engineered comfort to advanced sleep technology and our most sophisticated constructions.' );

$collections = array();
if ( function_exists( 'get_field' ) ) {
	$scf_items = get_field( 'collections_items', $post_id );
	if ( ! empty( $scf_items ) && is_array( $scf_items ) ) {
		foreach ( $scf_items as $item ) {
			$img_url = '';
			if ( is_array( $item['image'] ?? null ) && ! empty( $item['image']['url'] ) ) {
				$img_url = (string) $item['image']['url'];
			} elseif ( is_numeric( $item['image'] ?? null ) ) {
				$img_url = (string) wp_get_attachment_image_url( (int) $item['image'], 'full' );
			} elseif ( is_string( $item['image'] ?? null ) ) {
				$img_url = (string) $item['image'];
			}

			if ( ! empty( $item['title'] ) ) {
				$collections[] = array(
					'title'       => (string) $item['title'],
					'subtitle'    => (string) ( $item['subtitle'] ?? '' ),
					'description' => (string) ( $item['description'] ?? '' ),
					'image_url'   => $img_url,
					'alt'         => (string) ( $item['alt_text'] ?? $item['title'] ),
					'url'         => ! empty( $item['url'] ) ? (string) $item['url'] : '/mattresses',
				);
			}
		}
	}
}

// Fallback to default collections fixture if none added in SCF yet.
if ( empty( $collections ) ) {
	$default_cards = goodnite_collection_cards();
	foreach ( $default_cards as $card ) {
		$collections[] = array(
			'title'       => $card['title'],
			'subtitle'    => $card['subtitle'],
			'description' => $card['description'],
			'image_url'   => goodnite_asset_url( 'images/' . $card['image'] ),
			'alt'         => $card['alt'],
			'url'         => $card['url'],
		);
	}
}
?>
<section class="section collections-section" aria-labelledby="collections-heading">
	<div class="container-shell">
		<div class="reveal section-intro">
			<h2 id="collections-heading">
				<?php echo goodnite_heading( $collections_title, true ); ?>
			</h2>
			<p>
				<?php echo esc_html( $collections_body ); ?>
			</p>
		</div>

		<div class="collection-grid">
			<?php foreach ( $collections as $index => $item ) : ?>
				<div class="reveal" style="--reveal-delay:<?php echo esc_attr( (string) ( $index * 80 ) ); ?>ms">
					<a class="collection-card focus-ring" href="<?php echo esc_url( goodnite_url( $item['url'] ) ); ?>">
						<div class="collection-image">
							<img src="<?php echo esc_url( $item['image_url'] ); ?>" alt="<?php echo esc_attr( $item['alt'] ); ?>" width="1086" height="1448" loading="lazy" decoding="async">
						</div>
						<div class="collection-meta">
							<div>
								<h3><?php echo esc_html( $item['title'] ); ?></h3>
								<strong><?php echo esc_html( $item['subtitle'] ); ?></strong>
								<p><?php echo esc_html( $item['description'] ); ?></p>
								<span class="card-link explore-cta">Explore <?php echo esc_html( str_replace( ' Series', '', $item['title'] ) ); ?> <?php echo goodnite_icon( 'arrow-up-right', 17 ); ?></span>
							</div>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
