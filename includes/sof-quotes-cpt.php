<?php
/**
 * Custom Post Type Class.
 *
 * Handles the Custom Post Type for Quotes.
 *
 * @since 0.1
 *
 * @package Spirit_Of_Football_Quotes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * SOF Quotes Custom Post Type Class.
 *
 * A class that encapsulates a Custom Post Type for Quotes.
 *
 * @since 0.1
 */
class Spirit_Of_Football_Quotes_CPT {

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

		// Always create post types.
		add_action( 'init', [ $this, 'create_post_types' ] );

		// Make sure our feedback is appropriate.
		add_filter( 'post_updated_messages', [ $this, 'updated_messages' ] );

	}

	/**
	 * Actions to perform on plugin activation.
	 *
	 * @since 0.1
	 */
	public function activate() {

		// Pass through.
		$this->create_post_types();

		// Go ahead and flush.
		flush_rewrite_rules();

	}

	/**
	 * Actions to perform on plugin deactivation (NOT deletion)
	 *
	 * @since 0.1
	 */
	public function deactivate() {

		// Flush rules to reset.
		flush_rewrite_rules();

	}

	// -------------------------------------------------------------------------

	/**
	 * Create our Custom Post Type.
	 *
	 * @since 0.1
	 */
	public function create_post_types() {

		// Only call this once.
		static $registered;

		// Bail if already done.
		if ( $registered ) {
			return;
		}

		// Set up the post type called "Quote".
		register_post_type( 'quote',
			[
				'labels' => [
					'name' => __( 'Quotes', 'sof-quotes' ),
					'singular_name' => __( 'Quote', 'sof-quotes' ),
					'add_new' => _x( 'Add New', 'quote', 'sof-quotes' ),
					'add_new_item' => __( 'Add New Quote', 'sof-quotes' ),
					'edit_item' => __( 'Edit Quote', 'sof-quotes' ),
					'new_item' => __( 'New Quote', 'sof-quotes' ),
					'all_items' => __( 'All Quotes', 'sof-quotes' ),
					'view_item' => __( 'View Quote', 'sof-quotes' ),
					'item_published' => __( 'Quote published.', 'sof-quotes' ),
					'item_published_privately' => __( 'Quote published privately.', 'sof-quotes' ),
					'item_reverted_to_draft' => __( 'Quote reverted to draft.', 'sof-quotes' ),
					'item_scheduled' => __( 'Quote scheduled.', 'sof-quotes' ),
					'item_updated' => __( 'Quote updated.', 'sof-quotes' ),
					'search_items' => __( 'Search Quotes', 'sof-quotes' ),
					'not_found' => __( 'No matching Quote found', 'sof-quotes' ),
					'not_found_in_trash' => __( 'No Quotes found in Trash', 'sof-quotes' ),
					'parent_item_colon' => '',
					'menu_name' => __( 'Quotes', 'sof-quotes' ),
				],
				'public' => true,
				'publicly_queryable' => true,
				'has_archive' => true,
				'show_ui' => true,
				'rewrite' => [
					'slug' => 'quotes',
					'with_front' => false,
				],
				'query_var' => true,
				'capability_type' => 'post',
				'hierarchical' => true,
				'show_in_nav_menus' => false,
				'menu_position' => 5,
				'exclude_from_search' => false,
				'supports' => [
					'title',
					'editor',
				],
			]
		);

		// Flag.
		$registered = true;

	}

	/**
	 * Override messages for a custom post type.
	 *
	 * @param array $messages The existing messages.
	 * @return array $messages The modified messages.
	 */
	public function updated_messages( $messages ) {

		// Access relevant globals.
		global $post, $post_ID;

		// Define custom messages for our custom post type.
		$messages['quote'] = [

			// Unused - messages start at index 1.
			0 => '',

			// Item updated.
			1 => sprintf(
				/* translators: %s: Post permalink URL. */
				__( 'Quote updated. <a href="%s">View quote</a>', 'sof-quotes' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Custom fields.
			2 => __( 'Custom field updated.', 'sof-quotes' ),
			3 => __( 'Custom field deleted.', 'sof-quotes' ),
			4 => __( 'Quote updated.', 'sof-quotes' ),

			// Item restored to a revision.
			5 => isset( $_GET['revision'] ) ?

				// Revision text.
				sprintf(
					/* translators: %s: Title of the revision. */
					__( 'Quote restored to revision from %s', 'sof-quotes' ),
					wp_post_revision_title( (int) $_GET['revision'], false )
				) :

				// No revision.
				false,

			// Item published.
			6 => sprintf(
				/* translators: %s: Post permalink URL. */
				__( 'Quote published. <a href="%s">View quote</a>', 'sof-quotes' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Item saved.
			7 => __( 'Quote saved.', 'sof-quotes' ),

			// Item submitted.
			8 => sprintf(
				/* translators: %s: Post preview URL. */
				__( 'Quote submitted. <a target="_blank" href="%s">Preview quote</a>', 'sof-quotes' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// Item scheduled.
			9 => sprintf(
				/* translators: 1: Publish box date format, see http://php.net/date, 2: Post date, 3: Post permalink. */
				__( 'Quote scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview quote</a>', 'sof-quotes' ),
				/* translators: Publish box date format, see http://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'sof-quotes' ),
				strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Draft updated.
			10 => sprintf(
				/* translators: %s: Post preview URL. */
				__( 'Quote draft updated. <a target="_blank" href="%s">Preview quote</a>', 'sof-quotes' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

		];

		// --<
		return $messages;

	}

}
