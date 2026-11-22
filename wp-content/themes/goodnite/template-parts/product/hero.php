<?php
/**
 * Product hero section: breadcrumbs, gallery, configurator, and specifications.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

$comfort_labels = $product['comfort']['labels'] ?? array('Extra Firm', 'Firm', 'Medium', 'Plush', 'Extra Plush');
$comfort_pos = (int) ($product['comfort']['position'] ?? 3);
$comfort_name = $comfort_labels[$comfort_pos] ?? ($comfort_labels[2] ?? 'Medium');

$main_img = $product['gallery'][0] ?? array('src' => '', 'alt' => '', 'label' => '');
$main_img_url = (0 === strpos($main_img['src'], 'http') || 0 === strpos($main_img['src'], '//'))
	? $main_img['src']
	: goodnite_asset_url($main_img['src']);
?>
<section class="gn-pdp-hero container-shell" aria-labelledby="pdp-title">
	<div class="gn-gallery" data-gallery
		aria-label="<?php echo esc_attr(sprintf(__('%s product gallery', 'goodnite'), $product['name'] ?? 'Mattress')); ?>">
		<nav class="gn-pdp__breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'goodnite'); ?>">
			<a href="<?php echo esc_url(goodnite_url('/')); ?>"><?php esc_html_e('Home', 'goodnite'); ?></a><span
				aria-hidden="true">/</span>
			<a
				href="<?php echo esc_url(goodnite_url('/mattresses')); ?>"><?php esc_html_e('Mattresses', 'goodnite'); ?></a><span
				aria-hidden="true">/</span>
			<a
				href="<?php echo esc_url(goodnite_url($product['collection_url'] ?? '/collection')); ?>"><?php echo esc_html($product['collection'] ?? 'Collection'); ?></a><span
				aria-hidden="true">/</span>
			<span aria-current="page"><?php echo esc_html($product['name']); ?></span>
		</nav>
		<div class="gn-gallery__stage">
			<button class="gn-gallery__main" type="button" data-gallery-zoom
				aria-label="<?php esc_attr_e('Enlarge product image', 'goodnite'); ?>">
				<img src="<?php echo esc_url($main_img_url); ?>"
					alt="<?php echo esc_attr($main_img['alt'] ?? ''); ?>" width="1100" height="1375"
					fetchpriority="high" decoding="async" data-gallery-image>
				<span class="gn-gallery__zoom-hint" role="img"
					aria-label="<?php esc_attr_e('Click to enlarge', 'goodnite'); ?>"><?php echo goodnite_icon('zoom-in', 18, 1.8); ?></span>
			</button>
			<?php if (count((array) $product['gallery']) > 1): ?>
				<button class="gn-gallery__arrow gn-gallery__arrow--prev" type="button" data-gallery-prev
					aria-label="<?php esc_attr_e('Previous image', 'goodnite'); ?>"><?php echo goodnite_icon('arrow-left', 20, 1.8); ?></button>
				<button class="gn-gallery__arrow gn-gallery__arrow--next" type="button" data-gallery-next
					aria-label="<?php esc_attr_e('Next image', 'goodnite'); ?>"><?php echo goodnite_icon('arrow-right', 20, 1.8); ?></button>
			<?php endif; ?>
			<?php if (count((array) $product['gallery']) > 1): ?>
				<div class="gn-gallery__indicator" data-gallery-indicator role="tablist"
					aria-label="<?php esc_attr_e('Choose product image', 'goodnite'); ?>">
					<?php foreach ($product['gallery'] as $index => $image): ?>
						<button class="gn-gallery__indicator-dot<?php echo $index === 0 ? ' is-active' : ''; ?>" type="button"
							data-gallery-indicator-dot="<?php echo (int) $index; ?>" role="tab"
							aria-label="<?php echo esc_attr(sprintf(__('Show image %d', 'goodnite'), (int) ($index + 1))); ?>"
							aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="gn-gallery__thumbs" aria-label="<?php esc_attr_e('Choose product image', 'goodnite'); ?>">
			<?php foreach ((array) $product['gallery'] as $index => $image): ?>
				<?php
				$thumb_url = (0 === strpos($image['src'], 'http') || 0 === strpos($image['src'], '//'))
					? $image['src']
					: goodnite_asset_url($image['src']);
				?>
				<button class="gn-gallery__thumb<?php echo $index === 0 ? ' is-active' : ''; ?>" type="button"
					data-gallery-thumb="<?php echo (int) $index; ?>"
					aria-label="<?php echo esc_attr(sprintf(__('Show %s', 'goodnite'), $image['label'] ?? '')); ?>"
					aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>">
					<img src="<?php echo esc_url($thumb_url); ?>" alt="" width="80" height="100" loading="lazy"
						decoding="async">
				</button>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="gn-pdp-panel">
		<div class="gn-pdp-panel__heading">
			<h1 id="pdp-title"><?php echo esc_html($product['name']); ?></h1>
		</div>
		<div class="gn-pdp-panel__intro gn-pdp-feature-pills"
			aria-label="<?php esc_attr_e('Mattress features', 'goodnite'); ?>">
			<?php foreach ((array) ($product['feature_pills'] ?? array()) as $pill): ?>
				<?php
				$pill_icon_url = '';
				if (!empty($pill['icon'])) {
					$pill_icon_url = (0 === strpos($pill['icon'], 'http') || 0 === strpos($pill['icon'], '//'))
						? $pill['icon']
						: goodnite_asset_url($pill['icon']);
				}
				?>
				<span class="gn-pdp-feature-pill">
					<?php if ($pill_icon_url): ?>
						<img src="<?php echo esc_url($pill_icon_url); ?>" alt="" width="24" height="24" loading="lazy"
							decoding="async">
					<?php endif; ?>
					<span><?php echo esc_html($pill['label'] ?? ''); ?></span>
				</span>
			<?php endforeach; ?>
		</div>
		<div class="gn-comfort"
			aria-label="<?php echo esc_attr(sprintf(__('Comfort feel: %s', 'goodnite'), $comfort_name)); ?>">
			<div class="gn-comfort__heading">
				<h2><?php esc_html_e('Comfort Feel:', 'goodnite'); ?></h2>
				<strong><?php echo esc_html($comfort_name); ?></strong>
			</div>
			<div class="gn-comfort__scale" aria-hidden="true">
				<?php foreach ($comfort_labels as $index => $label): ?>
					<span class="<?php echo $index === $comfort_pos ? 'is-current' : ''; ?>"></span>
				<?php endforeach; ?>
			</div>
			<div class="gn-comfort__labels">
				<?php foreach ($comfort_labels as $label): ?>
					<span><?php echo esc_html($label); ?></span>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="gn-pdp-config">
			<div class="gn-pdp-config__field">
				<div class="gn-pdp-config__size-head">
					<h2 id="size-label"><?php esc_html_e('Mattress Size', 'goodnite'); ?></h2>
					<button type="button" data-open-chart><?php esc_html_e('Size Chart', 'goodnite'); ?></button>
				</div>
				<div class="gn-size-picker__row">
					<button class="gn-size-picker__trigger" type="button" id="size-trigger" aria-haspopup="dialog"
						aria-controls="size-picker" aria-labelledby="size-label size-value" data-size-trigger>
						<span id="size-value"
							data-size-value><?php echo esc_html(gn_variant_label($variant)); ?></span><?php echo goodnite_icon('chevron-down', 18, 1.8); ?>
					</button>
					<button class="gn-size-picker__custom" type="button" data-open-custom>
						<span><?php esc_html_e('Custom Size', 'goodnite'); ?></span><?php echo goodnite_icon('edit', 16, 1.8); ?>
					</button>
				</div>
				<noscript>
					<p><?php esc_html_e('Enable JavaScript to compare all size options.', 'goodnite'); ?></p>
				</noscript>
			</div>
		</div>
		<div class="gn-pdp-purchase-row">
			<div class="gn-pdp-price" aria-live="polite">
				<strong data-price><?php
				$starting_price = gn_display_price($product, $variant['id'], false);
				echo esc_html(null === $starting_price ? __('Price unavailable', 'goodnite') : gn_money($starting_price));
				?></strong>
				<small><?php esc_html_e('Inclusive all taxes', 'goodnite'); ?></small>
			</div>
		</div>
		<div class="gn-pdp-purchase" data-purchase-area>
			<button class="gn-pdp-purchase__main" type="button" data-open-store-locator>
				<?php echo goodnite_icon('map-pin', 18, 1.8); ?>
				<span><?php esc_html_e('Locate Nearby Store', 'goodnite'); ?></span>
			</button>
			<button class="gn-pdp-purchase__help" type="button" data-open-help>
				<?php echo goodnite_icon('phone', 18, 1.8); ?>
				<span><?php esc_html_e('Need Help?', 'goodnite'); ?></span>
			</button>
		</div>
		<div class="gn-pdp-panel__accordions" aria-label="<?php esc_attr_e('Product information', 'goodnite'); ?>">
			<details class="gn-pdp-panel__accordion">
				<summary>
					<?php esc_html_e('Product Specifications', 'goodnite'); ?><?php echo goodnite_icon('chevron-down', 18, 1.8); ?>
				</summary>
				<div class="gn-pdp-panel__accordion-copy">
					<?php echo wp_kses_post($product['specs']['materials'] ?? ''); ?></div>
			</details>
			<details class="gn-pdp-panel__accordion">
				<summary>
					<?php esc_html_e('Care Instructions', 'goodnite'); ?><?php echo goodnite_icon('chevron-down', 18, 1.8); ?>
				</summary>
				<div class="gn-pdp-panel__accordion-copy"><?php echo wp_kses_post($product['specs']['care'] ?? ''); ?>
				</div>
			</details>
			<details class="gn-pdp-panel__accordion">
				<summary>
					<?php esc_html_e('Shipping and Return Policy', 'goodnite'); ?><?php echo goodnite_icon('chevron-down', 18, 1.8); ?>
				</summary>
				<div class="gn-pdp-panel__accordion-copy">
					<?php echo wp_kses_post($product['specs']['shipping'] ?? ''); ?></div>
			</details>
		</div>
	</div>
</section>