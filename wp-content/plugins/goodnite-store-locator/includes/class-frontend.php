<?php
/**
 * Frontend Views & Shortcode Handler for Goodnite Store Locator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Goodnite_SL_Frontend {

	public static function init() {
		add_shortcode( 'goodnite_store_locator', array( __CLASS__, 'render_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
	}

	public static function register_assets() {
		wp_register_style(
			'goodnite-sl-frontend-css',
			GOODNITE_SL_URL . 'assets/css/frontend.css',
			array(),
			GOODNITE_SL_VERSION
		);

		wp_register_script(
			'goodnite-sl-frontend-js',
			GOODNITE_SL_URL . 'assets/js/frontend.js',
			array(),
			GOODNITE_SL_VERSION,
			true
		);

		wp_localize_script(
			'goodnite-sl-frontend-js',
			'goodnite_sl_config',
			array(
				'api_url'  => esc_url_raw( rest_url( 'goodnite/v1/stores/search' ) ),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
				'i18n'     => array(
					'searching'       => __( 'Searching for nearest stores...', 'goodnite-store-locator' ),
					'invalid_pincode' => __( 'Please enter a valid 6-digit postal/PIN code.', 'goodnite-store-locator' ),
					'exact_match'     => __( 'Authorized Store Found for Your Pincode', 'goodnite-store-locator' ),
					'nearby_match'    => __( 'Nearest Authorized Store in Your Region', 'goodnite-store-locator' ),
					'no_stores'       => __( 'No authorized stores found near this pincode currently.', 'goodnite-store-locator' ),
					'call_now'        => __( 'Call Store', 'goodnite-store-locator' ),
					'get_directions'  => __( 'Get Directions', 'goodnite-store-locator' ),
					'open_hours'      => __( 'Timings', 'goodnite-store-locator' ),
					'error'           => __( 'Unable to search stores right now. Please try again.', 'goodnite-store-locator' ),
					'geo_error'       => __( 'Location access was denied or not supported by your browser.', 'goodnite-store-locator' ),
				),
			)
		);
	}

	/**
	 * Render the [goodnite_store_locator] shortcode
	 */
	public static function render_shortcode( $atts ) {
		// Enqueue registered assets when shortcode is on the page
		wp_enqueue_style( 'goodnite-sl-frontend-css' );
		wp_enqueue_script( 'goodnite-sl-frontend-js' );

		$atts = shortcode_atts(
			array(
				'title'       => __( 'Find a Goodnite Experience Store', 'goodnite-store-locator' ),
				'subtitle'    => __( 'Enter your pincode to locate your nearest authorized mattress experience center and dealer.', 'goodnite-store-locator' ),
				'placeholder' => __( 'Enter 6-digit PIN code (e.g. 110001, 560038)', 'goodnite-store-locator' ),
				'max_results' => 6,
			),
			$atts,
			'goodnite_store_locator'
		);

		ob_start();
		?>
		<section class="goodnite-sl-section" id="goodnite-store-locator-section">
			<div class="goodnite-sl-container">
				<!-- Section Header -->
				<div class="goodnite-sl-header">
					<span class="goodnite-sl-pill-tag">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
						<?php esc_html_e( 'Official Stores & Dealers', 'goodnite-store-locator' ); ?>
					</span>
					<h2 class="goodnite-sl-title"><?php echo esc_html( $atts['title'] ); ?></h2>
					<?php if ( ! empty( $atts['subtitle'] ) ) : ?>
						<p class="goodnite-sl-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>

				<!-- Search Box -->
				<div class="goodnite-sl-search-wrapper">
					<form class="goodnite-sl-search-form" id="goodnite-sl-search-form" onsubmit="return false;">
						<div class="goodnite-sl-input-group">
							<span class="goodnite-sl-input-icon">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
							</span>
							<input 
								type="text" 
								id="goodnite-sl-pincode-input" 
								class="goodnite-sl-input" 
								placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>" 
								maxlength="10" 
								autocomplete="postal-code"
								aria-label="<?php esc_attr_e( 'Enter PIN code', 'goodnite-store-locator' ); ?>"
							/>
							<button type="button" id="goodnite-sl-geolocate-btn" class="goodnite-sl-geo-btn" title="<?php esc_attr_e( 'Use my current location', 'goodnite-store-locator' ); ?>">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polygon points="12 8 8 12 12 16 16 12 12 8"></polygon></svg>
								<span class="geo-btn-text"><?php esc_html_e( 'Locate Me', 'goodnite-store-locator' ); ?></span>
							</button>
							<button type="submit" id="goodnite-sl-search-btn" class="goodnite-sl-btn-primary">
								<span class="btn-text"><?php esc_html_e( 'Find Stores', 'goodnite-store-locator' ); ?></span>
								<span class="btn-spinner" style="display:none;"></span>
							</button>
						</div>
					</form>
					<!-- Quick Popular Pincodes / Cities -->
					<div class="goodnite-sl-quick-pills">
						<span class="quick-pills-label"><?php esc_html_e( 'Quick Search:', 'goodnite-store-locator' ); ?></span>
						<button type="button" class="quick-pill-btn" data-pincode="110001">Delhi (110001)</button>
						<button type="button" class="quick-pill-btn" data-pincode="400050">Mumbai (400050)</button>
						<button type="button" class="quick-pill-btn" data-pincode="560038">Bangalore (560038)</button>
						<button type="button" class="quick-pill-btn" data-pincode="600001">Chennai (600001)</button>
					</div>
				</div>

				<!-- Live Status & Feedback -->
				<div class="goodnite-sl-status-bar" id="goodnite-sl-status-bar" style="display:none;">
					<div class="status-badge" id="goodnite-sl-status-badge"></div>
					<div class="status-text" id="goodnite-sl-status-text"></div>
				</div>

				<!-- Results Container -->
				<div class="goodnite-sl-results-wrapper" id="goodnite-sl-results-wrapper">
					<!-- Initial State or Dynamic Cards will be rendered here -->
					<div class="goodnite-sl-empty-initial">
						<div class="empty-icon-circle">
							<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
						</div>
						<h3><?php esc_html_e( 'Discover Stores in Your City', 'goodnite-store-locator' ); ?></h3>
						<p><?php esc_html_e( 'Enter your 6-digit postal PIN code above to view authorized Goodnite experience centers, check store timings, and get instant directions.', 'goodnite-store-locator' ); ?></p>
					</div>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}
}
