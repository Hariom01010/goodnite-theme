<?php
/**
 * The template for displaying the flagship homepage.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main-content" class="site-main">
	<?php
	get_template_part( 'template-parts/home/section-hero' );
	get_template_part( 'template-parts/home/section-legacy' );
	get_template_part( 'template-parts/home/section-collections' );
	get_template_part( 'template-parts/home/section-finder' );
	get_template_part( 'template-parts/home/section-technology' );
	get_template_part( 'template-parts/home/section-products' );
	get_template_part( 'template-parts/home/section-hospitality' );
	get_template_part( 'template-parts/home/section-locator' );
	?>
</main>
<?php
get_footer();
