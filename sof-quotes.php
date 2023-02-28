<?php
/**
 * Plugin Name: SOF Quotes
 * Plugin URI: https://github.com/spiritoffootball/sof-quotes
 * Description: Provides Quotes and associated functionality.
 * Author: Christian Wach
 * Version: 0.2
 * Author URI: https://haystack.co.uk
 * Text Domain: sof-quotes
 * Domain Path: /languages
 *
 * @package Spirit_Of_Football_Quotes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Set our version here.
define( 'SOF_QUOTES_VERSION', '0.2' );

// Store reference to this file.
if ( ! defined( 'SOF_QUOTES_FILE' ) ) {
	define( 'SOF_QUOTES_FILE', __FILE__ );
}

// Store URL to this plugin's directory.
if ( ! defined( 'SOF_QUOTES_URL' ) ) {
	define( 'SOF_QUOTES_URL', plugin_dir_url( SOF_QUOTES_FILE ) );
}

// Store PATH to this plugin's directory.
if ( ! defined( 'SOF_QUOTES_PATH' ) ) {
	define( 'SOF_QUOTES_PATH', plugin_dir_path( SOF_QUOTES_FILE ) );
}

/**
 * SOF Quotes Class.
 *
 * A class that encapsulates network-wide quotations.
 *
 * @since 0.1
 */
class Spirit_Of_Football_Quotes {

	/**
	 * Custom Post Type object.
	 *
	 * @since 0.1
	 * @access public
	 * @var object $cpt The Custom Post Type object.
	 */
	public $cpt;

	/**
	 * Metabox object.
	 *
	 * @since 0.1
	 * @access public
	 * @var object $metabox The Metabox object.
	 */
	public $metabox;

	/**
	 * Shortcode object.
	 *
	 * @since 0.1
	 * @access public
	 * @var object $shortcod The Shortcode object.
	 */
	public $shortcode;

	/**
	 * ACF loader object.
	 *
	 * @since 0.1.1
	 * @access public
	 * @var object $acf The ACF loader object.
	 */
	public $acf;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// Initialise on "plugins_loaded".
		add_action( 'plugins_loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Initialise this plugin.
	 *
	 * @since 0.1.1
	 */
	public function initialise() {

		// Only do this once.
		static $done;
		if ( isset( $done ) && $done === true ) {
			return;
		}

		// Bootstrap plugin.
		$this->translation();
		$this->include_files();
		$this->setup_globals();
		$this->register_hooks();

		/**
		 * Broadcast that this plugin is now loaded.
		 *
		 * @since 0.1.1
		 */
		do_action( 'sof_quotes/loaded' );

		// We're done.
		$done = true;

	}

	/**
	 * Include files.
	 *
	 * @since 0.1
	 */
	public function include_files() {

		// Include files.
		include_once SOF_QUOTES_PATH . 'includes/class-cpt-quotes.php';
		include_once SOF_QUOTES_PATH . 'includes/class-metabox-quotes.php';
		include_once SOF_QUOTES_PATH . 'includes/class-shortcode-quotes.php';
		include_once SOF_QUOTES_PATH . 'includes/class-acf-quotes.php';

	}

	/**
	 * Set up objects.
	 *
	 * @since 0.1
	 */
	public function setup_globals() {

		// Instantiate objects.
		$this->cpt = new Spirit_Of_Football_Quotes_CPT( $this );
		$this->metabox = new Spirit_Of_Football_Quotes_Metabox( $this );
		$this->shortcode = new Spirit_Of_Football_Quotes_Shortcode( $this );
		$this->acf = new Spirit_Of_Football_Quotes_ACF( $this );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Initialise widgets.
		add_action( 'widgets_init', [ $this, 'register_widgets' ] );

	}

	/**
	 * Perform plugin activation tasks.
	 *
	 * @since 0.1.1
	 */
	public function activate() {

		// Maybe init.
		$this->initialise();

		/**
		 * Broadcast plugin activation.
		 *
		 * @since 0.1.1
		 */
		do_action( 'sof_quotes/activate' );

	}

	/**
	 * Perform plugin deactivation tasks.
	 *
	 * @since 0.1.1
	 */
	public function deactivate() {

		// Maybe init.
		$this->initialise();

		/**
		 * Broadcast plugin deactivation.
		 *
		 * @since 0.1.1
		 */
		do_action( 'sof_quotes/deactivate' );

	}

	/**
	 * Loads translation, if present.
	 *
	 * @since 0.1
	 */
	public function translation() {

		// Load translations.
		// phpcs:ignore WordPress.WP.DeprecatedParameters.Load_plugin_textdomainParam2Found
		load_plugin_textdomain(
			'sof-quotes', // Unique name.
			false, // Deprecated argument.
			dirname( plugin_basename( SOF_QUOTES_FILE ) ) . '/languages/' // Relative path to files.
		);

	}

	/**
	 * Register widgets for this plugin.
	 *
	 * @since 0.1
	 */
	public function register_widgets() {

		// Include widgets.
		require_once SOF_QUOTES_PATH . 'widgets/sof-quotes-widget-random.php';

		// Register widgets.
		register_widget( 'SOF_Quote_Widget' );

	}

}

/**
 * Utility to get a reference to this plugin.
 *
 * @since 0.1.1
 *
 * @return Spirit_Of_Football_Quotes $plugin The plugin reference.
 */
function spirit_of_football_quotes() {

	// Store instance in static variable.
	static $plugin = false;

	// Maybe return instance.
	if ( false === $plugin ) {
		$plugin = new Spirit_Of_Football_Quotes();
	}

	// --<
	return $plugin;

}

// Initialise plugin now.
spirit_of_football_quotes();

// Activation.
register_activation_hook( __FILE__, [ spirit_of_football_quotes(), 'activate' ] );

// Deactivation.
register_deactivation_hook( __FILE__, [ spirit_of_football_quotes(), 'deactivate' ] );
