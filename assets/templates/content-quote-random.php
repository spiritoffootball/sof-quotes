<?php
/**
 * The default template for displaying a Random Quote.
 *
 * @since 0.1.1
 *
 * @package Spirit_Of_Football_Quotes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?><!-- content-quote.php -->
<style>
	.widget_sof_random_quote ol {
		list-style: none;
		margin: 0;
		padding: 0;
	}
	.widget_sof_random_quote blockquote {
		border: none;
		line-height: 1.5;
	}
	.widget_sof_random_quote cite:before {
		content: '- ';
	}
</style>
<ol>
	<?php while ( $quotes->have_posts() ) : ?>
		<?php $quotes->the_post(); ?>
		<li class="widget-entry-title">
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>" style="position: relative;">
				<div class="entry-content">
					<?php edit_post_link( __( 'Edit Quote', 'sof-quotes' ), '<span class="edit-link" style="position: absolute; top: 4px; right: 4px; text-transform: uppercase;">', '</span>' ); ?>
					<?php the_content(); ?>
				</div><!-- .entry-content -->
			</article><!-- #post-<?php the_ID(); ?> -->
		</li>
	<?php endwhile; ?>
</ol>
