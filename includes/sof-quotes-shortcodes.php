<?php

/**
 * SOF Quotes Custom Shortcodes Class.
 *
 * A class that encapsulates all Shortcodes for Quotes.
 *
 * @package WordPress
 * @subpackage SOF
 */
class Spirit_Of_Football_Quotes_Shortcodes {



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

		// Register shortcodes.
		add_shortcode( 'quote', array( $this, 'quote_shortcode' ) );

	}




	// #########################################################################




	/**
	 * Add a quote to a page/post via a shortcode.
	 *
	 * @since 0.1
	 *
	 * @param array $attr The saved shortcode attributes.
	 * @param str $content The enclosed content of the shortcode.
	 */
	public function quote_shortcode( $attr, $content = null ) {

		// Get params.
		extract( shortcode_atts( array(
			'id'	=> '',
			'align'	=> 'none'
		), $attr ) );

		// Kick out if there's anything amiss.
		if ( $id == '' OR is_feed() ) return;

		// Get the quote.
		query_posts( 'post_type=quote&p=' . $id );

		// Set it up.
		the_post();

		// Prevent immediate output.
		ob_start();

		// Get the quote.
		get_template_part( 'content', 'quote' );
		$quote = ob_get_contents();

		// Clean up.
		ob_end_clean();
		wp_reset_query();

		// Give class to article.
		switch( $align ) {
			case 'none': $class = 'alignnone'; break;
			case 'right': $class = 'alignright'; break;
			case 'left': $class = 'alignleft'; break;
		}

		// Give it an alignment.
		$quote = str_replace( '<article class="', '<article class="' . $class . ' ', $quote );

		// --<
		return $quote;

	}




} // class Spirit_Of_Football_Quotes_Shortcodes ends



