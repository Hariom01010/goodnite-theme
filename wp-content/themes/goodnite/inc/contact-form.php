<?php
/**
 * Contact Form AJAX and POST Submission Handler.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ensure contact hero images exist in theme assets directory by copying from prototype fallback if needed.
 */
function goodnite_ensure_contact_assets(): void {
	$img_dir = get_template_directory() . '/assets/images';
	if ( ! is_dir( $img_dir ) ) {
		wp_mkdir_p( $img_dir );
	}

	$images = array(
		'contact-hero-desktop.webp',
		'contact-hero-mobile.webp',
	);

	$proto_dir = ABSPATH . 'Goodnite Website/Contact Us Page/assets/images/';

	foreach ( $images as $image ) {
		$target_file = $img_dir . '/' . $image;
		$source_file = $proto_dir . $image;
		if ( ! file_exists( $target_file ) && file_exists( $source_file ) ) {
			@copy( $source_file, $target_file );
		}
	}
}
add_action( 'after_setup_theme', 'goodnite_ensure_contact_assets' );

/**
 * Handle contact form submission via AJAX.
 */
function goodnite_ajax_submit_contact(): void {
	// 1. Verify Nonce.
	$nonce_verified = false;

	if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'goodnite_contact_action' ) ) {
		$nonce_verified = true;
	} elseif ( ! empty( $_POST['goodnite_contact_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['goodnite_contact_nonce'] ) ), 'goodnite_contact_action' ) ) {
		$nonce_verified = true;
	} elseif ( check_ajax_referer( 'goodnite_contact_action', 'nonce', false ) ) {
		$nonce_verified = true;
	}

	if ( ! $nonce_verified ) {
		wp_send_json_error(
			array(
				'message' => __( 'Security verification failed. Please refresh the page and try again.', 'goodnite' ),
			),
			403
		);
	}

	// 2. Honeypot check (bot prevention).
	if ( ! empty( $_POST['goodnite_hp'] ) ) {
		// Silent rejection or 400 for bot submissions.
		wp_send_json_error(
			array(
				'message' => __( 'Bot activity detected.', 'goodnite' ),
			),
			400
		);
	}

	// 3. Sanitize inputs.
	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	// 4. Validate fields.
	if ( '' === $name || '' === $phone || '' === $email || '' === $message ) {
		wp_send_json_error(
			array(
				'message' => __( 'Please fill in all required fields.', 'goodnite' ),
			),
			422
		);
	}

	if ( ! is_email( $email ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Please provide a valid email address.', 'goodnite' ),
			),
			422
		);
	}

	$phone_digits = (string) preg_replace( '/\D/', '', $phone );
	if ( strlen( $phone_digits ) < 10 ) {
		wp_send_json_error(
			array(
				'message' => __( 'Please provide a valid mobile number with at least 10 digits.', 'goodnite' ),
			),
			422
		);
	}

	// 5. Determine recipient email.
	$recipient = (string) get_option( 'admin_email' );
	if ( function_exists( 'get_field' ) ) {
		$custom_recipient = get_field( 'contact_recipient_email', 'option' );
		if ( empty( $custom_recipient ) ) {
			$custom_recipient = get_field( 'contact_recipient_email' );
		}
		if ( ! empty( $custom_recipient ) && is_string( $custom_recipient ) && is_email( $custom_recipient ) ) {
			$recipient = $custom_recipient;
		}
	}

	// 6. Dispatch notification email.
	$subject = sprintf( __( 'New Contact Enquiry from %s - Goodnite', 'goodnite' ), $name );
	$body    = sprintf(
		"Name: %s\nPhone: %s\nEmail: %s\n\nMessage:\n%s\n\n---\nSent from Goodnite Website: %s",
		$name,
		$phone,
		$email,
		$message,
		home_url( '/' )
	);

	$server_host = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : 'goodnite.local';
	$headers     = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: Goodnite India <no-reply@' . $server_host . '>',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	wp_mail( $recipient, $subject, $body, $headers );

	// 7. Respond with clean JSON success.
	wp_send_json_success(
		array(
			'message' => __( 'Thank you. A Goodnite team member will be in touch shortly.', 'goodnite' ),
		)
	);
}
add_action( 'wp_ajax_goodnite_submit_contact', 'goodnite_ajax_submit_contact' );
add_action( 'wp_ajax_nopriv_goodnite_submit_contact', 'goodnite_ajax_submit_contact' );

/**
 * Handle standard POST fallback for non-JS browsers.
 */
function goodnite_post_submit_contact(): void {
	// 1. Verify Nonce.
	$nonce = isset( $_POST['goodnite_contact_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['goodnite_contact_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'goodnite_contact_action' ) ) {
		wp_die( esc_html__( 'Security check failed. Please return and try again.', 'goodnite' ), 403 );
	}

	// 2. Honeypot check.
	if ( ! empty( $_POST['goodnite_hp'] ) ) {
		wp_die( esc_html__( 'Bot activity detected.', 'goodnite' ), 400 );
	}

	// 3. Sanitize inputs.
	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	$referer = wp_get_referer();
	if ( ! $referer ) {
		$referer = home_url( '/contact/' );
	}

	// 4. Validate fields.
	$phone_digits = (string) preg_replace( '/\D/', '', $phone );
	if ( '' === $name || '' === $phone || '' === $email || '' === $message || ! is_email( $email ) || strlen( $phone_digits ) < 10 ) {
		$redirect_url = add_query_arg( 'contact_status', 'error', $referer );
		wp_safe_redirect( $redirect_url );
		exit;
	}

	// 5. Determine recipient email.
	$recipient = (string) get_option( 'admin_email' );
	if ( function_exists( 'get_field' ) ) {
		$custom_recipient = get_field( 'contact_recipient_email', 'option' );
		if ( empty( $custom_recipient ) ) {
			$custom_recipient = get_field( 'contact_recipient_email' );
		}
		if ( ! empty( $custom_recipient ) && is_string( $custom_recipient ) && is_email( $custom_recipient ) ) {
			$recipient = $custom_recipient;
		}
	}

	// 6. Send notification.
	$subject = sprintf( __( 'New Contact Enquiry from %s - Goodnite', 'goodnite' ), $name );
	$body    = sprintf(
		"Name: %s\nPhone: %s\nEmail: %s\n\nMessage:\n%s\n\n---\nSent from Goodnite Website: %s",
		$name,
		$phone,
		$email,
		$message,
		home_url( '/' )
	);

	$server_host = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : 'goodnite.local';
	$headers     = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: Goodnite India <no-reply@' . $server_host . '>',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	wp_mail( $recipient, $subject, $body, $headers );

	$redirect_url = add_query_arg( 'contact_status', 'success', $referer );
	wp_safe_redirect( $redirect_url );
	exit;
}
add_action( 'admin_post_goodnite_submit_contact', 'goodnite_post_submit_contact' );
add_action( 'admin_post_nopriv_goodnite_submit_contact', 'goodnite_post_submit_contact' );

