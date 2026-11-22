<?php
/**
 * Interactive 2-preference "Your Sleep. Your Way." finder section.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

$finder_title = function_exists( 'get_field' ) && get_field( 'finder_title', $post_id ) ? (string) get_field( 'finder_title', $post_id ) : 'Your Sleep. Your Way.';
$finder_body  = function_exists( 'get_field' ) && get_field( 'finder_body', $post_id ) ? (string) get_field( 'finder_body', $post_id ) : (string) goodnite_mod( 'goodnite_finder_body', 'Some prefer a cooler night. Others seek firmer support, softer comfort or more natural materials. Tell us what matters to you, and discover a better place to begin.' );

$finder_options = array(
	array( 'id' => 'cooling', 'title' => 'Stay Cooler', 'desc' => 'Cooling surfaces and breathable comfort.', 'icon' => '✧' ),
	array( 'id' => 'support', 'title' => 'Feel Supported', 'desc' => 'A steadier, more supportive sleep feel.', 'icon' => '⌁' ),
	array( 'id' => 'pressure_relief', 'title' => 'Ease Pressure', 'desc' => 'Contouring and cushioning comfort.', 'icon' => '∿' ),
	array( 'id' => 'motion_isolation', 'title' => 'Sleep Undisturbed', 'desc' => 'Less disturbance from movement.', 'icon' => '☾' ),
	array( 'id' => 'natural', 'title' => 'Natural Comfort', 'desc' => 'Explore latex and naturally derived materials.', 'icon' => '⌇' ),
	array( 'id' => 'luxury', 'title' => 'Luxury Feel', 'desc' => 'Sophisticated, layered sleep experiences.', 'icon' => '◇' ),
);
?>
<section class="gn-sleep-finder" aria-labelledby="gn-sleep-finder-title" data-sleep-finder>
	<div class="gn-sleep-finder__inner">
		<div class="gn-sleep-finder__content">
			<h2 id="gn-sleep-finder-title">
				<?php esc_html_e( 'Your Sleep.', 'goodnite' ); ?> <span class="gn-sleep-finder__way"><?php esc_html_e( 'Your Way.', 'goodnite' ); ?></span>
			</h2>

			<p class="gn-sleep-finder__intro">
				<?php echo esc_html( $finder_body ); ?>
			</p>

			<div class="gn-sleep-finder__question">
				<h3><?php esc_html_e( 'Choose Two Options :', 'goodnite' ); ?></h3>

				<div class="gn-sleep-finder__options" role="group" aria-label="<?php esc_attr_e( 'Sleep preferences', 'goodnite' ); ?>">
					<?php foreach ( $finder_options as $option ) : ?>
						<button class="gn-sleep-finder__option focus-ring" type="button" data-preference="<?php echo esc_attr( $option['id'] ); ?>" aria-pressed="false">
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
					<a class="gn-sleep-finder__cta focus-ring" href="<?php echo esc_url( goodnite_url( '/find-your-mattress' ) ); ?>" data-finder-cta><?php esc_html_e( 'Find My Sleep Match', 'goodnite' ); ?> <span aria-hidden="true">→</span></a>
					<a class="gn-sleep-finder__secondary focus-ring" href="<?php echo esc_url( goodnite_url( '/mattresses' ) ); ?>"><?php esc_html_e( 'Explore All Mattresses', 'goodnite' ); ?></a>
				</div>
			</div>
		</div>

		<div class="gn-sleep-finder__visual">
			<picture>
				<source media="(max-width:767px)" srcset="<?php echo esc_url( goodnite_asset_url( 'images/goodnite-sleep-finder.webp' ) ); ?>">
				<img src="<?php echo esc_url( goodnite_asset_url( 'images/goodnite-sleep-finder.webp' ) ); ?>" width="1448" height="1086" loading="lazy" decoding="async" alt="<?php esc_attr_e( 'A young woman sleeping peacefully in a calm luxury bedroom', 'goodnite' ); ?>">
			</picture>
		</div>
	</div>
</section>
