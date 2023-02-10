<?php
/**
 * Metabox Class.
 *
 * Handles Metabox for Quotes.
 *
 * @since 0.1
 *
 * @package Spirit_Of_Football_Quotes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * SOF Quotes Metabox Class.
 *
 * A class that encapsulates all Metabox for Quotes.
 *
 * @since 0.1
 */
class Spirit_Of_Football_Quotes_Metabox {

	/**
	 * Plugin object.
	 *
	 * @since 0.1.1
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;

	/**
	 * Featured Quote meta key.
	 *
	 * @since 0.1
	 * @access public
	 * @var str $meta_key The meta key for featured quotes.
	 */
	public $featured_meta_key = 'featured_quote';

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param object $parent The parent object.
	 */
	public function __construct( $parent ) {

		// Store reference to plugin.
		$this->plugin = $parent;

		// Init when this plugin is loaded.
		add_action( 'sof_quotes/loaded', [ $this, 'initialise' ] );

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
		do_action( 'sof_orgs/metabox/loaded' );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Add meta boxes.
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );

		// Intercept save.
		add_action( 'save_post', [ $this, 'save_post' ], 1, 2 );

	}


	// -------------------------------------------------------------------------

	/**
	 * Adds meta boxes to admin screens.
	 *
	 * @since 0.1
	 */
	public function add_meta_boxes() {

		// Add our meta box.
		add_meta_box(
			'sof_quote_options',
			__( 'Featured', 'sof-quotes' ),
			[ $this, 'metabox_render' ],
			'quote',
			'side'
		);

	}

	/**
	 * Adds meta box to page edit screens.
	 *
	 * @since 0.1
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function metabox_render( $post ) {

		// Use nonce for verification.
		wp_nonce_field( 'sof_quote_settings', 'sof_quote_nonce' );

		// ---------------------------------------------------------------------
		// Set "Featured" Status.
		// ---------------------------------------------------------------------

		$db_key = '_' . $this->featured_meta_key;

		// Default to empty.
		$val = '';

		// Get value if if the custom field already has one.
		$existing = get_post_meta( $post->ID, $db_key, true );
		if ( ! empty( $existing ) ) {
			$val = get_post_meta( $post->ID, $db_key, true );
		}

		// Construct shortcode.
		$shortcode = esc_attr( '[quote id="' . $post->ID . '"]' );

		// Include template file.
		include SOF_QUOTES_PATH . 'assets/templates/metabox-quote.php';

	}

	/**
	 * Stores our additional params.
	 *
	 * @since 0.1
	 *
	 * @param integer $post_id The ID of the post or revision.
	 * @param integer $post The post object.
	 */
	public function save_post( $post_id, $post ) {

		// We don't use post_id because we're not interested in revisions.

		// Store our page meta data.
		$result = $this->save_page_meta( $post );

	}

	// -------------------------------------------------------------------------

	/**
	 * When a page is saved, this also saves the options.
	 *
	 * @since 0.1
	 *
	 * @param WP_Post $post_obj The object for the post or revision.
	 */
	private function save_page_meta( $post_obj ) {

		// If no post, kick out.
		if ( ! $post_obj ) {
			return;
		}

		// Authenticate.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$nonce = isset( $_POST['sof_quote_nonce'] ) ? wp_unslash( $_POST['sof_quote_nonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'sof_quote_settings' ) ) {
			return;
		}

		// Is this an auto save routine?
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_page', $post_obj->ID ) ) {
			return;
		}

		// Check for revision.
		if ( $post_obj->post_type == 'revision' ) {

			// Get parent.
			if ( $post_obj->post_parent != 0 ) {
				$post = get_post( $post_obj->post_parent );
			} else {
				$post = $post_obj;
			}

		} else {
			$post = $post_obj;
		}

		// Bail if not quote post type.
		if ( $post->post_type == 'quote' ) {
			return;
		}

		// ---------------------------------------------------------------------
		// Okay, we're through.
		// ---------------------------------------------------------------------

		// Define key.
		$db_key = '_' . $this->featured_meta_key;

		// If checkbox checked.
		$data = ( isset( $_POST[ $this->featured_meta_key ] ) ) ? '1' : '0';

		// Save metadata.
		$this->save_meta( $post, $db_key, $data );

	}

	/**
	 * Utility to automate meta data saving.
	 *
	 * @since 0.1
	 *
	 * @param WP_Post $post The WordPress post object.
	 * @param string $key The meta key.
	 * @param mixed $data The data to be saved.
	 * @return mixed $data The data that was saved.
	 */
	private function save_meta( $post, $key, $data = '' ) {

		// If the custom field already has a value.
		$existing = get_post_meta( $post->ID, $key, true );
		if ( ! empty( $existing ) ) {

			// Update the data.
			update_post_meta( $post->ID, $key, $data );

		} else {

			// Add the data.
			add_post_meta( $post->ID, $key, $data );

		}

		// --<
		return $data;

	}

}
