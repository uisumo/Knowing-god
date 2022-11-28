<?php
/**
 * File which defines the demo import functions
 *
 * @package Knowing God
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

if ( class_exists( 'OCDI_Plugin' ) ) {
	/**
	 * Returns the introduction text for before install the demo data
	 *
	 * @param string $default_text text to display.
	 * @since 1.0
	 * @return string
	 */
	function auto_point_ocdi_plugin_intro_text( $default_text ) {
		$default_text .= '
		<div class="ocdi__intro-text">
		<h3>' . esc_html__( 'Read this before importing demo data!', 'knowing-god' ) . '</h3>
		<p>' . esc_html__( 'Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.', 'knowing-god' ) . '</p>
		<hr />
		</div>
		';

		return $default_text;
	}
	add_filter( 'pt-ocdi/plugin_intro_text', 'auto_point_ocdi_plugin_intro_text' );

	/**
	 * Add filter for 'confirmation_dialog_options'
	 *
	 * @param string $options Options to apply.
	 * @since 1.0
	 * @return string
	 */
	function auto_point_ocdi_confirmation_dialog_options( $options ) {
		return array_merge( $options, array(
			'width'       => 600,
			'dialogClass' => 'wp-dialog',
			'resizable'   => false,
			'height'      => 'auto',
			'modal'       => true,
		) );
	}
	add_filter( 'pt-ocdi/confirmation_dialog_options', 'auto_point_ocdi_confirmation_dialog_options', 10, 1 );

	/**
	 * Setup basic demo import.
	 *
	 * @since 1.0
	 * @return array
	 */
	function auto_point_import_files() {

		$import_notice_all = '
			<h3>' . esc_html__( 'Knowing God Demo Data', 'knowing-god' ) . '</h3>
			<p>' . esc_html__( 'Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.', 'knowing-god' ) . '</p>
			<p>' . esc_html__( 'Since you\'re importing Digi - Portal Demo Data, please ensure "Digi - Portal Core" plugin is enabled in "plugins". This will contain all of your posts, pages, comments, custom fields, terms, navigation menus, and custom posts if any.', 'knowing-god' ) . '</p>
		';

		$import_notice_vehicle = '
			<h3>' . esc_html__( 'Knowing God Images', 'knowing-god' ) . '</h3>
			<p>' . esc_html__( 'Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.', 'knowing-god' ) . '</p>
			<p>' . esc_html__( 'Since you\'re importing Knowing God Demo Data, please ensure "Knowing God Core" plugin is enabled in "plugins". This will contain only images.', 'knowing-god' ) . '</p>
		';

		$import_notice_variant_images = '
			<h3>' . esc_html__( 'Ready to Import Knowing God Demo Images ONLY', 'knowing-god' ) . '</h3>
			<p>' . esc_html__( 'This will import the required demo images for Knowing God Demo Images. This will not add any page or post data.', 'knowing-god' ) . '</p>
		';

		return array(
			array(
				'import_file_name'             => esc_html__( 'Knowing God Demo Data', 'knowing-god' ),
				'import_file_url'              => get_template_directory_uri() . '/admin/demo-data/knowing-god.xml',
				'import_widget_file_url'       => get_template_directory_uri() . '/admin/demo-data/widgets.wie',
				'import_notice'                => $import_notice_all,
			),
			array(
				'import_file_name'             => esc_html__( 'Knowing God Demo Images ONLY', 'knowing-god' ),
				'import_file_url'              => get_template_directory_uri() . '/admin/demo-data/media.xml',
				'import_notice'                => $import_notice_variant_images,
			),
		);

	}
	add_filter( 'pt-ocdi/import_files', 'auto_point_import_files' );

	/**
	 * Setup front page and menus.
	 *
	 * @since 1.0
	 * @return void
	 */
	function auto_point_after_import_setup() {

		// Assign menus to their locations.
		$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
		set_theme_mod( 'nav_menu_locations', array(
			'primary-menu'  => $main_menu->term_id,
		)
		);
		
		$footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
		set_theme_mod( 'nav_menu_locations', array(
			'footer-menu'  => $footer_menu->term_id,
		)
		);

		// Assign front page and posts page (blog page).
		update_option( 'show_on_front', 'page' );		
		$front_page_id = get_page_by_title( 'Knowing God' );
		if ( null !== $front_page_id ) {
			update_option( 'page_on_front', $front_page_id->ID );
		}
		
		$blog_page_id  = get_page_by_title( 'KnowingGod Blog' );
		if ( null !== $blog_page_id ) {
			update_option( 'page_for_posts', $blog_page_id->ID );
		}
		
		//Import Revolution Slider
		if ( class_exists( 'RevSlider' ) ) {
			$slider_array = array(
				get_template_directory() . '/admin/demo-data/3eq.zip',
				get_template_directory() . '/admin/demo-data/d3d.zip',
				get_template_directory() . '/admin/demo-data/encountering.zip',
				get_template_directory() . '/admin/demo-data/glo.zip',
				get_template_directory() . '/admin/demo-data/good-news.zip',
				get_template_directory() . '/admin/demo-data/home-slider.zip',
				get_template_directory() . '/admin/demo-data/in-my-name.zip',
				get_template_directory() . '/admin/demo-data/Kg-slider1.zip',
			);

			$slider = new RevSlider();

			foreach( $slider_array as $filepath ) {
				$slider->importSliderFromPost( true, true, $filepath );  
			}
		}
	}
	add_action( 'pt-ocdi/after_import', 'auto_point_after_import_setup' );

	// Remove Branding.
	add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

	// Save customize options.
	add_action( 'pt-ocdi/enable_wp_customize_save_hooks', '__return_true' );

	// Stop thumbnail generation.
	add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
} // End if().
