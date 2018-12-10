<?php

/**
 * SOF Quotes Custom Post Type Class
 *
 * A class that encapsulates a Custom Post Types for Quotes
 *
 * @package WordPress
 * @subpackage SOF
 */
class Spirit_Of_Football_Quotes_CPT {



	/**
	 * Constructor
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// nothing

	}



	/**
	 * Register WordPress hooks
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// always create post types
		add_action( 'init', array( $this, 'create_post_types' ) );

		// make sure our feedback is appropriate
		add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

	}




	/**
	 * Actions to perform on plugin activation
	 *
	 * @since 0.1
	 */
	public function activate() {

		// pass through
		$this->create_post_types();

		// go ahead and flush
		flush_rewrite_rules();

	}



	/**
	 * Actions to perform on plugin deactivation (NOT deletion)
	 *
	 * @since 0.1
	 */
	public function deactivate() {

		// flush rules to reset
		flush_rewrite_rules();

	}



	// #########################################################################



	/**
	 * Create our Custom Post Types
	 *
	 * @since 0.1
	 */
	public function create_post_types() {

		// only call this once
		static $registered;

		// bail if already done
		if ( $registered ) return;

		// set up the post type called "Quote"
		register_post_type( 'quote',

			array(
				'labels' => array(
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
					'not_found' =>  __( 'No matching Quote found', 'sof-quotes' ),
					'not_found_in_trash' => __( 'No Quotes found in Trash', 'sof-quotes' ),
					'parent_item_colon' => '',
					'menu_name' => __( 'Quotes', 'sof-quotes' ),
				),
				'public' => true,
				'publicly_queryable' => true,
				'has_archive' => true,
				'show_ui' => true,
				'rewrite' => array( 'slug' => 'quotes', 'with_front' => false ),
				'query_var' => true,
				'capability_type' => 'post',
				'hierarchical' => true,
				'show_in_nav_menus' => false,
				'menu_position' => 5,
				'exclude_from_search' => false,
				'supports' => array(
					'title',
					'editor'
				),
			)

		);

		//flush_rewrite_rules();

		// flag
		$registered = true;

	}



	/**
	 * Override messages for a custom post type
	 *
	 * @param array $messages The existing messages
	 * @return array $messages The modified messages
	 */
	public function updated_messages( $messages ) {

		// access relevant globals
		global $post, $post_ID;

		// define custom messages for our custom post type
		$messages['quote'] = array(

			// unused - messages start at index 1
			0 => '',

			// item updated
			1 => sprintf(
				__( 'Quote updated. <a href="%s">View quote</a>', 'sof-quotes' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// custom fields
			2 => __( 'Custom field updated.', 'sof-quotes' ),
			3 => __( 'Custom field deleted.', 'sof-quotes' ),
			4 => __( 'Quote updated.', 'sof-quotes' ),

			// item restored to a revision
			5 => isset( $_GET['revision'] ) ?

					// revision text
					sprintf(
						// translators: %s: date and time of the revision
						__( 'Quote restored to revision from %s', 'sof-quotes' ),
						wp_post_revision_title( (int) $_GET['revision'], false )
					) :

					// no revision
					false,

			// item published
			6 => sprintf(
				__( 'Quote published. <a href="%s">View quote</a>', 'sof-quotes' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// item saved
			7 => __( 'Quote saved.', 'sof-quotes' ),

			// item submitted
			8 => sprintf(
				__( 'Quote submitted. <a target="_blank" href="%s">Preview quote</a>', 'sof-quotes' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// item scheduled
			9 => sprintf(
				__( 'Quote scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview quote</a>', 'sof-quotes' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ),
				strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) )
			),

			// draft updated
			10 => sprintf(
				__( 'Quote draft updated. <a target="_blank" href="%s">Preview quote</a>', 'sof-quotes' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			)

		);

		// --<
		return $messages;

	}



} // class Spirit_Of_Football_Quotes_CPT ends



