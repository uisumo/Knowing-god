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
class Knowing_God_Widget_Recent_Posts extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_entries',
			'description' => esc_html__( 'Your site&#8217;s most recent Posts.', 'knowing-god' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'knowing-god-recent-posts', esc_html__( 'Knowing God Fresh From Blog', 'knowing-god' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_entries';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
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

		$custom_post_type     = isset( $instance['custom_post_type'] ) ? $instance['custom_post_type'] : 'post';
		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'meta_key' => 'custom_post_type',
			'meta_value' => $custom_post_type,
		) ) );

		if ( $r->have_posts() ) :
		?>
		<?php echo $args['before_widget']; ?>
		<?php echo $args['before_title'] . $title . $args['after_title']; ?>
		<ul class="list-unstyled">
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
		<?php
		$post_id = get_the_ID();
		?>
		<li class="media mt-1 mb-0 recent-post">
             <?php
			 $icon_image = get_post_meta( $post_id, 'icon_image', true );
			 if ( ! empty( $icon_image ) ) { ?>
			 <img class="d-flex mr-3 align-self-center" src="<?php echo esc_url( $icon_image ); ?>" alt="<?php get_the_title() ? the_title_attribute() : the_ID(); ?>">
			 <?php
			 } elseif ( has_post_thumbnail( $post_id ) ) {
				the_post_thumbnail( 'knowing-god-recent', array( 'class' => 'd-flex mr-3 align-self-center' ) );
			} else {
			?>
				<img class="d-flex mr-3 align-self-center" src="<?php echo esc_url( get_template_directory_uri() ) . '/images/pforward-written.png'; ?>" alt="<?php get_the_title() ? the_title_attribute() : the_ID(); ?>">
			<?php
			}
			?>
            <div class="media-body">
                <h6 class="mt-1 mb-1">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php echo ( strlen( get_the_title() ) > 45 ) ? substr( get_the_title(), 0, 42 ) . '...' : get_the_title(); ?>
					</a>
				</h6>
                <p class="mb-0"><?php echo knowing_god_get_excerpt( 0, 80 ); ?><span><?php esc_html_e( ' | ', 'knowing-god' ); ?><small> <em><?php esc_html_e( 'by', 'knowing-god' ); ?></em> <?php echo esc_html( get_the_author() ); ?></small></span></p>
            </div>
        </li>
		<?php endwhile; ?>
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
		$instance['custom_post_type'] = sanitize_text_field( $new_instance['custom_post_type'] );
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
		$custom_post_type     = isset( $instance['custom_post_type'] ) ? $instance['custom_post_type'] : 'post';
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'knowing-god' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'custom_post_type' ) ); ?>"><?php esc_html_e( 'Post Type:', 'knowing-god' ); ?></label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'custom_post_type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'custom_post_type' ) ); ?>" title="<?php esc_attr_e( 'Post Type', 'knowing-god' ); ?>">
			<option value="post" <?php if ( 'post' === $custom_post_type ) echo 'selected';?>><?php esc_html_e( 'Post', 'knowing-god' ); ?></option>
			<option value="article" <?php if ( 'article' === $custom_post_type ) echo 'selected';?>><?php esc_html_e( 'Article', 'knowing-god' ); ?></option>
		</select>
		</p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'knowing-god' ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>
<?php
	}
}
