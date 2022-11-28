<?php
/**
 * File which defines the recent posts
 *
 * @package Knowing God
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0
 */
 
/**
 * Recent Posts Widget
 */
class Knowing_God_Widget_Recent_Courses extends WP_Widget {

	/**
	 * Sets up a new Recent Courses widget instance.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_courses',
			'description' => esc_html__( 'Your site&#8217;s most recent Courses.', 'knowing-god' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'knowing-god-recent-courses', esc_html__( 'Knowing God Recent Courses', 'knowing-god' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_entries';
	}

	/**
	 * Outputs the content for the current Recent Courses widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		global $wpdb;
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recent Posts', 'knowing-god' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}

		$number = 3;
		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */		
		$query = 'SELECT lmsseries.*, quizcategories.category, users.name AS author_name FROM lmsseries 
		INNER JOIN quizcategories ON quizcategories.id = lmsseries.lms_category_id
		INNER JOIN users ON users.id = lmsseries.created_by
		WHERE lmsseries.parent_id = 0 AND lmsseries.privacy="public" ORDER BY lmsseries.created_at DESC LIMIT 4
		';
		$series = $wpdb->get_results( $query );
		
		if ( ! empty( $series ) ) :
		?>
		<?php echo $args['before_widget']; ?>
		<?php echo $args['before_title'] . $title . $args['after_title']; 
		
		?>		
		
			  <ul class="list-unstyled">
				<?php foreach($series as $c) : 
				$image_icon = get_bloginfo('template_url') . '/images/pforward-courses.png';
				if ( ! empty( $c->image_icon ) ) {
					$image_icon = IMAGE_PATH_UPLOAD_LMS_SERIES . $c->image_icon;
				}
				?>
				<li class="media mt-1 mb-0">
					  <img class="d-flex mr-3 align-self-center" height="40" width="40" src="<?php echo esc_url( $image_icon ); ?>" alt="Generic placeholder image">
					  <div class="media-body">
						  <h6 class="mt-1 mb-1"><a style="font-weight:400; color:black;" href="<?php echo URL_FRONTEND_LMSSERIES . $c->slug; ?>"><?php echo $c->title; ?></a></h6>
						  <p class="mb-0" style="font-size:.9rem; color:#555; line-height:.9rem;"><?php echo strip_tags( $c->short_description ); ?><span> <small>| <em>by</em> <?php echo $c->author_name; ?></small> | Lessons: <?php echo $c->total_items; ?></span>
						<?php /* ?>
						  | <?php echo $c->category; ?>
						  <?php */ ?>
						  </p>
					  </div>
				  </li>
				  <?php endforeach; ?>
			  </ul>
		
		<?php echo $args['after_widget']; ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? $instance['title'] : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'knowing-god' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'knowing-god' ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>
<?php
	}
}
