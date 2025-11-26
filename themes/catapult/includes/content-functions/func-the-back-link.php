<?php
/**
 * Displays the back link.
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.17
 * @since   3.1.0
 */

/**
 * Displays the back link.
 *
 * @param string $post_id    The post ID.
 * @param string $title      An optional custom post title text.
 * @param string $permalink  An optional custom post link.
 */
function catapult_the_back_link( $post_id = null, $title = null, $permalink = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}

	if ( empty( $post_id ) ) {
		return;
	}

	$back_button_link = get_field( 'back_button_link' );

	if ( ! empty( $back_button_link ) ) {
		if ( ! empty( $back_button_link['title'] ) ) {
			$title = $back_button_link['title'];
		}

		if ( ! empty( $back_button_link['url'] ) ) {
			$permalink = $back_button_link['url'];
		}
	} else {
		$parent_id = wp_get_post_parent_id( $post_id );
	}

	if ( ! empty( $parent_id ) ) {
		if ( empty( $title ) ) {
			$title = get_the_title( $parent_id );
		}

		if ( empty( $permalink ) ) {
			$permalink = get_the_permalink( $parent_id );
		}
	}

	if ( is_tag() || is_category() || is_singular( 'post' ) ) {
		if ( empty( $title ) ) {
			$title = __( 'Blog', 'catapult' );
		}

		$permalink = get_the_permalink( get_option( 'page_for_posts' ) );
	} elseif ( is_tax() || is_singular() ) {
		$this_post_type   = get_post_type();
		$post_type_object = get_post_type_object( $this_post_type );

		if ( ! empty( $post_type_object ) ) {
			$permalink = get_post_type_archive_link( $this_post_type );

			if ( empty( $title ) ) {
				$title = sprintf( __( 'Back to %s', 'catapult' ), $post_type_object->labels->name );
			}
		}
	}

	if ( catapult_is_block_library() ) {
		if ( empty( $title ) ) {
			$title = __( 'Back', 'catapult' );
		}

		$permalink = home_url();
	}

	if ( ! empty( $title ) && ! empty( $permalink ) ) {
		echo catapult_array_to_link(
			array(
				'url'   => $permalink,
				'title' => $title,
			),
			'is-style-tertiary wp-block-button--small wp-block-button--back',
			array(
				'icon'          => 'icon-chev-left',
				'icon_position' => 'left',
			)
		);
	}
}
