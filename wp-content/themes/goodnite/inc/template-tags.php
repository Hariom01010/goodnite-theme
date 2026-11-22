<?php
/**
 * Presentation helpers, SVG icons, and default content fixtures.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the URL to a theme asset.
 *
 * @param string $path Relative path inside assets/.
 * @return string
 */
function goodnite_asset_url( string $path ): string {
	if ( '' === $path ) {
		return '';
	}
	if ( 0 === strpos( $path, 'http://' ) || 0 === strpos( $path, 'https://' ) || 0 === strpos( $path, '//' ) ) {
		return $path;
	}
	return trailingslashit( get_template_directory_uri() ) . 'assets/' . ltrim( $path, '/' );
}

/**
 * Wrapper for get_theme_mod with fallback.
 *
 * @param string $key Theme mod key.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function goodnite_mod( string $key, mixed $default = '' ): mixed {
	return get_theme_mod( $key, $default );
}

/**
 * Format a heading with an optional line break after the first period.
 *
 * @param string $text Raw text.
 * @param bool   $break_after_first_period Whether to split at the first period.
 * @return string Escaped HTML.
 */
function goodnite_heading( string $text, bool $break_after_first_period = false ): string {
	$text = trim( $text );
	if ( $break_after_first_period && false !== strpos( $text, '.' ) ) {
		$parts = explode( '.', $text, 2 );
		return esc_html( trim( $parts[0] ) . '.' ) . '<br>' . esc_html( trim( $parts[1] ) );
	}
	return esc_html( $text );
}

/**
 * Resolve an internal or external URL.
 *
 * @param mixed  $value Link destination.
 * @param string $fallback Fallback path.
 * @return string Escaped URL.
 */
function goodnite_url( mixed $value, string $fallback = '/' ): string {
	$value = is_string( $value ) && '' !== trim( $value ) ? trim( $value ) : $fallback;
	if ( '#' === $value || preg_match( '#^(https?:|mailto:|tel:)#i', $value ) ) {
		return $value;
	}
	return home_url( '/' . ltrim( $value, '/' ) );
}

/**
 * Render an accessible inline SVG icon.
 *
 * @param string $name Name of the icon.
 * @param int    $size Pixel width & height.
 * @param float  $stroke_width Stroke thickness.
 * @return string
 */
function goodnite_icon( string $name, int $size = 18, float $stroke_width = 2.0 ): string {
	$icons = array(
		'arrow-up-right' => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
		'arrow-right'    => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'chevron-down'   => '<path d="m6 9 6 6 6-6"/>',
		'chevron-left'   => '<path d="m15 18-6-6 6-6"/>',
		'chevron-right'  => '<path d="m9 18 6-6-6-6"/>',
		'menu'           => '<path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/>',
		'close'          => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'search'         => '<path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>',
		'shopping-bag'   => '<path d="M16 10a4 4 0 0 1-8 0"/><path d="M3.103 6.034h17.794"/><path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"/>',
		'cart-simple'    => '<path d="M16 10a4 4 0 0 0-8 0"/><path d="M3.5 7h17l-1 14h-15z"/><path d="M6 7 8 3h8l2 4"/>',
		'user'           => '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
		'check'          => '<path d="M20 6 9 17l-5-5"/>',
		'layers'         => '<path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z"/><path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12"/><path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17"/>',
		'instagram'      => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".8" fill="currentColor" stroke="none"/>',
		'youtube'        => '<rect x="3" y="5" width="18" height="14" rx="4"/><path d="m10 9 5 3-5 3z" fill="currentColor" stroke="none"/>',
		'linkedin'       => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 10v6M8 7.5v.01M12 16v-3.2a2.3 2.3 0 0 1 4.6 0V16M12 10v6"/>',
		'location'       => '<path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
		'phone'          => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92Z"/>',
		'mail'           => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	$class = 'user' === $name ? 'user-round' : $name;

	return sprintf(
		'<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="goodnite-icon goodnite-icon--%4$s" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%2$s" stroke-linecap="round" stroke-linejoin="round">%3$s</svg>',
		absint( $size ),
		esc_attr( (string) $stroke_width ),
		$icons[ $name ],
		esc_attr( $class )
	);
}

/**
 * Output the official Goodnite brand logo.
 */
function goodnite_logo(): void {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		printf(
			'<a class="brand-logo-link focus-ring" href="%1$s" rel="home" aria-label="%2$s">%3$s</a>',
			esc_url( home_url( '/' ) ),
			esc_attr__( 'Goodnite India home', 'goodnite' ),
			wp_get_attachment_image(
				$custom_logo_id,
				'full',
				false,
				array(
					'class' => 'brand-logo-image',
					'alt'   => esc_attr__( 'Goodnite', 'goodnite' ),
				)
			)
		);
		return;
	}

	// Canonical brand artwork fallback.
	printf(
		'<a class="brand-logo-link focus-ring" href="%1$s" rel="home" aria-label="%2$s"><img class="brand-logo-image" src="%3$s" alt="Goodnite 1989" width="180" height="48"></a>',
		esc_url( home_url( '/' ) ),
		esc_attr__( 'Goodnite India home', 'goodnite' ),
		esc_url( goodnite_asset_url( 'images/Brand Logo.png' ) )
	);
}

