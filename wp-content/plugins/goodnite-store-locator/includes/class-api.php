<?php
/**
 * REST API & AJAX Endpoints for Goodnite Store Locator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Goodnite_SL_API {

	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
		add_action( 'wp_ajax_goodnite_search_stores', array( __CLASS__, 'handle_ajax_search' ) );
		add_action( 'wp_ajax_nopriv_goodnite_search_stores', array( __CLASS__, 'handle_ajax_search' ) );
	}

	/**
	 * Register REST API Route
	 */
	public static function register_rest_routes() {
		register_rest_route(
			'goodnite/v1',
			'/stores/search',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'rest_search_stores' ),
				'permission_callback' => '__return_true', // Public endpoint
				'args'                => array(
					'pincode' => array(
						'required'          => false,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
					'city'    => array(
						'required'          => false,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);
	}

	/**
	 * REST API Callback
	 */
	public static function rest_search_stores( WP_REST_Request $request ) {
		$pincode = $request->get_param( 'pincode' );
		$city    = $request->get_param( 'city' );

		if ( empty( $pincode ) && empty( $city ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => __( 'Please provide a valid pincode or city.', 'goodnite-store-locator' ),
					'dealers' => array(),
				),
				400
			);
		}

		$result = Goodnite_SL_DB::search_dealers( $pincode, $city );

		return new WP_REST_Response(
			array(
				'success'          => true,
				'pincode_searched' => $pincode,
				'city_searched'    => $city,
				'match_type'       => $result['match_type'],
				'total_found'      => count( $result['dealers'] ),
				'dealers'          => array_map( array( __CLASS__, 'format_dealer_payload' ), $result['dealers'] ),
			),
			200
		);
	}

	/**
	 * AJAX Fallback Handler
	 */
	public static function handle_ajax_search() {
		$pincode = isset( $_REQUEST['pincode'] ) ? sanitize_text_field( $_REQUEST['pincode'] ) : '';
		$city    = isset( $_REQUEST['city'] ) ? sanitize_text_field( $_REQUEST['city'] ) : '';

		if ( empty( $pincode ) && empty( $city ) ) {
			wp_send_json_error( array( 'message' => __( 'Pincode is required.', 'goodnite-store-locator' ) ) );
		}

		$result = Goodnite_SL_DB::search_dealers( $pincode, $city );

		wp_send_json_success( array(
			'pincode_searched' => $pincode,
			'match_type'       => $result['match_type'],
			'total_found'      => count( $result['dealers'] ),
			'dealers'          => array_map( array( __CLASS__, 'format_dealer_payload' ), $result['dealers'] ),
		) );
	}

	/**
	 * Format dealer for clean frontend payload
	 */
	public static function format_dealer_payload( $dealer ) {
		// Clean and generate direct click-to-call link
		$clean_phone = preg_replace( '/[^0-9+]/', '', $dealer['phone'] );

		// Clean Google maps link
		$maps_link = ! empty( $dealer['google_maps_link'] ) ? $dealer['google_maps_link'] : 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $dealer['store_name'] . ' ' . $dealer['address'] . ' ' . $dealer['pincode'] );

		return array(
			'id'               => (int) $dealer['id'],
			'name'             => esc_html( $dealer['store_name'] ),
			'contact_person'   => esc_html( $dealer['contact_person'] ),
			'phone'            => esc_html( $dealer['phone'] ),
			'phone_url'        => 'tel:' . esc_attr( $clean_phone ),
			'email'            => esc_html( $dealer['email'] ),
			'address'          => esc_html( $dealer['address'] ),
			'city'             => esc_html( $dealer['city'] ),
			'state'            => esc_html( $dealer['state'] ),
			'pincode'          => esc_html( $dealer['pincode'] ),
			'served_pincodes'  => ! empty( $dealer['served_pincodes'] ) ? explode( ',', $dealer['served_pincodes'] ) : array(),
			'timings'          => ! empty( $dealer['timings'] ) ? esc_html( $dealer['timings'] ) : '10:00 AM - 8:00 PM',
			'google_maps_link' => esc_url( $maps_link ),
		);
	}
}
