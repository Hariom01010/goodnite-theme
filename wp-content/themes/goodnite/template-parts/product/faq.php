<?php
/**
 * Product FAQ section: collapsible accordions for common customer questions.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faqs = (array) ( $product['faqs'] ?? array() );
?>
<section class="gn-pdp-section gn-pdp-faq" aria-labelledby="faq-title">
	<div class="container-shell">
		<div class="gn-pdp-section__head">
			<h2 id="faq-title"><?php esc_html_e( 'Frequently Asked Questions.', 'goodnite' ); ?></h2>
		</div>
		<div class="gn-pdp-faq__list">
			<?php foreach ( $faqs as $faq ) : ?>
				<details class="gn-pdp-faq__item">
					<summary>
						<?php echo esc_html( $faq['question'] ?? '' ); ?>
						<span aria-hidden="true">+</span>
					</summary>
					<p><?php echo esc_html( $faq['answer'] ?? '' ); ?></p>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
