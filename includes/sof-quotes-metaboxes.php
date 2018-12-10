<?php

/**
 * SOF Quotes Metaboxes Class
 *
 * A class that encapsulates all Metaboxes for Quotes
 *
 * @package WordPress
 * @subpackage SOF
 */
class Spirit_Of_Football_Quotes_Metaboxes {



	/**
	 * Featured Quote meta key
	 *
	 * @since 0.1
	 * @access public
	 * @var str $meta_key The meta key for featured quotes
	 */
	public $featured_meta_key = 'featured_quote';



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

		// exclude from SOF eV for now...
		//if ( 'sofev' == sof_get_site() ) return;

		// add meta boxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		// intercept save
		add_action( 'save_post', array( $this, 'save_post' ), 1, 2 );

	}




	// #########################################################################



	/**
	 * Adds meta boxes to admin screens
	 *
	 * @since 0.1
	 */
	public function add_meta_boxes() {

		// add our meta box
		add_meta_box(
			'sof_quote_options',
			__( 'Featured', 'sof-quotes' ),
			array( $this, 'quote_box' ),
			'quote',
			'side'
		);

	}



	/**
	 * Adds meta box to page edit screens
	 *
	 * @since 0.1
	 * @param WP_Post $post The object for the current post/page
	 */
	public function quote_box( $post ) {

		// use nonce for verification
		wp_nonce_field( 'sof_quote_settings', 'sof_quote_nonce' );

		// ---------------------------------------------------------------------
		// Set "Featured" Status
		// ---------------------------------------------------------------------

		$db_key = '_' . $this->featured_meta_key;

		// default to empty
		$val = '';

		// get value if if the custom field already has one
		$existing = get_post_meta( $post->ID, $db_key, true );
		if ( ! empty( $existing ) ) {
			$val = get_post_meta( $post->ID, $db_key, true );
		}

		// open
		echo '<p>';

		// checkbox
		echo '<input id="' . $this->featured_meta_key . '" name="' . $this->featured_meta_key . '" value="1" type="checkbox" ' . (($val == '1') ? ' checked="checked"' : '') . '/>';

		// construct label
		echo '<strong><label for="' . $this->featured_meta_key . '">' . __( 'Make quote featured', 'sof-quotes' ) . '</label></strong>';

		// close
		echo '</p>';

		echo '<hr>';

		// ---------------------------------------------------------------------
		// Show Shortcode
		// ---------------------------------------------------------------------

		// open
		echo '<p>';

		// construct label
		echo '<strong><label for="' . $this->featured_meta_key . '">' . __( 'Shortcode', 'sof-quotes' ) . '</label></strong><br />';

		// construct shortcode
		$shortcode = esc_attr( '[quote id="' . $post->ID . '"]' );
		echo '<input type="text" value="' . $shortcode . '" />';

		// close
		echo '</p>';

	}



	/**
	 * Stores our additional params
	 *
	 * @since 0.1
	 * @param integer $post_id the ID of the post (or revision)
	 * @param integer $post the post object
	 */
	public function save_post( $post_id, $post ) {

		// we don't use post_id because we're not interested in revisions

		// store our page meta data
		$result = $this->_save_page_meta( $post );

	}



	// #########################################################################



	/**
	 * When a page is saved, this also saves the options
	 *
	 * @since 0.1
	 * @param WP_Post $post_obj The object for the post (or revision)
	 */
	private function _save_page_meta( $post_obj ) {

		// if no post, kick out
		if ( ! $post_obj ) return;

		// authenticate
		$nonce = isset( $_POST['sof_quote_nonce'] ) ? $_POST['sof_quote_nonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'sof_quote_settings' ) ) return;

		// is this an auto save routine?
		if ( defined('DOING_AUTOSAVE') AND DOING_AUTOSAVE ) return;

		// Check permissions
		if ( ! current_user_can( 'edit_page', $post_obj->ID ) ) return;

		// check for revision
		if ( $post_obj->post_type == 'revision' ) {

			// get parent
			if ( $post_obj->post_parent != 0 ) {
				$post = get_post( $post_obj->post_parent );
			} else {
				$post = $post_obj;
			}

		} else {
			$post = $post_obj;
		}

		// bail if not quote post type
		if ( $post->post_type == 'quote' ) return;

		// ---------------------------------------------------------------------
		// okay, we're through...
		// ---------------------------------------------------------------------

		// define key
		$db_key = '_' . $this->featured_meta_key;

		// if checkbox checked
		$data = ( isset( $_POST[$this->featured_meta_key] ) ) ? '1' : '0';

		// save metadata
		$this->_save_meta( $post, $db_key, $data );

	}



	/**
	 * Utility to automate meta data saving
	 *
	 * @since 0.1
	 * @param WP_Post $post_obj The WordPress post object
	 * @param string $key The meta key
	 * @param mixed $data The data to be saved
	 * @return mixed $data The data that was saved
	 */
	private function _save_meta( $post, $key, $data = '' ) {

		// if the custom field already has a value
		$existing = get_post_meta( $post->ID, $key, true );
		if ( ! empty( $existing ) ) {

			// update the data
			update_post_meta( $post->ID, $key, $data );

		} else {

			// add the data
			add_post_meta( $post->ID, $key, $data );

		}

		// --<
		return $data;

	}




} // class Spirit_Of_Football_Quotes_Metaboxes ends



