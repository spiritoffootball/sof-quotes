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
 * Makes a custom Widget for displaying a Random Featured Quote.
 *
 * @since 0.1
 */
class SOF_Quote_Widget extends WP_Widget {



	/**
	 * Constructor registers widget with WordPress.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// init parent
		parent::__construct(

			// Base ID.
			'sof_random_quote',

			// Widget Title.
			__( 'Quote (Random Featured)', 'sof-quotes' ),

			// Args.
			array(
				'description' => __( 'Use this widget to show a random featured quote.', 'sof-quotes' ),
			)

		);

	}



	/**
	 * Outputs the HTML for this widget.
	 *
	 * @since 0.1
	 *
	 * @param array $args An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	public function widget( $args, $instance ) {

		// Define args for query.
		$quotes_args = array(
			'post_type' => 'quote',
			'no_found_rows' => true,
			'post_status' => 'publish',
			'orderby' => 'rand',
			'meta_key' => '_featured_quote',
			'meta_value' => 1,
			'posts_per_page' => 1,
		);

		// Do query.
		$quotes = new WP_Query( $quotes_args );

		// Did we get any results?
		if ( $quotes->have_posts() ) :

			// Get widget title.
			$title = apply_filters( 'widget_title', $instance['title'] );

			// Show before.
			echo $args['before_widget'];

			// If we have a title, show it.
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			// Prevent immediate output.
			ob_start();

			?>
			<style>
			.widget_sof_random_quote ol { list-style: none; margin: 0; padding: 0; }
			.widget_sof_random_quote blockquote { border: none; line-height: 1.5; }
			.widget_sof_random_quote cite:before { content: '- '; }
			</style>
			<ol>
				<?php while ( $quotes->have_posts() ) : $quotes->the_post(); ?>
					<li class="widget-entry-title">
						<article <?php post_class(); ?> id="post-<?php the_ID(); ?>" style="position: relative;">
							<div class="entry-content">
								<?php edit_post_link( __( 'Edit Quote', 'sof-quotes' ), '<span class="edit-link" style="position: absolute; top: 4px; right: 4px; text-transform: uppercase;">', '</span>' ); ?>
								<?php the_content(); ?>
							</div><!-- .entry-content -->
						</article><!-- #post-<?php the_ID(); ?> -->
					</li>
				<?php endwhile; ?>
			</ol>
			<?php

			// Get the quote.
			$quote = ob_get_contents();

			// Clean up.
			ob_end_clean();

			// Give article edit button a class.
			$quote = str_replace( '<a class="post-edit-link', '<a class="post-edit-link button', $quote );

			// Show markup.
			echo $quote;

			// Show after.
			echo $args['after_widget'];

			// Reset the post globals as this query will have stomped on it.
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



