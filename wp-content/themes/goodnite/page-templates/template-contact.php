<?php
/**
 * Template Name: Contact Us
 * Description: Dedicated Contact Us page template matching Goodnite reference prototype with AJAX submission and SCF fields.
 *
 * @package Goodnite
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	// 1. Resolve Hero content.
	$page_title = get_the_title();
	$hero_title = 'We’re Here to Help.';
	$hero_desc  = 'Questions, feedback or a concern? Tell us how we can make your Goodnite experience better.';

	if ( function_exists( 'get_field' ) ) {
		$custom_hero_title = (string) get_field( 'contact_hero_title' );
		if ( '' !== trim( $custom_hero_title ) ) {
			$hero_title = $custom_hero_title;
		}

		$custom_hero_desc = (string) get_field( 'contact_hero_description' );
		if ( '' !== trim( $custom_hero_desc ) ) {
			$hero_desc = $custom_hero_desc;
		}
	}

	// 2. Resolve Hero Images.
	$theme_desktop_file = get_template_directory() . '/assets/images/contact-hero-desktop.webp';
	$theme_mobile_file  = get_template_directory() . '/assets/images/contact-hero-mobile.webp';

	// Self-healing image copy from prototype if not present in theme assets.
	if ( ! file_exists( $theme_desktop_file ) || ! file_exists( $theme_mobile_file ) ) {
		$proto_img_dir = ABSPATH . 'Goodnite Website/Contact Us Page/assets/images/';
		$theme_img_dir = get_template_directory() . '/assets/images';
		if ( ! is_dir( $theme_img_dir ) ) {
			wp_mkdir_p( $theme_img_dir );
		}
		if ( ! file_exists( $theme_desktop_file ) && file_exists( $proto_img_dir . 'contact-hero-desktop.webp' ) ) {
			@copy( $proto_img_dir . 'contact-hero-desktop.webp', $theme_desktop_file );
		}
		if ( ! file_exists( $theme_mobile_file ) && file_exists( $proto_img_dir . 'contact-hero-mobile.webp' ) ) {
			@copy( $proto_img_dir . 'contact-hero-mobile.webp', $theme_mobile_file );
		}
	}

	$desktop_hero = file_exists( $theme_desktop_file )
		? get_template_directory_uri() . '/assets/images/contact-hero-desktop.webp'
		: home_url( '/Goodnite%20Website/Contact%20Us%20Page/assets/images/contact-hero-desktop.webp' );

	$mobile_hero = file_exists( $theme_mobile_file )
		? get_template_directory_uri() . '/assets/images/contact-hero-mobile.webp'
		: home_url( '/Goodnite%20Website/Contact%20Us%20Page/assets/images/contact-hero-mobile.webp' );

	$hero_alt = __( 'A calm Goodnite bedroom interior', 'goodnite' );

	if ( function_exists( 'get_field' ) ) {
		$desktop_field = get_field( 'contact_hero_desktop_image' );
		if ( is_array( $desktop_field ) && ! empty( $desktop_field['url'] ) ) {
			$desktop_hero = (string) $desktop_field['url'];
			if ( ! empty( $desktop_field['alt'] ) ) {
				$hero_alt = (string) $desktop_field['alt'];
			}
		} elseif ( is_string( $desktop_field ) && '' !== trim( $desktop_field ) ) {
			$desktop_hero = $desktop_field;
		}

		$mobile_field = get_field( 'contact_hero_mobile_image' );
		if ( is_array( $mobile_field ) && ! empty( $mobile_field['url'] ) ) {
			$mobile_hero = (string) $mobile_field['url'];
		} elseif ( is_string( $mobile_field ) && '' !== trim( $mobile_field ) ) {
			$mobile_hero = $mobile_field;
		}
	}

	// 3. Resolve Form Headings.
	$form_title = __( 'How can we help?', 'goodnite' );
	$form_intro = __( 'Share the details below and our team will get back to you with the right support.', 'goodnite' );

	if ( function_exists( 'get_field' ) ) {
		$custom_form_title = (string) get_field( 'contact_form_title' );
		if ( '' !== trim( $custom_form_title ) ) {
			$form_title = $custom_form_title;
		}

		$custom_form_intro = (string) get_field( 'contact_form_intro' );
		if ( '' !== trim( $custom_form_intro ) ) {
			$form_intro = $custom_form_intro;
		}
	}

	// 4. Resolve Sidebar Details.
	$details_title = __( 'Let’s talk about better sleep.', 'goodnite' );
	$details_intro = __( 'Our team is here to help with product guidance, orders and support.', 'goodnite' );

	if ( function_exists( 'get_field' ) ) {
		$custom_details_title = (string) get_field( 'contact_details_title' );
		if ( '' !== trim( $custom_details_title ) ) {
			$details_title = $custom_details_title;
		}

		$custom_details_intro = (string) get_field( 'contact_details_intro' );
		if ( '' !== trim( $custom_details_intro ) ) {
			$details_intro = $custom_details_intro;
		}
	}

	// 5. Resolve Contact Details Items.
	$details_items = array();
	if ( function_exists( 'have_rows' ) && have_rows( 'contact_details_items' ) ) {
		while ( have_rows( 'contact_details_items' ) ) {
			the_row();
			$item_label = (string) get_sub_field( 'label' );
			$item_val   = (string) get_sub_field( 'value' );
			if ( '' !== trim( $item_label ) || '' !== trim( $item_val ) ) {
				$details_items[] = array(
					'label' => $item_label,
					'value' => $item_val,
				);
			}
		}
	}

	if ( empty( $details_items ) ) {
		$details_items = array(
			array(
				'label' => __( 'Location', 'goodnite' ),
				'value' => __( 'New Delhi, India', 'goodnite' ),
			),
			array(
				'label' => __( 'Company', 'goodnite' ),
				'value' => __( 'Goodnite India', 'goodnite' ),
			),
			array(
				'label' => __( 'Operating hours', 'goodnite' ),
				'value' => __( "Monday to Saturday\n9:30 AM – 6:30 PM", 'goodnite' ),
			),
			array(
				'label' => __( 'Response time', 'goodnite' ),
				'value' => __( 'Within 1–2 business days', 'goodnite' ),
			),
		);
	}

	// 6. Resolve Social Links.
	$social_heading = __( 'Follow Goodnite', 'goodnite' );
	if ( function_exists( 'get_field' ) ) {
		$custom_social_heading = (string) get_field( 'contact_social_heading' );
		if ( '' !== trim( $custom_social_heading ) ) {
			$social_heading = $custom_social_heading;
		}
	}

	$social_links = array();
	if ( function_exists( 'have_rows' ) && have_rows( 'contact_social_links' ) ) {
		while ( have_rows( 'contact_social_links' ) ) {
			the_row();
			$link_label = (string) get_sub_field( 'label' );
			$link_url   = (string) get_sub_field( 'url' );
			if ( '' !== trim( $link_url ) ) {
				$social_links[] = array(
					'label' => '' !== trim( $link_label ) ? $link_label : __( 'Social Link', 'goodnite' ),
					'url'   => $link_url,
				);
			}
		}
	}

	if ( empty( $social_links ) ) {
		$social_links = array(
			array(
				'label' => 'Instagram',
				'url'   => 'https://www.instagram.com/',
			),
			array(
				'label' => 'YouTube',
				'url'   => 'https://www.youtube.com/',
			),
			array(
				'label' => 'LinkedIn',
				'url'   => 'https://www.linkedin.com/',
			),
		);
	}

	// Check for standard POST submission query params.
	$has_post_error   = isset( $_GET['contact_status'] ) && 'error' === $_GET['contact_status'];
	$has_post_success = isset( $_GET['contact_status'] ) && 'success' === $_GET['contact_status'];
	?>

	<main id="main-content" class="contact-page">
		<section class="contact-hero" aria-labelledby="contact-title">
			<picture>
				<source media="(max-width: 767px)" srcset="<?php echo esc_url( $mobile_hero ); ?>">
				<img src="<?php echo esc_url( $desktop_hero ); ?>" alt="<?php echo esc_attr( $hero_alt ); ?>" width="1672" height="941" fetchpriority="high" decoding="async">
			</picture>
			<div class="contact-hero__shade"></div>
			<div class="container-shell contact-hero__content">
				<div class="contact-hero__copy">
					<h1 id="contact-title"><?php echo esc_html( $hero_title ); ?></h1>
					<p><?php echo esc_html( $hero_desc ); ?></p>
				</div>
			</div>
		</section>

		<nav class="container-shell contact-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'goodnite' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'goodnite' ); ?></a>
			<span aria-hidden="true">/</span>
			<span><?php echo esc_html( $page_title ); ?></span>
		</nav>

		<section class="contact-content" aria-labelledby="contact-form-title">
			<div class="container-shell contact-grid">
				<div class="contact-form-wrap">
					<h2 id="contact-form-title"><?php echo esc_html( $form_title ); ?></h2>
					<p class="contact-intro"><?php echo esc_html( $form_intro ); ?></p>

					<form class="contact-form" data-contact-form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
						<input type="hidden" name="action" value="goodnite_submit_contact">
						<?php wp_nonce_field( 'goodnite_contact_action', 'goodnite_contact_nonce' ); ?>
						<div style="display:none;" aria-hidden="true">
							<input type="text" name="goodnite_hp" value="" tabindex="-1" autocomplete="off">
						</div>

						<div class="contact-form__row">
							<label><?php esc_html_e( 'Full name', 'goodnite' ); ?>
								<input name="name" type="text" autocomplete="name" required>
							</label>
							<label><?php esc_html_e( 'Mobile number', 'goodnite' ); ?>
								<input name="phone" type="tel" inputmode="numeric" autocomplete="tel" required>
							</label>
						</div>

						<label><?php esc_html_e( 'Email address', 'goodnite' ); ?>
							<input name="email" type="email" autocomplete="email" required>
						</label>

						<label><?php esc_html_e( 'Message', 'goodnite' ); ?>
							<textarea name="message" rows="5" required></textarea>
						</label>

						<p class="contact-form__error" data-contact-error role="alert" <?php echo $has_post_error ? '' : 'hidden'; ?>>
							<?php esc_html_e( 'Please complete all fields with valid details.', 'goodnite' ); ?>
						</p>

						<button class="contact-submit" type="submit">
							<?php esc_html_e( 'Send Enquiry', 'goodnite' ); ?> <span aria-hidden="true">&rarr;</span>
						</button>

						<p class="contact-form__success" data-contact-success role="status" <?php echo $has_post_success ? '' : 'hidden'; ?>>
							<?php esc_html_e( 'Thank you. A Goodnite team member will be in touch shortly.', 'goodnite' ); ?>
						</p>
					</form>
				</div>

				<aside class="contact-details" aria-labelledby="contact-details-title">
					<h2 id="contact-details-title"><?php echo esc_html( $details_title ); ?></h2>
					<p class="contact-details__intro"><?php echo esc_html( $details_intro ); ?></p>

					<dl class="contact-details__list">
						<?php foreach ( $details_items as $item ) : ?>
							<div>
								<dt><?php echo esc_html( $item['label'] ); ?></dt>
								<dd><?php echo nl2br( esc_html( $item['value'] ) ); ?></dd>
							</div>
						<?php endforeach; ?>
					</dl>

					<?php if ( ! empty( $social_links ) ) : ?>
						<div class="contact-social">
							<span><?php echo esc_html( $social_heading ); ?></span>
							<div>
								<?php foreach ( $social_links as $link ) : ?>
									<a href="<?php echo esc_url( $link['url'] ); ?>" aria-label="<?php echo esc_attr( $link['label'] ); ?>" target="_blank" rel="noopener noreferrer">
										<?php echo esc_html( $link['label'] ); ?>
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</aside>
			</div>
		</section>
	</main>

	<?php
endwhile;

get_footer();

