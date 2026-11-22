<?php
/**
 * Goodnite brand USP marquee credibility strip with SCF repeater support.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id   = get_queried_object_id();
$usp_items = array();

// 1. Resolve from SCF repeater if populated.
if ( function_exists( 'get_field' ) ) {
	$scf_usps = get_field( 'usp_items', $post_id );
	if ( ! empty( $scf_usps ) && is_array( $scf_usps ) ) {
		foreach ( $scf_usps as $item ) {
			$title = ! empty( $item['text'] ) ? (string) $item['text'] : ( ! empty( $item['title'] ) ? (string) $item['title'] : '' );
			if ( empty( $title ) ) {
				continue;
			}

			$icon_img = '';
			if ( ! empty( $item['icon'] ) ) {
				if ( is_array( $item['icon'] ) && ! empty( $item['icon']['url'] ) ) {
					$icon_img = (string) $item['icon']['url'];
				} elseif ( is_numeric( $item['icon'] ) ) {
					$icon_img = (string) wp_get_attachment_image_url( (int) $item['icon'], 'thumbnail' );
				} elseif ( is_string( $item['icon'] ) ) {
					$icon_img = (string) $item['icon'];
				}
			}

			$usp_items[] = array(
				'title'    => $title,
				'icon_img' => $icon_img,
				'svg_icon' => '',
			);
		}
	}
}

// 2. Fallback default USPs.
if ( empty( $usp_items ) ) {
	$usp_items = array(
		array(
			'title'    => __( 'Since 1989', 'goodnite' ),
			'icon_img' => '',
			'svg_icon' => '<circle cx="12" cy="12" r="8.5"/><path d="M3.8 9h16.4M3.8 15h16.4M12 3.5c2.2 2.3 3.3 5.1 3.3 8.5S14.2 18.2 12 20.5C9.8 18.2 8.7 15.4 8.7 12S9.8 5.8 12 3.5Z"/>',
		),
		array(
			'title'    => __( '5M+ Mattresses Sold', 'goodnite' ),
			'icon_img' => '',
			'svg_icon' => '<path d="M4 18.5h16M5.5 15.5h13M7 12.5h10M8.5 9.5h7M10 6.5h4"/><path d="M4 18.5 10 3.5M20 18.5 14 3.5"/>',
		),
		array(
			'title'    => __( '15+ Countries Reached', 'goodnite' ),
			'icon_img' => '',
			'svg_icon' => '<path d="M4 20V8.5L12 4l8 4.5V20M7 20v-5h10v5M9 10h1M14 10h1M9 12h1M14 12h1"/>',
		),
		array(
			'title'    => __( 'Integrated Expertise', 'goodnite' ),
			'icon_img' => '',
			'svg_icon' => '<path d="M4 18.5h16M5.5 15.5h13M7 12.5h10M8.5 9.5h7M10 6.5h4"/><path d="M4 18.5 10 3.5M20 18.5 14 3.5"/>',
		),
		array(
			'title'    => __( 'Trusted by Hotels', 'goodnite' ),
			'icon_img' => '',
			'svg_icon' => '<path d="M4 20V8.5L12 4l8 4.5V20M7 20v-5h10v5M9 10h1M14 10h1M9 12h1M14 12h1"/>',
		),
		array(
			'title'    => __( 'Engineered Comfort', 'goodnite' ),
			'icon_img' => '',
			'svg_icon' => '<path d="M4 17.5h16M5 17.5V12h14v5.5M7 12V9.5h4V12M13 12V8h4v4M4 20h2M18 20h2"/>',
		),
	);
}
?>
<section class="gn-home-usp-strip gn-usp-strip" data-section="brand-usp-strip" aria-label="<?php esc_attr_e( 'Goodnite brand highlights', 'goodnite' ); ?>">
	<div class="gn-usp-strip__viewport">
		<div class="gn-usp-strip__track">
			<?php for ( $copy = 0; $copy < 3; $copy++ ) : ?>
				<div class="gn-usp-strip__group"<?php echo $copy > 0 ? ' aria-hidden="true"' : ''; ?>>
					<?php foreach ( $usp_items as $usp ) : ?>
						<div class="gn-usp-strip__item">
							<?php if ( ! empty( $usp['icon_img'] ) ) : ?>
								<img class="gn-usp-strip__icon-img" src="<?php echo esc_url( $usp['icon_img'] ); ?>" alt="" width="28" height="28" loading="lazy" style="width:28px;height:auto;object-fit:contain;flex-shrink:0;">
							<?php elseif ( ! empty( $usp['svg_icon'] ) ) : ?>
								<svg class="gn-usp-strip__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><?php echo $usp['svg_icon']; ?></svg>
							<?php else : ?>
								<span class="gn-usp-strip__icon" aria-hidden="true" style="display:grid;place-items:center;font-size:18px;color:var(--brand-red,#c02b2b);">✧</span>
							<?php endif; ?>
							<div class="gn-usp-strip__copy">
								<strong class="gn-usp-strip__title"><?php echo esc_html( $usp['title'] ); ?></strong>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</section>
