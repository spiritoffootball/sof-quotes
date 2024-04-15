<?php
/**
 * Custom Shortcode Class.
 *
 * Handles all Shortcode for Quotes.
 *
 * @since 0.1
 *
 * @package Spirit_Of_Football_Quotes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Custom Shortcode Class.
 *
 * A class that encapsulates a Shortcode for Quotes.
 *
 * @since 0.1
 */
class Spirit_Of_Football_Quotes_Shortcode_Single {

	/**
	 * Shortcodes object.
	 *
	 * @since 0.1.1
	 * @access public
	 * @var Spirit_Of_Football_Quotes_Shortcodes
	 */
	public $shortcodes;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param object $parent The parent object.
	 */
	public function __construct( $parent ) {

		// Store reference to Shortcodes object.
		$this->shortcodes = $parent;

		// Init when the Shortcodes object is loaded.
		add_action( 'sof_quotes/shortcodes/loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Initialises this object.
	 *
	 * @since 0.1.1
	 */
	public function initialise() {

		// Bootstrap class.
		$this->register_hooks();

		/**
		 * Broadcast that this class is active.
		 *
		 * @since 0.1.1
		 */
		do_action( 'sof_quotes/shortcode/single/loaded' );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Register shortcodes.
		add_shortcode( 'quote', [ $this, 'shortcode_render' ] );

	}

	// -------------------------------------------------------------------------

	/**
	 * Add a quote to a page/post via a shortcode.
	 *
	 * @since 0.1
	 *
	 * @param array  $attr The saved Shortcode attributes.
	 * @param string $content The enclosed content of the Shortcode.
	 * @param string $tag The Shortcode which invoked the callback.
	 * @return string $quote The HTML-formatted Shortcode content.
	 */
	public function shortcode_render( $attr, $content = '', $tag = '' ) {

		// Init return.
		$quote = '';

		// Default Shortcode attributes.
		$defaults = [
			'id'    => '',
			'align' => 'none',
		];

		// Get parsed attributes.
		$atts = shortcode_atts( $defaults, $attr, $tag );

		// Bail if there's anything amiss.
		if ( empty( $atts['id'] ) || is_feed() ) {
			return $quote;
		}

		// Define args for query.
		$query_args = [
			'post_type'      => 'quote',
			'p'              => $atts['id'],
			'no_found_rows'  => true,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
		];

		// Do query.
		$quotes = new WP_Query( $query_args );

		// Give class to article.
		$class = 'alignnone';
		switch ( $atts['align'] ) {
			case 'none':
				$class = 'alignnone';
				break;
			case 'right':
				$class = 'alignright';
				break;
			case 'left':
				$class = 'alignleft';
				break;
		}

		// Make sure any theme (other than TwentyEleven) gets no alignment.
		if ( function_exists( 'bp_core_get_user_domain' ) ) {
			$class = 'alignnone';
		}

		// Did we get any results?
		if ( $quotes->have_posts() ) :

			// Prevent immediate output.
			ob_start();

			while ( $quotes->have_posts() ) :
				$quotes->the_post();

				if ( has_term( 'pledge', 'quote-type' ) ) :
					get_template_part( 'template-parts/content', 'quote-pledge' );
				elseif ( has_term( 'statement', 'quote-type' ) ) :
					get_template_part( 'template-parts/content', 'quote-statement' );
				else :
					include SOF_QUOTES_PATH . 'assets/templates/content-quote-shortcode.php';
				endif;
			endwhile;

			// Get the quote.
			$quote = ob_get_contents();

			// Clean up.
			ob_end_clean();

		endif;

		// Reset the post globals as this query will have stomped on it.
		wp_reset_postdata();

		/*
		// Give article tag an alignment.
		$quote = str_replace( '<article class="', '<article class="' . $class . ' ', $quote );
		*/

		// Give article edit button a class.
		$quote = str_replace( '<a class="post-edit-link', '<a class="post-edit-link button', $quote );

		// --<
		return $quote;

	}

}
