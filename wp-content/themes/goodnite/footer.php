<?php
/**
 * Site footer template.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$footer_groups = array(
	array(
		'title'    => __( 'Shop', 'goodnite' ),
		'location' => 'footer_shop',
		'fallback' => array(
			'All Mattresses'   => '/mattresses',
			'Dreamer Series'   => '/mattresses/dreamer',
			'Love Series'      => '/mattresses/love',
			'Signature Series' => '/mattresses/signature',
		),
	),
	array(
		'title'    => __( 'Discover', 'goodnite' ),
		'location' => 'footer_discover',
		'fallback' => array(
			'Our Story'        => '/our-story',
			'Sleep Technology' => '/sleep-technology',
			'Hospitality'      => '/hospitality',
		),
	),
	array(
		'title'    => __( 'Policies', 'goodnite' ),
		'location' => 'footer_policies',
		'fallback' => array(
			'Shipping & Delivery' => '/shipping-delivery',
			'Returns & Refunds'   => '/returns-refunds',
			'Warranty'            => '/warranty',
			'Privacy Policy'      => '/privacy',
			'Terms & Conditions'  => '/terms',
		),
	),
	array(
		'title'    => __( 'Social', 'goodnite' ),
		'location' => 'footer_social',
		'fallback' => array(
			'Instagram' => 'https://www.instagram.com/',
			'YouTube'   => 'https://www.youtube.com/',
			'LinkedIn'  => 'https://www.linkedin.com/',
		),
	),
);
?>
<footer class="site-footer">
	<div class="container-shell">
		<div class="footer-top">
			<div class="footer-brand">
				<?php goodnite_logo(); ?>
				<p>
					<?php echo esc_html( goodnite_mod( 'goodnite_footer_tagline', 'International sleep expertise. Thoughtful innovation. Comfort made personal.' ) ); ?>
				</p>
			</div>

			<?php foreach ( $footer_groups as $group ) : ?>
				<div class="footer-group">
					<h2><?php echo esc_html( $group['title'] ); ?></h2>
					<?php
					if ( has_nav_menu( $group['location'] ) ) {
						wp_nav_menu(
							array(
								'theme_location' => $group['location'],
								'container'      => false,
								'menu_class'     => 'footer-menu',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
					} elseif ( 'footer_social' === $group['location'] ) {
						?>
						<ul class="menu footer-menu footer-social-menu">
							<li>
								<a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'goodnite' ); ?>">
									<span>Instagram</span>
									<?php echo goodnite_icon( 'instagram', 18, 1.7 ); ?>
								</a>
							</li>
							<li>
								<a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'YouTube', 'goodnite' ); ?>">
									<span>YouTube</span>
									<?php echo goodnite_icon( 'youtube', 18, 1.7 ); ?>
								</a>
							</li>
							<li>
								<a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'goodnite' ); ?>">
									<span>LinkedIn</span>
									<?php echo goodnite_icon( 'linkedin', 18, 1.7 ); ?>
								</a>
							</li>
						</ul>
						<?php
					} else {
						goodnite_fallback_menu( $group['fallback'], 'footer-menu' );
					}
					?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="footer-bottom">
			<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php esc_html_e( 'Goodnite India', 'goodnite' ); ?></span>
			<span class="footer-bottom__slogan"><?php echo esc_html( goodnite_mod( 'goodnite_footer_slogan', 'GLOBAL LUXURY SLEEP' ) ); ?></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
