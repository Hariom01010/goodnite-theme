<?php
/**
 * Product technologies section: video cards illustrating core materials and design.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tech_title   = ! empty( $product['tech_section_title'] )
	? $product['tech_section_title']
	: __( 'The Details Make the Difference.', 'goodnite' );
$technologies = (array) ( $product['technologies'] ?? array() );
?>
<section class="gn-pdp-section gn-pdp-tech" aria-labelledby="technology-title">
	<div class="container-shell">
		<div class="gn-pdp-section__head">
			<h2 id="technology-title"><?php echo esc_html( $tech_title ); ?></h2>
		</div>
		<div class="gn-pdp-tech__grid">
			<?php foreach ( $technologies as $technology ) : ?>
				<?php
				$tech_name = (string) ( $technology['name'] ?? '' );
				$tagline   = (string) ( $technology['tagline'] ?? ( $technology['description'] ?? '' ) );
				$has_tm    = ! empty( $technology['has_tm'] ) || in_array( $tech_name, array( 'CloudFlex', 'SupportCore' ), true );

				// Resolve video URL.
				$video_url = '';
				if ( ! empty( $technology['video'] ) ) {
					$video_url = ( 0 === strpos( $technology['video'], 'http' ) || 0 === strpos( $technology['video'], '//' ) )
						? $technology['video']
						: goodnite_asset_url( $technology['video'] );
				}

				// Resolve poster URL.
				$poster_url = '';
				if ( ! empty( $technology['poster'] ) ) {
					$poster_url = ( 0 === strpos( $technology['poster'], 'http' ) || 0 === strpos( $technology['poster'], '//' ) )
						? $technology['poster']
						: goodnite_asset_url( $technology['poster'] );
				}

				$has_video = ! empty( $video_url );
				?>
				<article class="gn-pdp-tech__item">
					<div class="gn-pdp-tech__visual<?php echo $has_video ? ' gn-pdp-tech__visual--video' : ''; ?>">
						<?php if ( $has_video ) : ?>
							<video autoplay loop muted playsinline preload="metadata"<?php echo $poster_url ? ' poster="' . esc_url( $poster_url ) . '"' : ''; ?> aria-hidden="true">
								<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
							</video>
						<?php else : ?>
							<span><?php echo esc_html( $tech_name ); ?></span>
						<?php endif; ?>
						<div class="gn-pdp-tech__overlay">
							<h3><?php echo esc_html( $tech_name ); ?><?php echo $has_tm ? '™' : ''; ?></h3>
							<p><?php echo esc_html( $tagline ); ?></p>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<div class="products-carousel-controls gn-pdp-tech__carousel-controls" data-tech-carousel-controls></div>
	</div>
</section>
