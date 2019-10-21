<?php
/**
 * The default template for displaying a Quote.
 *
 * @package SOF_Quotes
 * @since 0.3
 */
?>

<!-- content-quote.php -->

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="entry-content">
		<?php edit_post_link( __( 'Edit', 'sof-quotes' ), '<span class="edit-link alignright">', '</span>' ); ?>
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
