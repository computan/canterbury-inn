<?php
/**
 * Functions and hooks for the gated content functionality.
 *
 * @package Catapult
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || die();

/**
 * Class managing the gated content functionality.
 */
class Theme_Core_Gated_Content extends Theme_Core_Component {
	/**
	 * Add support for thumbnails and register all images sizes
	 * added in the settings file.
	 */
	protected function init() {
		add_filter( 'render_block', array( $this, 'hide_gated_content' ), 99, 2 );
		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_route' ) );
	}

	/**
	 * Hide the gated content from the rendered blocks.
	 *
	 * @param string $block_content The block content about to be appended.
	 * @param array  $block         The full block, including name and attributes.
	 */
	public function hide_gated_content( $block_content, $block ) {
		if ( ! empty( $block['attrs'] ) && ! empty( $block['attrs']['data'] ) && ! empty( $block['attrs']['data']['gated_content'] ) ) {
			if ( $this->is_post_content_endpoint() ) {
				preg_match( '/(?<=\/post-content\/).*/m', $GLOBALS['wp']->query_vars['rest_route'], $matches );

				if ( ! empty( $matches[0] ) ) {
					$post_id = $matches[0];

					if ( ! empty( $_COOKIE[ $post_id . '-ungated' ] ) ) {
						if ( 'gated' === $block['attrs']['data']['gated_content'] ) {
							return $block_content;
						} elseif ( 'ungated' === $block['attrs']['data']['gated_content'] ) {
							return;
						}
					}
				}
			}

			if ( 'gated' === $block['attrs']['data']['gated_content'] ) {
				return;
			}
		}

		if ( ! empty( $block['blockName'] ) && $this->is_post_content_endpoint() ) {
			if ( 'contact-form-7/contact-form-selector' === $block['blockName'] ) {
				return;
			}
		}

		return $block_content;
	}

	/**
	 * Check whether the current request is for the gated content endpoint.
	 *
	 * @return bool  Whether or not the current request is for the gated content endpoint.
	 */
	public function is_post_content_endpoint() {
		if ( defined( 'REST_REQUEST' ) && ! empty( REST_REQUEST ) && ! empty( $GLOBALS['wp']->query_vars['rest_route'] ) && false !== strpos( $GLOBALS['wp']->query_vars['rest_route'], '/catapult/v1/post-content/' ) ) {
			return true;
		}
	}

	/**
	 * Add has-gated-content class to the body if blocks on the page have the ACF field gated_content set to "gated".
	 *
	 * @param array $classes         An array of classes to add to the body tag.
	 */
	public function body_class( $classes ) {
		global $blocks;
		global $innerblocks;

		if ( ! empty( $blocks ) ) {
			$blocks_to_parse = $blocks;

			if ( ! empty( $innerblocks ) ) {
				$blocks_to_parse = array_merge( $blocks, $innerblocks );
			}

			foreach ( $blocks_to_parse as $block ) {
				if ( ! empty( $block['attrs'] ) && ! empty( $block['attrs']['data'] ) && ! empty( $block['attrs']['data']['gated_content'] ) && 'gated' === $block['attrs']['data']['gated_content'] ) {
					wp_enqueue_style( 'catapult-component-gated-content' );
					wp_enqueue_script( 'catapult-component-gated-content' );

					$classes[] = 'has-gated-content';

					break;
				}
			}
		}

		return $classes;
	}

	/**
	 * Register rest routes.
	 */
	public function register_rest_route() {
		register_rest_route(
			'catapult/v1',
			'/post-content/(?P<id>[\d]+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'post_content_ungated' ),
				'permission_callback' => '__return_true',
			)
		);

		$args = array(
			'public' => true,
		);

		$post_types = get_post_types( $args, 'names' );

		if ( empty( $post_types ) ) {
			return;
		}

		register_rest_field(
			array_keys( $post_types ),
			'post_content_ungated',
			array(
				'get_callback' => array( $this, 'post_content_ungated' ),
			)
		);
	}

	/**
	 * Load the post content if ungated.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public function post_content_ungated( $request ) {
		if ( empty( $request['id'] ) ) {
			return;
		}

		if ( empty( $_COOKIE[ $request['id'] . '-ungated' ] ) ) {
			return;
		}

		$this_post = get_post( $request['id'] );

		if ( empty( $this_post ) || is_wp_error( $this_post ) ) {
			return;
		}

		global $post;
		global $wp_query;

		$wp_query->is_singular = true;

		$post = $this_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		setup_postdata( $this_post );

		catapult_default_content();

		$content = ob_get_contents();
		ob_end_clean();

		wp_reset_postdata();

		return $content;
	}
}
