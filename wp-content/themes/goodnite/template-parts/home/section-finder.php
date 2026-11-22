<?php
/**
 * Goodnite sleep preference discovery section.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id          = get_queried_object_id();
$finder_title     = function_exists( 'get_field' ) ? (string) get_field( 'finder_title', $post_id ) : '';
$finder_title     = $finder_title ?: 'Your Sleep. <span class="gn-sleep-finder__way">Your Way.</span>';

$finder_desc      = function_exists( 'get_field' ) ? (string) get_field( 'finder_description', $post_id ) : '';
$finder_desc      = $finder_desc ?: 'Some prefer a cooler night. Others seek firmer support, softer comfort or more natural materials. Tell us what matters to you, and discover a better place to begin.';

$finder_prompt    = function_exists( 'get_field' ) ? (string) get_field( 'finder_prompt_text', $post_id ) : '';
$finder_prompt    = $finder_prompt ?: 'Choose Two Options :';

$finder_pri_lbl   = function_exists( 'get_field' ) ? (string) get_field( 'finder_primary_cta_label', $post_id ) : '';
$finder_pri_lbl   = $finder_pri_lbl ?: 'Find My Sleep Match';

$finder_pri_url   = function_exists( 'get_field' ) ? (string) get_field( 'finder_primary_cta_url', $post_id ) : '';
$finder_pri_url   = $finder_pri_url ?: '/find-your-mattress';

$finder_sec_lbl   = function_exists( 'get_field' ) ? (string) get_field( 'finder_secondary_cta_label', $post_id ) : '';
$finder_sec_lbl   = $finder_sec_lbl ?: 'Explore All Mattresses';

$finder_sec_url   = function_exists( 'get_field' ) ? (string) get_field( 'finder_secondary_cta_url', $post_id ) : '';
$finder_sec_url   = $finder_sec_url ?: '/mattresses';

$finder_img_raw   = function_exists( 'get_field' ) ? get_field( 'finder_image', $post_id ) : null;
$finder_img_url   = goodnite_resolve_image_url( $finder_img_raw, 'images/goodnite-sleep-finder.webp' );

$scf_options      = function_exists( 'get_field' ) ? get_field( 'finder_options', $post_id ) : null;
$options          = array();

if ( ! empty( $scf_options ) && is_array( $scf_options ) ) {
	foreach ( $scf_options as $opt ) {
		$slug  = sanitize_key( $opt['id'] ?? '' );
		$icon  = ! empty( $opt['icon'] ) ? (string) $opt['icon'] : '○';
		$title = ! empty( $opt['title'] ) ? (string) $opt['title'] : '';
		if ( $slug && $title ) {
			$options[] = array(
				'slug'  => $slug,
				'icon'  => $icon,
				'title' => $title,
			);
		}
	}
}

if ( empty( $options ) ) {
	$options = array(
		array( 'slug' => 'cooling', 'icon' => '○', 'title' => 'Stay Cooler' ),
		array( 'slug' => 'support', 'icon' => '○', 'title' => 'Feel Supported' ),
		array( 'slug' => 'pressure_relief', 'icon' => '○', 'title' => 'Ease Pressure' ),
		array( 'slug' => 'motion_isolation', 'icon' => '○', 'title' => 'Sleep Undisturbed' ),
	);
}
?>
<section class="gn-home-sleep-finder gn-sleep-finder" data-section="sleep-finder" data-sleep-finder aria-labelledby="gn-sleep-finder-title">
  <div class="gn-sleep-finder__inner">
    <div class="gn-sleep-finder__content">
      <h2 id="gn-sleep-finder-title"><?php echo wp_kses_post( $finder_title ); ?></h2>
      <p class="gn-sleep-finder__intro"><?php echo esc_html( $finder_desc ); ?></p>
      <div class="gn-sleep-finder__question">
        <h3><?php echo esc_html( $finder_prompt ); ?></h3>
        <div class="gn-sleep-finder__options" role="group" aria-label="<?php esc_attr_e( 'Sleep preferences', 'goodnite' ); ?>">
          <?php foreach ( $options as $opt ) : ?>
            <button class="gn-sleep-finder__option" type="button" data-preference="<?php echo esc_attr( $opt['slug'] ); ?>" aria-pressed="false"><span class="gn-sleep-finder__icon" aria-hidden="true"><?php echo esc_html( $opt['icon'] ); ?></span><span><strong><?php echo esc_html( $opt['title'] ); ?></strong></span></button>
          <?php endforeach; ?>
        </div>
        <p class="gn-sleep-finder__limit" role="status" aria-live="polite"></p>
        <div class="gn-sleep-finder__actions">
          <a class="gn-sleep-finder__cta" href="<?php echo esc_url( goodnite_url( $finder_pri_url ) ); ?>"><?php echo esc_html( $finder_pri_lbl ); ?> <span aria-hidden="true">&rarr;</span></a>
          <a class="gn-sleep-finder__secondary" href="<?php echo esc_url( goodnite_url( $finder_sec_url ) ); ?>"><?php echo esc_html( $finder_sec_lbl ); ?></a>
        </div>
      </div>
    </div>
    <div class="gn-sleep-finder__visual"><picture><source media="(max-width:767px)" srcset="<?php echo esc_url( $finder_img_url ); ?>"><img src="<?php echo esc_url( $finder_img_url ); ?>" width="1448" height="1086" loading="lazy" decoding="async" alt="<?php esc_attr_e( 'Softly creased white bedding in a calm bedroom at morning', 'goodnite' ); ?>"></picture></div>
  </div>
</section>
