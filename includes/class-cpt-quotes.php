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
	 * Plugin object.
	 *
	 * @since 0.1
	 * @access public
	 * @var Spirit_Of_Football_Quotes
	 */
	public $plugin;

	/**
	 * Custom Post Type name.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $post_type_name = 'quote';

	/**
	 * Custom Post Type REST base.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $post_type_rest_base = 'quotes';

	/**
	 * Primary Taxonomy name.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $taxonomy_name = 'quote-type';

	/**
	 * Primary Taxonomy REST base.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $taxonomy_rest_base = 'quote-type';

	/**
	 * Statement Term slug.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $term_statement = 'statement';

	/**
	 * Pledge Term slug.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $term_pledge = 'pledge';

	/**
	 * Free Taxonomy name.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $taxonomy_free_name = 'quote-tag';

	/**
	 * Free Taxonomy REST base.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $taxonomy_free_rest_base = 'quote-tags';

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
		do_action( 'sof_quotes/cpt/loaded' );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Always create post type.
		add_action( 'init', [ $this, 'post_type_create' ] );

		// Make sure our feedback is appropriate.
		add_filter( 'post_updated_messages', [ $this, 'post_type_messages' ] );

		// Create primary taxonomy.
		add_action( 'init', [ $this, 'taxonomy_primary_create' ] );
		add_filter( 'wp_terms_checklist_args', [ $this, 'taxonomy_primary_metabox_fix' ], 10, 2 );
		add_action( 'restrict_manage_posts', [ $this, 'taxonomy_primary_post_type_filter' ] );

		// Create free taxonomy.
		add_action( 'init', [ $this, 'taxonomy_free_create' ] );

	}

	/**
	 * Actions to perform on plugin activation.
	 *
	 * @since 0.1
	 */
	public function activate() {

		// Pass through.
		$this->post_type_create();

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

	// -----------------------------------------------------------------------------------

	/**
	 * Create our Custom Post Type.
	 *
	 * @since 0.1
	 */
	public function post_type_create() {

		// Only call this once.
		static $registered;
		if ( $registered ) {
			return;
		}

		$labels = [
			'name'                     => __( 'Quotes', 'sof-quotes' ),
			'singular_name'            => __( 'Quote', 'sof-quotes' ),
			'add_new'                  => _x( 'Add New', 'quote', 'sof-quotes' ),
			'add_new_item'             => __( 'Add New Quote', 'sof-quotes' ),
			'edit_item'                => __( 'Edit Quote', 'sof-quotes' ),
			'new_item'                 => __( 'New Quote', 'sof-quotes' ),
			'all_items'                => __( 'All Quotes', 'sof-quotes' ),
			'view_item'                => __( 'View Quote', 'sof-quotes' ),
			'item_published'           => __( 'Quote published.', 'sof-quotes' ),
			'item_published_privately' => __( 'Quote published privately.', 'sof-quotes' ),
			'item_reverted_to_draft'   => __( 'Quote reverted to draft.', 'sof-quotes' ),
			'item_scheduled'           => __( 'Quote scheduled.', 'sof-quotes' ),
			'item_updated'             => __( 'Quote updated.', 'sof-quotes' ),
			'search_items'             => __( 'Search Quotes', 'sof-quotes' ),
			'not_found'                => __( 'No matching Quote found', 'sof-quotes' ),
			'not_found_in_trash'       => __( 'No Quotes found in Trash', 'sof-quotes' ),
			'parent_item_colon'        => '',
			'menu_name'                => __( 'Quotes', 'sof-quotes' ),
		];

		// Build args.
		$args = [

			'labels'              => $labels,

			// Defaults.
			'description'         => __( 'A quote post type', 'sof-quotes' ),
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'hierarchical'        => false,
			'has_archive'         => false,
			'menu_icon'           => 'dashicons-format-quote',
			'menu_position'       => 50,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,

			// Rewrite.
			'rewrite'             => [
				'slug'       => 'quotes',
				'with_front' => false,
			],

			// Supports.
			'supports'            => [
				'title',
				'editor',
				'thumbnail',
			],

			// REST setup.
			'show_in_rest'        => true,
			'rest_base'           => $this->post_type_rest_base,

		];

		// Set up the Custom Post Type called "Quote".
		register_post_type( $this->post_type_name, $args );

		// Flag.
		$registered = true;

	}

	/**
	 * Overrides messages for a Custom Post Type.
	 *
	 * @param array $messages The existing messages.
	 * @return array $messages The modified messages.
	 */
	public function post_type_messages( $messages ) {

		// Access relevant globals.
		global $post, $post_ID;

		// Define custom messages for our Custom Post Type.
		$messages[ $this->post_type_name ] = [

			// Unused - messages start at index 1.
			0  => '',

			// Item updated.
			1  => sprintf(
				/* translators: %s: Post permalink URL. */
				__( 'Quote updated. <a href="%s">View quote</a>', 'sof-quotes' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Custom fields.
			2  => __( 'Custom field updated.', 'sof-quotes' ),
			3  => __( 'Custom field deleted.', 'sof-quotes' ),
			4  => __( 'Quote updated.', 'sof-quotes' ),

			// Item restored to a revision.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			5  => isset( $_GET['revision'] ) ?

				// Revision text.
				sprintf(
					/* translators: %s: Title of the revision. */
					__( 'Quote restored to revision from %s', 'sof-quotes' ),
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					wp_post_revision_title( (int) $_GET['revision'], false )
				) :

				// No revision.
				false,

			// Item published.
			6  => sprintf(
				/* translators: %s: Post permalink URL. */
				__( 'Quote published. <a href="%s">View quote</a>', 'sof-quotes' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Item saved.
			7  => __( 'Quote saved.', 'sof-quotes' ),

			// Item submitted.
			8  => sprintf(
				/* translators: %s: Post preview URL. */
				__( 'Quote submitted. <a target="_blank" href="%s">Preview quote</a>', 'sof-quotes' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// Item scheduled.
			9  => sprintf(
				/* translators: 1: Publish box date format, see http://php.net/date, 2: Post date, 3: Post permalink. */
				__( 'Quote scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview quote</a>', 'sof-quotes' ),
				/* translators: Publish box date format, see http://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'sof-quotes' ), strtotime( $post->post_date ) ),
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

	// -----------------------------------------------------------------------------------

	/**
	 * Creates our Custom Taxonomy.
	 *
	 * @since 0.1
	 */
	public function taxonomy_primary_create() {

		// Only register once.
		static $registered;
		if ( $registered ) {
			return;
		}

		// Arguments.
		$args = [

			// Same as "category".
			'hierarchical'      => true,

			// Labels.
			'labels'            => [
				'name'              => _x( 'Quote Types', 'taxonomy general name', 'sof-quotes' ),
				'singular_name'     => _x( 'Quote Type', 'taxonomy singular name', 'sof-quotes' ),
				'menu_name'         => __( 'Quote Types', 'sof-quotes' ),
				'search_items'      => __( 'Search Quote Types', 'sof-quotes' ),
				'all_items'         => __( 'All Quote Types', 'sof-quotes' ),
				'edit_item'         => __( 'Edit Quote Type', 'sof-quotes' ),
				'update_item'       => __( 'Update Quote Type', 'sof-quotes' ),
				'add_new_item'      => __( 'Add New Quote Type', 'sof-quotes' ),
				'new_item_name'     => __( 'New Quote Type Name', 'sof-quotes' ),
				'not_found'         => __( 'No Quote Types found', 'sof-quotes' ),
				'parent_item'       => __( 'Parent Quote Type', 'sof-quotes' ),
				'parent_item_colon' => __( 'Parent Quote Type:', 'sof-quotes' ),
			],

			// Rewrite rules.
			'rewrite'           => [
				'slug'       => 'quotes/types',
				'with_front' => true,
			],

			// Show column in wp-admin.
			'show_admin_column' => true,
			'show_ui'           => true,

			// REST setup.
			'show_in_rest'      => true,
			'rest_base'         => $this->taxonomy_rest_base,

		];

		// Register a taxonomy for this CPT.
		register_taxonomy( $this->taxonomy_name, $this->post_type_name, $args );

		// Flag done.
		$registered = true;

	}

	/**
	 * Fixes the Custom Taxonomy metabox.
	 *
	 * @see https://core.trac.wordpress.org/ticket/10982
	 *
	 * @since 0.1
	 *
	 * @param array $args The existing arguments.
	 * @param int   $post_id The WordPress post ID.
	 */
	public function taxonomy_primary_metabox_fix( $args, $post_id ) {

		// If rendering metabox for our taxonomy.
		if ( isset( $args['taxonomy'] ) && $args['taxonomy'] === $this->taxonomy_name ) {

			// Setting 'checked_ontop' to false seems to fix this.
			$args['checked_ontop'] = false;

		}

		// --<
		return $args;

	}

	/**
	 * Adds a filter for this Custom Taxonomy to the Custom Post Type listing.
	 *
	 * @since 0.1
	 */
	public function taxonomy_primary_post_type_filter() {

		// Access current post type.
		global $typenow;

		// Bail if not our post type.
		if ( $typenow !== $this->post_type_name ) {
			return;
		}

		// Get tax object.
		$taxonomy = get_taxonomy( $this->taxonomy_name );

		// Build args.
		$args = [
			/* translators: %s: The plural name of the taxonomy terms. */
			'show_option_all' => sprintf( __( 'Show All %s', 'sof-quotes' ), $taxonomy->label ),
			'taxonomy'        => $this->taxonomy_name,
			'name'            => $this->taxonomy_name,
			'orderby'         => 'name',
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
			'selected'        => isset( $_GET[ $this->taxonomy_name ] ) ? wp_unslash( $_GET[ $this->taxonomy_name ] ) : '',
			'show_count'      => true,
			'hide_empty'      => true,
			'value_field'     => 'slug',
			'hierarchical'    => 1,
		];

		// Show a dropdown.
		wp_dropdown_categories( $args );

	}

	// -----------------------------------------------------------------------------------

	/**
	 * Creates a free-tagging Taxonomy for Quotes.
	 *
	 * @since 4.0
	 */
	public function taxonomy_free_create() {

		// Only register once.
		static $registered;
		if ( $registered ) {
			return;
		}

		// Define Taxonomy arguments.
		$args = [

			// General.
			'public'            => true,
			'hierarchical'      => false,

			// Labels.
			'labels'            => [
				'name'                       => _x( 'Quote Tags', 'taxonomy general name', 'sof-quotes' ),
				'singular_name'              => _x( 'Quote Tag', 'taxonomy singular name', 'sof-quotes' ),
				'menu_name'                  => __( 'Quote Tags', 'sof-quotes' ),
				'search_items'               => __( 'Search Quote Tags', 'sof-quotes' ),
				'all_items'                  => __( 'All Quote Tags', 'sof-quotes' ),
				'edit_item'                  => __( 'Edit Quote Tag', 'sof-quotes' ),
				'update_item'                => __( 'Update Quote Tag', 'sof-quotes' ),
				'add_new_item'               => __( 'Add New Quote Tag', 'sof-quotes' ),
				'new_item_name'              => __( 'New Quote Tag Name', 'sof-quotes' ),
				'not_found'                  => __( 'No Quote Tags found', 'sof-quotes' ),
				'popular_items'              => __( 'Popular Quote Tags', 'sof-quotes' ),
				'separate_items_with_commas' => __( 'Separate Quote Tags with commas', 'sof-quotes' ),
				'add_or_remove_items'        => __( 'Add or remove Quote Tag', 'sof-quotes' ),
				'choose_from_most_used'      => __( 'Choose from the most popular Quote Tags', 'sof-quotes' ),
			],

			// Permalinks.
			'rewrite'           => [
				'slug'       => 'quotes/tags',
				'with_front' => true,
			],

			// Show column in wp-admin.
			'show_admin_column' => true,
			'show_ui'           => true,

			// REST setup.
			'show_in_rest'      => true,
			'rest_base'         => $this->taxonomy_free_rest_base,

		];

		// Go ahead and register the Taxonomy now.
		register_taxonomy( $this->taxonomy_free_name, $this->post_type_name, $args );

	}

}
