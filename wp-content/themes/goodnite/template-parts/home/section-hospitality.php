<?php
/**
 * Homepage hospitality showcase.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id          = get_queried_object_id();
$hosp_title       = function_exists( 'get_field' ) ? (string) get_field( 'hospitality_title', $post_id ) : '';
$hosp_title       = $hosp_title ?: 'Exceptional Sleep for Exceptional Stays.';

$hosp_desc        = function_exists( 'get_field' ) ? (string) get_field( 'hospitality_description', $post_id ) : '';
$hosp_desc        = $hosp_desc ?: 'For hotels, resorts and hospitality destinations, Goodnite brings decades of international mattress expertise to the guest experience, delivering thoughtfully engineered comfort where expectations are high.';

$hosp_cta_lbl     = function_exists( 'get_field' ) ? (string) get_field( 'hospitality_cta_label', $post_id ) : '';
$hosp_cta_lbl     = $hosp_cta_lbl ?: 'Explore Hospitality Solutions';

$hosp_cta_url     = function_exists( 'get_field' ) ? (string) get_field( 'hospitality_cta_url', $post_id ) : '';
$hosp_cta_url     = $hosp_cta_url ?: '/hospitality';

$hosp_client_lbl  = function_exists( 'get_field' ) ? (string) get_field( 'hospitality_clients_label', $post_id ) : '';
$hosp_client_lbl  = $hosp_client_lbl ?: 'OUR HOSPITALITY CLIENT PORTFOLIO';

$scf_img          = function_exists( 'get_field' ) ? get_field( 'hospitality_image', $post_id ) : null;
$hosp_img_url     = is_array( $scf_img ) ? ( $scf_img['url'] ?? '' ) : ( is_string( $scf_img ) ? $scf_img : '' );
if ( empty( $hosp_img_url ) ) {
	$hosp_img_url = goodnite_asset_url( 'images/goodnite-hospitality-suite-v2.png' );
}

$hospitality_logos = array(
	array( 'name' => 'Taj', 'file' => '01_Taj 1.png' ),
	array( 'name' => 'Grand Hyatt', 'file' => '02_Grand_Hyatt 1.png' ),
	array( 'name' => 'Radisson', 'file' => '03_Radisson 1.png' ),
	array( 'name' => 'Wyndham Hotels & Resorts', 'file' => '04_Wyndham 1.png' ),
	array( 'name' => 'ITC Hotels', 'file' => '05_ITC_Hotels 1.png' ),
	array( 'name' => 'Marriott', 'file' => '06_Marriott 1.png' ),
	array( 'name' => 'IHG', 'file' => '07_IHG 1.png' ),
	array( 'name' => 'Westin', 'file' => '08_Westin 1.png' ),
	array( 'name' => 'Godwin Hotels', 'file' => '09_Godwin_Hotels 1.png' ),
	array( 'name' => 'Sayaji', 'file' => '10_Sayaji 1.png' ),
	array( 'name' => 'Ascott', 'file' => '11_Ascott 1.png' ),
	array( 'name' => 'Maayafushi Maldives', 'file' => '11_Ascott 2.png' ),
	array( 'name' => 'Mayfair', 'file' => '13_Mayfair 1.png' ),
	array( 'name' => 'Atmantan', 'file' => '14_Atmantan 1.png' ),
	array( 'name' => 'ibis Hotel', 'file' => '15_ibis_Hotel 1.png' ),
	array( 'name' => 'The Fern', 'file' => '16_The_Fern 1.png' ),
	array( 'name' => 'Lemon Tree Hotels', 'file' => '17_Lemon_Tree 1.png' ),
	array( 'name' => 'Sarovar', 'file' => '18_Sarovar 1.png' ),
	array( 'name' => 'The Park', 'file' => '19_The_Park 1.png' ),
	array( 'name' => 'Lords Hotels & Resorts', 'file' => '20_Lords 1.png' ),
);
?>
<section class="gn-home-hospitality gn-hospitality" data-section="hospitality-showcase" aria-labelledby="hospitality-heading">
	<div class="gn-hospitality__visual"><img src="<?php echo esc_url( $hosp_img_url ); ?>" alt="Contemporary luxury hotel suite with a prominent king-size bed and layered ivory bedding" width="1400" height="1600" loading="lazy" decoding="async"></div>
	<div class="gn-hospitality__content reveal">
		<h2 id="hospitality-heading"><?php echo esc_html( $hosp_title ); ?></h2>
		<p class="gn-hospitality__description"><?php echo esc_html( $hosp_desc ); ?></p>
		<a class="button-link primary gn-hospitality__cta" href="<?php echo esc_url( goodnite_url( $hosp_cta_url ) ); ?>"><?php echo esc_html( $hosp_cta_lbl ); ?> <span aria-hidden="true">→</span></a>
		<div class="gn-hospitality__clients">
			<div class="gn-hospitality__clients-label"><?php echo esc_html( $hosp_client_lbl ); ?></div>
			<div class="gn-hospitality__marquee" data-hospitality-marquee tabindex="0" aria-label="<?php esc_attr_e( 'Hospitality client portfolio logos', 'goodnite' ); ?>">
				<div class="gn-hospitality__marquee-track">
					<div class="gn-hospitality__logo-set">
						<?php foreach ( $hospitality_logos as $logo ) : ?><span class="gn-hospitality__logo" role="img" aria-label="<?php echo esc_attr( $logo['name'] ); ?>"><img src="<?php echo esc_url( goodnite_asset_url( 'Hotel Logos/' . $logo['file'] ) ); ?>" alt="<?php echo esc_attr( $logo['name'] ); ?>" loading="lazy" decoding="async"></span><?php endforeach; ?>
					</div>
					<div class="gn-hospitality__logo-set" aria-hidden="true">
						<?php foreach ( $hospitality_logos as $logo ) : ?><span class="gn-hospitality__logo"><img src="<?php echo esc_url( goodnite_asset_url( 'Hotel Logos/' . $logo['file'] ) ); ?>" alt="" loading="lazy" decoding="async"></span><?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
