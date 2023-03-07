<?php
/**
 * Quote Slider Shortcode Class.
 *
 * Handles the Shortcode for Quote Sliders.
 *
 * @since 0.2
 *
 * @package Spirit_Of_Football_Quote_Slider
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Custom Shortcode Class.
 *
 * A class that encapsulates a Shortcode for Quote Sliders.
 *
 * @since 0.2
 */
class Spirit_Of_Football_Quotes_Shortcode_Slider {

	/**
	 * Plugin object.
	 *
	 * @since 0.2
	 * @access public
	 * @var object
	 */
	public $plugin;

	/**
	 * Constructor.
	 *
	 * @since 0.2
	 *
	 * @param object $parent The parent object.
	 */
	public function __construct( $parent ) {

		// Store reference to plugin.
		$this->plugin = $parent;

		// Init when this plugin is loaded.
		add_action( 'sof_quotes/shortcodes/loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Initialises this object.
	 *
	 * @since 0.2
	 */
	public function initialise() {

		// Bootstrap class.
		$this->register_hooks();

		/**
		 * Broadcast that this class is active.
		 *
		 * @since 0.2
		 */
		do_action( 'sof_quotes/shortcode/slider/loaded' );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.2
	 */
	public function register_hooks() {

		// Register shortcodes.
		add_shortcode( 'quote_slider', [ $this, 'shortcode_render' ] );

	}

	// -------------------------------------------------------------------------

	/**
	 * Adds a Quote Slider to a page/post via a Shortcode.
	 *
	 * @since 0.2
	 *
	 * @param array $attr The saved Shortcode attributes.
	 * @param string $content The enclosed content of the Shortcode.
	 * @param string $tag The Shortcode which invoked the callback.
	 * @return string $quote The HTML-formatted Shortcode content.
	 */
	public function shortcode_render( $attr, $content = '', $tag = '' ) {

		// Init return.
		$quote = '';

		// Default Shortcode attributes.
		$defaults = [
			'type' => '',
		];

		// Get parsed attributes.
		$atts = shortcode_atts( $defaults, $attr, $tag );

		// Bail if there's anything amiss.
		if ( empty( $atts['type'] ) || is_feed() ) {
			return $quote;
		}

		// Prevent immediate output.
		ob_start();

		if ( 'pledge' === $atts['type'] ) :
			$pledge_loop = locate_template( 'template-parts/loop-quotes-pledges.php' );
			if ( ! empty( $pledge_loop ) ) :
				load_template( $pledge_loop );
			endif;
		elseif ( 'statement' === $atts['type'] ) :
			$statement_loop = locate_template( 'template-parts/loop-quotes-statements.php' );
			if ( ! empty( $statement_loop ) ) :
				load_template( $statement_loop );
			endif;
		endif;

		$quote = ob_get_contents();

		// Clean up.
		ob_end_clean();

		// --<
		return $quote;

	}

}
