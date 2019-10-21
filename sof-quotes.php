<?php
/*
Plugin Name: SOF Quotes
Plugin URI: http://spiritoffootball.com
Description: Provides Quotes and associated functionality.
Author: Christian Wach
Version: 0.1.1
Author URI: http://haystack.co.uk
*/


// set our version here
define( 'SOF_QUOTES_VERSION', '0.1.1' );

// store reference to this file
if ( ! defined( 'SOF_QUOTES_FILE' ) ) {
	define( 'SOF_QUOTES_FILE', __FILE__ );
}

// store URL to this plugin's directory
if ( ! defined( 'SOF_QUOTES_URL' ) ) {
	define( 'SOF_QUOTES_URL', plugin_dir_url( SOF_QUOTES_FILE ) );
}

// store PATH to this plugin's directory
if ( ! defined( 'SOF_QUOTES_PATH' ) ) {
	define( 'SOF_QUOTES_PATH', plugin_dir_path( SOF_QUOTES_FILE ) );
}



/**
 * SOF Quotes Class
 *
 * A class that encapsulates network-wide quotations
 *
 * @package WordPress
 * @subpackage SOF
 */
class Spirit_Of_Football_Quotes {



	/**
	 * Custom Post Type object
	 *
	 * @since 0.1
	 * @access public
	 * @var object $cpt The Custom Post Type object
	 */
	public $cpt;



	/**
	 * Metaboxes object
	 *
	 * @since 0.1
	 * @access public
	 * @var object $metaboxes The Metaboxes object
	 */
	public $metaboxes;



	/**
	 * Shortcodes object
	 *
	 * @since 0.1
	 * @access public
	 * @var object $shortcodes The Shortcodes object
	 */
	public $shortcodes;



	/**
	 * Constructor
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// include files
		$this->include_files();

		// setup globals
		$this->setup_globals();

		// register hooks
		$this->register_hooks();

	}



	/**
	 * Include files
	 *
	 * @since 0.1
	 */
	public function include_files() {

		// include CPT class
		include_once SOF_QUOTES_PATH . 'includes/sof-quotes-cpt.php';

		// include Metaboxes class
		include_once SOF_QUOTES_PATH . 'includes/sof-quotes-metaboxes.php';

		// include Shortcodes class
		include_once SOF_QUOTES_PATH . 'includes/sof-quotes-shortcodes.php';

	}



	/**
	 * Set up objects
	 *
	 * @since 0.1
	 */
	public function setup_globals() {

		// init CPT object
		$this->cpt = new Spirit_Of_Football_Quotes_CPT;

		// init Metaboxes object
		$this->metaboxes = new Spirit_Of_Football_Quotes_Metaboxes;

		// init Shortcodes object
		$this->shortcodes = new Spirit_Of_Football_Quotes_Shortcodes;

	}



	/**
	 * Register WordPress hooks
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// use translation
		add_action( 'plugins_loaded', array( $this, 'translation' ) );

		// hooks that always need to be present
		$this->cpt->register_hooks();
		$this->metaboxes->register_hooks();
		$this->shortcodes->register_hooks();

		// add widgets
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

	}



	/**
	 * Actions to perform on plugin activation
	 *
	 * @since 0.1
	 */
	public function activate() {

		// pass through
		$this->cpt->activate();

	}



	/**
	 * Actions to perform on plugin deactivation (NOT deletion)
	 *
	 * @since 0.1
	 */
	public function deactivate() {

		// pass through
		$this->cpt->deactivate();

	}



	/**
	 * Loads translation, if present
	 *
	 * @since 0.1
	 */
	function translation() {

		// only use, if we have it...
		if ( function_exists( 'load_plugin_textdomain' ) ) {

			// not used, as there are no translations as yet
			load_plugin_textdomain(

				// unique name
				'sof-quotes',

				// deprecated argument
				false,

				// relative path to directory containing translation files
				dirname( plugin_basename( SOF_QUOTES_FILE ) ) . '/languages/'

			);

		}

	}



	/**
	 * Register widgets for this plugin
	 *
	 * @since 0.1
	 */
	public function register_widgets() {

		// include widgets
		require_once( SOF_QUOTES_PATH . 'widgets/sof-quotes-widget.php' );

	}



} // class Spirit_Of_Football_Quotes ends



// Instantiate the class
global $sof_quotes_plugin;
$sof_quotes_plugin = new Spirit_Of_Football_Quotes();

// activation
register_activation_hook( __FILE__, array( $sof_quotes_plugin, 'activate' ) );

// deactivation
register_deactivation_hook( __FILE__, array( $sof_quotes_plugin, 'deactivate' ) );



