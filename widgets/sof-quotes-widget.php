<?php /*
================================================================================
Spirit of Football Random Featured Quote Widget
================================================================================
AUTHOR: Christian Wach <needle@haystack.co.uk>
--------------------------------------------------------------------------------
NOTES
=====

--------------------------------------------------------------------------------
*/



/**
 * Makes a custom Widget for displaying a Featured Quote
 *
 * @since 0.1
 */
class SOF_Quote_Widget extends WP_Widget {



	/**
	 * Constructor registers widget with WordPress
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// init parent
		parent::__construct(

			// base ID
			'sofcic_widget',

			// name
			__( 'Quote (Random Featured)', 'sof-quotes' ),

			// args
			array(
				'description' => __( 'Use this widget to show a random featured quote.', 'sof-quotes' ),
			)

		);

	}



	/**
	 * Outputs the HTML for this widget
	 *
	 * @since 0.1
	 *
	 * @param array $args An array of standard parameters for widgets in this theme
	 * @param array $instance An array of settings for this widget instance
	 */
	public function widget( $args, $instance ) {

		// define args for query
		$boxes_args = array(
			'post_type' => 'quote',
			'no_found_rows' => true,
			'post_status' => 'publish',
			'orderby' => 'rand',
			//'posts_per_page' => 1,
		);

		// do query
		$boxes = new WP_Query( $boxes_args );

		// did we get any results?
		if ( $boxes->have_posts() ) :

			// get widget title
			$title = apply_filters( 'widget_title', $instance['title'] );

			// show before
			echo $args['before_widget'];

			// if we have a title, show it
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			?>
			<ol>
			<?php

			while ( $boxes->have_posts() ) : $boxes->the_post();

				// default to empty
				$val = '';
				$key = '_featured_quote';

				// override if the custom field already has a value...
				if ( get_post_meta( get_the_ID(), $key, true ) != '' ) {
					$val = get_post_meta( get_the_ID(), $key, true );
				}

				// if featured
				if ( $val == '1' ) { ?>

					<li class="widget-entry-title">
						<?php get_template_part( 'content', 'quote' ); ?>
					</li><?php

					break;

				}

			endwhile; ?>
			</ol>
			<?php

			// show after
			echo $args['after_widget'];

			//print_r( $args ); die();

			// Reset the post globals as this query will have stomped on it
			wp_reset_postdata();

		// end check for boxes
		endif;

	}



	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @since 0.1
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		//print_r( $instance ); die();

		// get title
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Featured Quote', 'sof-quotes' );
		}

		?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'sof-quotes' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<?php

	}



	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @since 0.1
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array $instance Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		// never lose a value
		$instance = wp_parse_args( $new_instance, $old_instance );

		// --<
		return $instance;

	}



} // ends class SOF_Quote_Widget



// register this widget
register_widget( 'SOF_Quote_Widget' );



