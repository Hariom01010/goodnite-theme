<?php
/**
 * Product construction section: exploded diagram and numbered layer details.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

$const_title = !empty($product['construction']['title'])
	? $product['construction']['title']
	: __("What's Inside the Mattress?", 'goodnite');

$const_image = !empty($product['construction']['image'])
	? $product['construction']['image']
	: 'images/What inside Mattress.png';

$const_image_url = (0 === strpos($const_image, 'http') || 0 === strpos($const_image, '//'))
	? $const_image
	: goodnite_asset_url($const_image);
?>
<section class="gn-pdp-section gn-construction" aria-labelledby="construction-title">
	<div class="container-shell">
		<div class="gn-pdp-section__head">
			<h2 id="construction-title"><?php echo esc_html($const_title); ?></h2>
		</div>
		<div class="gn-construction__pending">
			<div class="gn-construction__visual">
				<img src="<?php echo esc_url($const_image_url); ?>"
					alt="<?php echo esc_attr(sprintf(__('Exploded view of the %s mattress layers', 'goodnite'), $product['name'] ?? 'Goodnite')); ?>">
			</div>
			<div class="gn-construction__details">
				<?php foreach ((array) ($product['construction']['layers'] ?? array()) as $layer): ?>
					<article class="gn-construction__detail">
						<h3><span><?php echo esc_html($layer['step'] ?? ''); ?></span>
							<?php echo esc_html($layer['title'] ?? ''); ?></h3>
						<p><?php echo esc_html($layer['description'] ?? ''); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>