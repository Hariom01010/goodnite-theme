<?php
/**
 * Goodnite brand USP strip.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id   = get_queried_object_id();
$scf_usps  = function_exists( 'get_field' ) ? get_field( 'usp_items', $post_id ) : null;
$usp_items = array();

if ( ! empty( $scf_usps ) && is_array( $scf_usps ) ) {
	foreach ( $scf_usps as $item ) {
		$text     = ! empty( $item['text'] ) ? (string) $item['text'] : '';
		$icon_arr = $item['icon'] ?? null;
		$icon_url = is_array( $icon_arr ) ? ( $icon_arr['url'] ?? '' ) : ( is_string( $icon_arr ) ? $icon_arr : '' );
		if ( $text ) {
			$usp_items[] = array(
				'title'    => $text,
				'icon_url' => $icon_url,
				'icon'     => '',
			);
		}
	}
}

if ( empty( $usp_items ) ) {
	$usp_items = array(
		array(
			'title'    => 'Since 1989',
			'icon_url' => '',
			'icon'     => '<circle cx="12" cy="12" r="8.5"/><path d="M3.8 9h16.4M3.8 15h16.4M12 3.5c2.2 2.3 3.3 5.1 3.3 8.5S14.2 18.2 12 20.5C9.8 18.2 8.7 15.4 8.7 12S9.8 5.8 12 3.5Z"/>',
		),
		array(
			'title'    => '5M+ Mattresses Sold',
			'icon_url' => '',
			'icon'     => '<path d="M4 18.5h16M5.5 15.5h13M7 12.5h10M8.5 9.5h7M10 6.5h4"/><path d="M4 18.5 10 3.5M20 18.5 14 3.5"/>',
		),
		array(
			'title'    => '15+ Countries Reached',
			'icon_url' => '',
			'icon'     => '<path d="M4 20V8.5L12 4l8 4.5V20M7 20v-5h10v5M9 10h1M14 10h1M9 12h1M14 12h1"/>',
		),
		array(
			'title'    => 'Integrated Expertise',
			'icon_url' => '',
			'icon'     => '<path d="M4 18.5h16M5.5 15.5h13M7 12.5h10M8.5 9.5h7M10 6.5h4"/><path d="M4 18.5 10 3.5M20 18.5 14 3.5"/>',
		),
		array(
			'title'    => 'Trusted by Hotels',
			'icon_url' => '',
			'icon'     => '<path d="M4 20V8.5L12 4l8 4.5V20M7 20v-5h10v5M9 10h1M14 10h1M9 12h1M14 12h1"/>',
		),
		array(
			'title'    => 'Engineered Comfort',
			'icon_url' => '',
			'icon'     => '<path d="M4 17.5h16M5 17.5V12h14v5.5M7 12V9.5h4V12M13 12V8h4v4M4 20h2M18 20h2"/>',
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
							<?php if ( ! empty( $usp['icon_url'] ) ) : ?>
								<img class="gn-usp-strip__icon" src="<?php echo esc_url( $usp['icon_url'] ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async">
							<?php else : ?>
								<svg class="gn-usp-strip__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><?php echo $usp['icon']; ?></svg>
							<?php endif; ?>
							<div class="gn-usp-strip__copy"><strong class="gn-usp-strip__title"><?php echo esc_html( $usp['title'] ); ?></strong></div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</section>
