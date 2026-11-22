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

$scf_img          = function_exists( 'get_field' ) ? get_field( 'finder_image', $post_id ) : null;
$finder_img_url   = is_array( $scf_img ) ? ( $scf_img['url'] ?? '' ) : ( is_string( $scf_img ) ? $scf_img : '' );
if ( empty( $finder_img_url ) ) {
	$finder_img_url = goodnite_asset_url( 'images/goodnite-sleep-finder.webp' );
}
?>
<section class="gn-home-sleep-finder gn-sleep-finder" data-section="sleep-finder" data-sleep-finder aria-labelledby="gn-sleep-finder-title">
  <div class="gn-sleep-finder__inner">
    <div class="gn-sleep-finder__content">
      <h2 id="gn-sleep-finder-title"><?php echo wp_kses_post( $finder_title ); ?></h2>
      <p class="gn-sleep-finder__intro"><?php echo esc_html( $finder_desc ); ?></p>
      <div class="gn-sleep-finder__question"><h3><?php echo esc_html( $finder_prompt ); ?></h3><div class="gn-sleep-finder__options" role="group" aria-label="Sleep preferences">
        <button class="gn-sleep-finder__option" type="button" data-preference="cooling" aria-pressed="false"><span class="gn-sleep-finder__icon" aria-hidden="true">○</span><span><strong>Stay Cooler</strong></span></button>
        <button class="gn-sleep-finder__option" type="button" data-preference="support" aria-pressed="false"><span class="gn-sleep-finder__icon" aria-hidden="true">○</span><span><strong>Feel Supported</strong></span></button>
        <button class="gn-sleep-finder__option" type="button" data-preference="pressure_relief" aria-pressed="false"><span class="gn-sleep-finder__icon" aria-hidden="true">○</span><span><strong>Ease Pressure</strong></span></button>
        <button class="gn-sleep-finder__option" type="button" data-preference="motion_isolation" aria-pressed="false"><span class="gn-sleep-finder__icon" aria-hidden="true">○</span><span><strong>Sleep Undisturbed</strong></span></button>
      </div><p class="gn-sleep-finder__limit" role="status" aria-live="polite"></p><div class="gn-sleep-finder__actions"><a class="gn-sleep-finder__cta" href="<?php echo esc_url( goodnite_url( $finder_pri_url ) ); ?>"><?php echo esc_html( $finder_pri_lbl ); ?> <span aria-hidden="true">→</span></a><a class="gn-sleep-finder__secondary" href="<?php echo esc_url( goodnite_url( $finder_sec_url ) ); ?>"><?php echo esc_html( $finder_sec_lbl ); ?></a></div></div>
    </div>
    <div class="gn-sleep-finder__visual"><picture><source media="(max-width:767px)" srcset="<?php echo esc_url( $finder_img_url ); ?>"><img src="<?php echo esc_url( $finder_img_url ); ?>" width="1448" height="1086" loading="lazy" decoding="async" alt="Softly creased white bedding in a calm bedroom at morning"></picture></div>
  </div>
</section>
