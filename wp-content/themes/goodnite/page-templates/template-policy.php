<?php
/**
 * Template Name: Policy Page
 * Description: Reusable legal and policy page template (Terms & Conditions, Privacy Policy, Shipping, Returns, Warranty).
 *
 * @package Goodnite
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

get_header();

/**
 * Retrieve reference prototype policy dataset as fallback.
 *
 * @return array{intro: string, sections: array<int, array{heading: string, paragraphs: array<int, string>}>}
 */
if ( ! function_exists( 'goodnite_get_default_policy_sections' ) ) {
	function goodnite_get_default_policy_sections(): array {
		return array(
			'intro'    => 'Welcome! You have entered the website owned and operated by Goodnite Outlet identified by the URL www.goodniteoutlet.com.my (the “Site”), but before proceeding further please read carefully the following Terms and Conditions of Use which will govern your use of the Site (”Terms and Conditions” which expression shall include any modification that may from time to time be made thereto by Goodnite Outlet). By using the Site or by placing an order through the Site you agree to and accept all these Terms and Conditions and therefore have entered into an agreement with Goodnite Outlet governing your use of the Site. In addition, when you use any current or future Goodnite Outlet service(s), you also will be subject to the guidelines and conditions applicable to such service or business.',
			'sections' => array(
				array(
					'heading'    => 'Use Of Site Access',
					'paragraphs' => array(
						'Goodnite Outlet grants you a limited non-exclusive license to access and make personal and non-commercial home use of this Site. As such, you cannot download the Site (other than page caching) or modify the Site or its contents or any portion of the Site or any of its contents, except with the prior written consent of Goodnite Outlet. This license does not include any resale or commercial use of this Site or its contents; any collection and use of any product listings, descriptions, or prices; any derivative use of this Site or its contents; any downloading or copying of account information for the benefit of another merchant and/or user; or any use of data mining, robots, or similar data gathering and extracting tools. This Site or any portion of this Site may NOT be reproduced, duplicated, copied, sold, resold, visited, or otherwise exploited for any commercial purpose without prior express written consent of Goodnite Outlet. You may not frame or utilise framing techniques to enclose any trademark, logo, or other proprietary information (including images, text, page layout, or form) of Goodnite Outlet and the Site without Goodnite Outlet’s prior express written consent. You may not use meta tags or any other “hidden text” utilising Goodnite Outlet and the Site’s name or trademarks without the prior written consent of Goodnite Outlet. Any unauthorised use automatically terminates the permission or license granted by Goodnite Outlet. You are granted a limited, revocable, and non-exclusive right to create a hyperlink to the homepage of the Site so long as the link does not in any manner whatsoever or howsoever portray or depict the Site, or its products or services in false, misleading, derogatory, obscene or otherwise offensive light or manner. You may not use any Goodnite Outlet logo, the Site logo or other proprietary graphic or trade mark as part of the link without Goodnite Outlet’s prior express written consent.',
					),
				),
				array(
					'heading'    => 'Your Particulars',
					'paragraphs' => array(
						'When you register with Goodnite Outlet, you agree to (i) provide true, accurate, current and complete information about yourself as prompted by the Order form (the “Order Data”) and to (ii) maintain and promptly update the Order Data to keep it true, accurate, current and complete. You are responsible for maintaining the confidentiality of your Order Data or passwords and for restricting access to your computer, and you agree to accept responsibility for all activities that occur under your particulars. Goodnite Outlet cannot and will not be liable for any loss or damage arising from your failure to comply with this Terms and Conditions. If you are under 18 years of age, you can use the Site only with involvement of a parent or guardian. Goodnite Outlet reserves the right to refuse service, remove or edit content, or cancel orders at its sole discretion.',
					),
				),
				array(
					'heading'    => 'Risk Of Loss',
					'paragraphs' => array(
						'All items purchased from the Site are made pursuant to a shipment contract. This means that the risk of loss and title for such items pass to you upon our delivery to the carrier.',
					),
				),
				array(
					'heading'    => 'Termination',
					'paragraphs' => array(
						'You agree that Goodnite Outlet, at its sole and absolute discretion, may terminate your use of the Site for any reason, including, without limitation, lack of use or if Goodnite Outlet believes that you have breached or acted inconsistently with or in disregard of the letter or spirit of the Terms and Conditions. Goodnite Outlet may also at its sole and absolute discretion and at any time, with or without notice, discontinue providing its services or any part thereof. You agree that any termination of your access to the Site under any provision of this Terms and Conditions may be affected without prior notice, and acknowledge and agree that Goodnite Outlet immediately bar any further access to the Site. On the happening of any of the afore-going, you agree that Goodnite Outlet shall not in any manner whatsoever or howsoever, be liable to you or any third party for any termination of your access to the Site.',
					),
				),
				array(
					'heading'    => 'Indemnity',
					'paragraphs' => array(
						'You hereby agree to indemnify and forever save Goodnite Outlet, its affiliates, directors, officers, employees, owners, agents, information providers, licensors and licensees harmless, from and against any and all losses, claims, demands, damages, liabilities and/or costs and expenses, including legal costs, on a full indemnity basis, which may arise from your use of the Site, including, but not limited to, any breach of any of the Terms and Conditions, or your violation of any rights of another. You shall use your best efforts to cooperate with us in the defence of any claim. We reserve the right (but have no obligation), at our own expense, to assume the exclusive defence and control of any matter subject always to full indemnification by you. You hereby acknowledge and agree that any breach of the Terms and Conditions will result in irreparable harm and/or losses to Goodnite Outlet for which monetary damages would or may not be an adequate remedy and, therefore, in addition to its rights and remedies otherwise available at law, Goodnite Outlet will be entitled to equitable relief, including both a preliminary or interlocutory and permanent injunction, if such a breach occurs. You irrevocably waive any requirement for the posting of a bond or other security if Goodnite Outlet seeks such an injunction.',
					),
				),
				array(
					'heading'    => 'Disclaimer Of Warranties And Limitation Of Liability',
					'paragraphs' => array(
						'This site is provided by Goodnite Outlet on an “as is” and “purchase now” basis. Goodnite Outlet makes no representations or warranties of any kind, express or implied, as to the operation of this site or the information, content, materials or products included on this site. You expressly acknowledge and agree that your use of this site is at your sole risk. Goodnite Outlet disclaims all express or implied representations, warranties and conditions of any kind, including, but not limited to, implied warranty or condition of merchantability and/or fitness for a particular purpose or non-infringement. Goodnite Outlet makes no representations, warranties, conditions or guarantees that this site or the functions contained in the materials on the site will meet your requirements; will be uninterrupted, timely, secure or error-free or that defects will be corrected. Goodnite Outlet does not warrant that this site, its servers, or e-mail sent from Goodnite Outlet are free of viruses or other harmful components or that the quality of any products, services, information, or other material purchased or obtained by you through the site will meet your expectations. All such warranties, representations, conditions, undertakings and terms are hereby excluded. Goodnite Outlet will not be liable for any special, direct, indirect, incidental, punitive, and consequential damages or any damages whatsoever, including, but not limited to, damages for loss of profits, goodwill or savings, business interruption, loss of information, whether in contract, negligence, tort or otherwise or any other damages resulting from any of the following (I) The use or the inability to use the site, (II) Any defect in goods, samples, data, information or services purchased or obtained from a user or a third party service provider through the site, (III) Unauthorised access by third parties to your data or private information, (IV) Statements or conduct of any user of the site, or (V) Any matters relating to services acquired through the site however arising, including negligence.',
						'Any material downloaded or otherwise obtained through the site is done at your sole discretion and risk and you are solely responsible for any damage to your computer system or loss or corruption of data that results or may result from the download of any such material, including any damages resulting from computer viruses. No advice or information, whether oral or written, obtained by you from Goodnite Outlet or through or from the site shall create any warranty or condition not expressly stated herein. Under no circumstances shall Goodnite Outlet be held liable for a delay or failure or disruption of the content or services delivered though the site resulting directly or indirectly from acts of nature, forces or causes beyond its reasonable control, including without limitation, internet failures, computer, telecommunications or any other equipment failures electrical power failures, strikes, labour disputes, riots, insurrections, civil disturbances, shortages of labour or materials, fires, flood, storms, explosions, acts of god, acts of terrorists, war, government actions, orders of domestic or foreign courts or tribunals or non-performance of third parties.',
					),
				),
				array(
					'heading'    => 'Applicable Law',
					'paragraphs' => array(
						'This Site is created, owned and controlled by Goodnite International Sdn. Bhd. The Terms and Conditions shall be governed and interpreted in accordance with the laws of Malaysia without giving effect to any principles of conflicts of laws. Those who choose to access the Site do so on their own initiative and are responsible for compliance with local laws, if and to the extent local laws are applicable. By visiting or using the Site or by placing an order through the Site you hereby irrevocably accept, agree and consent to the exclusive jurisdiction of the Courts in Malaysia in all disputes, controversies or differences of whatsoever nature which may arise, out of or in relation to the Terms and Conditions, or the breach hereof relating to the use of the Site.',
					),
				),
				array(
					'heading'    => 'Accuracy Of Information',
					'paragraphs' => array(
						'We attempt to be as accurate as possible when describing our products on the Site. However, to the extent permitted by applicable law, we do not warrant that the product descriptions or other content available on the Site is accurate, complete, reliable, current, or error-free.',
					),
				),
				array(
					'heading'    => 'Modification And Discontinuance',
					'paragraphs' => array(
						'We reserve the right at any time and from time to time to modify or discontinue, temporarily or permanently, the Site and the Terms and Conditions (or any part thereof) with or without notice to you. The amended and restated Terms and Conditions will be posted on the Site and shall be effective immediately upon posting. Posting by Goodnite Outlet of the amended and restated Terms and Conditions and your continued use of the Site shall be deemed to be acceptance of the amended terms. You agree that Goodnite Outlet shall not be liable to you or to any third party for any modification, suspension or discontinuance of the Site. Please also review our other policies posted on the Site. These policies also govern your visit to Goodnite Outlet’s website.',
					),
				),
				array(
					'heading'    => 'Waiver Of Conditions Of Use',
					'paragraphs' => array(
						'The failure of Goodnite Outlet in any one or more instances to insist upon performance of any of the terms or conditions contained herein, or to exercise any right or privilege hereunder, or the waiver by Goodnite Outlet of any breach by you, of these terms or conditions of sale, shall not be construed as thereafter waiving such terms and condition, rights of privileges, and the same shall continue and remain in force and effect as if no failure or waiver had occurred.',
					),
				),
				array(
					'heading'    => 'Links To Other Websites',
					'paragraphs' => array(
						'Links to third party websites on this Site are provided solely as a convenience to you. Please exercise discretion while browsing the Internet using this Site. If you use these links, you will leave this Site. Goodnite Outlet has not reviewed all of these third-party sites and does not control and is not responsible for any of these sites or their content. Thus, Goodnite Outlet does not endorse or make any representations about them, or any information, software or other products or materials found there, or any results that may be obtained from using them. If you decide to access any of the third-party sites linked to this Site you do this entirely at your own risk. Goodnite Outlet will not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with use of or reliance on any such content, goods or services available on or through any such site or resource.',
					),
				),
				array(
					'heading'    => 'Intellectual Property Rights',
					'paragraphs' => array(
						'Goodnite Outlet is the sole owner or lawful licensee of all the rights to the Site and all information, text, messages, illustrations, images, logos, designs, icons, photographs, programs, music, sound, video clips, directories, files, databases, listings or other materials available on or through the Site (collectively the “Content”). The Site and the Content embody trade secret and intellectual property rights protected under worldwide copyright and other laws. All title, ownership and intellectual property rights in the Site and the Content shall remain with Goodnite Outlet, its affiliates or licensors of the Content, as the case may be. All rights not otherwise claimed under this Terms and Conditions or by Goodnite Outlet are hereby reserved. Goodnite Outlet and related icons and logos of Goodnite Outlet in various jurisdictions are protected under applicable copyright, trademark and other proprietary rights laws. The unauthorised copying, modification, use or publication of these marks is strictly prohibited.',
					),
				),
				array(
					'heading'    => 'Notices And Communications',
					'paragraphs' => array(
						'When you visit the Site or send e-mails to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by e-mail or by posting notices on the Site. You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing.',
					),
				),
				array(
					'heading'    => 'Severability',
					'paragraphs' => array(
						'If any provision of the Terms and Conditions shall be unlawful, void, or for any reason unenforceable, then that provision shall be deemed severable from these terms and shall not affect the validity and enforceability of any remaining provisions.',
					),
				),
				array(
					'heading'    => 'Entire Agreement',
					'paragraphs' => array(
						'The Terms and Conditions constitute the entire agreement between you and Goodnite Outlet and govern your use of the Site, superseding any prior agreements between you and Goodnite Outlet (including, but not limited to, any prior versions of the Terms and Conditions). You also may be subject to additional terms and conditions that may apply when you use other Goodnite Outlet services, third-party content or third-party software. Any assignment or transfer of either the Terms and Conditions or any right granted hereunder is strictly prohibited.',
					),
				),
				array(
					'heading'    => 'Heading',
					'paragraphs' => array(
						'The headings of each of the Terms and Conditions are for convenience of reference only and shall not form part of the Terms and Conditions. Such heading shall be ignored in the interpretation or construction of any of the Terms and Conditions.',
					),
				),
				array(
					'heading'    => 'Return Policy',
					'paragraphs' => array(
						'Please make sure you have your heart set on your purchases. All our pieces are made to order therefore we do not accept returns or exchanges.',
					),
				),
			),
		);
	}
}

