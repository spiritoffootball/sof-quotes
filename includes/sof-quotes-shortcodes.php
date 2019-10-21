<?php

/**
 * SOF Quotes Custom Shortcodes Class.
 *
 * A class that encapsulates all Shortcodes for Quotes.
 *
 * @package WordPress
 * @subpackage SOF
 */
class Spirit_Of_Football_Quotes_Shortcodes {



	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// Nothing.

	}



	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Register shortcodes.
		add_shortcode( 'quote', array( $this, 'quote_shortcode' ) );

	}




	// #########################################################################




	/**
	 * Add a quote to a page/post via a shortcode.
	 *
	 * @since 0.1
	 *
	 * @param array $attr The saved shortcode attributes.
	 * @param str $content The enclosed content of the shortcode.
	 * @return str $quote The rendered shortcode.
	 */
	public function quote_shortcode( $attr, $content = null ) {

		// Init return.
		$quote = '';

		// Get params.
		extract( shortcode_atts( array(
			'id'	=> '',
			'align'	=> 'none'
		), $attr ) );

		// Kick out if there's anything amiss.
		if ( $id == '' OR is_feed() ) {
			return $quote;
		}

		// Define args for query.
		$query_args = array(
			'post_type' => 'quote',
			'p' => $id,
			'no_found_rows' => true,
			'post_status' => 'publish',
			'posts_per_page' => 1,
		);

		// Do query.
		$quotes = new WP_Query( $query_args );

		// Give class to article.
		$class = 'alignnone';
		switch( $align ) {
			case 'none': $class = 'alignnone'; break;
			case 'right': $class = 'alignright'; break;
			case 'left': $class = 'alignleft'; break;
		}

		// Make sure any theme (other than TwentyEleven) gets no alignment.
		if ( function_exists( 'bp_core_get_user_domain' ) ) {
			$class = 'alignnone';
		}

		// Did we get any results?
		if ( $quotes->have_posts() ) :

			// Prevent immediate output.
			ob_start();

			while ( $quotes->have_posts() ) : $quotes->the_post(); ?>

				<style>
				.quote cite:before { content: '- '; }
				</style>
				<article <?php post_class( $class ); ?> id="post-<?php the_ID(); ?>" style="position: relative;">
					<div class="entry-content">
						<?php edit_post_link( __( 'Edit Quote', 'sof-quotes' ), '<span class="edit-link" style="position: absolute; top: 4px; right: 4px; text-transform: uppercase;">', '</span>' ); ?>
						<?php the_content(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-<?php the_ID(); ?> -->

			<?php endwhile;

			// Get the quote.
			$quote = ob_get_contents();

			// Clean up.
			ob_end_clean();

		endif;

		// Reset the post globals as this query will have stomped on it.
		wp_reset_postdata();

		// Give article tag an alignment.
		//$quote = str_replace( '<article class="', '<article class="' . $class . ' ', $quote );

		// Give article edit button a class.
		$quote = str_replace( '<a class="post-edit-link', '<a class="post-edit-link button', $quote );

		// --<
		return $quote;

	}




} // class Spirit_Of_Football_Quotes_Shortcodes ends



