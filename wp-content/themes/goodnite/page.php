<?php
/**
 * Standard page template.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main-content" class="content-page container-shell">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="page-<?php the_ID(); ?>" <?php post_class( 'gn-page' ); ?>>
			<header class="content-header">
				<span class="eyebrow"><?php esc_html_e( 'Goodnite India', 'goodnite' ); ?></span>
				<h1><?php the_title(); ?></h1>
			</header>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
