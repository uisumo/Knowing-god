<?php
/**
 * Register taxonomy - faqcategory
 *
 * @package     Knowing God
 * @subpackage  taxonomy
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function knowing_god_faqcategory_taxonomy() {
//Vehicle FAQ Categories
$type_labels = array(
	'name'              => sprintf( _x( '%s FAQ Category', 'taxonomy general name', 'knowing-god' ), 'KG' ),
	'singular_name'     => sprintf( _x( '%s FAQ Categories', 'taxonomy singular name', 'knowing-god' ), 'KG' ),
	'search_items'      => sprintf( esc_html__( 'Search %s FAQ Categories', 'knowing-god' ), 'KG' ),
	'all_items'         => sprintf( esc_html__( 'All %s FAQ Categories', 'knowing-god' ), 'KG' ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s FAQ Category', 'knowing-god' ), 'KG' ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s FAQ Category:', 'knowing-god' ), 'KG' ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s FAQ Category', 'knowing-god' ), 'KG' ),
	'update_item'       => sprintf( esc_html__( 'Update %s FAQ Category', 'knowing-god' ), 'KG' ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s FAQ Category', 'knowing-god' ), 'KG' ),
	'new_item_name'     => sprintf( esc_html__( 'New %s FAQ Category Name', 'knowing-god' ), 'KG' ),
	'menu_name'         => esc_html__( 'KG FAQ Categories', 'knowing-god' ),
);
$type_args = apply_filters( 'knowing_god_faqcategory_args', array(
		'hierarchical' => false,
		'labels'       => apply_filters('knowing_god_faqcategory_labels', $type_labels),
		'show_ui'      => true,
		'query_var'    => 'faqcategory',
		'rewrite'      => array('slug' => 'faqcategory', 'with_front' => false, 'hierarchical' => true ),
		'show_admin_column'=>true,
		'map_meta_cap' => true,
	)
);
register_taxonomy( 'faqcategory', array('post'), $type_args );
}