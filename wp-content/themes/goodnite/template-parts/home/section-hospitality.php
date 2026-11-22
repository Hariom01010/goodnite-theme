<?php
/**
 * Goodnite Hospitality showcase recreated from Goodnite Website/Homepage/templates/home/hospitality-showcase.php
 * Fully wired to SCF fields with infinite logo marquee.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

// 1. Image.
$img_url = '';
if ( function_exists( 'get_field' ) ) {
	$img_val = get_field( 'hospitality_image', $post_id );
	if ( ! empty( $img_val ) ) {
		$img_url = is_array( $img_val ) ? (string) ( $img_val['url'] ?? '' ) : (string) wp_get_attachment_image_url( (int) $img_val, 'full' );
	}
}
if ( empty( $img_url ) ) {
	$img_url = goodnite_asset_url( 'images/goodnite-hospitality-suite-v2.png' );
}

// 2. Title.
$title = function_exists( 'get_field' ) && get_field( 'hospitality_title', $post_id )
	? (string) get_field( 'hospitality_title', $post_id )
	: (string) goodnite_mod( 'goodnite_hospitality_title', 'Exceptional Sleep for Exceptional Stays.' );

// 3. Description.
$desc = function_exists( 'get_field' ) && get_field( 'hospitality_description', $post_id )
	? (string) get_field( 'hospitality_description', $post_id )
	: (string) ( function_exists( 'get_field' ) && get_field( 'hospitality_body', $post_id )
		? get_field( 'hospitality_body', $post_id )
		: goodnite_mod( 'goodnite_hospitality_body', 'For hotels, resorts and hospitality destinations, Goodnite brings decades of international mattress expertise to the guest experience, delivering thoughtfully engineered comfort where expectations are high.' )
	);

// 4. CTA Text & URL.
$cta_label = function_exists( 'get_field' ) && get_field( 'hospitality_cta_label', $post_id )
	? (string) get_field( 'hospitality_cta_label', $post_id )
	: (string) goodnite_mod( 'goodnite_hospitality_cta_label', 'Explore Hospitality Solutions' );

$cta_url = function_exists( 'get_field' ) && get_field( 'hospitality_cta_url', $post_id )
	? (string) get_field( 'hospitality_cta_url', $post_id )
	: (string) goodnite_mod( 'goodnite_hospitality_cta_url', '/hospitality' );

// 5. OUR HOSPITALITY CLIENT PORTFOLIO text.
$clients_label = function_exists( 'get_field' ) && get_field( 'hospitality_clients_label', $post_id )
	? (string) get_field( 'hospitality_clients_label', $post_id )
	: 'OUR HOSPITALITY CLIENT PORTFOLIO';

// 6. Repeater for client logos.
$hospitality_logos = array();
if ( function_exists( 'get_field' ) ) {
	$scf_logos = get_field( 'hospitality_logos', $post_id );
	if ( ! empty( $scf_logos ) && is_array( $scf_logos ) ) {
		foreach ( $scf_logos as $item ) {
			$logo_src = '';
			if ( ! empty( $item['logo_image'] ) ) {
				if ( is_array( $item['logo_image'] ) && ! empty( $item['logo_image']['url'] ) ) {
					$logo_src = (string) $item['logo_image']['url'];
				} elseif ( is_numeric( $item['logo_image'] ) ) {
					$logo_src = (string) wp_get_attachment_image_url( (int) $item['logo_image'], 'full' );
				} elseif ( is_string( $item['logo_image'] ) ) {
					$logo_src = (string) $item['logo_image'];
				}
			}

			if ( ! empty( $logo_src ) ) {
				$hospitality_logos[] = array(
					'name' => (string) ( $item['client_name'] ?? 'Hospitality Partner' ),
					'src'  => $logo_src,
				);
			}
		}
	}
}

// Fallback to default prototype logo images.
if ( empty( $hospitality_logos ) ) {
	$default_logos = array(
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

	foreach ( $default_logos as $dl ) {
		$hospitality_logos[] = array(
			'name' => $dl['name'],
			'src'  => goodnite_asset_url( 'Hotel Logos/' . $dl['file'] ),
		);
	}
}
?>
<section class="gn-home-hospitality gn-hospitality" data-section="hospitality-showcase" aria-labelledby="hospitality-heading">
	<div class="gn-hospitality__visual">
		<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php esc_attr_e( 'Contemporary luxury hotel suite with a prominent king-size bed and layered ivory bedding', 'goodnite' ); ?>" width="1400" height="1600" loading="lazy" decoding="async">
	</div>

	<div class="gn-hospitality__content reveal">
		<h2 id="hospitality-heading"><?php echo esc_html( $title ); ?></h2>

		<p class="gn-hospitality__description">
			<?php echo esc_html( $desc ); ?>
		</p>

		<a class="button-link primary gn-hospitality__cta focus-ring" href="<?php echo esc_url( goodnite_url( $cta_url ) ); ?>">
			<?php echo esc_html( $cta_label ); ?> <span aria-hidden="true">→</span>
		</a>

		<div class="gn-hospitality__clients">
			<div class="gn-hospitality__clients-label">
				<?php echo esc_html( $clients_label ); ?>
			</div>

			<div class="gn-hospitality__marquee" data-hospitality-marquee tabindex="0" aria-label="<?php esc_attr_e( 'Hospitality client portfolio logos', 'goodnite' ); ?>">
				<div class="gn-hospitality__marquee-track">
					<div class="gn-hospitality__logo-set">
						<?php foreach ( $hospitality_logos as $logo ) : ?>
							<span class="gn-hospitality__logo" role="img" aria-label="<?php echo esc_attr( $logo['name'] ); ?>">
								<img src="<?php echo esc_url( $logo['src'] ); ?>" alt="<?php echo esc_attr( $logo['name'] ); ?>" loading="lazy" decoding="async">
							</span>
						<?php endforeach; ?>
					</div>

					<div class="gn-hospitality__logo-set" aria-hidden="true">
						<?php foreach ( $hospitality_logos as $logo ) : ?>
							<span class="gn-hospitality__logo">
								<img src="<?php echo esc_url( $logo['src'] ); ?>" alt="" loading="lazy" decoding="async">
							</span>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
