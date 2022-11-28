<?php
add_action('add_meta_boxes', 'knowing_god_add_page_meta');
function knowing_god_add_page_meta()
{
	add_meta_box(
				 'tags_meta', // $id
				 'Page Options', // $title
				 'knowing_god_display_options', // $callback
				 array( 'page', 'post' ), // $page
				 'side', // $context
				 'high' ); // $priority
}

function knowing_god_display_options()
{
	global $post;
	global $wpdb;
	$page_banner = get_post_meta( $post->ID, 'page_banner', true );
	$page_title = get_post_meta( $post->ID, 'page_title', true );
	$sidebar_position = get_post_meta( $post->ID, 'sidebar_position', true );
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_script( 'knowing-god-admin', get_template_directory_uri() . '/js/admin.js', array( 'jquery', 'thickbox', 'media-upload' ) );

	echo '<input type="hidden" name="knowing_god_noncename" id="knowing_god_noncename" value="' .
		wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	?>
	<table class="st-table-vehicles" width="100%">
		<tr>
			<td>
				<div class="form-field">
					 <label for="page_title"><?php esc_html_e( 'Page Title', 'knowing-god' ); ?></label>
					 <select name="page_title" id="page_title" title="<?php esc_html_e( 'Page Title', 'knowing-god' ); ?>">
						<option value="show" <?php if ( 'show' === $page_title ) echo 'selected'; ?>><?php esc_html_e( 'Show', 'knowing-god' ); ?></option>
						<option value="hide" <?php if ( 'hide' === $page_title ) echo 'selected';?>><?php esc_html_e( 'Hide', 'knowing-god' ); ?></option>
					 </select>
				</div>

			</td>
		</tr>

		<tr>
			<td>
				<div class="form-field">
					 <label for="custom_post_type"><?php esc_html_e( 'Post Type', 'knowing-god' ); ?></label>
					 <?php
					 $custom_post_type = get_post_meta( $post->ID, 'custom_post_type', true );
					 ?>
					 <select name="custom_post_type" id="custom_post_type" title="<?php esc_attr_e( 'Post Type', 'knowing-god' ); ?>">
						<option value="post" <?php if ( 'post' === $custom_post_type ) echo 'selected';?>><?php esc_html_e( 'Post', 'knowing-god' ); ?></option>
						<option value="article" <?php if ( 'article' === $custom_post_type ) echo 'selected';?>><?php esc_html_e( 'Article', 'knowing-god' ); ?></option>
					 </select>
				</div>

			</td>
		</tr>

		<tr>
			<td>
				<div class="form-field">
					 <label for="page_banner"><?php esc_html_e( 'Bread Crumb', 'knowing-god' ); ?></label>
					 <select name="page_banner" id="page_banner" title="<?php esc_html_e( 'Page Banner', 'knowing-god' ); ?>">
						<option value="show" <?php if ( 'show' === $page_banner ) echo 'selected'; ?>><?php esc_html_e( 'Show', 'knowing-god' ); ?></option>
						<option value="hide" <?php if ( 'hide' === $page_banner ) echo 'selected';?>><?php esc_html_e( 'Hide', 'knowing-god' ); ?></option>
					 </select>
				</div>

			</td>
		</tr>

		<tr>
			<td>
				<div class="form-field">
					 <label for="sidebar_position"><?php esc_html_e( 'Sidebar Position', 'knowing-god' ); ?></label>
					 <select name="sidebar_position" id="sidebar_position" title="<?php esc_html_e( 'Sidebar Position', 'knowing-god' ); ?>">
						<option value="right" <?php if ( 'right' === $sidebar_position ) echo 'selected';?>><?php esc_html_e( 'Right', 'knowing-god' ); ?></option>
						<option value="left" <?php if ( 'left' === $sidebar_position ) echo 'selected';?>><?php esc_html_e( 'Left', 'knowing-god' ); ?></option>
						<option value="none" <?php if ( 'none' === $sidebar_position ) echo 'selected';?>><?php esc_html_e( 'None', 'knowing-god' ); ?></option>
					 </select>
				</div>

			</td>
		</tr>

		<tr>
			<td>
				<div class="form-field">
					 <label for="show_share_icon"><?php esc_html_e( 'Share Icon', 'knowing-god' ); ?></label>
					 <?php $show_share_icon = get_post_meta( $post->ID, 'show_share_icon', true ); ?>
					 <select name="show_share_icon" id="show_share_icon" title="<?php esc_html_e( 'Sidebar Position', 'knowing-god' ); ?>">
						<option value="yes" <?php if ( 'yes' === $show_share_icon ) echo 'selected';?>><?php esc_html_e( 'Yes', 'knowing-god' ); ?></option>
						<option value="no" <?php if ( 'no' === $show_share_icon ) echo 'selected';?>><?php esc_html_e( 'No', 'knowing-god' ); ?></option>
					 </select>
				</div>
			</td>
		</tr>

		<tr>
			<td>
				<div class="form-field">
					 <label for="show_globe_icon"><?php esc_html_e( 'Globe Icon', 'knowing-god' ); ?></label>
					 <?php $show_globe_icon = get_post_meta( $post->ID, 'show_globe_icon', true ); ?>
					 <select name="show_globe_icon" id="show_globe_icon" title="<?php esc_html_e( 'Sidebar Position', 'knowing-god' ); ?>">
						<option value="yes" <?php if ( 'yes' === $show_globe_icon ) echo 'selected';?>><?php esc_html_e( 'Yes', 'knowing-god' ); ?></option>
						<option value="no" <?php if ( 'no' === $show_globe_icon ) echo 'selected';?>><?php esc_html_e( 'No', 'knowing-god' ); ?></option>
					 </select>
				</div>
			</td>
		</tr>

		<tr>
			<td>
				<div class="form-field">
					 <label for="quiz_id"><?php esc_html_e( 'Quiz', 'knowing-god' ); ?></label>
					 <?php
					$quizzes = $wpdb->get_results( "SELECT * FROM quizzes ORDER BY `title`" );
					 $quiz_id = get_post_meta( $post->ID, 'quiz_id', true ); ?>
					 <select name="quiz_id" id="quiz_id" title="<?php esc_html_e( 'Quiz', 'knowing-god' ); ?>">
						<option value="0" <?php if ( '0' === $quiz_id ) echo 'selected';?>><?php esc_html_e( 'No Quiz', 'knowing-god' ); ?></option>
						<?php foreach ( $quizzes as $quizz ) : ?>
						<option value="<?php echo $quizz->id; ?>" <?php if ( $quizz->id === $quiz_id ) echo 'selected';?>><?php echo esc_html__( $quizz->title, 'knowing-god' ); ?></option>
						<?php endforeach; ?>
					 </select>
				</div>
			</td>
		</tr>
		
		<tr>
			<td>
				<div class="form-field">
					 <label for="pathway"><?php esc_html_e( 'Pathway', 'knowing-god' ); ?></label>
					 <?php $pathway = get_post_meta( $post->ID, 'pathway', true ); ?>
					 <select name="pathway" id="pathway" title="<?php esc_html_e( 'Pathway', 'knowing-god' ); ?>">
						<option value="pathwaystart" <?php if ( 'pathwaystart' === $pathway ) echo 'selected';?>><?php esc_html_e( 'PathwayStart', 'knowing-god' ); ?></option>
						<option value="pathwayforward" <?php if ( 'pathwayforward' === $pathway ) echo 'selected';?>><?php esc_html_e( 'PathwayForward', 'knowing-god' ); ?></option>
						<option value="pathwayforever" <?php if ( 'pathwayforever' === $pathway ) echo 'selected';?>><?php esc_html_e( 'PathwayForever', 'knowing-god' ); ?></option>
					 </select>
				</div>
			</td>
		</tr>

		<?php
		$icon_row = array(
			'audio_file' => esc_html__( 'Audio File', 'knowing-god' ),
			'icon_image' => esc_html__( 'Icon Image', 'knowing-god' ),
			'file_word' => esc_html__( 'File Word', 'knowing-god' ),
			'file_ppt' => esc_html__( 'File PPT', 'knowing-god' ),
			'file_pdf' => esc_html__( 'File PDF', 'knowing-god' ),
		);
		foreach( $icon_row as $key => $val ) {
		$placeholder = '';
		if ( $key == 'icon_image' ) {
			$placeholder = '40x20px';
		}
		$value = get_post_meta( $post->ID, $key, true );
		?>
			<tr>
				<td>
					<div class="form-field">
						 <label for="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( $val, 'knowing-god' ); ?></label>
						 <input type="text" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="" placeholder="<?php esc_html_e( $placeholder, 'knowing-god' ); ?>"/>
						 <?php
						 if ( $key != 'icon_image' ) {
							 echo __( '<font color="red">Make sure click on "File URL"</font>', 'knowing-god' );
						 }
						 ?>
						 <input style="float: left;" type="button" class="button custom_image_button" name="<?php echo esc_attr( $key ); ?>_button" id="<?php echo esc_attr( $key ); ?>_button" value="<?php esc_html_e( 'Browse', 'knowing-god' ); ?>" data-field_name="<?php echo esc_attr( $key ); ?>"/>
						 <?php
						 if ( ! empty( $value ) ) {
							 if ( $key === 'icon_image' ) {
							 ?>
							 <img src="<?php echo esc_url( $value ); ?>" title="<?php esc_html_e($val, 'knowing-god'); ?>" alt="<?php esc_html_e($val, 'knowing-god' ); ?>" width="40" height="20">
							 <?php
							 } else { ?>
							 <a href="<?php echo esc_url( $value ); ?>"><?php esc_html_e( 'Download', 'knowing-god' ); ?></a>
							 <?php
							 }
						 }
						 ?>
					</div>

				</td>
			</tr>
		<?php } ?>

	</table>
	<?php
}

