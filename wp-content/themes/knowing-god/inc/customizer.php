<?php
/**
 * Knowing God Theme Customizer
 *
 * @package Knowing_God
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function knowing_god_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'knowing_god_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'knowing_god_customize_partial_blogdescription',
		) );
	}
	
	// Custom Settings
	knowing_god_custom_settings( $wp_customize );
}
add_action( 'customize_register', 'knowing_god_customize_register' );

/**
 * Render the custom settings in customizer.
 *
 * @return void
 */
function knowing_god_custom_settings( $wp_customize ) {
	$pages = get_pages();
	$pages_array = array();
	foreach ( $pages as $page ) {
		$pages_array[ $page->ID ] = $page->post_title;
	}
	
	$wp_customize->add_panel( 'knowing_god_theme_settings', array(
		'priority'       => 30,
		'capability'     => 'edit_theme_options',
		'title'          => esc_html__( 'Knowing God Customizer', 'knowing-god' ),
		'description'    => esc_html__( 'Customize the Knowing God theme of your website.', 'knowing-god' ),
	)
	);
	$wp_customize->add_section( 'knowing_god_top_info_section', array(
		'priority' => 5,
		'title' => esc_html__( 'Customize Header', 'knowing-god' ),
		'panel'  => 'knowing_god_theme_settings',
	)
	);
				
	// Page Banner
	$wp_customize->add_setting( 'banner-show-hide', array(
		'default' => 'show',
		'sanitize_callback' => 'knowing_god_sanitize_show_hide',
	)
	);
	$wp_customize->add_control( 'banner-show-hide', array(
		'label' => esc_html__( 'Website show page banner', 'knowing-god' ),
		'section' => 'knowing_god_top_info_section',
		'type' => 'select',
		'description' => esc_html__( 'Choose Whether to show page banner OR not', 'knowing-god' ),
		'choices' => array( 
			'show' => esc_html__( 'Show', 'knowing-god' ), 
			'hide' => esc_html__( 'Hide', 'knowing-god' ),
		),
	)
	);
	
	// Bread Crumb on Page Banner.
	$wp_customize->add_setting( 'breadcrumb-show-hide', array(
		'default' => 'hide',
		'sanitize_callback' => 'knowing_god_sanitize_show_hide',
	)
	);
	$wp_customize->add_control( 'breadcrumb-show-hide', array(
		'label' => esc_html__( 'Website header shows the breadcrumb on page banner', 'knowing-god' ),
		'section' => 'knowing_god_top_info_section',
		'type' => 'select',
		'description' => esc_html__( 'Choose Whether to show the breadcrumb on page banner or not', 'knowing-god' ),
		'choices' => array( 
			'show' => esc_html__( 'Show', 'knowing-god' ),
			'hide' => esc_html__( 'Hide', 'knowing-god' ),
		),
	)
	);
	
	// Footer Section.
	$wp_customize->add_section( 'knowing_god_footer_section', array(
		'priority' => 5,
		'title' => esc_html__( 'Customize Footer', 'knowing-god' ),
		'panel'  => 'knowing_god_theme_settings',
	)
	);
	
	// Footer Credits Show / Hide.
	$wp_customize->add_setting( 'footer-credits-show-hide', array(
		'default' => 'show',
		'sanitize_callback' => 'knowing_god_sanitize_show_hide',
	)
	);
	$wp_customize->add_control( 'footer-credits-show-hide', array(
		'label' => esc_html__( 'Website header shows the footer-credits on page banner', 'knowing-god' ),
		'section' => 'knowing_god_footer_section',
		'type' => 'select',
		'description' => esc_html__( 'Choose Whether to show the footer-credits on page banner or not', 'knowing-god' ),
		'choices' => array( 
			'show' => esc_html__( 'Show', 'knowing-god' ),
			'hide' => esc_html__( 'Hide', 'knowing-god' ),
		),
	)
	);
	
	// Footer Credits.
	$wp_customize->add_setting( 'footer-credits', array(
		'default' => '',
		'sanitize_callback' => 'knowing_god_sanitize_strip_slashes',
	)
	);
	$wp_customize->add_control( 'footer-credits', array(
		'label' => esc_html__( 'Footer Credits', 'knowing-god' ),
		'section' => 'knowing_god_footer_section',
		'type' => 'textarea',
		'description' => esc_html__( 'Footer Credits', 'knowing-god' ),		
	)
	);
		
	/**
	 * Blog Display
	 */
	$wp_customize->add_section('knowing_god_blogdisplay_section', array(
		'priority' => 5,
		'title' => esc_html__( 'Blog Display', 'knowing-god' ),
		'panel'  => 'knowing_god_theme_settings',
		)
	);
		
	// Sidebar Position
	$wp_customize->add_setting( 'sidebar-position', array(
		'default' => 'right',
		'type' => 'theme_mod',
		'sanitize_callback' => 'knowing_god_sanitize_position',
	)
	);
	$wp_customize->add_control( 'sidebar-position', array(
		'label' => esc_html__( 'Choose where to display sidebar', 'knowing-god' ),
		'section' => 'knowing_god_blogdisplay_section',
		'type' => 'select',
		'description' => esc_html__( 'Choose where to display sidebar', 'knowing-god' ),
		'choices' => array( 
			'right' => esc_html__( 'Right', 'knowing-god' ), 
			'left' => esc_html__( 'Left', 'knowing-god' ),
			'none' => esc_html__( 'No Sidebar', 'knowing-god' ),
			),
	)
	);

	// Social Networks Section
	$wp_customize->add_section( 'knowing_god_footer_social_section', array(
		'priority' => 5,
		'title' => esc_html__( 'Social Networks', 'knowing-god' ),
		'panel'  => 'knowing_god_theme_settings',
	)
	);
	
	// Social Icons.
	$wp_customize->add_setting( 'footer-socialicons-show-hide', array(
		'default' => 'hide',
		'sanitize_callback' => 'knowing_god_sanitize_show_hide',
	)
	);
	$wp_customize->add_control( 'footer-socialicons-show-hide', array(
		'label' => esc_html__( 'Website footer shows the social icons in footer area', 'knowing-god' ),
		'section' => 'knowing_god_footer_social_section',
		'type' => 'select',
		'priority' => 1,
		'description' => esc_html__( 'Choose Whether to show the social icons in footer area', 'knowing-god' ),
		'choices' => array( 
			'show' => esc_html__( 'Show', 'knowing-god' ), 
			'hide' => esc_html__( 'Hide', 'knowing-god' ),
		),
	)
	);	
	
	$social = knowing_god_get_social_networks();
	foreach ( $social as $key => $val ) {
		$wp_customize->add_setting( $key, array(
			'default' => '',
			'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control( $key, array(
			'label' => esc_attr( $val['title'], 'knowing-god' ),
			'section' => 'knowing_god_footer_social_section',
			'type' => 'url',
			'priority' => 25,
			'settings' => $key,
			)
		);
		
		// Whether to share OR Not.
		if ( in_array( $key, array( 'knowing_god_facebook', 'knowing_god_twitter', 'knowing_god_google-plus', 'knowing_god_pinterest' ), true ) ) :
			$wp_customize->add_setting( $key . '_share', array(
				'default' => 'no',
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'knowing_god_sanitize_yes_no',
				)
			);

			$wp_customize->add_control( $key . '_share', array(
				'label' => esc_html__( 'Enable ', 'knowing-god' ) . $val['title'] . esc_html__( ' Share', 'knowing-god' ),
				'section' => 'knowing_god_footer_social_section',
				'type' => 'select',
				'priority' => 2,
				'settings' => $key . '_share',
				'choices' => array( 
					'no' => esc_html__( 'No', 'knowing-god' ), 
					'yes' => esc_html__( 'Yes', 'knowing-god' ),
				),
				)
			);
		endif;
	} // End foreach().
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function knowing_god_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function knowing_god_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function knowing_god_customize_preview_js() {
	wp_enqueue_script( 'knowing-god-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'knowing_god_customize_preview_js' );

/**
 * Adds sanitization callback function: Strip Slashes.
 *
 * @param string $input value from the customizer.
 */
function knowing_god_sanitize_strip_slashes( $input ) {
	return wp_kses_stripslashes( $input );
}

/**
 * Adds sanitization callback function: knowing_god_sanitize_show_hide.
 *
 * @param string $input value from the customizer.
 */
function knowing_god_sanitize_show_hide( $input ) {
	if ( in_array( $input, array( 'show', 'hide' ), true ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: knowing_god_sanitize_show_hide.
 *
 * @param string $input value from the customizer.
 */
function knowing_god_sanitize_displaytype( $input ) {
	if ( in_array( $input, array( 'classic', 'grid' ), true ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: knowing_god_sanitize_yes_no.
 *
 * @param string $input value from the customizer.
 */
function knowing_god_sanitize_yes_no( $input ) {
	if ( in_array( $input, array( 'yes', 'no' ), true ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: knowing_god_sanitize_position.
 *
 * @param string $input value from the customizer.
 */
function knowing_god_sanitize_position( $input ) {
	if ( in_array( $input, array( 'left', 'right', 'none' ), true ) ) {
		return $input;
	} else {
		return '';
	}
}
