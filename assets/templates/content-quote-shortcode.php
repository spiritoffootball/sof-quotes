<?php
/**
 * The default template for displaying a Quote.
 *
 * @since 0.1.1
 *
 * @package Spirit_Of_Football_Quotes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?><!-- content-quote.php -->
<style>
.quote cite:before { content: '- '; }
</style>
<article <?php post_class( $class ); ?> id="post-<?php the_ID(); ?>" style="position: relative;">
	<div class="entry-content">
		<?php edit_post_link( __( 'Edit Quote', 'sof-quotes' ), '<span class="edit-link" style="position: absolute; top: 4px; right: 4px; text-transform: uppercase;">', '</span>' ); ?>
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
