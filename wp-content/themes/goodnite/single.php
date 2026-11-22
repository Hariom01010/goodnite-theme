<?php
/**
 * Single post template.
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
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'gn-single-article' ); ?>>
			<header class="content-header">
				<span class="eyebrow"><?php esc_html_e( 'Journal', 'goodnite' ); ?></span>
				<h1><?php the_title(); ?></h1>
				<div class="article-meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</div>
			</header>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