while ( have_posts() ) :
	the_post();

	// 1. Resolve Hero Title.
	$hero_title = get_the_title();
	if ( function_exists( 'get_field' ) ) {
		$custom_hero_title = (string) get_field( 'policy_hero_title' );
		if ( '' !== trim( $custom_hero_title ) ) {
			$hero_title = $custom_hero_title;
		}
	}
	if ( '' === trim( $hero_title ) ) {
		$hero_title = __( 'Terms & Conditions', 'goodnite' );
	}

	// 2. Resolve Hero Images.
	$theme_desktop_file = get_template_directory() . '/assets/images/policy-hero-desktop.png';
	$theme_mobile_file  = get_template_directory() . '/assets/images/policy-hero-mobile.png';

	$desktop_hero = file_exists( $theme_desktop_file )
		? get_template_directory_uri() . '/assets/images/policy-hero-desktop.png'
		: home_url( '/Goodnite%20Website/Policy%20Page%20Template/assets/images/policy-hero-desktop.png' );

	$mobile_hero = file_exists( $theme_mobile_file )
		? get_template_directory_uri() . '/assets/images/policy-hero-mobile.png'
		: home_url( '/Goodnite%20Website/Policy%20Page%20Template/assets/images/policy-hero-mobile.png' );

	$hero_alt = sprintf( __( '%s - Goodnite', 'goodnite' ), $hero_title );

	if ( function_exists( 'get_field' ) ) {
		$desktop_field = get_field( 'policy_hero_desktop_image' );
		if ( is_array( $desktop_field ) && ! empty( $desktop_field['url'] ) ) {
			$desktop_hero = (string) $desktop_field['url'];
			if ( ! empty( $desktop_field['alt'] ) ) {
				$hero_alt = (string) $desktop_field['alt'];
			}
		} elseif ( is_string( $desktop_field ) && '' !== trim( $desktop_field ) ) {
			$desktop_hero = $desktop_field;
		}

		$mobile_field = get_field( 'policy_hero_mobile_image' );
		if ( is_array( $mobile_field ) && ! empty( $mobile_field['url'] ) ) {
			$mobile_hero = (string) $mobile_field['url'];
		} elseif ( is_string( $mobile_field ) && '' !== trim( $mobile_field ) ) {
			$mobile_hero = $mobile_field;
		}
	}

	// 3. Resolve Introduction block.
	$intro_heading = __( 'Introduction', 'goodnite' );
	$intro_text    = '';
	if ( function_exists( 'get_field' ) ) {
		$custom_intro_heading = (string) get_field( 'policy_intro_heading' );
		if ( '' !== trim( $custom_intro_heading ) ) {
			$intro_heading = $custom_intro_heading;
		}
		$intro_text = (string) get_field( 'policy_intro_text' );
	}

	// 4. Resolve Policy Sections / Content.
	$has_repeater_rows = function_exists( 'have_rows' ) && have_rows( 'policy_sections' );
	$raw_content       = get_the_content();
	?>
	<main id="main-content" class="policy-page">
		<section class="policy-hero" aria-labelledby="policy-title">
			<picture>
				<source media="(max-width: 767px)" srcset="<?php echo esc_url( $mobile_hero ); ?>">
				<img src="<?php echo esc_url( $desktop_hero ); ?>" alt="<?php echo esc_attr( $hero_alt ); ?>" width="1672" height="941" fetchpriority="high" decoding="async">
			</picture>
			<div class="policy-hero__shade"></div>
			<div class="container-shell policy-hero__content">
				<h1 id="policy-title"><?php echo esc_html( $hero_title ); ?></h1>
			</div>
		</section>

		<nav class="container-shell policy-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'goodnite' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'goodnite' ); ?></a>
			<span aria-hidden="true">/</span>
			<span><?php echo esc_html( get_the_title() ); ?></span>
		</nav>

		<section class="policy-content" aria-labelledby="policy-introduction">
			<div class="container-shell policy-content__layout">
				<article class="policy-copy">
					<?php if ( '' !== trim( $intro_text ) ) : ?>
						<header class="policy-intro">
							<h2 id="policy-introduction"><?php echo esc_html( $intro_heading ); ?></h2>
							<p><?php echo esc_html( $intro_text ); ?></p>
						</header>
					<?php endif; ?>

					<?php
					if ( $has_repeater_rows ) :
						while ( have_rows( 'policy_sections' ) ) :
							the_row();
							$section_heading = (string) get_sub_field( 'heading' );
							$section_content = (string) get_sub_field( 'content' );
							if ( '' === trim( $section_heading ) && '' === trim( $section_content ) ) {
								continue;
							}
							?>
							<section class="policy-section">
								<?php if ( '' !== trim( $section_heading ) ) : ?>
									<h3><?php echo esc_html( $section_heading ); ?></h3>
								<?php endif; ?>
								<?php if ( '' !== trim( $section_content ) ) : ?>
									<?php echo wp_kses_post( wpautop( $section_content ) ); ?>
								<?php endif; ?>
							</section>
							<?php
						endwhile;
					elseif ( '' !== trim( (string) $raw_content ) ) :
						the_content();
					else :
						// Fallback to reference prototype dataset when neither SCF nor editor content is set.
						$default_data = goodnite_get_default_policy_sections();
						if ( '' === trim( $intro_text ) && ! empty( $default_data['intro'] ) ) :
							?>
							<header class="policy-intro">
								<h2 id="policy-introduction"><?php echo esc_html( $intro_heading ); ?></h2>
								<p><?php echo esc_html( $default_data['intro'] ); ?></p>
							</header>
							<?php
						endif;

						if ( ! empty( $default_data['sections'] ) ) :
							foreach ( $default_data['sections'] as $sec ) :
								?>
								<section class="policy-section">
									<h3><?php echo esc_html( $sec['heading'] ); ?></h3>
									<?php foreach ( $sec['paragraphs'] as $p ) : ?>
										<p><?php echo esc_html( $p ); ?></p>
									<?php endforeach; ?>
								</section>
								<?php
							endforeach;
						endif;
					endif;
					?>
				</article>
			</div>
		</section>
	</main>
	<?php
endwhile;

get_footer();
