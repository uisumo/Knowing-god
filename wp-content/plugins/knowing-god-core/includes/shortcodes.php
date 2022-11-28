<?php
/**
 * Knowing God short code definitions.
 *
 * @package Knowing God
 */
if ( class_exists( 'Vc_Manager' ) ) :
 /**
 * Add file picker shartcode param.
 *
 * @param array $settings   Array of param seetings.
 * @param int   $value      Param value.
 */
function knowing_god_file_picker_settings_field( $settings, $value ) {
  $output = '';
  $select_file_class = '';
  $remove_file_class = ' hidden';
  $attachment_url = wp_get_attachment_url( $value );
  if ( $attachment_url ) {
    $select_file_class = ' hidden';
    $remove_file_class = '';
  }
  $output .= '<div class="file_picker_block">
                <div class="' . esc_attr( $settings['type'] ) . '_display">' .
                  $attachment_url .
                '</div>
                <input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
                 esc_attr( $settings['param_name'] ) . ' ' .
                 esc_attr( $settings['type'] ) . '_field" value="' . esc_attr( $value ) . '" />
                <button class="button file-picker-button' . $select_file_class . '">' . esc_html__( 'Select File', 'knowing-god' ) . '</button>
                <button class="button file-remover-button' . $remove_file_class . '">' . esc_html__( 'Remove File', 'knowing-god' ) . '</button>
              </div>
              ';
  return $output;
}
vc_add_shortcode_param( 'file_picker', 'knowing_god_file_picker_settings_field', KNOWING_GOD_PLUGIN_URL . '/includes/js/file_picker.js' );
endif;

 /**
 * Visual Composer Shortcodes start
*/
add_action( 'vc_before_init', 'knowing_god_WITHVS' );
function knowing_god_WITHVS() {
	$pages = get_pages();
	$pages_array = array(esc_html__( 'No Page', 'knowing-god' ) => '' );
	foreach ( $pages as $page ) {
		$pages_array[ $page->post_title ] = $page->ID;
	}

	$faqcategories = array();
	$terms = get_terms( 'faqcategory', array( 'hide_empty' => false ) );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$faqcategories[ $term->name ] = $term->term_id;
		}
	}

	$available_formats = array();
	$available_formats[ esc_html__( 'PDF', 'knowing-god' ) ] = 'fa fa-file-pdf-o';
	$available_formats[ esc_html__( 'PPT', 'knowing-god' ) ] = 'fa fa-file-powerpoint-o';
	$available_formats[ esc_html__( 'Word', 'knowing-god' ) ] = 'fa fa-file-word-o';
	$available_formats[ esc_html__( 'Comments', 'knowing-god' ) ] = 'fa fa-comments-o';
	$available_formats[ esc_html__( 'Share', 'knowing-god' ) ] = 'fa fa-share';
	$available_formats[ esc_html__( 'Globe', 'knowing-god' ) ] = 'fa fa-globe';
	$available_formats[ esc_html__( 'Group', 'knowing-god' ) ] = 'fa fa-group';
	$available_formats[ esc_html__( 'Cap', 'knowing-god' ) ] = 'fa fa-graduation-cap';

	$icon = 'icon-wpb-knowing-god';
	// Heading.
	vc_map( array(
        'name' => esc_html__( 'Knowing God Heading', 'knowing-god' ),
		'description' => esc_html__( 'It displays the Heading', 'knowing-god' ),
        'base' => 'knowing_god_heading',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Type', 'knowing-god' ),
				'param_name' => 'type',
				'value' => array(
					esc_html__( 'H1', 'knowing-god' ) => 'h1',
					esc_html__( 'H2', 'knowing-god' ) => 'h2',
					esc_html__( 'H3', 'knowing-god' ) => 'h3',
					esc_html__( 'H4', 'knowing-god' ) => 'h4',
					esc_html__( 'H5', 'knowing-god' ) => 'h5',
					esc_html__( 'H6', 'knowing-god' ) => 'h6',
				),
				'default' => 'h1',
				'description' => esc_html__( 'Select heading type here.', 'knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Alignment', 'knowing-god' ),
				'param_name' => 'heading_align',
				'value' => array(
					esc_html__( 'Left', 'knowing-god' ) => 'left',
					esc_html__( 'Right', 'knowing-god' ) => 'right',
					esc_html__( 'Center', 'knowing-god' ) => 'center',
				),
				'description' => esc_html__( 'Select heading alignment', 'knowing-god' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title', 'knowing-god' ),
				'param_name' => 'heading_title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Title text Here.', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Heading Color', 'knowing-god' ),
				'param_name' => 'knowing_god_heading_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select Heading Color.', 'knowing-god' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Colored Text', 'knowing-god' ),
				'param_name' => 'colored_text',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Enter the text you want to be in color.', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Colored Text Color', 'knowing-god' ),
				'param_name' => 'colored_text_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select Colored Text Color.', 'knowing-god' ),
			),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Readmore Page', 'knowing-god' ),
				'param_name' => 'readmore_page',
				'value' => $pages_array,
				'description' => esc_html__( 'Insert Button Link here.', 'knowing-god' ),
			),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Readmore Target', 'knowing-god' ),
				'param_name' => 'readmore_page_target',
				'value' => array(
					esc_html__( 'Same Page', 'knowing-god' ) => '_parent',
					esc_html__( 'Another Page', 'knowing-god' ) => '_blank',
				),
				'description' => esc_html__( 'Insert Button Link Target here.', 'knowing-god' ),
				'dependency' => array(
					'element'=>'readmore_page',
					'not_empty' => true,
				),
			),
        )
    ));

	/**
     * Team Element
     */
    vc_map( array(
        'name' => esc_html__( 'Knowing God Our team/progress','knowing-god' ),
        'description' => esc_html__( 'This will display Our team on the page', 'knowing-god' ),
        'base' => 'knowing_god_ourteam_container',
        'as_parent' => array( 'only' => 'knowing_god_ourteam' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
        'content_element' => true,
        'show_settings_on_create' => false,
		'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
            array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title','knowing-god' ),
				'param_name' => 'title',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter title here.','knowing-god' ),
            ),
            array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Sub Title','knowing-god' ),
				'param_name' => 'sub_title',
				'value' => esc_html__( 'Our Team', 'knowing-god' ),
				'description' => esc_html__( 'Enter sub title here.','knowing-god' ),
            ),
        ),
        'js_view' => 'VcColumnView'
    ));


    //Your 'container' content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
    if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
        class WPBakeryShortCode_knowing_god_ourteam_container extends WPBakeryShortCodesContainer {
        }
    }
    vc_map( array(
        'name' => esc_html__( 'Knowing God ourteam Content','knowing-god' ),
        'base' => 'knowing_god_ourteam',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'as_child' => array( 'only' => 'knowing_god_ourteam_container' ),
        'params' => array(
            array(
				'type' => 'attach_image',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Upload Client Image','knowing-god' ),
				'param_name' => 'image',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Upload Image Here.','knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Conteiner Columns','knowing-god' ),
				'param_name' => 'container_columns',
				'value' => array(
					esc_html__( 'One', 'knowing-god' ) => '1',
					esc_html__( 'Two', 'knowing-god' ) => '2',
					esc_html__( 'Three', 'knowing-god' ) => '3',
					esc_html__( 'Four', 'knowing-god' ) => '4',
				),
				'default' => '4',
				'description' => esc_html__( 'Select Conteiner Type.','knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Alignment','knowing-god' ),
				'param_name' => 'alignment',
				'value' => array(
					esc_html__( 'Center', 'knowing-god' ) => 'center',
					esc_html__( 'Left', 'knowing-god' ) => 'left',
					esc_html__( 'Right', 'knowing-god' ) => 'right',
				),
				'default' => 'center',
				'description' => esc_html__( 'Select Alignment.','knowing-god' ),
            ),
            array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Client Name','knowing-god' ),
				'param_name' => 'name',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Client Name Here.','knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Colored Text', 'knowing-god' ),
				'param_name' => 'colored_name',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Enter the text you want to be in color.', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Colored Text Color', 'knowing-god' ),
				'param_name' => 'colored_name_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select Colored Text Color.', 'knowing-god' ),
			),
            array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Role','knowing-god' ),
				'param_name' => 'role',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter Role OR Place here.','knowing-god' ),
            ),
			array(
				'type' => 'textarea',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Description','knowing-god' ),
				'param_name' => 'description',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter About the team.','knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Readmore','knowing-god' ),
				'param_name' => 'readmore_page',
				'value' => $pages_array,
				'description' => esc_html__( 'Client Readmore Here.','knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Display Type','knowing-god' ),
				'param_name' => 'display_type',
				'value' => array(
					esc_html__( 'Email', 'knowing-god' ) => 'email',
					esc_html__( 'Social Links', 'knowing-god' ) => 'social_links',
					),
				'description' => esc_html__( 'Enter About the team.','knowing-god' ),
            ),
            array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Facebook','knowing-god' ),
				'param_name' => 'facebook',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter facebook Place here.','knowing-god' ),
				'dependency' => array(
					'element'=>'display_type',
					'value' => array( 'social_links' ),
				),
            ),
            array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Twitter','knowing-god' ),
				'param_name' => 'twitter',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter twitter Place here.','knowing-god' ),
				'dependency' => array(
					'element'=>'display_type',
					'value' => array( 'social_links' ),
				),
            ),
            array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Instagram','knowing-god' ),
				'param_name' => 'instagram',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter instagram here.','knowing-god' ),
				'dependency' => array(
					'element'=>'display_type',
					'value' => array( 'social_links' ),
				),
            ),
            array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Linkedin','knowing-god' ),
				'param_name' => 'linkedin',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter linkedin Place here.','knowing-god' ),
				'dependency' => array(
					'element'=>'display_type',
					'value' => array( 'social_links' ),
				),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Email','knowing-god' ),
				'param_name' => 'email',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter Email address here.','knowing-god' ),
				'dependency' => array(
					'element'=>'display_type',
					'value' => array( 'email' ),
				),
            ),
        )
    ) );

	/**
	 * Partners Slider
	*/
	vc_map( array(
        'name' => esc_html__( 'Knowing God Partners', 'knowing-god' ),
		'description' => esc_html__( 'This will display Partners on the page', 'knowing-god' ),
        'base' => 'knowing_god_partners_container',
		'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
		'as_parent' => array( 'only' => 'knowing_god_partners_single' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
        'content_element' => true,
		'show_settings_on_create' => false,
        'params' => array(),
		'js_view' => 'VcColumnView'
    ));
	//Your 'container' content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_knowing_god_partners_container extends WPBakeryShortCodesContainer {
		}
	}

	vc_map( array(
        'name' => esc_html__( 'Knowing God Partners', 'knowing-god' ),
        'base' => 'knowing_god_partners_single',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
		'as_child' => array( 'only' => 'knowing_god_partners_container' ),
        'params' => array(
		    array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Partner name', 'knowing-god' ),
				'param_name' => 'partner_name',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Partner name Here.', 'knowing-god' )
            ),
			array(
				'type' => 'attach_image',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Upload Testimonial Image', 'knowing-god' ),
				'param_name' => 'partner_img',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Upload Image Here.', 'knowing-god' )
			),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Read more', 'knowing-god' ),
				'param_name' => 'partner_read_more',
				'value' => $pages_array,
				'description' => esc_html__( 'Select Read more Page.', 'knowing-god' ),
			),
        )
    ) );

	// Knowing God Button.
	vc_map( array(
        'name' => esc_html__( 'Knowing God Button', 'knowing-god' ),
		'description' => esc_html__( 'It displays the button', 'knowing-god' ),
        'base' => 'knowing_god_button',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Button Type', 'knowing-god' ),
				'param_name' => 'downloadbutton_type',
				'value' => array(
					esc_html__( 'Default', 'knowing-god' ) => 'default',
					esc_html__( 'Gray', 'knowing-god' ) => 'gray',
				),
				'description' => esc_html__( 'Insert Button Type here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Button Title', 'knowing-god' ),
				'param_name' => 'downloadbutton_title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Button Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Button Color', 'knowing-god' ),
				'param_name' => 'downloadbutton_color',
				'value' => esc_html__( '#222222', 'knowing-god' ),
				'description' => esc_html__( 'Select Button Color.', 'knowing-god' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Button Link', 'knowing-god' ),
				'param_name' => 'downloadbutton_link',
				'value' => $pages_array,
				'description' => esc_html__( 'Select Button Link here.', 'knowing-god' ),
			),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Button Link Target', 'knowing-god' ),
				'param_name' => 'downloadbutton_link_target',
				'value' => array(
					esc_html__( 'Same Page', 'knowing-god' ) => '_parent',
					esc_html__( 'Another Page', 'knowing-god' ) => '_blank',
					esc_html__( 'Modal', 'knowing-god' ) => 'modal',
				),
				'description' => esc_html__( 'Insert Button Link Target here.', 'knowing-god' ),
			),
        )
    ));

	// Knowing God About Us Content.
	vc_map( array(
        'name' => esc_html__( 'Knowing God About', 'knowing-god' ),
		'description' => esc_html__( 'It displays the button', 'knowing-god' ),
        'base' => 'knowing_god_about',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
			array(
				'type' => 'attach_image',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Upload Image', 'knowing-god' ),
				'param_name' => 'image',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Upload Image Here.', 'knowing-god' )
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title', 'knowing-god' ),
				'param_name' => 'title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title Colored', 'knowing-god' ),
				'param_name' => 'title_colored',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Title Colored here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Colored Text Color', 'knowing-god' ),
				'param_name' => 'title_colored_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select Colored Text Color.', 'knowing-god' ),
			),
			array(
				'type' => 'textarea',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Description', 'knowing-god' ),
				'param_name' => 'description',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Description here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Button Link', 'knowing-god' ),
				'param_name' => 'downloadbutton_link',
				'value' => $pages_array,
				'description' => esc_html__( 'Select Button Link here.', 'knowing-god' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Button Title', 'knowing-god' ),
				'param_name' => 'downloadbutton_link_title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Button Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Button Link Target', 'knowing-god' ),
				'param_name' => 'downloadbutton_link_target',
				'value' => array(
					esc_html__( 'Same Page', 'knowing-god' ) => '_parent',
					esc_html__( 'Another Page', 'knowing-god' ) => '_blank',
				),
				'description' => esc_html__( 'Insert Button Link Target here.', 'knowing-god' ),
			),
        )
    ));

	// Knowing God LMS Content.
	vc_map( array(
        'name' => esc_html__( 'Knowing God LMS', 'knowing-god' ),
		'description' => esc_html__( 'It displays the LMS', 'knowing-god' ),
        'base' => 'knowing_god_lms',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
			array(
				'type' => 'attach_image',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Upload Image', 'knowing-god' ),
				'param_name' => 'image',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Upload Image Here.', 'knowing-god' )
			),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Columns', 'knowing-god' ),
				'param_name' => 'columns',
				'value' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'description' => esc_html__( 'Insert Columns here', 'knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Alignment', 'knowing-god' ),
				'param_name' => 'alignment',
				'value' => array(
					esc_html__( 'Center', 'knowing-god' ) => 'center',
					esc_html__( 'Left', 'knowing-god' ) => 'left',
					esc_html__( 'Right', 'knowing-god' ) => 'right',
				),
				'description' => esc_html__( 'Select Alignment here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title', 'knowing-god' ),
				'param_name' => 'title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title Color', 'knowing-god' ),
				'param_name' => 'title_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Title Color here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Video URL', 'knowing-god' ),
				'param_name' => 'video_link',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Enter Video URL here', 'knowing-god' ),
            ),
			array(
				'type' => 'attach_image',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Upload Title Image', 'knowing-god' ),
				'param_name' => 'title_image',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Upload Title Image of Size 30x30 Here.', 'knowing-god' )
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Sub Title', 'knowing-god' ),
				'param_name' => 'sub_title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Sub Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Author', 'knowing-god' ),
				'param_name' => 'author',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Author here', 'knowing-god' ),
            ),
			array(
				'type' => 'textarea',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Description', 'knowing-god' ),
				'param_name' => 'description',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Description here', 'knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Readmore Link', 'knowing-god' ),
				'param_name' => 'readmore_page',
				'value' => $pages_array,
				'description' => esc_html__( 'Select Readmore Link here.', 'knowing-god' ),
			),
			array(
				'type' => 'checkbox',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Available Formats', 'knowing-god' ),
				'param_name' => 'available_formats',
				'value' => $available_formats,
				'description' => esc_html__( 'Select Icons to show here', 'knowing-god' ),
            ),
			array(
				'type' => 'file_picker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Audio File', 'knowing-god' ),
				'param_name' => 'audio_file',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select audio file here', 'knowing-god' ),
            ),
        )
    ));

	/**
	 * Holder for the elements
	*/
	vc_map( array(
        'name' => esc_html__( 'Knowing God Holder', 'knowing-god' ),
		'description' => esc_html__( 'This will display hold the elements on the page', 'knowing-god' ),
        'base' => 'knowing_god_element_holder',
		'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
		'as_parent' => array( 'only' => 'knowing_god_lms,knowing_god_image_text' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
        'content_element' => true,
		'show_settings_on_create' => false,
        'params' => array(),
		'js_view' => 'VcColumnView'
    ));
	//Your 'container' content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_knowing_god_element_holder extends WPBakeryShortCodesContainer {
		}
	}

	// Knowing God LMS Content.
	vc_map( array(
        'name' => esc_html__( 'Knowing God Breadcrumb', 'knowing-god' ),
		'description' => esc_html__( 'It displays the Breadcrumb', 'knowing-god' ),
        'base' => 'knowing_god_breadcrumb',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title', 'knowing-god' ),
				'param_name' => 'title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title Colored', 'knowing-god' ),
				'param_name' => 'title_colored',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title Colored Color', 'knowing-god' ),
				'param_name' => 'title_colored_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'vc_link',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 1', 'knowing-god' ),
				'param_name' => 'link_1',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select URL Segment here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 1 Colored Text', 'knowing-god' ),
				'param_name' => 'link_1_colored',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 1 here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 1 Color for Colored Text Color', 'knowing-god' ),
				'param_name' => 'link_1_colored_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 1 here', 'knowing-god' ),
            ),
			array(
				'type' => 'vc_link',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 2', 'knowing-god' ),
				'param_name' => 'link_2',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select URL Segment here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 2 Colored Text', 'knowing-god' ),
				'param_name' => 'link_2_colored',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 2 here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 2 Color for Colored Text', 'knowing-god' ),
				'param_name' => 'link_2_colored_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 2 here', 'knowing-god' ),
            ),
			array(
				'type' => 'vc_link',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 3', 'knowing-god' ),
				'param_name' => 'link_3',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select URL Segment here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 3 Colored Text', 'knowing-god' ),
				'param_name' => 'link_3_colored',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 3 here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 3 Color for Colored Text', 'knowing-god' ),
				'param_name' => 'link_3_colored_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 3 here', 'knowing-god' ),
            ),
			array(
				'type' => 'vc_link',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 4', 'knowing-god' ),
				'param_name' => 'link_4',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select URL Segment here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 4 Colored Text', 'knowing-god' ),
				'param_name' => 'link_4_colored',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 4 here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 4 Color for Colored Text', 'knowing-god' ),
				'param_name' => 'link_4_colored_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 4 here', 'knowing-god' ),
            ),
			array(
				'type' => 'vc_link',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 5', 'knowing-god' ),
				'param_name' => 'link_5',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select URL Segment here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 5 Colored Text', 'knowing-god' ),
				'param_name' => 'link_5_colored',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 5 here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 5 Color for Colored Text', 'knowing-god' ),
				'param_name' => 'link_5_colored_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 5 here', 'knowing-god' ),
            ),
             array(
				'type' => 'vc_link',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 6', 'knowing-god' ),
				'param_name' => 'link_6',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select URL Segment here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 6 Colored Text', 'knowing-god' ),
				'param_name' => 'link_6_colored',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 6 here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL Segment 6 Color for Colored Text', 'knowing-god' ),
				'param_name' => 'link_6_colored_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert URL Segment 6 here', 'knowing-god' ),
            ),
        )
    ));

	/**
	 * Engage Component
	*/
	vc_map( array(
        'name' => esc_html__( 'Knowing God Engage', 'knowing-god' ),
		'description' => esc_html__( 'This will display Engage on the page', 'knowing-god' ),
        'base' => 'knowing_god_engage_container',
		'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
		'as_parent' => array( 'only' => 'knowing_god_engage_single' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
        'content_element' => true,
		'show_settings_on_create' => false,
        'params' => array(),
		'js_view' => 'VcColumnView'
    ));
	//Your 'container' content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_knowing_god_engage_container extends WPBakeryShortCodesContainer {
		}
	}

	vc_map( array(
        'name' => esc_html__( 'Knowing God Engage', 'knowing-god' ),
        'base' => 'knowing_god_engage_single',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
		'as_child' => array( 'only' => 'knowing_god_engage_container' ),
        'params' => array(
		    array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title', 'knowing-god' ),
				'param_name' => 'title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Title Here.', 'knowing-god' )
            ),
			array(
				'type' => 'textarea',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Description', 'knowing-god' ),
				'param_name' => 'description',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Description Here.', 'knowing-god' )
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Read more', 'knowing-god' ),
				'param_name' => 'read_more',
				'value' => $pages_array,
				'description' => esc_html__( 'Select Read more Page.', 'knowing-god' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Read more title', 'knowing-god' ),
				'param_name' => 'read_more_title',
				'value' => $pages_array,
				'description' => esc_html__( 'Select Read more button title.', 'knowing-god' ),
			),
        )
    ) );

	// Knowing God Image text.
	vc_map( array(
        'name' => esc_html__( 'Knowing God Image Text', 'knowing-god' ),
		'description' => esc_html__( 'It displays the Image Text', 'knowing-god' ),
        'base' => 'knowing_god_image_text',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
			array(
				'type' => 'attach_image',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Image', 'knowing-god' ),
				'param_name' => 'image',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Image here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title', 'knowing-god' ),
				'param_name' => 'title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Columns', 'knowing-god' ),
				'param_name' => 'columns',
				'value' => array(
					esc_html__( 'One', 'knowing-god' ) => '1',
					esc_html__( 'Two', 'knowing-god' ) => '2',
					esc_html__( 'Three', 'knowing-god' ) => '3',
					esc_html__( 'Four', 'knowing-god' ) => '4',
				),
				'description' => esc_html__( 'Select columns here', 'knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Read more', 'knowing-god' ),
				'param_name' => 'read_more',
				'value' => $pages_array,
				'description' => esc_html__( 'Select Read more Page.', 'knowing-god' ),
			),
        )
    ));

	/**
     * Knowing God Video Pop-up
     */
	vc_map( array(
        'name' => esc_html__( 'Knowing God Video Pop-up','knowing-god' ),
        'description' => esc_html__( 'This will display Video Pop-up on the page', 'knowing-god' ),
        'base' => 'knowing_god_video_popup',
		'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
            array(
				'type' => 'attach_image',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Image', 'knowing-god' ),
				'param_name' => 'image',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Upload Image of Size 1200x450 Here.', 'knowing-god' )
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Video Link','knowing-god' ),
				'param_name' => 'video_link',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter Video Link Eg: https://www.youtube.com/embed/qA39SpcxOkE?rel=0&amp;showinfo=0.','knowing-god' ),
            ),
        ),
    ));

	// FAQ Conteiner
	vc_map( array(
        'name' => esc_html__( 'Knowing God FAQs', 'knowing-god' ),
		'description' => esc_html__( 'This will display FAQs on the page', 'knowing-god' ),
        'base' => 'knowing_god_faq_container',
		'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
		'as_parent' => array( 'only' => 'knowing_god_faq' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
        'content_element' => true,
		'show_settings_on_create' => false,
        'params' => array(),
		'js_view' => 'VcColumnView'
    ));
	//Your 'container' content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_knowing_god_faq_container extends WPBakeryShortCodesContainer {
		}
	}
	// FAQ
	vc_map( array(
        'name' => esc_html__( 'Knowing God FAQ', 'knowing-god' ),
        'base' => 'knowing_god_faq',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
		'as_child' => array( 'only' => 'knowing_god_faq_container' ),
        'params' => array(
		    array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Category', 'knowing-god' ),
				'param_name' => 'category',
				'value' => $faqcategories,
				'description' => esc_html__( 'Select Category Here.', 'knowing-god' )
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Question', 'knowing-god' ),
				'param_name' => 'question',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Question Here.', 'knowing-god' )
            ),
			array(
				'type' => 'textarea',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Description', 'knowing-god' ),
				'param_name' => 'description',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Description Here.', 'knowing-god' )
            ),
        )
    ) );

	/**
     * Knowing God Pathway Challenges
     */
	vc_map( array(
        'name' => esc_html__( 'Knowing God Pathway Challenges','knowing-god' ),
        'description' => esc_html__( 'This will display Pathway Challenges on the page', 'knowing-god' ),
        'base' => 'knowing_god_pathway_challenges',
		'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Number of Challenges','knowing-god' ),
				'param_name' => 'number_of_challenges',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter Number of Challenges to display.','knowing-god' ),
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Show card footer','knowing-god' ),
				'param_name' => 'show_card_footer',
				'value' => array(
					esc_html__( 'No', 'knowing-god' ) => 'no',
					esc_html__( 'Yes', 'knowing-god' ) => 'yes',
				),
				'description' => esc_html__( 'Show whether to show card footer.','knowing-god' ),
            ),
        ),
    ));

	// Knowing God 3 Easy Questions Banner.
	vc_map( array(
        'name' => esc_html__( 'Knowing God 3 Easy Questions Banner', 'knowing-god' ),
		'description' => esc_html__( 'It displays the banner', 'knowing-god' ),
        'base' => 'knowing_god_easy_questions_banner',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Banner Sub Heading', 'knowing-god' ),
				'param_name' => 'banner_sub_heading',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Button Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Banner Sub Heading Color', 'knowing-god' ),
				'param_name' => 'banner_sub_heading_color',
				'value' => esc_html__( '#ddd', 'knowing-god' ),
				'description' => esc_html__( 'Insert Sub Heading Color here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Banner Heading', 'knowing-god' ),
				'param_name' => 'banner_heading',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Banner Heading here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Banner Heading Color', 'knowing-god' ),
				'param_name' => 'banner_heading_color',
				'value' => esc_html__( '#00ab7e', 'knowing-god' ),
				'description' => esc_html__( 'Insert Heading Color here', 'knowing-god' ),
            ),
			array(
				'type' => 'attach_image',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Back Ground Image', 'knowing-god' ),
				'param_name' => 'banner_background',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select Image.', 'knowing-god' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Video URL', 'knowing-god' ),
				'param_name' => 'banner_video_url',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Enter Video URL here like: "https://www.youtube.com/embed/qA39SpcxOkE?rel=0&showinfo=0"', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Video Title', 'knowing-god' ),
				'param_name' => 'banner_video_title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Enter Video Title here ', 'knowing-god' ),
            ),
        )
    ));

	// Knowing God Banner.
	vc_map( array(
        'name' => esc_html__( 'Knowing God Banner', 'knowing-god' ),
		'description' => esc_html__( 'It displays the banner', 'knowing-god' ),
        'base' => 'knowing_god_banner',
        'class' => '',
        'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Banner Sub Heading', 'knowing-god' ),
				'param_name' => 'banner_sub_heading',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Button Title here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Banner Sub Heading Color', 'knowing-god' ),
				'param_name' => 'banner_sub_heading_color',
				'value' => esc_html__( '#ddd', 'knowing-god' ),
				'description' => esc_html__( 'Insert Sub Heading Color here', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Banner Heading', 'knowing-god' ),
				'param_name' => 'banner_heading',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Insert Banner Heading here', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Banner Heading Color', 'knowing-god' ),
				'param_name' => 'banner_heading_color',
				'value' => esc_html__( '#00ab7e', 'knowing-god' ),
				'description' => esc_html__( 'Insert Heading Color here', 'knowing-god' ),
            ),
			array(
				'type' => 'attach_image',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Back Ground Image', 'knowing-god' ),
				'param_name' => 'banner_background',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select Image.', 'knowing-god' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Video URL', 'knowing-god' ),
				'param_name' => 'banner_video_url',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Enter Video URL here like: "https://www.youtube.com/embed/qA39SpcxOkE?rel=0&showinfo=0"', 'knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Video Title', 'knowing-god' ),
				'param_name' => 'banner_video_title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Enter Video Title here ', 'knowing-god' ),
            ),
        )
    ));
	global $wpdb;
	/**
     * Knowing God Special Courses
     */
	$special_courses = array( esc_html__( 'Please select', 'knowing-god' ) => '0' );
	$terms = $wpdb->get_results( 'SELECT * FROM lmsseries WHERE course_type = "special"' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$special_courses[ $term->title ] = $term->id;
		}
	}
	vc_map( array(
        'name' => esc_html__( 'Knowing God Special Courses','knowing-god' ),
        'description' => esc_html__( 'This will display Special Courses on the page', 'knowing-god' ),
        'base' => 'knowing_god_special_courses',
		'category' => esc_html__( 'Knowing God Widgets', 'knowing-god' ),
		'icon' => $icon,
        'params' => array(
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Course','knowing-god' ),
				'param_name' => 'special_course',
				'value' => $special_courses,
				'description' => esc_html__( 'Enter title here.','knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title','knowing-god' ),
				'param_name' => 'title',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter title here.','knowing-god' ),
            ),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Colored Title', 'knowing-god' ),
				'param_name' => 'colored_title',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Enter the text you want to be in color.', 'knowing-god' ),
            ),
			array(
				'type' => 'colorpicker',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Colored Title Color', 'knowing-god' ),
				'param_name' => 'colored_title_color',
				'value' => esc_html__( '', 'knowing-god' ),
				'description' => esc_html__( 'Select Colored Title Color.', 'knowing-god' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Number of lessons','knowing-god' ),
				'param_name' => 'number_of_lessons',
				'value' => esc_html__( '','knowing-god' ),
				'description' => esc_html__( 'Enter Number of Lessons to display.','knowing-god' ),
            ),

        ),
    ));

}

