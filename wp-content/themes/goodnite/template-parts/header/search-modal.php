<?php
/**
 * Header search modal and JSON search index.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="gn-search-panel" id="site-search-panel" hidden data-search-panel>
	<div class="gn-search-panel__scrim" data-search-close aria-hidden="true"></div>
	<div class="gn-search-panel__surface" role="dialog" aria-modal="true" aria-labelledby="search-panel-title">
		<div class="gn-search-panel__head">
			<h2 id="search-panel-title"><?php esc_html_e( 'Search Goodnite', 'goodnite' ); ?></h2>
			<button type="button" class="gn-search-panel__close focus-ring" data-search-close aria-label="<?php esc_attr_e( 'Close search', 'goodnite' ); ?>">×</button>
		</div>

		<form class="gn-search-panel__form" data-search-form action="<?php echo esc_url( goodnite_url( '/search.php' ) ); ?>" method="get" role="search">
			<label class="screen-reader-text" for="goodnite-search-input"><?php esc_html_e( 'Search mattresses, collections, technologies, articles', 'goodnite' ); ?></label>
			<span class="gn-search-panel__icon" aria-hidden="true"><?php echo goodnite_icon( 'search', 20, 1.7 ); ?></span>
			<input id="goodnite-search-input" name="q" type="search" autocomplete="off" placeholder="<?php esc_attr_e( 'Search mattresses, collections, technologies, articles...', 'goodnite' ); ?>" data-search-input>
			<button type="button" class="gn-search-panel__clear focus-ring" data-search-clear aria-label="<?php esc_attr_e( 'Clear search query', 'goodnite' ); ?>" hidden>×</button>
		</form>

		<div class="gn-search-panel__initial" data-search-initial>
			<p class="gn-search-panel__kicker"><?php esc_html_e( 'EXPLORE BY SEARCH', 'goodnite' ); ?></p>
			<div class="gn-search-suggestions">
				<?php foreach ( array( 'Cooling', 'Memory Foam', 'Natural Latex', 'Kingsley', 'Signature' ) as $suggestion ) : ?>
					<button type="button" class="gn-search-suggestion-btn focus-ring" data-search-suggestion="<?php echo esc_attr( $suggestion ); ?>">
						<?php echo esc_html( $suggestion ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="gn-search-panel__results" data-search-results hidden></div>
		<p class="gn-search-panel__empty" data-search-empty hidden>
			<?php esc_html_e( 'No results found. Try a mattress name, collection, sleep technology or article topic.', 'goodnite' ); ?>
		</p>
	</div>
</div>

<script type="application/json" id="goodnite-search-index">
<?php echo wp_json_encode( goodnite_search_index(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>
</script>
