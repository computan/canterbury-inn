<?php
/**
 * Testimonial Post Type.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 */

if ( get_theme_setting( 'use_testimonal_post_type' ) ) {
	$labels = array(
		'name'                  => __( 'Testimonials', 'catapult' ),
		'singular_name'         => __( 'Testimonial', 'catapult' ),
		'menu_name'             => __( 'Testimonials', 'catapult' ),
		'name_admin_bar'        => __( 'Testimonial', 'catapult' ),
		'add_new'               => __( 'Add New', 'catapult' ),
		'add_new_item'          => __( 'Add New Testimonial', 'catapult' ),
		'new_item'              => __( 'New Testimonial', 'catapult' ),
		'edit_item'             => __( 'Edit Testimonial', 'catapult' ),
		'view_item'             => __( 'View Testimonial', 'catapult' ),
		'all_items'             => __( 'All Testimonials', 'catapult' ),
		'search_items'          => __( 'Search Testimonials', 'catapult' ),
		'parent_item_colon'     => __( 'Parent Testimonials:', 'catapult' ),
		'not_found'             => __( 'No testimonials found.', 'catapult' ),
		'not_found_in_trash'    => __( 'No testimonials found in Trash.', 'catapult' ),
		'featured_image'        => __( 'Testimonial Cover Image', 'catapult' ),
		'archives'              => __( 'Testimonial archives', 'catapult' ),
		'insert_into_item'      => __( 'Insert into testimonial', 'catapult' ),
		'uploaded_to_this_item' => __( 'Uploaded to this testimonial', 'catapult' ),
		'filter_items_list'     => __( 'Filter testimonials list', 'catapult' ),
		'items_list_navigation' => __( 'Testimonials list navigation', 'catapult' ),
		'items_list'            => __( 'Testimonials list', 'catapult' ),
	);

	$args = array(
		'labels'              => $labels,
		'menu_icon'           => 'dashicons-format-chat',
		'public'              => false,
		'has_archive'         => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'exclude_from_search' => true,
		'supports'            => array( 'title', 'thumbnail' ),
		'rewrite'             => array( 'slug' => 'testimonial' ),
	);

	register_post_type( 'testimonial', $args );
}