/**
 * Render a standardized button link.
 *
 * @param string $label Button label.
 * @param string $url Destination link.
 * @param string $variant Button style variant (primary, secondary-light, text, outline).
 * @param string $extra_classes Additional CSS classes.
 */
function goodnite_button( string $label, string $url, string $variant = 'primary', string $extra_classes = '' ): void {
	$allowed = array( 'primary', 'secondary-light', 'outline', 'text' );
	$variant = in_array( $variant, $allowed, true ) ? $variant : 'primary';
	$focus_class = 'text' === $variant ? '' : ' focus-ring';
	$classes = trim( sprintf( 'button-link %s%s %s', $variant, $focus_class, $extra_classes ) );

	printf(
		'<a class="%1$s" href="%2$s">%3$s%4$s</a>',
		esc_attr( $classes ),
		esc_url( goodnite_url( $url ) ),
		esc_html( $label ),
		goodnite_icon( 'arrow-up-right', 16, 1.8 )
	);
}

/**
 * Output a responsive <picture> element with fallback.
 *
 * @param string $desktop_setting Customizer key for desktop image.
 * @param string $mobile_setting Customizer key for mobile image.
 * @param string $desktop_fallback Filename in assets/images/.
 * @param string $mobile_fallback Filename in assets/images/.
 * @param string $alt Image alt text.
 * @param string $class Extra CSS class.
 * @param bool   $priority High priority above the fold.
 */
function goodnite_picture( string $desktop_setting, string $mobile_setting, string $desktop_fallback, string $mobile_fallback, string $alt, string $class = '', bool $priority = false ): void {
	$desktop_id  = absint( goodnite_mod( $desktop_setting, 0 ) );
	$mobile_id   = absint( goodnite_mod( $mobile_setting, 0 ) );
	$desktop_url = $desktop_id ? wp_get_attachment_image_url( $desktop_id, 'full' ) : goodnite_asset_url( 'images/' . $desktop_fallback );
	$mobile_url  = $mobile_id ? wp_get_attachment_image_url( $mobile_id, 'full' ) : goodnite_asset_url( 'images/' . $mobile_fallback );
	$desktop_set = $desktop_id ? wp_get_attachment_image_srcset( $desktop_id, 'full' ) : '';
	$mobile_set  = $mobile_id ? wp_get_attachment_image_srcset( $mobile_id, 'full' ) : '';

	$dimensions = array(
		'goodnite-hero-desktop.webp'        => array( 1920, 960 ),
		'goodnite-brand-craftsmanship.webp' => array( 1122, 1402 ),
		'goodnite-showroom.webp'            => array( 1122, 1402 ),
		'goodnite-sleep-finder.webp'        => array( 1448, 1086 ),
		'goodnite-technology-cutaway.webp'  => array( 1448, 1086 ),
	);

	$width  = $dimensions[ $desktop_fallback ][0] ?? 1600;
	$height = $dimensions[ $desktop_fallback ][1] ?? 900;

	if ( $desktop_id ) {
		$src = wp_get_attachment_image_src( $desktop_id, 'full' );
		if ( $src ) {
			$width  = $src[1];
			$height = $src[2];
		}
	}
	?>
	<picture class="goodnite-picture <?php echo esc_attr( $class ); ?>">
		<source media="(max-width: 600px)" srcset="<?php echo esc_url( $mobile_set ? $mobile_set : $mobile_url ); ?>">
		<img src="<?php echo esc_url( $desktop_url ); ?>"<?php echo $desktop_set ? ' srcset="' . esc_attr( $desktop_set ) . '" sizes="100vw"' : ''; ?> alt="<?php echo esc_attr( $alt ); ?>" width="<?php echo esc_attr( (string) $width ); ?>" height="<?php echo esc_attr( (string) $height ); ?>" loading="<?php echo $priority ? 'eager' : 'lazy'; ?>" decoding="async"<?php echo $priority ? ' fetchpriority="high"' : ''; ?>>
	</picture>
	<?php
}