// Save the Metabox Data
function knowing_god_save_page_meta( $post_id, $post ) {
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times

	if ( isset( $_POST['knowing_god_noncename'] ) ) {
		if ( ! wp_verify_nonce( $_POST['knowing_god_noncename'], plugin_basename( __FILE__ ) ) ) {
			return $post->ID;
		}

		// Is the user allowed to edit the post or page?
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return $post->ID;
		}
		$knowing_god_meta = array();
		$knowing_god_meta['page_banner'] = sanitize_text_field( $_POST['page_banner'] );
		$knowing_god_meta['page_title'] = sanitize_text_field( $_POST['page_title'] );
		$knowing_god_meta['sidebar_position'] = sanitize_text_field( $_POST['sidebar_position'] );
		$knowing_god_meta['custom_post_type'] = sanitize_text_field( $_POST['custom_post_type'] );
		$knowing_god_meta['show_share_icon'] = sanitize_text_field( $_POST['show_share_icon'] );
		$knowing_god_meta['show_globe_icon'] = sanitize_text_field( $_POST['show_globe_icon'] );
		$knowing_god_meta['pathway'] = sanitize_text_field( $_POST['pathway'] );
		$knowing_god_meta['quiz_id'] = $_POST['quiz_id'];


		$icon_row = array(
			'audio_file' => esc_html__( 'Audio File', 'knowing-god' ),
			'icon_image' => esc_html__( 'Icon Image', 'knowing-god' ),
			'file_word' => esc_html__( 'File Word', 'knowing-god' ),
			'file_ppt' => esc_html__( 'File PPT', 'knowing-god' ),
			'file_pdf' => esc_html__( 'File PDF', 'knowing-god' ),
		);
		foreach( $icon_row as $key => $val ) {
			if ( ! empty( $_POST[ $key ] ) ) :
				$knowing_god_meta[ $key ] = esc_url_raw( $_POST[ $key ] );
			endif;
		}

		// Add values of $cabs_meta as custom fields
		foreach ( $knowing_god_meta as $key => $value ) { // Cycle through the
			if ( $post->post_type == 'revision' ) {
				return; // Don't store custom data twice
			}
			if ( get_post_meta( $post->ID, $key, FALSE ) ) { // If the custom field already has a value
				update_post_meta( $post->ID, $key, $value );
			} else { // If the custom field doesn't have a value
				add_post_meta( $post->ID, $key, $value );
			}
			if ( ! $value ) {
				delete_post_meta( $post->ID, $key ); // Delete if blank
			}
		}
	}
}
add_action( 'save_post', 'knowing_god_save_page_meta', 1, 2 ); // save the custom fields


add_filter('manage_posts_columns', 'hs_product_table_head');
function hs_product_table_head( $columns ) {
    $columns['pathway']  = esc_html__( 'Pathway', 'knowing-god' );
    return $columns;

}
add_action( 'manage_posts_custom_column', 'hs_product_table_content', 10, 2 );

function hs_product_table_content( $column_name, $post_id ) {
    if( $column_name == 'pathway' ) {
        $pathway = get_post_meta( $post_id, 'pathway', true );
        if( $pathway != '' ) {
            echo $pathway;
        } else {
			echo '-';
		}
    }
}