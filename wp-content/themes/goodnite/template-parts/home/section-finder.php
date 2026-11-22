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
?>
<section class="gn-home-sleep-finder gn-sleep-finder" data-section="sleep-finder" data-sleep-finder aria-labelledby="gn-sleep-finder-title">
  <div class="gn-sleep-finder__inner">
    <div class="gn-sleep-finder__content">
      <h2 id="gn-sleep-finder-title">Your Sleep. <span class="gn-sleep-finder__way">Your Way.</span></h2>
      <p class="gn-sleep-finder__intro">Some prefer a cooler night. Others seek firmer support, softer comfort or more natural materials. Tell us what matters to you, and discover a better place to begin.</p>
      <div class="gn-sleep-finder__question"><h3>Choose Two Options :</h3><div class="gn-sleep-finder__options" role="group" aria-label="Sleep preferences">
        <button class="gn-sleep-finder__option" type="button" data-preference="cooling" aria-pressed="false"><span class="gn-sleep-finder__icon" aria-hidden="true">○</span><span><strong>Stay Cooler</strong></span></button>
        <button class="gn-sleep-finder__option" type="button" data-preference="support" aria-pressed="false"><span class="gn-sleep-finder__icon" aria-hidden="true">○</span><span><strong>Feel Supported</strong></span></button>
        <button class="gn-sleep-finder__option" type="button" data-preference="pressure_relief" aria-pressed="false"><span class="gn-sleep-finder__icon" aria-hidden="true">○</span><span><strong>Ease Pressure</strong></span></button>
        <button class="gn-sleep-finder__option" type="button" data-preference="motion_isolation" aria-pressed="false"><span class="gn-sleep-finder__icon" aria-hidden="true">○</span><span><strong>Sleep Undisturbed</strong></span></button>
      </div><p class="gn-sleep-finder__limit" role="status" aria-live="polite"></p><div class="gn-sleep-finder__actions"><a class="gn-sleep-finder__cta" href="<?php echo esc_url( goodnite_url( '/find-your-mattress' ) ); ?>">Find My Sleep Match <span aria-hidden="true">→</span></a><a class="gn-sleep-finder__secondary" href="<?php echo esc_url( goodnite_url( '/mattresses' ) ); ?>">Explore All Mattresses</a></div></div>
    </div>
    <div class="gn-sleep-finder__visual"><picture><source media="(max-width:767px)" srcset="<?php echo esc_url( goodnite_asset_url( 'images/goodnite-sleep-finder.webp' ) ); ?>"><img src="<?php echo esc_url( goodnite_asset_url( 'images/goodnite-sleep-finder.webp' ) ); ?>" width="1448" height="1086" loading="lazy" decoding="async" alt="Softly creased white bedding in a calm bedroom at morning"></picture></div>
  </div>
</section>
