<?php
/**
 * Functions related to custom scripts added in the dashboard.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.1.6
 * @since   3.1.7
 */

namespace Catapult\UserScripts;

/**
 * Load the user scripts added in the Theme Options.
 */
function load_user_scripts() {
	$current_filter = current_filter();

	$scripts = get_field( 'scripts', 'scripts' );

	if ( empty( $scripts ) ) {
		return;
	}

	foreach ( $scripts as $script ) {
		if ( empty( $script['script'] ) || empty( $script['location'] ) ) {
			continue;
		}

		if ( 'wp_' . $script['location'] !== $current_filter ) {
			continue;
		}

		if ( false === $script['load_script_everywhere'] ) {
			$script_allowed = false;

			if ( is_singular() ) {
				if ( ! empty( $script['post_types'] ) ) {
					$post_type = get_post_type();

					if ( in_array( $post_type, $script['post_types'], true ) ) {
						$script_allowed = true;
					}
				}

				if ( ! empty( $script['specific_posts'] ) ) {
					$post_id = get_the_ID();

					if ( in_array( $post_id, $script['specific_posts'], true ) ) {
						$script_allowed = true;
					}
				}
			} elseif ( is_post_type_archive() || is_home() ) {
				$post_type = get_post_type();

				if ( ! empty( $script['post_types'] ) ) {
					$post_type = get_post_type();

					if ( in_array( $post_type, $script['post_types'], true ) ) {
						$script_allowed = true;
					}
				}
			} elseif ( is_tax() || is_category() || is_tag() ) {
				$term_object = get_queried_object();

				if ( ! empty( $term_object->taxonomy ) ) {
					if ( ! empty( $script['taxonomies'] ) ) {
						if ( in_array( $term_object->taxonomy, $script['taxonomies'], true ) ) {
							$script_allowed = true;
						}
					}

					if ( ! empty( $term_object->term_id ) && ! empty( $script['terms'] ) ) {
						if ( in_array( $term_object->taxonomy . '&&' . $term_object->term_id, $script['terms'], true ) ) {
							$script_allowed = true;
						}
					}
				}
			}

			if ( false === $script_allowed ) {
				continue;
			}
		}

		if ( ! empty( $script['delay_initialization'] ) ) {
			$script['script'] = str_replace( 'type=', 'data-type=', $script['script'] );
			$script['script'] = str_replace( '<script', '<script type="catapult-delayed-script"', $script['script'] );
		}

		echo wp_kses(
			$script['script'],
			array(
				'script' => array(
					'id'          => true,
					'type'        => true,
					'async'       => true,
					'defer'       => true,
					'src'         => true,
					'crossorigin' => true,
					'integrity'   => true,
					'charset'     => true,
					'data-*'      => true,
				),
			)
		);
	}
}
add_action( 'wp_head', 'Catapult\UserScripts\load_user_scripts', 999 );
add_action( 'wp_body_open', 'Catapult\UserScripts\load_user_scripts', 999 );
add_action( 'wp_footer', 'Catapult\UserScripts\load_user_scripts', -999 );
