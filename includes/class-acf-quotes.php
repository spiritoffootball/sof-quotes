<?php
/**
 * Quotes ACF Class.
 *
 * Handles ACF functionality for Quotes.
 *
 * @package SOF_Quotes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Quotes ACF Class.
 *
 * A class that encapsulates ACF functionality for Quotes.
 *
 * @package SOF_Quotes
 */
class Spirit_Of_Football_Quotes_ACF {

	/**
	 * Plugin object.
	 *
	 * @since 0.1.1
	 * @access public
	 * @var object
	 */
	public $plugin;

	/**
	 * ACF Field Group prefix.
	 *
	 * @since 0.1.1
	 * @access public
	 * @var string
	 */
	public $group_prefix = 'group_sof_quote_';

	/**
	 * Statement ACF Field prefix.
	 *
	 * @since 0.1.1
	 * @access public
	 * @var string
	 */
	public $field_statement_prefix = 'field_sof_quote_statement_';

	/**
	 * Pledge ACF Field prefix.
	 *
	 * @since 0.1.1
	 * @access public
	 * @var string
	 */
	public $field_pledge_prefix = 'field_sof_quote_pledge_';

	/**
	 * Constructor.
	 *
	 * @since 0.1.1
	 *
	 * @param object $parent The parent object.
	 */
	public function __construct( $parent ) {

		// Store references.
		$this->plugin = $parent;

		// Init when this plugin is loaded.
		add_action( 'sof_quotes/loaded', [ $this, 'register_hooks' ] );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1.1
	 */
	public function register_hooks() {

		// Add Field Group and Fields.
		add_action( 'acf/init', [ $this, 'field_groups_add' ] );
		add_action( 'acf/init', [ $this, 'fields_add' ] );

	}

	// -------------------------------------------------------------------------

	/**
	 * Add ACF Field Groups.
	 *
	 * @since 0.1.1
	 */
	public function field_groups_add() {

		// Add our ACF Fields.
		$this->field_group_statement_add();
		$this->field_group_pledge_add();

	}

	/**
	 * Add Statements Field Group.
	 *
	 * @since 0.1.1
	 */
	public function field_group_statement_add() {

		// Attach the Field Group to our CPT.
		$field_group_location = [
			[
				// Statement Quote.
				[
					'param' => 'post_type',
					'operator' => '==',
					'value' => $this->plugin->cpt->post_type_name,
				],
				[
					'param' => 'post_taxonomy',
					'operator' => '==',
					'value' => $this->plugin->cpt->taxonomy_name . ':' . $this->plugin->cpt->term_statement,
				],
			],
		];

		// Hide UI elements on our CPT edit page.
		$field_group_hide_elements = [
			'the_content',
			//'excerpt',
			'discussion',
			'comments',
			//'revisions',
			'author',
			'format',
			'page_attributes',
			//'featured_image',
			'tags',
			'send-trackbacks',
		];

		// Define Field Group.
		$field_group = [
			'key' => $this->group_prefix . 'statement',
			'title' => __( 'Statement Details', 'sof-quotes' ),
			'fields' => [],
			'location' => $field_group_location,
			'hide_on_screen' => $field_group_hide_elements,
		];

		// Now add the Field Group.
		acf_add_local_field_group( $field_group );

		/*
		$e = new \Exception();
		$trace = $e->getTraceAsString();
		error_log( print_r( [
			'method' => __METHOD__,
			'field_group' => $field_group,
			//'backtrace' => $trace,
		], true ) );
		*/

	}

	/**
	 * Add Pledges Field Group.
	 *
	 * @since 0.1.1
	 */
	public function field_group_pledge_add() {

		// Attach the Field Group to our CPT.
		$field_group_location = [
			[
				[
					'param' => 'post_type',
					'operator' => '==',
					'value' => $this->plugin->cpt->post_type_name,
				],
				[
					'param' => 'post_taxonomy',
					'operator' => '==',
					'value' => $this->plugin->cpt->taxonomy_name . ':' . $this->plugin->cpt->term_pledge,
				],
			],
		];

		// Hide UI elements on our CPT edit page.
		$field_group_hide_elements = [
			'the_content',
			//'excerpt',
			'discussion',
			'comments',
			//'revisions',
			'author',
			'format',
			'page_attributes',
			//'featured_image',
			'tags',
			'send-trackbacks',
		];

		// Define Field Group.
		$field_group = [
			'key' => $this->group_prefix . 'pledge',
			'title' => __( 'Pledge Details', 'sof-quotes' ),
			'fields' => [],
			'location' => $field_group_location,
			'hide_on_screen' => $field_group_hide_elements,
		];

		// Now add the Field Group.
		acf_add_local_field_group( $field_group );

		/*
		$e = new \Exception();
		$trace = $e->getTraceAsString();
		error_log( print_r( [
			'method' => __METHOD__,
			'field_group' => $field_group,
			//'backtrace' => $trace,
		], true ) );
		*/

	}

	/**
	 * Add ACF Fields.
	 *
	 * @since 0.1.1
	 */
	public function fields_add() {

		// Add our ACF Fields.
		$this->fields_statement_add();
		$this->fields_pledge_add();

	}

	/**
	 * Add "Statement" Fields.
	 *
	 * @since 0.1.1
	 */
	public function fields_statement_add() {

		// Define Field.
		$field = [
			'type' => 'text',
			'name' => 'source',
			'parent' => $this->group_prefix . 'statement',
			'key' => $this->field_statement_prefix . 'source',
			'label' => __( 'Source of Statement', 'sof-quotes' ),
			'instructions' => __( 'Who gave or wrote this Statement?', 'sof-quotes' ),
			'default_value' => '',
			'placeholder' => '',
		];

		/*
		$e = new \Exception();
		$trace = $e->getTraceAsString();
		error_log( print_r( [
			'method' => __METHOD__,
			'field' => $field,
			//'backtrace' => $trace,
		], true ) );
		*/

		// Now add Field.
		acf_add_local_field( $field );

		// Define Field.
		$field = [
			'type' => 'text',
			'name' => 'about',
			'parent' => $this->group_prefix . 'statement',
			'key' => $this->field_statement_prefix . 'about',
			'label' => __( 'About the Source', 'sof-quotes' ),
			'instructions' => __( 'For example: Job Title and Employer.', 'sof-quotes' ),
			'default_value' => '',
			'placeholder' => '',
		];

		// Now add Field.
		acf_add_local_field( $field );

		// Define Field.
		$field = [
			'type' => 'date_picker',
			'name' => 'date',
			'parent' => $this->group_prefix . 'statement',
			'key' => $this->field_statement_prefix . 'date',
			'label' => __( 'Statement Date', 'sof-quotes' ),
			'instructions' => __( 'Date of the Statement.', 'sof-quotes' ),
			'display_format' => 'd/m/Y',
			'return_format' => 'd/m/Y',
			'first_day' => 1,
		];

		/*
		$e = new \Exception();
		$trace = $e->getTraceAsString();
		error_log( print_r( [
			'method' => __METHOD__,
			'field' => $field,
			//'backtrace' => $trace,
		], true ) );
		*/

		// Now add Field.
		acf_add_local_field( $field );

		// Define Field.
		$field = [
			'type' => 'wysiwyg',
			'name' => 'content',
			'parent' => $this->group_prefix . 'statement',
			'key' => $this->field_statement_prefix . 'content',
			'label' => __( 'Statement Content', 'sof-quotes' ),
			'instructions' => __( 'The main text of the Statement.', 'sof-quotes' ),
			'default_value' => '',
			'placeholder' => '',
		];

		// Now add Field.
		acf_add_local_field( $field );

		// Define Field.
		$field = [
			'type' => 'image',
			'name' => 'image',
			'parent' => $this->group_prefix . 'statement',
			'key' => $this->field_statement_prefix . 'image',
			'label' => __( 'Statement Image', 'sof-quotes' ),
			'instructions' => __( 'Feature Image of the Statement.', 'sof-quotes' ),
			'required' => 0,
			'conditional_logic' => 0,
			'preview_size' => 'medium',
			'acfe_thumbnail' => 0,
			//'uploader' => 'basic',
			//'min_size' => 0,
			//'max_size' => $this->civicrm->attachment->field_max_size_get(),
			//'mime_types' => $field['mime_types'],
			'library' => 'all',
			'return_format' => 'array',
		];

		// Now add Field.
		acf_add_local_field( $field );

	}

	/**
	 * Add "Pledge" Fields.
	 *
	 * @since 0.1.1
	 */
	public function fields_pledge_add() {

		// Define Field.
		$field = [
			'type' => 'text',
			'name' => 'source',
			'parent' => $this->group_prefix . 'pledge',
			'key' => $this->field_pledge_prefix . 'source',
			'label' => __( 'Source of Pledge', 'sof-quotes' ),
			'instructions' => __( 'Who said, wrote or made this Pledge? This field is displayed as the citation.', 'sof-quotes' ),
			'default_value' => '',
			'placeholder' => '',
		];

		/*
		$e = new \Exception();
		$trace = $e->getTraceAsString();
		error_log( print_r( [
			'method' => __METHOD__,
			'field' => $field,
			//'backtrace' => $trace,
		], true ) );
		*/

		// Now add Field.
		acf_add_local_field( $field );

		// Define Field.
		$field = [
			'type' => 'text',
			'name' => 'about',
			'parent' => $this->group_prefix . 'pledge',
			'key' => $this->field_pledge_prefix . 'about',
			'label' => __( 'About the Source', 'sof-quotes' ),
			'instructions' => __( 'About Source, e.g. Job Title and Employer. This field is displayed below the citation.', 'sof-quotes' ),
			'default_value' => '',
			'placeholder' => '',
		];

		// Now add Field.
		acf_add_local_field( $field );

		// Define Field.
		$field = [
			'type' => 'date_picker',
			'name' => 'date',
			'parent' => $this->group_prefix . 'pledge',
			'key' => $this->field_pledge_prefix . 'date',
			'label' => __( 'Pledge Date', 'sof-quotes' ),
			'instructions' => __( 'Date of the Pledge. This field is displayed below the citation.', 'sof-quotes' ),
			'display_format' => 'd/m/Y',
			'return_format' => 'd/m/Y',
			'first_day' => 1,
		];

		// Now add Field.
		acf_add_local_field( $field );

		// Define Field.
		$field = [
			'type' => 'wysiwyg',
			'name' => 'content',
			'parent' => $this->group_prefix . 'pledge',
			'key' => $this->field_pledge_prefix . 'content',
			'label' => __( 'Pledge Content', 'sof-quotes' ),
			'instructions' => __( 'The main text of the Pledge.', 'sof-quotes' ),
			'default_value' => '',
			'placeholder' => '',
		];

		// Now add Field.
		acf_add_local_field( $field );

		// Define Field.
		$field = [
			'type' => 'image',
			'name' => 'image',
			'parent' => $this->group_prefix . 'pledge',
			'key' => $this->field_pledge_prefix . 'image',
			'label' => __( 'Pledge Image', 'sof-quotes' ),
			'instructions' => __( 'Feature Image of the Pledge.', 'sof-quotes' ),
			'required' => 0,
			'conditional_logic' => 0,
			//'uploader' => 'basic',
			//'min_size' => 0,
			//'max_size' => $this->civicrm->attachment->field_max_size_get(),
			//'mime_types' => $field['mime_types'],
			'library' => 'all',
			'return_format' => 'array',
		];

		// Now add Field.
		acf_add_local_field( $field );

		// Define Field.
		$field = [
			'type' => 'image',
			'name' => 'card',
			'parent' => $this->group_prefix . 'pledge',
			'key' => $this->field_pledge_prefix . 'card',
			'label' => __( 'Card Image', 'sof-quotes' ),
			'instructions' => __( 'Pre-designed Pledge Card Image.', 'sof-quotes' ),
			'required' => 0,
			'conditional_logic' => 0,
			//'uploader' => 'basic',
			//'min_size' => 0,
			//'max_size' => $this->civicrm->attachment->field_max_size_get(),
			//'mime_types' => $field['mime_types'],
			'library' => 'all',
			'return_format' => 'array',
		];

		// Now add Field.
		acf_add_local_field( $field );

	}

}