/**
 * Return collection cards for the homepage.
 *
 * @return array
 */
function goodnite_collection_cards(): array {
	return array(
		array(
			'id'          => '01',
			'title'       => 'Dreamer Series',
			'subtitle'    => 'Essential Engineered Comfort',
			'description' => 'Purposeful materials, dependable support and familiar comfort technologies.',
			'image'       => 'goodnite-collection-dreamer.webp',
			'alt'         => 'Light contemporary bedroom representing the Dreamer Series',
			'url'         => '/mattresses/dreamer',
		),
		array(
			'id'          => '02',
			'title'       => 'Love Series',
			'subtitle'    => 'Advanced Sleep Technology',
			'description' => 'A technology-led range shaped around individual comfort preferences.',
			'image'       => 'goodnite-collection-love.webp',
			'alt'         => 'Refined modern bedroom representing the Love Series',
			'url'         => '/mattresses/love',
		),
		array(
			'id'          => '03',
			'title'       => 'Signature Series',
			'subtitle'    => 'Engineered Beyond Comfort',
			'description' => 'Sophisticated constructions and premium materials for an elevated sleep experience.',
			'image'       => 'goodnite-collection-signature.webp',
			'alt'         => 'Richly detailed hotel-style bedroom representing the Signature Series',
			'url'         => '/mattresses/signature',
		),
	);
}

/**
 * Return flagship products for the homepage.
 *
 * @return array
 */
function goodnite_product_cards(): array {
	return array(
		array(
			'name'       => 'Kayler',
			'collection' => 'Dreamer Series',
			'technology' => 'SupportCore Foam',
			'price'      => '₹42,990',
			'image'      => 'goodnite-product-01.webp',
			'alt'        => 'Studio shot of the Kayler mattress',
			'url'        => '/mattresses/kayler',
		),
		array(
			'name'       => 'Dream Maker',
			'collection' => 'Dreamer Series',
			'technology' => 'Zoned Comfort',
			'price'      => '₹54,990',
			'image'      => 'goodnite-product-02.webp',
			'alt'        => 'Studio shot of the Dream Maker mattress',
			'url'        => '/mattresses/dream-maker',
		),
		array(
			'name'       => 'Dream Catcher',
			'collection' => 'Love Series',
			'technology' => 'Memory Foam Relief',
			'price'      => '₹48,990',
			'image'      => 'goodnite-product-03.webp',
			'alt'        => 'Studio shot of the Dream Catcher mattress',
			'url'        => '/mattresses/dream-catcher',
		),
		array(
			'name'       => 'Orion Care LX',
			'collection' => 'Love Series',
			'technology' => 'IceSleep Surface',
			'price'      => '₹64,990',
			'image'      => 'goodnite-product-04.webp',
			'alt'        => 'Studio shot of the Orion Care LX mattress',
			'url'        => '/mattresses/orion-care-lx',
		),
	);
}

/**
 * Render fallback menu items when no WP menu is assigned.
 *
 * @param array  $items Associative array of Label => URL.
 * @param string $class CSS class.
 */
function goodnite_fallback_menu( array $items, string $class = '' ): void {
	printf( '<ul class="menu %s">', esc_attr( $class ) );
	foreach ( $items as $label => $path ) {
		printf(
			'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
			esc_url( goodnite_url( $path ) ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}
