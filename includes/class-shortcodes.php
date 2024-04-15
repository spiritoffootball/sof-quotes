<?php
/**
 * Shortcodes Class.
 *
 * Handles registration of Shortcodes.
 *
 * @since 0.2
 *
 * @package Spirit_Of_Football_Quotes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Custom Shortcodes Class.
 *
 * A class that encapsulates registration of Shortcodes in this plugin.
 *
 * @since 0.2
 */
class Spirit_Of_Football_Quotes_Shortcodes {

	/**
	 * Plugin object.
	 *
	 * @since 0.2
	 * @access public
	 * @var Spirit_Of_Football_Quotes
	 */
	public $plugin;

	/**
	 * Single Quote Shortcode object.
	 *
	 * @since 0.2
	 * @access public
	 * @var Spirit_Of_Football_Quotes_Shortcode_Single
	 */
	public $single;

	/**
	 * Quote Slider Shortcode object.
	 *
	 * @since 0.2
	 * @access public
	 * @var Spirit_Of_Football_Quotes_Shortcode_Slider
	 */
	public $slider;

	/**
	 * Constructor.
	 *
	 * @since 0.2
	 *
	 * @param object $plugin The plugin object.
	 */
	public function __construct( $plugin ) {

		// Store reference to plugin.
		$this->plugin = $plugin;

		// Init when this plugin is loaded.
		add_action( 'sof_quotes/loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Initialise this object.
	 *
	 * @since 0.2
	 */
	public function initialise() {

		// Register Shortcodes.
		$this->include_files();
		$this->setup_globals();

		/**
		 * Broadcast that this class is active.
		 *
		 * @since 0.2
		 */
		do_action( 'sof_quotes/shortcodes/loaded' );

	}

	/**
	 * Include files.
	 *
	 * @since 0.1
	 */
	public function include_files() {

		// Include files.
		include_once SOF_QUOTES_PATH . 'includes/class-shortcode-quote-single.php';
		include_once SOF_QUOTES_PATH . 'includes/class-shortcode-quote-slider.php';

	}

	/**
	 * Set up objects.
	 *
	 * @since 0.1
	 */
	public function setup_globals() {

		// Instantiate objects.
		$this->single = new Spirit_Of_Football_Quotes_Shortcode_Single( $this );
		$this->slider = new Spirit_Of_Football_Quotes_Shortcode_Slider( $this );

	}

}
