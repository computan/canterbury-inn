<?php
/**
 * ACF default field options.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

namespace Catapult\AcfDefaults;

/**
 * Load post types field options.
 *
 * @param array $field       ACF field array.
 */
function post_types( $field ) {
	$field['choices'] = array();

	if ( editing_field_group() ) {
		return $field;
	}

	$post_types = get_post_types( array( 'public' => true ), 'objects' );

	foreach ( $post_types as $post_type ) {
		if ( in_array( $post_type->name, array( 'attachment', 'library_block' ), true ) ) {
			continue;
		}

		$field['choices'][ $post_type->name ] = $post_type->label;
	}

	return $field;
}
add_filter( 'acf/load_field/name=post_types', 'Catapult\AcfDefaults\post_types' );
add_filter( 'acf/load_field/name=archive_link_post_type', 'Catapult\AcfDefaults\post_types' );

/**
 * Load terms field options.
 *
 * @param array $field       ACF field array.
 */
function terms( $field ) {
	$field['choices'] = array();

	if ( editing_field_group() ) {
		return $field;
	}

	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

	if ( ! is_wp_error( $taxonomies ) && ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy->name,
					'hide_empty' => false,
				),
			);

			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$field['choices'][ $taxonomy->name . '_label' ] = '<span>' . $taxonomy->label . '</span>';

				foreach ( $terms as $term ) {
					$field['choices'][ $taxonomy->name . '&&' . $term->term_id ] = $term->name;
				}
			}
		}
	}

	return $field;
}
add_filter( 'acf/load_field/name=terms', 'Catapult\AcfDefaults\terms' );

/**
 * Alignment field options.
 *
 * @param array $field       ACF field array.
 */
function alignment( $field ) {
	$field['choices'] = array(
		'start'  => 'Left',
		'center' => 'Center',
		'end'    => 'Right',
	);

	$field['default'] = 'start';

	return $field;
}
add_filter( 'acf/load_field/name=alignment', 'Catapult\AcfDefaults\alignment' );

/**
 * Load taxonomies field options.
 *
 * @param array $field       ACF field array.
 */
function taxonomies( $field ) {
	$field['choices'] = array();

	if ( editing_field_group() ) {
		return $field;
	}

	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

	if ( ! is_wp_error( $taxonomies ) && ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {
			$field['choices'][ $taxonomy->name ] = $taxonomy->label;
		}
	}

	return $field;
}
add_filter( 'acf/load_field/name=taxonomies', 'Catapult\AcfDefaults\taxonomies' );

/**
 * Returns true if currently editing a field group.
 */
function editing_field_group() {
	if ( function_exists( 'get_current_screen' ) ) {
		$current_screen = get_current_screen();

		if ( ! empty( $current_screen ) && 'acf-field-group' === $current_screen->id ) {
			return true;
		}
	}
}


/**
 * Load Contact Form 7 list.
 *
 * @param array $field ACF field array.
 * @return array
 */
function contact_form_7( $field ) {
	$field['choices'] = array();

	if ( ! class_exists( '\WPCF7_ContactForm' ) ) {
		return $field;
	}

	$forms = \WPCF7_ContactForm::find();

	if ( ! empty( $forms ) ) {
		foreach ( $forms as $form ) {
			if ( ! empty( $form->id() ) && ! empty( $form->title() ) ) {
				$field['choices'][ $form->id() ] = $form->title();
			}
		}
	}

	return $field;
}
add_filter( 'acf/load_field/name=contact_form_7', 'Catapult\AcfDefaults\contact_form_7' );
