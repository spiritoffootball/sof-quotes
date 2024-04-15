<?php
/**
 * The default template for displaying a Quote metabox.
 *
 * @since 0.1.1
 *
 * @package Spirit_Of_Football_Quotes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?><!-- assets/templates/metabox-quote.php -->
<p>
	<input id="<?php echo esc_attr( $this->featured_meta_key ); ?>" name="<?php echo esc_attr( $this->featured_meta_key ); ?>" value="1" type="checkbox" <?php checked( $val ); ?>/>
	<strong><label for="<?php echo esc_attr( $this->featured_meta_key ); ?>"><?php esc_html_e( 'Make quote featured', 'sof-quotes' ); ?></label></strong>
</p>

<hr>

<p>
	<strong><label><?php echo esc_html_e( 'Shortcode', 'sof-quotes' ); ?></label></strong><br />
	<input type="text" value="<?php echo esc_attr( $shortcode ); ?>" />
</p>
