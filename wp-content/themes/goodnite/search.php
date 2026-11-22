<?php
/**
 * Search results template.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$query   = trim( (string) ( $_GET['q'] ?? get_search_query() ) );
$type    = (string) ( $_GET['type'] ?? 'all' );
$allowed = array( 'all', 'product', 'collection', 'technology', 'article' );
if ( ! in_array( $type, $allowed, true ) ) {
	$type = 'all';
}

$results = goodnite_search_results( $query, $type );

get_header();
?>
<main id="main-content" class="gn-search-results-page">
	<div class="container-shell">
		<p class="gn-search-results-page__eyebrow"><?php esc_html_e( 'GOODNITE SEARCH', 'goodnite' ); ?></p>
		<h1>
			<?php
			if ( '' !== $query ) {
				/* translators: %s: Search term */
				printf( esc_html__( 'Search results for “%s”', 'goodnite' ), esc_html( $query ) );
			} else {
				esc_html_e( 'Search Goodnite', 'goodnite' );
			}
			?>
		</h1>

		<form class="gn-search-results-page__form" method="get" action="<?php echo esc_url( home_url( '/search.php' ) ); ?>" role="search">
			<label class="screen-reader-text" for="full-search-input"><?php esc_html_e( 'Search Goodnite', 'goodnite' ); ?></label>
			<input id="full-search-input" name="q" type="search" value="<?php echo esc_attr( $query ); ?>" placeholder="<?php esc_attr_e( 'Search mattresses, collections, technologies, articles...', 'goodnite' ); ?>">
			<button type="submit" class="focus-ring">
				<span><?php esc_html_e( 'Search', 'goodnite' ); ?></span>
				<?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?>
			</button>
		</form>

		<nav class="gn-search-results-page__filters" aria-label="<?php esc_attr_e( 'Search result categories', 'goodnite' ); ?>">
			<?php
			$filter_categories = array(
				'all'        => __( 'All', 'goodnite' ),
				'product'    => __( 'Products', 'goodnite' ),
				'collection' => __( 'Collections', 'goodnite' ),
				'technology' => __( 'Technologies', 'goodnite' ),
				'article'    => __( 'Articles', 'goodnite' ),
			);
			foreach ( $filter_categories as $key => $label ) :
				$filter_url = add_query_arg(
					array(
						'q'    => rawurlencode( $query ),
						'type' => $key,
					),
					home_url( '/search.php' )
				);
				?>
				<a class="<?php echo $type === $key ? 'is-active' : ''; ?> focus-ring" href="<?php echo esc_url( $filter_url ); ?>">
					<?php echo esc_html( $label ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<?php if ( '' === $query ) : ?>
			<p class="gn-search-results-page__empty">
				<?php esc_html_e( 'Search for a mattress, collection, sleep technology or published article.', 'goodnite' ); ?>
			</p>
		<?php elseif ( empty( $results ) ) : ?>
			<p class="gn-search-results-page__empty">
				<?php
				/* translators: %s: Search term */
				printf( esc_html__( 'No results found for “%s”. Try a mattress name, collection, sleep technology or article topic.', 'goodnite' ), esc_html( $query ) );
				?>
			</p>
		<?php else : ?>
			<p class="gn-search-results-page__count">
				<?php
				printf(
					/* translators: %d: Result count */
					esc_html( _n( '%d result', '%d results', count( $results ), 'goodnite' ) ),
					count( $results )
				);
				?>
			</p>

			<div class="gn-search-results-page__grid">
				<?php foreach ( $results as $result ) : ?>
					<a class="gn-search-result-card focus-ring" href="<?php echo esc_url( goodnite_url( $result['url'] ) ); ?>">
						<?php if ( ! empty( $result['thumbnail'] ) ) : ?>
							<img src="<?php echo esc_url( $result['thumbnail'] ); ?>" alt="" loading="lazy" width="120" height="90">
						<?php endif; ?>
						<span class="gn-search-result-card__body">
							<small><?php echo esc_html( ucfirst( $result['type'] ) ); ?></small>
							<strong><?php echo esc_html( $result['title'] ); ?></strong>
							<span><?php echo esc_html( $result['description'] ); ?></span>
						</span>
						<?php echo goodnite_icon( 'arrow-up-right', 16, 1.8 ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
