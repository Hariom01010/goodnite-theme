<?php
/**
 * Interactive 2-preference "Your Sleep. Your Way." finder section with SCF & WooCommerce integration.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

// Dynamic SCF fields with fallbacks.
$finder_title = function_exists( 'get_field' ) && get_field( 'finder_title', $post_id )
	? (string) get_field( 'finder_title', $post_id )
	: 'Your Sleep. Your Way.';

$finder_desc = function_exists( 'get_field' ) && get_field( 'finder_description', $post_id )
	? (string) get_field( 'finder_description', $post_id )
	: 'Some prefer a cooler night. Others seek firmer support, softer comfort or more natural materials. Tell us what matters to you, and discover a better place to begin.';

$finder_prompt = function_exists( 'get_field' ) && get_field( 'finder_prompt_text', $post_id )
	? (string) get_field( 'finder_prompt_text', $post_id )
	: 'Choose Two Options :';

$finder_cta_primary_label = function_exists( 'get_field' ) && get_field( 'finder_primary_cta_label', $post_id )
	? (string) get_field( 'finder_primary_cta_label', $post_id )
	: 'Find My Sleep Match';

$finder_cta_primary_url = function_exists( 'get_field' ) && get_field( 'finder_primary_cta_url', $post_id )
	? (string) get_field( 'finder_primary_cta_url', $post_id )
	: '/find-your-mattress';

$finder_cta_secondary_label = function_exists( 'get_field' ) && get_field( 'finder_secondary_cta_label', $post_id )
	? (string) get_field( 'finder_secondary_cta_label', $post_id )
	: 'Explore All Mattresses';

$finder_cta_secondary_url = function_exists( 'get_field' ) && get_field( 'finder_secondary_cta_url', $post_id )
	? (string) get_field( 'finder_secondary_cta_url', $post_id )
	: '/mattresses';

// Visual Image.
$finder_image_url = '';
if ( function_exists( 'get_field' ) ) {
	$img_val = get_field( 'finder_image', $post_id );
	if ( ! empty( $img_val ) ) {
		$finder_image_url = is_array( $img_val ) ? (string) ( $img_val['url'] ?? '' ) : (string) wp_get_attachment_image_url( (int) $img_val, 'full' );
	}
}
if ( empty( $finder_image_url ) ) {
	$finder_image_url = goodnite_asset_url( 'images/goodnite-sleep-finder.webp' );
}

// Preference options: check SCF repeater or use defaults.
$finder_options = array();
if ( function_exists( 'get_field' ) ) {
	$scf_options = get_field( 'finder_options', $post_id );
	if ( ! empty( $scf_options ) && is_array( $scf_options ) ) {
		foreach ( $scf_options as $opt ) {
			if ( ! empty( $opt['title'] ) ) {
				$prod_id  = ! empty( $opt['default_product'] ) ? (int) $opt['default_product'] : 0;
				$prod_url = $prod_id > 0 ? get_permalink( $prod_id ) : '';
				$finder_options[] = array(
					'id'          => ! empty( $opt['id'] ) ? sanitize_key( (string) $opt['id'] ) : sanitize_title( (string) $opt['title'] ),
					'icon'        => (string) ( $opt['icon'] ?? '✧' ),
					'title'       => (string) $opt['title'],
					'desc'        => (string) ( $opt['subtitle'] ?? '' ),
					'product_url' => $prod_url ? (string) $prod_url : '',
				);
			}
		}
	}
}

// Fallback options if none in SCF.
if ( empty( $finder_options ) ) {
	$finder_options = array(
		array( 'id' => 'cooling', 'title' => 'Stay Cooler', 'desc' => 'Cooling surfaces and breathable comfort.', 'icon' => '✧', 'product_url' => '' ),
		array( 'id' => 'support', 'title' => 'Feel Supported', 'desc' => 'A steadier, more supportive sleep feel.', 'icon' => '⌁', 'product_url' => '' ),
		array( 'id' => 'pressure_relief', 'title' => 'Ease Pressure', 'desc' => 'Contouring and cushioning comfort.', 'icon' => '∿', 'product_url' => '' ),
		array( 'id' => 'motion_isolation', 'title' => 'Sleep Undisturbed', 'desc' => 'Less disturbance from movement.', 'icon' => '☾', 'product_url' => '' ),
		array( 'id' => 'natural', 'title' => 'Natural Comfort', 'desc' => 'Explore latex and naturally derived materials.', 'icon' => '⌇', 'product_url' => '' ),
		array( 'id' => 'luxury', 'title' => 'Luxury Feel', 'desc' => 'Sophisticated, layered sleep experiences.', 'icon' => '◇', 'product_url' => '' ),
	);
}

// Combinations mapping: combinations of 2 preferences -> WooCommerce product.
$combinations_map = array();
if ( function_exists( 'get_field' ) ) {
	$scf_combinations = get_field( 'finder_combinations', $post_id );
	if ( ! empty( $scf_combinations ) && is_array( $scf_combinations ) ) {
		foreach ( $scf_combinations as $comb ) {
			$p1 = sanitize_key( (string) ( $comb['pref_1'] ?? '' ) );
			$p2 = sanitize_key( (string) ( $comb['pref_2'] ?? '' ) );
			$pid = ! empty( $comb['product'] ) ? (int) $comb['product'] : 0;
			if ( $p1 && $p2 && $pid > 0 ) {
				$prefs = array( $p1, $p2 );
				sort( $prefs );
				$key = implode( '+', $prefs );
				$combinations_map[ $key ] = array(
					'url'   => (string) get_permalink( $pid ),
					'name'  => (string) get_the_title( $pid ),
					'label' => ! empty( $comb['cta_label'] ) ? (string) $comb['cta_label'] : sprintf( __( 'Explore %s →', 'goodnite' ), get_the_title( $pid ) ),
				);
			}
		}
	}
}
?>
<section class="gn-sleep-finder" aria-labelledby="gn-sleep-finder-title" data-sleep-finder data-combinations="<?php echo esc_attr( wp_json_encode( $combinations_map ) ); ?>" data-default-cta="<?php echo esc_attr( $finder_cta_primary_label ); ?>">
	<div class="gn-sleep-finder__inner">
		<div class="gn-sleep-finder__content">
			<h2 id="gn-sleep-finder-title">
				<?php echo esc_html( $finder_title ); ?>
			</h2>

			<p class="gn-sleep-finder__intro">
				<?php echo esc_html( $finder_desc ); ?>
			</p>

			<div class="gn-sleep-finder__question">
				<h3><?php echo esc_html( $finder_prompt ); ?></h3>

				<div class="gn-sleep-finder__options" role="group" aria-label="<?php esc_attr_e( 'Sleep preferences', 'goodnite' ); ?>">
					<?php foreach ( $finder_options as $option ) : ?>
						<button class="gn-sleep-finder__option focus-ring" type="button" data-preference="<?php echo esc_attr( $option['id'] ); ?>" data-product-url="<?php echo esc_attr( $option['product_url'] ); ?>" aria-pressed="false">
							<span class="gn-sleep-finder__icon" aria-hidden="true"><?php echo esc_html( $option['icon'] ); ?></span>
							<span>
								<strong><?php echo esc_html( $option['title'] ); ?></strong>
								<small><?php echo esc_html( $option['desc'] ); ?></small>
							</span>
						</button>
					<?php endforeach; ?>
				</div>

				<p class="gn-sleep-finder__limit" role="status" aria-live="polite"></p>

				<div class="gn-sleep-finder__actions">
					<a class="gn-sleep-finder__cta focus-ring" href="<?php echo esc_url( goodnite_url( $finder_cta_primary_url ) ); ?>" data-finder-cta data-base-url="<?php echo esc_url( goodnite_url( $finder_cta_primary_url ) ); ?>"><?php echo esc_html( $finder_cta_primary_label ); ?> <span aria-hidden="true">→</span></a>
					<a class="gn-sleep-finder__secondary focus-ring" href="<?php echo esc_url( goodnite_url( $finder_cta_secondary_url ) ); ?>"><?php echo esc_html( $finder_cta_secondary_label ); ?></a>
				</div>
			</div>
		</div>

		<div class="gn-sleep-finder__visual">
			<picture>
				<source media="(max-width:767px)" srcset="<?php echo esc_url( $finder_image_url ); ?>">
				<img src="<?php echo esc_url( $finder_image_url ); ?>" width="1448" height="1086" loading="lazy" decoding="async" alt="<?php echo esc_attr( $finder_title ); ?>">
			</picture>
		</div>
	</div>
</section>