// Functions Start
if ( ! function_exists( 'knowing_god_heading' ) ) :
	/**
	 * Prints Heading where it placed.
	 *
	 * @param array $atts for the title.
	 *
	 * @return string.
	 */
	function knowing_god_heading( $atts ){
		$a = shortcode_atts( array(
				'type' => 'h1',
				'heading_title' => esc_html__( 'Heading', 'knowing-god' ),
				'heading_align' => 'left',
				'knowing_god_heading_color' => '',
				'colored_text' => '',
				'colored_text_color' => '',
				'readmore_page' => '',
				'readmore_page_target' => '',
				'uniq' => rand(),
			), $atts );
		$readmore_page = '';
		if( '' !== $a['readmore_page'] ) {
			$readmore_page = get_permalink( $a['readmore_page'] );
		}
		ob_start();
		if ( ! empty( $a['knowing_god_heading_color'] ) ) :
			echo '<' . $a['type'] . ' style="color:' . $a['knowing_god_heading_color'] . '" class="' . $a['heading_align'] . '">';
		else :
			echo '<' . $a['type'] . ' class="' . $a['heading_align'] . '">';
		endif;
		?>
			<?php if ( '' !== $readmore_page ) : ?>
			<a href='<?php echo esc_url( $readmore_page ); ?>' target='<?php echo esc_attr( $a['readmore_page_target'] ); ?>' title="<?php echo esc_attr( $a['heading_title'] ); ?>">
			<?php endif; ?>
			<?php
			if ( ! empty( $a['colored_text'] ) && ! empty( $a['colored_text_color'] ) ) {
				echo str_replace( $a['colored_text'], '<span style="color:' . $a['colored_text_color'] . ';">' . $a['colored_text'] . '</span>', $a['heading_title'] );
			} else {
				echo $a['heading_title'];
			}			?>
			<?php if ( '' !== $readmore_page ) : ?>
			</a>
			<?php endif; ?>
		<?php echo '</' . $a['type'] . '>'; ?>
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_heading', 'knowing_god_heading' );
endif;

/**
 * Knowing God team
 *
 * Displays the static team.
 *
 * @since 1.0
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string Fully formatted string
 */
function knowing_god_ourteam( $atts, $content = null ) {
    $a = shortcode_atts( array(
                'name' => '',

				'colored_name' => '',
				'colored_name_color' => '',

				'alignment' => 'center',
                'role' => '',
				'description' => '',
				'container_columns' => 4,
				'readmore_page' => '',
				'display_type' => 'email',
                'image' => '',
                'facebook' => '',
                'twitter' => '',
                'instagram' => '',
                'linkedin' => '',
				'email' => '',
            ), $atts );
	if ( $a['image'] !== '' ) {
        $src  = wp_get_attachment_image_src($a['image'] ,'full' );
        if ( ! empty($src) ) {
         $src  = $src[0];
         $thumb_w = '750';
         $thumb_h = '450';
         $a['image'] = ct_resize($src, $thumb_w, $thumb_h, true);
        }
    } else {
        $a['image'] = get_template_directory_uri() . '/images/750x450.png';
    }
	$readmore_page = '';
	if( ! empty( $a['readmore_page'] ) ) {
		$readmore_page = get_permalink( $a['readmore_page'] );
	}
	$mb = 12 / $a['container_columns'];
    ob_start();
    ?>
	<div class="col-sm-6 col-md-6 col-lg-<?php echo esc_attr( $mb ); ?> mb-4">
		<div class="card h-100 text-center">
			<img class="card-img-top" src="<?php echo esc_url( $a['image'] ); ?>" alt="<?php echo esc_attr( $a['name'] ); ?>" title="<?php echo esc_attr( $a['name'] ); ?>">
			<div class="card-body <?php echo esc_attr( $a['alignment'] ); ?>">
				<h4 class="card-title">
				<?php if ( '' !== $readmore_page ) : ?>
				<a href='<?php echo esc_url( $readmore_page ); ?>' title="<?php echo esc_attr( $a['name'] ); ?>">
				<?php endif; ?>
				<?php
				if ( ! empty( $a['colored_name'] ) && ! empty( $a['colored_name_color'] ) ) {
					echo str_replace( $a['colored_name'], '<span style="color:' . $a['colored_name_color'] . ';">' . $a['colored_name'] . '</span>', $a['name'] );
				} else {
					echo esc_attr( $a['name'] );
				}
				?>
				<?php if ( '' !== $readmore_page ) : ?>
				</a>
				<?php endif; ?>
				</h4>
				<?php if ( ! empty( $a['role'] ) ) : ?>
				<h6 class="card-subtitle mb-2 text-muted"><?php echo esc_attr( $a['role'] ); ?></h6>
				<?php endif; ?>
				<?php if ( ! empty( $a['description'] ) ) : ?>
				<p class="card-text"><?php echo esc_attr( $a['description'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( 'email' === $a['display_type'] && ! empty( $a['email'] ) ) : ?>
			<div class="card-footer kg-footer-email">
			<a href="mailto:<?php echo esc_attr( $a['email'] ); ?>" title="<?php echo esc_attr( $a['email'] ); ?>">
			<?php echo esc_attr( $a['email'] ); ?>
			</a>
			</div>
			<?php else : ?>
			<?php
			if ( false !== filter_var( $a['facebook'], FILTER_VALIDATE_URL )
				|| ( false !== filter_var( $a['twitter'], FILTER_VALIDATE_URL ) )
				|| ( false !== filter_var( $a['instagram'], FILTER_VALIDATE_URL ) )
				|| ( false !== filter_var( $a['linkedin'], FILTER_VALIDATE_URL ) )
				) { ?>
			<div class="card-footer kg-footer-social">
				<?php if ( false !== filter_var( $a['facebook'], FILTER_VALIDATE_URL ) ) { ?>
				<a href='<?php echo esc_url( $a['facebook'] ); ?>' target='_blank' title='<?php esc_html_e( 'Facebook', 'digi-portal' ); ?>'><i class='fa fa-facebook'></i></a>
				<?php } ?>
				<?php if ( false !== filter_var( $a['twitter'], FILTER_VALIDATE_URL ) ) { ?>
				<a href='<?php echo esc_url( $a['twitter'] ); ?>' target='_blank' title='<?php esc_html_e( 'Twitter', 'digi-portal' ); ?>'><i class='fa fa-twitter'></i></a>
				<?php } ?>
				<?php if ( false !== filter_var( $a['instagram'], FILTER_VALIDATE_URL ) ) { ?>
				<a href='<?php echo esc_url( $a['instagram'] ); ?>' target='_blank' title='<?php esc_html_e( 'Instagram', 'digi-portal' ); ?>'><i class='fa fa-instagram '></i></a>
				<?php } ?>
				<?php if ( false !== filter_var( $a['linkedin'], FILTER_VALIDATE_URL ) ) { ?>
				<a href='<?php echo esc_url( $a['linkedin'] ); ?>' target='_blank' title='<?php esc_html_e( 'Linkedin', 'digi-portal' ); ?>'><i class='fa fa-linkedin'></i></a>
				<?php } ?>
			</div>
			<?php }
			endif;
			?>
		</div>
	</div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'knowing_god_ourteam', 'knowing_god_ourteam' );

/**
 * Knowing God ourteam container
 *
 * Displays the static ourteam container and ourteam if any using short code.
 *
 * @since 1.0
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string Fully formatted string
 */
function knowing_god_ourteam_container( $atts, $content = null ) {
    ob_start();
    ?>
    <!-- OUR TEAM -->
	<div class="row">
		<?php echo do_shortcode( $content ); ?>
	</div>
    <!-- /OUR TEAM -->
    <?php
    return ob_get_clean();
}
add_shortcode( 'knowing_god_ourteam_container', 'knowing_god_ourteam_container' );

if( ! function_exists( 'knowing_god_partners_single' ) ) :
	/**
	 * Prints testimonial where it placed.
	 *
	 * @param array $atts for the partner_img, partner_name, partner_read_more.
	 *
	 * @return string.
	 */
	function knowing_god_partners_single( $atts, $content = null ) { // New function parameter $content is added!
		$a = shortcode_atts( array(
					'partner_name' => '',
					'partner_img' => '',
					'partner_read_more' => '',
				), $atts );
		$src  = wp_get_attachment_image_src( $a['partner_img'] ,'full' );
		if ( ! empty( $src ) ) {
		 $src  = $src[0];
		 $thumb_w = '700';
		 $thumb_h = '400';
		 $src = ct_resize( $src, $thumb_w, $thumb_h, true );
		}
		$readmore_page = '';
		if( '' !== $a['partner_read_more'] ) {
			$readmore_page = get_permalink( $a['partner_read_more'] );
		}
		ob_start();
		?>
		<div>
			<?php if ( '' !== $readmore_page ) : ?>
			<a href="<?php echo esc_url( $readmore_page ); ?>" title="<?php echo esc_attr( $a['partner_name'] ); ?>">
			<?php endif; ?>
			<img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $a['partner_name'] ); ?>" title="<?php echo esc_attr( $a['partner_name'] ); ?>">
			<?php if ( '' !== $readmore_page ) : ?>
			</a>
			<?php endif; ?>
		</div>
		<?php
		$result = ob_get_clean();
		return $result;
	}
	add_shortcode( 'knowing_god_partners_single', 'knowing_god_partners_single' );
endif;

if ( ! function_exists( 'knowing_god_partners_container' ) ) :
	/**
	 * Prints testimonial container where it placed.
	 *
	 * @param array $atts for the testimonial_img, testimonial_content, testimonial_name, testimonial_livesin.
	 *
	 * @return string.
	 */
	function knowing_god_partners_container( $atts, $content = null ) {
		ob_start();
		?>
		<!--Partners-->
		<div class="ap-partners partners-pad">
			<div class="container">
				<div class="ap-partner-slide ">
					<?php echo do_shortcode( $content ); ?>
				</div>
			</div>
		</div>
		<!--/Partners-->
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_partners_container', 'knowing_god_partners_container' );
endif;

if ( ! function_exists( 'knowing_god_button' ) ) :
	/**
	 * Prints Button where it placed.
	 *
	 * @param array $atts for the title.
	 *
	 * @return string.
	 */
	function knowing_god_button( $atts ){
		$a = shortcode_atts( array(
				'downloadbutton_type' => 'default',
				'downloadbutton_title' => 'DOWNLOAD BROCHURE',
				'downloadbutton_color' => '',
				'downloadbutton_link' => '',
				'downloadbutton_link_target' => '_parent',
			), $atts );
		$style = '';
		if ( ! empty( $a['downloadbutton_color'] ) ) {
			$style = ' style="color:' . $a['downloadbutton_color'] . '"';
		}
		$class = 'btn btn-info';
		if ( 'gray' === $a['downloadbutton_type'] ) {
			$class = 'btn btn-lg btn-secondary btn-block';
		}
		ob_start();
		?>
		<?php if( '' !== $a['downloadbutton_link'] ) : ?>
		<?php if ( 'modal' === $a['downloadbutton_link_target'] ) : ?>
		<a href='<?php echo esc_url( $a['downloadbutton_link'] ); ?>' class='<?php echo esc_attr( $class ); ?>' data-toggle="modal" <?php echo $style; ?>>
		<?php else : ?>
		<a href='<?php echo esc_url( $a['downloadbutton_link'] ); ?>' class='<?php echo esc_attr( $class ); ?>' target='<?php echo esc_attr( $a['downloadbutton_link_target'] ); ?>' <?php echo $style; ?>>
		<?php endif; ?>

		<?php echo esc_attr( $a['downloadbutton_title'] ); ?></a>
		<?php else : ?>
		<button class='<?php echo esc_attr( $class ); ?>' <?php echo $style; ?>><?php echo esc_attr( $a['downloadbutton_title'] ); ?></button>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_button', 'knowing_god_button' );
endif;

if ( ! function_exists( 'knowing_god_about' ) ) :
	/**
	 * Prints Button where it placed.
	 *
	 * @param array $atts for the title.
	 *
	 * @return string.
	 */
	function knowing_god_about( $atts ){
		$a = shortcode_atts( array(
				'image' => '',
				'title' => 'DOWNLOAD',
				'title_colored' => '',
				'title_colored_color' => '',
				'description' => '',
				'downloadbutton_link' => '',
				'downloadbutton_link_title' => '',
				'downloadbutton_link_target' => '_parent',
			), $atts );
		$readmore_page = '';
		if( '' !== $a['downloadbutton_link'] ) {
			$readmore_page = get_permalink( $a['downloadbutton_link'] );
		}
		if ( $a['image'] !== '' ) {
			$src  = wp_get_attachment_image_src($a['image'] ,'full' );
			if ( ! empty( $src ) ) {
			 $src  = $src[0];
			 $thumb_w = '750';
			 $thumb_h = '400';
			 $a['image'] = ct_resize($src, $thumb_w, $thumb_h, true);
			}
		} else {
			$a['image'] = get_template_directory_uri() . '/images/750x450.png';
		}
		ob_start();
		?>
		<div class="card mb-4 kg-image-text">
			  <div class="card-body">
				  <div class="row">
					  <div class="col-lg-6">
						  <?php if ( '' !== $a['downloadbutton_link'] ) : ?>
						  <a href="<?php echo esc_url( $a['downloadbutton_link'] ); ?>" target='<?php echo esc_attr( $a['downloadbutton_link_target'] ); ?>' title="<?php echo esc_attr( $a['title'] ); ?>">
						  <?php endif; ?>
							  <img class="img-fluid rounded" src="<?php echo esc_url( $a['image'] ); ?>" alt="<?php echo esc_attr( strip_tags( $a['title'] ) ); ?>" title="<?php echo esc_attr( strip_tags( $a['title'] ) ); ?>">
						  <?php if ( '' !== $a['downloadbutton_link'] ) : ?>
						  </a>
						  <?php endif; ?>
					  </div>
					  <div class="col-lg-6 mt-sm-3">
						  <h2 class="card-title">
						  <?php
							if ( ! empty( $a['title_colored'] ) && ! empty( $a['title_colored_color'] ) ) {
								echo str_replace( $a['title_colored'], '<span style="color:' . $a['title_colored_color'] . ';">' . $a['title_colored'] . '</span>', $a['title'] );
							} else {
								echo $a['title'];
							}
							?>
							</h2>
						  <p class="card-text"><?php echo esc_attr( $a['description'] ); ?></p>
						  <?php if( '' !== $a['downloadbutton_link'] ) : ?>
						  <a href="<?php echo esc_url( $a['downloadbutton_link'] ); ?>" class="btn btn-lg btn-success" target='<?php echo esc_attr( $a['downloadbutton_link_target'] ); ?>' title="<?php echo esc_attr( $a['downloadbutton_link_title'] ); ?>"><?php echo esc_attr( $a['downloadbutton_link_title'] ); ?></a>
						  <?php endif; ?>
					  </div>
				  </div>
			  </div>
		 </div>
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_about', 'knowing_god_about' );
endif;

if ( ! function_exists( 'knowing_god_element_holder' ) ) :
	/**
	 * Prints testimonial container where it placed.
	 *
	 * @param array $atts for the testimonial_img, testimonial_content, testimonial_name, testimonial_livesin.
	 *
	 * @return string.
	 */
	function knowing_god_element_holder( $atts, $content = null ) {
		ob_start();
		?>
		<div class="row mt-4">
			<?php echo do_shortcode( $content ); ?>
		</div>
		<!--/Partners-->
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_element_holder', 'knowing_god_element_holder' );
endif;

if ( ! function_exists( 'knowing_god_lms' ) ) :
	/**
	 * Prints Button where it placed.
	 *
	 * @param array $atts for the title.
	 *
	 * @return string.
	 */
	function knowing_god_lms( $atts ){
		$a = shortcode_atts( array(
				'image' => '',
				'columns' => 3,
				'alignment' => 'center',
				'title' => 'DOWNLOAD',
				'title_color' => '',
				'video_link' => '',
				'title_image' => '',
				'sub_title' => '',
				'author' => '',
				'description' => '',
				'readmore_page' => '',
				'available_formats' => '',
				'audio_file' => '',
			), $atts );

		$readmore_page = '';
		if ( ! empty( $a['readmore_page'] ) ) {
			$readmore_page = get_permalink( $a['readmore_page'] );
		}
		if ( $a['image'] !== '' ) {
			$src  = wp_get_attachment_image_src( $a['image'] ,'full' );
			if ( ! empty( $src ) ) {
			 $src  = $src[0];
			 $thumb_w = '750';
			 $thumb_h = '450';
			 $a['image'] = ct_resize($src, $thumb_w, $thumb_h, true);
			}
		} else {
			$a['image'] = get_template_directory_uri() . '/images/750x450.png';
		}
		$title_image = '';
		if ( $a['title_image'] !== '' ) {
			$src  = wp_get_attachment_image_src( $a['title_image'] ,'full' );
			if ( ! empty( $src ) ) {
			 $src  = $src[0];
			 $thumb_w = '30';
			 $thumb_h = '30';
			 $title_image = ct_resize($src, $thumb_w, $thumb_h, true);
			}
		}
		$audio_file = '';
		if ( ! empty( $a['audio_file'] ) ) {
			$audio_file = wp_get_attachment_url( $a['audio_file'] );
		}
		$title_color = '';
		if ( ! empty( $a['title_color'] ) ) {
			$title_color = ' style="color:' . $a['title_color'] . '"';
		}
		ob_start();
		?>
		<div class="col-lg-<?php echo esc_attr( $a['columns'] ); ?> mb-4">
          <div class="card h-100 text-center <?php echo esc_attr( $a['alignment'] ); ?>">
            <img class="card-img-top" src="<?php echo esc_url( $a['image'] ); ?>" alt="<?php echo esc_url( $a['title'] ); ?>" title="<?php echo esc_url( $a['title'] ); ?>">
            <div class="card-body">
              <h4 class="card-title">
			  <?php if ( ! empty( $readmore_page ) ) : ?>
			  <a style="text-decoration:none;" href="<?php echo esc_url( $readmore_page ); ?>" <?php echo esc_attr( $title_color ); ?>>
			  <?php elseif ( ! empty( $a['video_link'] ) ) : ?>
			  <a style="text-decoration:none;" data-fancybox="" href="<?php echo esc_url( $a['video_link'] ); ?>" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen" <?php echo esc_attr( $title_color ); ?>>
               <i class="fa fa-play" aria-hidden="true"></i>
			   <?php endif; ?>

			   <?php if ( ! empty( $title_image ) ) : ?>
			   <img class="align-self-center pb-1 mr-1" src="<?php echo esc_url( $title_image ); ?>" height="30" width="30">
			   <?php endif; ?>

			   <?php echo $a['title']; ?>

			   <?php if ( ! empty( $a['sub_title'] ) ) : ?>
			   <span style="font-size:1rem; color:#666;"><?php echo esc_attr( $a['sub_title'] ); ?></span>
			   <?php endif; ?>

                <?php if ( ! empty( $readmore_page ) || ! empty( $audio_file ) ) : ?>
				</a>
				<?php endif; ?>
				</h4>

              <?php if ( ! empty( $a['author'] ) ) : ?>
			  <h6 class="card-subtitle mb-2 text-muted"><?php echo esc_attr( $a['author'] ); ?></h6>
			  <?php endif; ?>
              <p class="card-text"><?php echo esc_attr( $a['description'] ); ?></p>
            </div>
            <?php
			if ( ! empty( $a['available_formats'] ) ) :
				$available_formats = explode( ',', $a['available_formats'] );
				if ( ! empty( $available_formats ) ) {
				?>
				<div class="card-footer kg-card-footer">
					<?php foreach( $available_formats as $format ) : ?>
					<a class="mr-2" href="#"><i class="<?php echo esc_attr( $format ); ?>"></i></a>
					<?php endforeach; ?>
				</div>
				<?php
				}
			endif; ?>
            <?php if( ! empty( $audio_file ) ) : ?>
			<div class="card-footer kg-audio">
                <audio controls="">
                  <source src="<?php echo $audio_file; ?>" type="audio/mpeg">
                    <?php esc_html_e( 'Your browser does not support the audio element.', 'knowing-god' ); ?>
                </audio>
            </div>
			<?php endif; ?>
          </div>
        </div>
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_lms', 'knowing_god_lms' );
endif;

/**
 * Knowing God ourteam container
 *
 * Displays the static ourteam container and ourteam if any using short code.
 *
 * @since 1.0
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string Fully formatted string
 */
function knowing_god_engage_container( $atts, $content = null ) {
    ob_start();
    ?>
    <!-- OUR TEAM -->
	<div class="row">
		<?php echo do_shortcode( $content ); ?>
	</div>
    <!-- /OUR TEAM -->
    <?php
    return ob_get_clean();
}
add_shortcode( 'knowing_god_engage_container', 'knowing_god_engage_container' );

if( ! function_exists( 'knowing_god_engage_single' ) ) :
	/**
	 * Prints testimonial where it placed.
	 *
	 * @param array $atts for the partner_img, partner_name, partner_read_more.
	 *
	 * @return string.
	 */
	function knowing_god_engage_single( $atts, $content = null ) { // New function parameter $content is added!
		$a = shortcode_atts( array(
					'title' => '',
					'description' => '',
					'read_more' => '',
					'read_more_title' => esc_html__( 'Learn More', 'knowing-god' ),
				), $atts );
		$readmore_page = '';
		if( '' !== $a['read_more'] ) {
			$readmore_page = get_permalink( $a['read_more'] );
		}
		ob_start();
		?>
		<div class="col-lg-4 col-md-6 mb-4">
          <div class="card h-100">
            <h4 class="card-header"><?php echo esc_attr( $a['title'] ); ?></h4>
            <div class="card-body">
              <p class="card-text"><?php echo esc_attr( $a['description'] ); ?></p>
            </div>
            <?php if( ! empty( $readmore_page ) ) : ?>
			<div class="card-footer kg-footer-read">
              <a href="<?php echo esc_url( $readmore_page ); ?>" class="btn btn-primary"><?php echo esc_attr( $a['read_more_title'] ); ?></a>
            </div>
			<?php endif; ?>
          </div>
        </div>
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_engage_single', 'knowing_god_engage_single' );
endif;

if( ! function_exists( 'knowing_god_image_text' ) ) :
	/**
	 * Prints testimonial where it placed.
	 *
	 * @param array $atts for the partner_img, partner_name, partner_read_more.
	 *
	 * @return string.
	 */
	function knowing_god_image_text( $atts, $content = null ) { // New function parameter $content is added!
		$a = shortcode_atts( array(
					'title' => '',
					'image' => '',
					'columns' => '2',
					'read_more' => '',
				), $atts );
		$readmore_page = '';
		if( '' !== $a['read_more'] ) {
			$readmore_page = get_permalink( $a['read_more'] );
		}
		$title_image = get_template_directory_uri() . '/images/500x300.png';
		if ( $a['image'] !== '' ) {
			$src  = wp_get_attachment_image_src( $a['image'] ,'full' );
			if ( ! empty( $src ) ) {
			 $src  = $src[0];
			 $thumb_w = '500';
			 $thumb_h = '300';
			 $title_image = ct_resize($src, $thumb_w, $thumb_h, true);
			}
		}
		ob_start();
		?>
		<div class="col-lg-<?php echo esc_attr( $a['columns'] ); ?> col-sm-4 mb-4 text-center">
			<img class="img-fluid" src="<?php echo esc_url( $title_image ); ?>" alt="<?php echo esc_attr( $a['title'] ); ?>" title="<?php echo esc_attr( $a['title'] ); ?>">
			<p>
				<?php if( ! empty( $readmore_page ) ) : ?>
				<a href="<?php echo esc_url( $readmore_page ); ?>">
				<?php endif; ?>
				<?php echo esc_attr( $a['title'] ); ?>
				<?php if( ! empty( $readmore_page ) ) : ?>
				</a>
				<?php endif; ?>
			</p>
        </div>
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_image_text', 'knowing_god_image_text' );
endif;

if( ! function_exists( 'knowing_god_breadcrumb' ) ) :
	/**
	 * Prints testimonial where it placed.
	 *
	 * @param array $atts for the partner_img, partner_name, partner_read_more.
	 *
	 * @return string.
	 */
	function knowing_god_breadcrumb( $atts, $content = null ) { // New function parameter $content is added!
		$a = shortcode_atts( array(
					'title' => '',
					'title_colored' => '',
					'title_colored_color' => '',

					'link_1' => '',
					'link_1_colored' => '',
					'link_1_colored_color' => '',

					'link_2' => '',
					'link_2_colored' => '',
					'link_2_colored_color' => '',

					'link_3' => '',
					'link_3_colored' => '',
					'link_3_colored_color' => '',

					'link_4' => '',
					'link_4_colored' => '',
					'link_4_colored_color' => '',

					'link_5' => '',
					'link_5_colored' => '',
					'link_5_colored_color' => '',

                    'link_6' => '',
					'link_6_colored' => '',
					'link_6_colored_color' => '',
				), $atts );
		$show_breadcrumb = false;
		for( $i = 1; $i <= 6; $i++ ) {
			$link = $a[ 'link_' . $i ];
			if ( ! empty( $link ) ) {
				$parts = explode( '|', $link );
				if ( ! empty( $parts ) ) {
					foreach( $parts as $part ) {
						if ( $part != '|' ) {
							$show_breadcrumb = true;
						}
					}
				}
			}

		}

		ob_start();
		?>
		<?php
		if ( ! empty( $a['title'] ) ) :
			?>
			<h1 class="mt-4 mb-3">
			<?php
			if ( ! empty( $a['title_colored'] ) && ! empty( $a['title_colored'] ) ) {
				echo str_replace( $a['title_colored'], '<span style="color:' . $a['title_colored_color'] . ';">' . $a['title_colored'] . '</span>', $a['title'] );
			} else {
				echo $a['title'];
			}
			?>
			</h1>
		<?php endif; ?>
		<?php if ( true === $show_breadcrumb ) : ?>
			<ol class="breadcrumb">
			<li class="breadcrumb-item">
			<?php
			for( $i = 1; $i <= 6; $i++ ) {
				$link = $a[ 'link_' . $i ];
				if ( ! empty( $link ) ) {
					$parts = explode( '|', $link );
					if ( ! empty( $parts ) ) {
						$title = esc_html__( 'Click here', 'knowing-god' );
						$url = '';
						foreach( $parts as $part ) {
							if ( $part != '|' ) {
								if ( 'title:' === substr( $part, 0, 6 ) ) {
									$title = urldecode( substr( $part, 6 ) );
								}
								if ( 'url:' === substr( $part, 0, 4 ) ) {
									$url = urldecode( substr( $part, 4 ) );
								}
							}
						}
						if ( 1 == $i ) { ?>
						<strong>
						<?php
						if ( ! empty( $a['link_'.$i.'_colored'] ) && ! empty( $a['link_'.$i.'_colored_color'] ) ) {
							echo str_replace( $a['link_' . $i . '_colored'], '<span style="color:' . $a['link_' . $i . '_colored_color'] . ';">' . $a['link_' . $i . '_colored'] . '</span>', $title );
						} else {
							echo $title;
						}
						?>
						</strong>
						<?php
						} else { ?>
						<?php echo esc_html__( '&#8594;', 'knowing-god' ); ?>
						<?php if ( ! empty( $url ) ) : ?>
						<a  href="<?php echo esc_url( $url ); ?>">
						<?php endif; ?>
						<?php
						if ( ! empty( $a['link_'.$i.'_colored'] ) && ! empty( $a['link_'.$i.'_colored_color'] ) ) {
							echo str_replace( $a['link_' . $i . '_colored'], '<span style="color:' . $a['link_' . $i . '_colored_color'] . ';">' . $a['link_' . $i . '_colored'] . '</span>', $title );
						} else {
							echo $title;
						}
						?>
						<?php if ( ! empty( $url ) ) : ?>
						</a>
						<?php endif; ?>
						<?php
						}
					}
				}

			}
			?>
			</li>
			</ol>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_breadcrumb', 'knowing_god_breadcrumb' );
endif;

if( ! function_exists( 'knowing_god_video_popup' ) ) :
	/**
	 * Prints testimonial where it placed.
	 *
	 * @param array $atts for the partner_img, partner_name, partner_read_more.
	 *
	 * @return string.
	 */
	function knowing_god_video_popup( $atts, $content = null ) { // New function parameter $content is added!
		$a = shortcode_atts( array(
					'image' => '',
					'video_link' => '',
				), $atts );

		$title_image = get_template_directory_uri() . '/images/800x450.png';
		if ( $a['image'] !== '' ) {
			$src  = wp_get_attachment_image_src( $a['image'] ,'full' );
			if ( ! empty( $src ) ) {
			 $src  = $src[0];
			 $thumb_w = '800';
			 $thumb_h = '450';
			 $title_image = ct_resize($src, $thumb_w, $thumb_h, true);
			}
		}
		ob_start();
		?>
		<div class="kg-video-popup">
			<div class="kg-bg-image">
				<img src="<?php echo esc_url( $title_image ); ?>" alt="" class="img-fluid">
			</div>
			<!--modal popup video-->
			<a data-fancybox="" href="<?php echo esc_url( $a['video_link'] ); ?>" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen">
				<div class="kg-play">
					<img src="<?php echo get_template_directory_uri(); ?>/images/play.png" alt="">
				</div>
			</a>
		</div>
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_video_popup', 'knowing_god_video_popup' );
endif;

if ( ! function_exists( 'knowing_god_forgotpassword' ) ) :
	/**
	 * [knowing_god_forgotpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function knowing_god_forgotpassword( $atts ){

		if ( is_user_logged_in() ) {
			echo '<meta http-equiv="refresh" content="0;URL=\'' . knowing_god_urls( 'user_account' ) .  '\'" />';
		} else {
			include_once( KNOWING_GOD_PLUGIN_PATH .  'includes/pages/forgotpassword.php' );
		}
	}
	add_shortcode( 'knowing_god_forgotpassword', 'knowing_god_forgotpassword' );
endif;



if ( ! function_exists( 'knowing_god_resetpassword' ) ) :
	/**
	 * [knowing_god_resetpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function knowing_god_resetpassword( $atts ){

		if ( is_user_logged_in() ) {
			echo '<meta http-equiv="refresh" content="0;URL=\'' . knowing_god_urls( 'user_account' ) . '\'" />';
		} else {
			include_once( KNOWING_GOD_PLUGIN_PATH. 'includes/pages/resetpassword.php' );
		}

	}
	add_shortcode( 'knowing_god_resetpassword', 'knowing_god_resetpassword' );
endif;

if ( ! function_exists( 'knowing_god_my_profile' ) ) :
	/**
	 * [knowing_god_forgotpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function knowing_god_my_profile( $atts ){

		if ( ! is_user_logged_in() ) {
			echo '<meta http-equiv="refresh" content="0;URL=\'' . knowing_god_urls( 'login' ) .  '\'" />';
		} else {
			include_once( KNOWING_GOD_PLUGIN_PATH .  'includes/pages/my-profile.php' );
		}
	}
	add_shortcode( 'knowing_god_my_profile', 'knowing_god_my_profile' );
endif;

if ( ! function_exists( 'knowing_god_wp_sync' ) ) :
	/**
	 * [knowing_god_forgotpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function knowing_god_wp_sync( $atts ){
		global $wpdb;
		if ( is_user_logged_in() ) {
			$user_details = (array) wp_get_current_user();

			if ( ! empty( $user_details ) ) {
				$data = $user_details['data'];
				$roles = $user_details['roles'];
				$username = $data->user_login;
				$email = $data->user_email;
				$user_id = $data->ID;
				$query = sprintf( "SELECT * FROM users WHERE ( username = '%s' OR email = '%s' )", $username, $email );
				$records = $wpdb->get_results( $query );

				if ( empty( $records ) ) {
					$role_id = ROLE_SUBSCRIBER;
					if( ! empty( $roles ) ) {
						foreach( $roles as $role ) {
							if ( $role === 'administrator' ) {
								$role_id = ROLE_OWNER;
							} elseif ( $role === 'administrator' ) {
								$role_id = ROLE_ADMIN;
							}
						}
					}
					$lms_user = array(
						'name' => $data->display_name,
						'first_name' => get_user_meta( $data->ID, 'first_name', true ),
						'last_name' => get_user_meta( $data->ID, 'last_name', true ),
						'email' => $email,
						'password' => $data->user_pass,
						'role_id' => $role_id,
						'login_enabled' => 1,
						'username' => $data->user_login,
						'slug' => sanitize_title( $data->display_name ),
						'phone' => '',
						'phone_code' => '',
						'created_at' => date( 'Y-m-d H:i:s' ),
						'updated_at' => date( 'Y-m-d H:i:s' ),
						'wp_user_id' => $data->ID,
						'confirmed' => '1',
						'status' => 'activated',
					);
					$wpdb->insert( 'users', $lms_user );
					$user_id = $wpdb->insert_id;
					if ( $user_id > 0 ) {
						$wpdb->insert( 'role_user', array(
							'user_id' => $user_id,
							'role_id' => $role_id,
						) );
					$redirect_to = knowing_god_urls( 'lms' ) . 'login/' . base64_encode( $user_id );
					wp_safe_redirect( $redirect_to );
					} else {
						$redirect_to = knowing_god_urls( 'login' );
						wp_safe_redirect( $redirect_to );
					}
				} else {
					$user_id = $records[0]->id;
					$redirect_to = knowing_god_urls( 'lms' ) . 'login/' . base64_encode( $user_id );
					wp_safe_redirect( $redirect_to );
				}
			} else {
				$redirect_to = knowing_god_urls( 'login' );
				wp_safe_redirect( $redirect_to );
			}
		} else {
			$redirect_to = knowing_god_urls( 'login' );
			wp_safe_redirect( $redirect_to );
		}
	}
	add_shortcode( 'knowing_god_wp_sync', 'knowing_god_wp_sync' );
endif;

if ( ! function_exists( 'knowing_god_wp_logout' ) ) :
	/**
	 * [knowing_god_forgotpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function knowing_god_wp_logout( $atts ) {
		wp_logout();
		$redirect_to = knowing_god_urls( 'login' );
		wp_safe_redirect( $redirect_to );
	}
	add_shortcode( 'knowing_god_wp_logout', 'knowing_god_wp_logout' );
endif;

/**
 * Knowing God ourteam container
 *
 * Displays the static ourteam container and ourteam if any using short code.
 *
 * @since 1.0
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string Fully formatted string
 */
function knowing_god_faq_container( $atts, $content = null ) {
    $faqcategories = array();
	$terms = get_terms( 'faqcategory', array( 'hide_empty' => false ) );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$faqcategories[ $term->name ] = $term->term_id;
		}
	}

	$a = explode( '[', $content );
	$shortcodes = array();
	foreach( $a as $key => $val ) {
		if ( ! empty( $val ) ) {
			$shortcodes[] = '[' . $val;
		}
	}

	ob_start();
    ?>
    <section>
        <div class="container">
            <div class="row knowing-god-faqs">
                <div class="col-lg-4 col-md-5 col-sm-6">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php
						$i = 0;
						foreach( $faqcategories as $title => $id ) :
						$active = '';
						if ( $i++ == 0 ) {
							$active = ' active';
						}
						?>
						 <li class="nav-item">
                            <a class="nav-link<?php echo $active; ?>" data-toggle="tab" href="#kg-tab<?php echo esc_attr( $id ); ?>" <?php if ( $active != '' ) echo ' area-expanded="true"'; ?>>
                                <div class="kg-tab-menu"> <span class="fa fa-book"></span> <span class="title hidden-xs"><?php echo esc_attr( $title ); ?></span> </div>
                            </a>
                        </li>
						<?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-lg-8 col-md-7 col-sm-6">
                    <div class="tab-content">
                        <?php
						$i = 0;
						foreach( $faqcategories as $title => $id ) :
						$active = '';
						if ( $i++ == 0 ) {
							$active = ' active show';
						}
						?>
						<div id="kg-tab<?php echo $id; ?>" class="tab-pane fade<?php echo $active; ?>">
                            <div class="kg-faqs top-pad" id="accordation<?php echo $id; ?>">
							<?php
							foreach( $shortcodes as $shortcode ) {
								$parsed = shortcode_parse_atts( $shortcode );
								if ( ! empty( $parsed['category'] ) && $parsed['category'] == $id ) {
									echo do_shortcode( $shortcode );
								}
							}														?>
							</div>
						</div>
						<?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode( 'knowing_god_faq_container', 'knowing_god_faq_container' );

if ( ! function_exists( 'knowing_god_faq' ) ) :
	/**
	 * [knowing_god_forgotpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function knowing_god_faq( $atts, $content = null ) {
		$a = shortcode_atts( array(
					'category' => '',
					'question' => '',
					'description' => '',
					'qid' => rand(),
				), $atts );
		ob_start();
		?>
		<!-- Single Item -->
		<div class="card">
            <div class="card-header"> <h4><b><a class="collapsed" data-toggle="collapse" data-parent="#accordation<?php echo $a['category']; ?>" href="#collapse<?php echo $a['qid']; ?>" aria-expanded="true"><?php echo $a['question']; ?></a></b></h4> </div>
			<div id="collapse<?php echo $a['qid']; ?>" class="panel-collapse collapse" aria-expanded="true" style="height: 0px;">
				<div class="card-body">
					<p><?php echo $a['description']; ?></p>
				</div>
			</div>
		</div>
		<!-- Single Item -->
		<?php
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_faq', 'knowing_god_faq' );
endif;

if ( ! function_exists( 'knowing_god_wp_login_redirect' ) ) :
	/**
	 * [knowing_god_forgotpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function knowing_god_wp_login_redirect(){
		global $wpdb;
		$wp_user_id = base64_decode( $_GET['wp_user_id'] );
		$redirect_to = base64_decode( $_GET['redirect_to'] );
		$user = get_user_by( 'ID', $wp_user_id );
		if ( ! empty( $user ) ) {
			$username = $user->username;
			wp_set_current_user($wp_user_id, $username);
			wp_set_auth_cookie($wp_user_id);
			do_action('wp_login', $username);
			wp_safe_redirect( $redirect_to );
		} else {
			$query = "SELECT * FROM users ORDER BY name ASC"; // Laravel Users table
			$data = $wpdb->get_results( $query );
			if ( ! empty( $data ) ) {
				$data = $data[0];
				$user_args = array(
					'user_login'      => isset( $data['username'] ) ? $data['username'] : '',
					'user_pass'       => isset( $data['password'] )  ? $data['password']  : '',
					'user_email'      => isset( $data['email'] ) ? $data['email'] : '',
					'first_name'      => isset( $data['first_name'] ) ? $data['first_name'] : '',
					'last_name'       => isset( $data['last_name'] )  ? $data['last_name']  : '',
					'user_nicename'		=> isset( $data['name'] ) ? $data['name'] : '',
					'display_name' 		=> $data['first_name'] . ' ' . $data['last_name'],
					'nickname' 			=> $data['username'],
					'user_registered'	=> date( 'Y-m-d H:i:s' ),
					);
				$user_id = wp_insert_user( $user_args ); // Insert into WordPress Users table.

				$user = new WP_User( $user_id );
				$user->set_role( 'subscriber' ); // Set Default Role.

				update_user_meta( absint( $user_id ), 'mobile_countrycode', wp_kses_post( $data['phone_code'] ) );
				update_user_meta( absint( $user_id ), 'mobile', wp_kses_post( $data['phone'] ) );
				wp_safe_redirect( $redirect_to );
			} else {
				wp_safe_redirect( $redirect_to );
			}

		}
		die('Iam here');
	}
	add_shortcode( 'knowing_god_wp_login_redirect', 'knowing_god_wp_login_redirect' );
endif;

if ( ! function_exists( 'knowing_god_sync_laravel_users' ) ) :
	/**
	 * [knowing_god_forgotpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function knowing_god_sync_laravel_users(){
		global $wpdb;
		$user_name = base64_decode( $_GET['user'] );
		$user_name_c = isset($_COOKIE['user']) ? base64_decode($_COOKIE['user']) : '';
		if ( empty( $user_name_c ) ) {
			$redirect_to = knowing_god_urls( 'login' );
			wp_safe_redirect( $redirect_to );
		} elseif ( $user_name != $user_name_c ) {
			$redirect_to = knowing_god_urls( 'login' );
			wp_safe_redirect( $redirect_to );
		}

		$user = get_user_by( 'email', $user_name );
		if ( empty( $user ) ) {
			$user = get_user_by( 'login', $user_name );
		}

		$redirect_to = knowing_god_urls( 'my-account' );
		if ( ! empty( $user ) ) {
			$username = $user->username;
			$wp_user_id = $user->ID;
			wp_set_current_user($wp_user_id, $username);
			wp_set_auth_cookie($wp_user_id);
			do_action('wp_login', $username);
			wp_safe_redirect( $redirect_to );
		} else {
			$query = sprintf( "SELECT * FROM users WHERE ( username = '%s' OR email = '%s' )", $user_name, $user_name );
			$records = $wpdb->get_results( $query );

			if ( ! empty( $records ) ) {
				$data = $records[0];
				$user_args = array(
					'user_login'      => isset( $data->username ) ? $data->username : '',
					'user_pass'       => isset( $data->password )  ? $data->password  : '',
					'user_email'      => isset( $data->email ) ? $data->email : '',
					'first_name'      => isset( $data->first_name ) ? $data->first_name : '',
					'last_name'       => isset( $data->last_name )  ? $data->last_name  : '',
					'user_nicename'		=> isset( $data->name ) ? $data->name : '',
					'display_name' 		=> $data->name,
					'nickname' 			=> $data->name,
					'user_registered'	=> date( 'Y-m-d H:i:s' ),
				);
				$user_id = wp_insert_user( $user_args );
				$user = new WP_User( $user_id );
				if ( $data->role_id == ROLE_SUBSCRIBER ) {
					$user->set_role( 'subscriber' );
				} elseif ( $data->role_id == ROLE_OWNER || $data->role_id == ROLE_ADMIN ) {
					$user->set_role( 'administrator' );
				}
				$username = $user_args['user_login'];
				wp_set_current_user($user_id, $username);
				wp_set_auth_cookie($user_id);
				do_action('wp_login', $username);
				$redirect_to = knowing_god_urls( 'my-account' );

				$wpdb->update( 'users', array( 'wp_user_id' => $user_id ), array( 'id' => $data->id ) );
				wp_safe_redirect( $redirect_to );
			} else {
				$redirect_to = knowing_god_urls( 'register' );
				wp_safe_redirect( $redirect_to );
			}
		}
	}
	add_shortcode( 'knowing_god_sync_laravel_users', 'knowing_god_sync_laravel_users' );
endif;

if ( ! function_exists( 'knowing_god_pathway_challenges' ) ) :
	/**
	 * Prints Button where it placed.
	 *
	 * @param array $atts for the title.
	 *
	 * @return string.
	 */
	function knowing_god_pathway_challenges( $args ){
		global $wpdb;
		$a = shortcode_atts( array(
					'number_of_challenges' => '5',
					'show_card_footer' => 'no',
				), $args );
		$defaults = array('orderby' => 'post_modified', 'order' => 'ASC', 'hide_empty' => true, 'number' => $a['number_of_challenges'] );

		$terms = get_series_ordered($defaults);

		$readmore_page = $title_color = '';
		ob_start();
		if ( ! empty( $terms ) ) {
			?>
			<div class="row">
			<?php
			foreach( $terms as $term ) {
				$series_icon_loc_display = series_get_icons( $term->term_id );
				$icon = get_template_directory_uri() . '/images/pchallenges-30x30.png';
				if ( $series_icon = series_get_icons( $term->term_id )) {
					$series_url = seriesicons_url();
					$icon = $series_url . $series_icon;
				}

				$series_image_loc = get_term_meta( $term->term_id, 'series_image_loc', true );
				if ( empty( $series_image_loc ) ) {
					$series_image_loc = get_template_directory_uri() . '/images/c-mtn.jpg';
				}

				$series_author = get_term_meta( $term->term_id, 'series_author', true );
			?>
			<div class="col-sm-6 col-md-6 mb-3">
				<div class="card h-100" style="position: relative; box-shadow: rgb(221, 221, 221) 2px 2px 2px;">


				<a href="<?php echo get_site_url(); ?>/series/<?php echo $term->slug; ?>">
				<img class="card-img-top" src="<?php echo esc_url( $series_image_loc ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" title="<?php echo esc_attr( $term->name ); ?>" height="175">
				</a>
				<div class="card-body">
				<?php if ( ! empty( $icon ) ) : ?>
				   <img class="align-self-center pb-1 mr-1" src="<?php echo esc_url( $icon ); ?>" height="60" width="60" style="position: absolute; top: 2%; left: 2%;">
				<?php endif; ?>
				<h4 class="card-title"><a href="<?php echo get_site_url(); ?>/series/<?php echo $term->slug; ?>" class="pathway_green"><?php echo $term->name; ?></a></h4>
				<p class="card-text" style="font-size: 0.9rem;"><?php echo term_description( $term->term_id, 'series' ); ?></p>
				<?php
				if ( ! empty( $series_author ) ) :
					$author = get_userdata( $series_author );
				endif;
				$count = wp_postlist_count( $term->term_id );
				?>
				<p class="card-text" style="font-size: 0.9rem;">
				<?php if ( ! empty( $author ) ) : ?>
				<i class="fa fa-user"></i>  <a class="pathway_gray" href="<?php echo esc_url( get_author_posts_url( $series_author ) ); ?>"><?php echo esc_attr( $author->display_name ); ?></a> |
				<?php endif; ?>
				<a href="<?php echo get_site_url(); ?>/series/<?php echo $term->slug; ?>"><span class="badge badge-dark">
				<?php
				if ( $count == 1 ) {
					echo $count . esc_html( ' Post', 'knowing-god' );
				} else {
					echo $count . esc_html( ' Posts', 'knowing-god' );
				} ?></span> </a></p></div>
				</div>
			</div>

			<?php } ?>
			</div>
			<?php
		}
		return ob_get_clean();
	}
	add_shortcode( 'knowing_god_pathway_challenges', 'knowing_god_pathway_challenges' );
endif;

function knowing_god_easy_questions_banner( $atts )
{
	$a = shortcode_atts( array(
			'banner_sub_heading' => '',
			'banner_sub_heading_color' => '#ddd',
			'banner_heading' => '',
			'banner_heading_color' => '#00ab7e',

			'banner_background' => '',
			'banner_video_url' => '',
			'banner_video_title' => '',

		), $atts );
	if ( $a['banner_background'] !== '' ) {
        $src  = wp_get_attachment_image_src($a['banner_background'] ,'full' );
        if ( ! empty($src) ) {
         $src  = $src[0];
         $thumb_w = '1903';
         $thumb_h = '425';
         $a['banner_background'] = ct_resize($src, $thumb_w, $thumb_h, true);
        }
    } else {
        $a['banner_background'] = get_template_directory_uri() . '/images/1903x425.png';
    }
	ob_start();
	?>
	<header class="threeq-banner" style="background-image:url('<?php echo esc_url( $a[ 'banner_background' ]); ?>')">
		<div class="threeq-banner-call text-center">
			<h3 class="threeq-banner-welcome-text" style="color:<?php echo esc_attr( $a['banner_sub_heading_color'] ); ?>"><?php echo esc_html( $a['banner_sub_heading'] ); ?></h3>
			<h1 class="threeq-banner-title" style="color:<?php echo esc_attr( $a['banner_heading_color'] ); ?>"> <?php echo esc_html( $a['banner_heading'] ); ?></span></h1>
			<p><a data-fancybox="" href="<?php echo esc_url( $a['banner_video_url'] ); ?>" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen" class="fancybox-banner-anchor"><i aria-hidden="true" class="fa fa-play-circle-o play-icon-banner"></i> <?php echo esc_html( $a['banner_video_title'] ); ?></a></p>
		</div>
	</header>
	<?php
	return ob_get_clean();
}
add_shortcode( 'knowing_god_easy_questions_banner', 'knowing_god_easy_questions_banner' );

function knowing_god_banner( $atts )
{
	$a = shortcode_atts( array(
			'banner_sub_heading' => '',
			'banner_sub_heading_color' => '#ddd',
			'banner_heading' => '',
			'banner_heading_color' => '#00ab7e',

			'banner_background' => '',
			'banner_video_url' => '',
			'banner_video_title' => '',

		), $atts );
	if ( $a['banner_background'] !== '' ) {
        $src  = wp_get_attachment_image_src($a['banner_background'] ,'full' );
        if ( ! empty($src) ) {
         $src  = $src[0];
         $thumb_w = '1903';
         $thumb_h = '425';
         $a['banner_background'] = ct_resize($src, $thumb_w, $thumb_h, true);
        }
    } else {
        $a['banner_background'] = get_template_directory_uri() . '/images/1903x425.png';
    }
	ob_start();
	?>
	<header class="kg-banner" style="background-image:url('<?php echo esc_url( $a[ 'banner_background' ]); ?>')">
		<div class="kg-banner-call text-center">
			<h3 class="kg-banner-welcome-text" style="color:<?php echo esc_attr( $a['banner_sub_heading_color'] ); ?>"><?php echo esc_html( $a['banner_sub_heading'] ); ?></h3>
			<h1 class="kg-banner-title" style="color:<?php echo esc_attr( $a['banner_heading_color'] ); ?>"> <?php echo esc_html( $a['banner_heading'] ); ?></span></h1>
			<p><a data-fancybox="" href="<?php echo esc_url( $a['banner_video_url'] ); ?>" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen" class="fancybox-banner-anchor"><i aria-hidden="true" class="fa fa-play-circle-o play-icon-banner"></i> <?php echo esc_html( $a['banner_video_title'] ); ?></a></p>
		</div>
	</header>
	<?php
	return ob_get_clean();
}
add_shortcode( 'knowing_god_banner', 'knowing_god_banner' );

function limit_text( $text, $limit = 20 ) {
  $excerpt = explode(' ', $text, $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}

function knowing_god_special_courses( $atts )
{
	global $wpdb;

	$a = shortcode_atts( array(
			'special_course' => 0,
			'title' => '',
			'colored_title' => '',
			'colored_title_color' => '',
			'number_of_lessons' => 6,
		), $atts );
	$lessons = $wpdb->get_results( "SELECT lmscontents.*, lmsseries_data.lmsseries_id, users.name  AS author, lmsseries.slug AS course_slug, lmsseries.parent_id AS module_id  FROM lmsseries_data
	INNER JOIN lmsseries ON lmsseries.id = lmsseries_data.lmsseries_id
	INNER JOIN lmscontents ON lmscontents.id = lmsseries_data.lmscontent_id
	INNER JOIN users ON users.id = lmsseries.created_by
	WHERE lmsseries.parent_id = 0 AND lmscontents.parent_id = 0 AND  lmscontents.lesson_status='active' AND lmsseries_id = " . $a['special_course'] .' LIMIT ' . $a['number_of_lessons'] );

	ob_start(); ?>
	<!-- <div class="wpb_wrapper"> -->
	<h2 class="mt-3 mb-3">
	<?php
	if ( ! empty( $a['colored_title'] ) && ! empty( $a['colored_title_color'] ) ) {
		echo str_replace( $a['colored_title'], '<span style="color:' . $a['colored_title_color'] . ';">' . $a['colored_title'] . '</span>', $a['title'] );
	} else {
		echo esc_html( $a['title'] );
	}
	?></h2>
	<div class="row mt-4">
	<?php foreach( $lessons as $content ) :
	$type = 'File';
	$audio_link = '';
	if($content->file_path) {
		switch($content->content_type)
        {
			case 'audio_url':
            case 'audio':
                if ( $content->content_type == 'audio' ) {
                    $url = URL_STUDENT_LMS_SERIES_VIEW.$content->course_slug.'/'.$content->slug;
                    $audio_link = IMAGE_PATH_UPLOAD_LMS_CONTENTS . $content->file_path;
                } else {
                    $url = $content->file_path;
                    $audio_link = $content->file_path;
                }

                $type = 'Audio';
                break;
		}
	}
	?>
	<?php
	$video_url = $content->lms_file_video;
	if ( $content->video_type == 'video' ) {
		$video_url = IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->file_path_video;
	}
	?>
	<?php
	$content_image_path = IMAGES . '900x400.png';
	$image_name = '900_400_' . $content->image;
	if ( ! empty( $content->image ) && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $image_name ) ) {
		$content_image_path = IMAGE_PATH_UPLOAD_LMS_CONTENTS . $image_name;
		//$thumb_w = '900';
		//$thumb_h = '400';
		//$content_image_path = ct_lms_resize($content_image_path, $thumb_w, $thumb_h, true);
	}
	elseif ( ! empty( $content->image ) && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->image ) ) {
		$content_image_path = IMAGE_PATH_UPLOAD_LMS_CONTENTS . $content->image;		
		//echo $_SERVER['DOCUMENT_ROOT'];
		$thumb_w = '900';
		$thumb_h = '400';
		$content_image_path = ct_lms_resize($content_image_path, $thumb_w, $thumb_h, true);
	}

	$conten_url = URL_FRONTEND_LMSSINGLELESSON . $content->course_slug . '/' . $content->slug;

	$class = 'icon icon-map-pointer';
	$icon_class = 'icon icon-tick-double';
	
	if ( is_lesson_completed( $content->id, $content->lmsseries_id, $content->module_id ) ) {
		$class = 'icon icon-pointer-border';
		$icon_class = 'icon icon-tick-border'; // If it is completed
	}
	
	$pieces = $wpdb->get_results( "SELECT * FROM lmscontents WHERE parent_id = $content->id");
	
	?>
	<div class="col-lg-4 mb-4">
	<div class="card h-100 text-center center spl-course-card">
	<a href="<?php echo esc_url( $conten_url ); ?>">
		<button class="fixed-top-left task-btn">
		<i class="<?php echo $icon_class; ?>" id="video_icon"></i></button>
		<img class="card-img-top kg-card-img" src="<?php echo esc_url( $content_image_path ); ?>" alt="<?php echo esc_attr( $content->title ); ?>" title="<?php echo esc_attr( $content->title ); ?>">
		<?php /* ?>
		<div class="video-list-pin fixed-top-right">		
		<i class="<?php echo $class; ?>"></i>
		
		</div><?php */ ?>
	</a>
	<div class="card-body">
	<h4 class="card-title">
		<?php if ( $video_url ) { ?>
		<a style="text-decoration:none;" data-fancybox="" href="<?php echo esc_url( $video_url ); ?>" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen">
		<i class="fa fa-play" aria-hidden="true"></i>
		<?php } else { ?>
			<a href="<?php echo esc_url( $conten_url ); ?>">
		<?php } ?>
		<?php echo esc_html( $content->title ); ?>
		<?php if ( ! empty( $content->reference ) ) : ?>
		<span style="font-size: 1rem; color: rgb(102, 102, 102);">(<?php echo esc_html( $content->reference ); ?>)</span>
		<?php endif; ?>
		</a>
		<?php if ( ! empty( $pieces ) ) : ?>
			<br>
			<small>Parts: 
			<?php
			$p = 1;
			foreach( $pieces as $piece ) : 
				echo $p++;
				if ( $p < count( $pieces ) ) {
					echo '&nbsp;|&nbsp;';
				}
			endforeach; ?>
			</small>
		<?php endif; ?>
	</h4>

	<?php if ( ! empty( $content->content_sub_title ) ) : ?>
	<h6 class="card-subtitle mb-2 text-muted"><?php echo esc_html( $content->content_sub_title ); ?></h6>
	<?php endif; ?>
	<p class="card-text"><?php echo limit_text( $content->description ); ?></p>
	</div>
	<div class="card-footer kg-card-footer icon-row">
	<?php
	if( $content->file_pdf != '' && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->file_pdf ) ) :
	?>
	<a class="mr-2" href="<?php echo esc_url( IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_pdf ); ?>"><i class="fa fa-file-pdf-o"></i></a>
	<?php else : ?>
	<span class="mr-2"><i class="fa fa-file-pdf-o"></i></span>
	<?php endif; ?>

	<?php
	if( $content->file_ppt != '' && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->file_ppt ) ) :
	?>
	<a class="mr-2" href="<?php echo esc_url( IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_ppt ); ?>"><i class="fa fa-file-powerpoint-o"></i></a>
	<?php else : ?>
	<span class="mr-2"><i class="fa fa-file-powerpoint-o"></i></span>
	<?php endif; ?>

	<?php if( $content->file_word != '' && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->file_word ) ) : ?>
	<a class="mr-2" href="<?php echo esc_url( IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_word ); ?>"><i class="fa fa-file-word-o"></i></a>
	<?php else : ?>
	<span class="mr-2"><i class="fa fa-file-word-o"></i></span>
	<?php endif; ?>

	<?php
	$comments = lmscontent_comments( $content->id );
	if( is_user_logged_in() ) : ?>
		<a class="mr-2" data-toggle="modal" data-target="#commentsModal" onclick="getData(<?php echo $content->lmsseries_id; ?>, '<?php echo $content->id; ?>', 'comments')">
	<?php else : ?>
		<a class="mr-2" data-toggle="modal" data-target="#loginModalForm" >
	<?php endif; ?>
		<i class="fa fa-comments-o"></i>
		<?php
		if( count( $comments ) > 0 ) {
			echo count( $comments );
		}
		?>
	</a>


	<a class="mr-2" data-toggle="modal" data-target="#genericModal" onclick="getData(<?php echo $content->lmsseries_id; ?>, <?php echo $content->id; ?>, 'sharecontent', <?php echo $content->lmsseries_id; ?>)"><i class="fa fa-share"></i></a>

	<a class="mr-2" data-toggle="modal" data-target="#genericModal" onclick="getData(<?php echo $content->lmsseries_id; ?>, <?php echo $content->id; ?>, 'translation')"><i class="fa fa-globe"></i></a>
	
	<?php $course_groups = course_groups( $content->lmsseries_id ); 
	if ( count( $course_groups ) > 0 ) { ?>
	<a class="mr-2" href="#" onclick="getData( <?php echo get_the_ID(); ?>, 0, 'coursegroups' )"><i class="fa fa-group"></i></a>
	<?php
	} else {
	?>
	<span class="mr-2"><i class="fa fa-group"></i></span>
	<?php } ?>

	<?php if ( $content->quiz_id > 0 ) : ?>
		<a class="mr-2" href="#quizModal" data-toggle="modal" data-target="#quizModal" onclick="start_exam(<?php echo $content->quiz_id ?>)"><i class="fa fa-graduation-cap"></i></a>
	<?php else : ?>
		<span class="mr-2"><i class="fa fa-graduation-cap"></i></span>
	<?php endif; ?>

	</div>
	<?php if( 'Audio' === $type ) : ?>
	<div class="card-footer kg-audio">
	<audio controls="">
	<source src="<?php echo esc_url( $audio_link ); ?>" type="audio/mpeg">
	Your browser does not support the audio element.                </audio>
	</div>
	<?php endif; ?>
	</div>
	</div>
	<?php endforeach; ?>
		</div>
	<!--/Partners-->
	<!-- </div> -->
	<?php
	/*
	if ( is_course_completed( $a['special_course'] ) ) {
		mark_as_completed_course( $a['special_course'] );
	}
	*/
	return ob_get_clean();
}
add_shortcode( 'knowing_god_special_courses', 'knowing_god_special_courses' );

if ( ! function_exists( 'knowing_god_make_login_with_id' ) ) :
	/**
	 * [knowing_god_forgotpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function knowing_god_make_login_with_id(){
		global $wpdb;
		/*
		$user_name_c = isset($_COOKIE['kg_user']) ? base64_decode($_COOKIE['kg_user']) : '';
		if ( ! empty( $user_name_c ) ) {
			$user = get_user_by( 'login', $user_name_c );
		}
		
		if ( empty( $user_name_c ) ) {
			$redirect_to = knowing_god_urls( 'login' );
			wp_safe_redirect( $redirect_to );
		}
		$user = get_user_by( 'email', $user_name_c );
		*/
		
		
		if ( empty( $user ) ) {
			$user_name_c = base64_decode( $_GET['key'] );
			if ( ! empty( $user_name_c ) ) {
				// Let us check the user is already logged in LMS
				$check = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM users WHERE username = '%s' AND is_lms_loggedin = 'yes'", $user_name_c ) );
				if ( $check ) {
					$user = get_user_by( 'login', $user_name_c );
				}
			}
		}
		$redirect_to = knowing_god_urls( 'my-account' );
		if ( ! empty( $_GET['redirect'] ) ) {
			$redirect_to = base64_decode( $_GET['redirect'] );
		}

		if ( ! empty( $user ) ) {
			// unset($_COOKIE['kg_user']);
			// setcookie('kg_user', null, -1, '/');
			$username = $user->username;
			$wp_user_id = $user->ID;
			wp_set_current_user($wp_user_id, $username);
			wp_set_auth_cookie($wp_user_id);
			do_action('wp_login', $username);
			
			$wpdb->query( "UPDATE users SET is_wp_loggedin='yes' WHERE id = " . knowing_god_get_lms_user_id() );
			
			wp_safe_redirect( $redirect_to );
		} else {
			$redirect_to = knowing_god_urls( 'register' );
			wp_safe_redirect( $redirect_to );
		}
	}
	add_shortcode( 'knowing_god_make_login_with_id', 'knowing_god_make_login_with_id' );
endif;
