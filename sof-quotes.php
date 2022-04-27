<?php
/**
 * Plugin Name: SOF Quotes
 * Plugin URI: http://spiritoffootball.com
 * Description: Provides Quotes and associated functionality.
 * Author: Christian Wach
 * Version: 0.1.1
 * Author URI: https://haystack.co.uk
 * Text Domain: sof-quotes
 * Domain Path: /languages
 *
 * @package Spirit_Of_Football_Quotes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Set our version here.
define( 'SOF_QUOTES_VERSION', '0.1.1' );

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
	 * Metaboxes object.
	 *
	 * @since 0.1
	 * @access public
	 * @var object $metaboxes The Metaboxes object.
	 */
	public $metaboxes;

	/**
	 * Shortcodes object.
	 *
	 * @since 0.1
	 * @access public
	 * @var object $shortcodes The Shortcodes object.
	 */
	public $shortcodes;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// Include files.
		$this->include_files();

		// Setup globals.
		$this->setup_globals();

		// Register hooks.
		$this->register_hooks();

	}

	/**
	 * Include files.
	 *
	 * @since 0.1
	 */
	public function include_files() {

		// Include CPT class.
		include_once SOF_QUOTES_PATH . 'includes/sof-quotes-cpt.php';

		// Include Metaboxes class.
		include_once SOF_QUOTES_PATH . 'includes/sof-quotes-metaboxes.php';

		// Include Shortcodes class.
		include_once SOF_QUOTES_PATH . 'includes/sof-quotes-shortcodes.php';

	}

	/**
	 * Set up objects.
	 *
	 * @since 0.1
	 */
	public function setup_globals() {

		// Init CPT object.
		$this->cpt = new Spirit_Of_Football_Quotes_CPT();

		// Init Metaboxes object.
		$this->metaboxes = new Spirit_Of_Football_Quotes_Metaboxes();

		// Init Shortcodes object.
		$this->shortcodes = new Spirit_Of_Football_Quotes_Shortcodes();

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Use translation.
		add_action( 'plugins_loaded', [ $this, 'translation' ] );

		// Hooks that always need to be present.
		$this->cpt->register_hooks();
		$this->metaboxes->register_hooks();
		$this->shortcodes->register_hooks();

		// Add widgets.
		add_action( 'widgets_init', [ $this, 'register_widgets' ] );

	}

	/**
	 * Actions to perform on plugin activation.
	 *
	 * @since 0.1
	 */
	public function activate() {

		// Pass through.
		$this->cpt->activate();

	}

	/**
	 * Actions to perform on plugin deactivation (NOT deletion)
	 *
	 * @since 0.1
	 */
	public function deactivate() {

		// Pass through.
		$this->cpt->deactivate();

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
