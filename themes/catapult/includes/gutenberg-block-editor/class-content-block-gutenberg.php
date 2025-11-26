<?php
/**
 * The Content Block Class.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   2.2.10
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 */

/**
 * Content Block Gutenberg
 *
 * A class for easily retrieving information related to our various content sections now powered by Gutenberg ACF blocks.
 */
class Content_Block_Gutenberg {
	/**
	 * The block data.
	 *
	 * @var array
	 */
	private $block;

	/**
	 * The block context.
	 *
	 * @var array
	 */
	private $context;

	/**
	 * Search for this string to replace content in the rendered block output. Can include regex.
	 *
	 * @var string
	 */
	private $search_string;

	/**
	 * Replace the search string in the rendered block output with this string.
	 *
	 * @var string
	 */
	private $replace_string;

	/**
	 * The maximum possible replacements for each pattern. Defaults to -1 (no limit).
	 *
	 * @var int
	 */
	private $limit = -1;

	/**
	 * The block name.
	 *
	 * @var string
	 */
	private $name = '';

	/**
	 * The block settings.
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * Constructor.
	 *
	 * Sets up the block.
	 *
	 * @param array $block   The block data.
	 * @param array $context The block context.
	 */
	public function __construct( $block, $context = array() ) {
		$this->block   = $block;
		$this->context = $context;

		if ( ! empty( $block['name'] ) ) {
			$this->name = $block['name'];
		}

		$block_name_without_prefix = str_replace( 'acf/', '', $block['name'] );

		if ( ! empty( $block['render_callback'] ) && ! empty( $block['render_callback'][0] ) && ! empty( $block['render_callback'][0]->all_block_data ) && ! empty( $block['render_callback'][0]->all_block_data[ $block_name_without_prefix ] ) ) {
			$this->settings = $block['render_callback'][0]->all_block_data[ $block_name_without_prefix ];
		}
	}

	/**
	 * Get block ID.
	 *
	 * @return string ID friendly title.
	 */
	public function get_block_id(): string {
		$scroll_id = get_field( 'scroll_id' );
		$block_id  = ! empty( $scroll_id ) ? sanitize_title( $scroll_id ) : '';

		return $block_id;
	}

	/**
	 * Get block ID with attribute definition.
	 *
	 * @return string ID friendly title.
	 */
	public function get_block_id_attr(): string {
		$block_id  = self::get_block_id();
		$attribute = '';

		if ( ! empty( $block_id ) ) {
			$attribute = 'id="' . $block_id . '" ';
		}

		return $attribute;
	}

	/**
	 * Get block classes.
	 *
	 * @param array $args {
	 *    Optional arguments.
	 *
	 *     @type string   $background_color   Background color to override ACF field value.
	 *
	 * @return string background classes.
	 */
	public function get_block_classes( $args = array() ): string {
		$output = '';

		if ( ! empty( $args['background_color'] ) ) {
			$background_color = $args['background_color'];
		} else {
			$background_color = get_field( 'background_color' );
		}

		if ( empty( $background_color ) && ! empty( $this->settings['default_background_color'] ) ) {
			$background_color = $this->settings['default_background_color'];
		}

		if ( ! isset( $args['background_color'] ) || ( null !== $args['background_color'] && false !== $args['background_color'] ) ) {
			if ( ! empty( $background_color ) ) {
				$output = ' bg-' . $background_color;
			} else {
				$output = ' bg-transparent';
			}
		}

		if ( ! empty( $this->block['className'] ) ) {
			$output .= ' ' . $this->block['className'];
		}

		if ( ! is_admin() ) {
			if ( empty( $this->block['active'] ) || ( true !== $this->block['active'] && 'true' !== $this->block['active'] ) ) {
				$output .= ' block-inactive';
			}
		}

		if ( ! empty( $this->block['starts_with_text'] ) ) {
			$output .= ' acf-block--starts-with-text';
		}

		if ( ! empty( $allow_localization ) ) {
			$output .= ' acf-block--localized';
		}

		return $output;
	}

	/**
	 * Get block style attribute. Checks for any ACF fields declared in the CSS Custom Props block setting.
	 *
	 * @param string $styles   Additional styles to add to the styles attribute.
	 *
	 * @return string the styles attribute.
	 */
	public function get_block_style_attr( $styles = '' ): string {
		$output = '';

		if ( ! empty( $this->block['css_custom_props'] ) && is_array( $this->block['css_custom_props'] ) ) {
			foreach ( $this->block['css_custom_props'] as $css_custom_prop_data ) {
				if ( empty( $css_custom_prop_data['name'] ) ) {
					continue;
				}

				$acf_field_value = get_field( $css_custom_prop_data['name'] );

				if ( empty( $acf_field_value ) && ! empty( $css_custom_prop_data['default'] ) ) {
					$acf_field_value = $css_custom_prop_data['default'];
				}

				if ( ! empty( $acf_field_value ) ) {
					$styles .= '--' . $css_custom_prop_data['name'] . ': ' . $acf_field_value . ';';
				}
			}
		}

		if ( ! empty( $styles ) ) {
			$output = 'style="' . $styles . '" ';
		}

		return $output;
	}

	/**
	 * Get block image and/or video.
	 *
	 * @param  string $image_size   Image size.
	 * @param  string $classes      Classes to add to the wrapper elements.
	 * @param  string $block_library_placeholder_aspect_ratio      The aspect ratio of the block library placeholder image to use.
	 *
	 * @return string image/video html.
	 */
	public function get_block_image_and_video( $image_size = 'col-6-square', $classes = '', $block_library_placeholder_aspect_ratio = '' ): string {
		$output = '';
		$image  = get_field( 'image' );
		$video  = get_field( 'video', false, false );
		$button = '';

		if ( ! empty( $classes ) ) {
			$classes .= ' ' . $classes;
		}

		if ( ! empty( $image ) || ! empty( $video ) ) {
			$focus       = get_post_meta( $image, '_wpsmartcrop_image_focus', true );
			$image_style = '';

			if ( ! empty( $focus ) && ! empty( $focus['top'] && ! empty( $focus['left'] ) ) ) {
				$image_style = 'object-position: ' . $focus['left'] . '% ' . $focus['top'] . '%;';
			}

			if ( ! empty( $video ) ) {
				$classes .= ' component-video';
				$output  .= '<figure class="' . $classes . '" data-embed-url="' . $video . '">';
			} else {
				$output .= '<figure class="' . $classes . '">';
			}

			$output .=
				wp_get_attachment_image(
					$image,
					$image_size,
					'',
					array(
						'class' => 'component-video__image',
						'style' => $image_style,
						'block_library_placeholder_aspect_ratio' => $block_library_placeholder_aspect_ratio,
					)
				);

			if ( ! empty( $video ) ) {
				$output .=
				'<button class="component-video__play-button c-btn--play c-btn--color-alt" type="button"><span class="sr-only">' . __( 'Play video', 'catapult' ) . '</span></button>
				</figure>';
			} else {
				$output .= '</figure>';
			}
		}

		return $output;
	}


	/**
	 * Get block background image and/or video.
	 *
	 * @param  string  $image_size   Image size.
	 * @param  string  $classes      Classes to add to the wrapper elements.
	 * @param  boolean $decorative   Whether the image should be considered decorative and hidden from assistive technologies.
	 *
	 * @return string background image/video html.
	 */
	public function get_block_background_image_and_video( $image_size = 'full-width', $classes = '', $decorative = false ): string {
		$output           = '';
		$background_image = get_field( 'background_image' );
		$background_video = get_field( 'background_video', false, false );

		if ( ! empty( $classes ) ) {
			$classes = ' ' . $classes;
		}

		if ( false !== strpos( $this->name, 'hero' ) && empty( $background_image ) ) {
			$background_image = get_post_thumbnail_id();
		}

		if ( ! empty( $background_image ) ) {
			$focus       = get_post_meta( $background_image, '_wpsmartcrop_image_focus', true );
			$image_style = '';

			if ( ! empty( $focus ) && ! empty( $focus['top'] && ! empty( $focus['left'] ) ) ) {
				$image_style = 'object-position: ' . $focus['left'] . '% ' . $focus['top'] . '%;';
			}

			$output .=
				'<figure class="acf-block__background-image-wrapper' . $classes . '">' .
				wp_get_attachment_image(
					$background_image,
					$image_size,
					'',
					array(
						'class'       => 'acf-block__background-image',
						'style'       => $image_style,
						'aria-hidden' => $decorative ? 'true' : 'false',
					)
				)
				. '</figure>';
		}

		if ( ! empty( $background_video ) ) {
			$background_video_ratio        = get_field( 'background_video_ratio' );
			$loop_background_video         = get_field( 'loop_background_video' );
			$background_video_ratio_width  = 16;
			$background_video_ratio_height = 9;

			if ( ! empty( $background_video_ratio ) ) {
				if ( ! empty( $background_video_ratio['width'] ) ) {
					$background_video_ratio_width = $background_video_ratio['width'];
				}

				if ( ! empty( $background_video_ratio['height'] ) ) {
					$background_video_ratio_height = $background_video_ratio['height'];
				}
			}

			if ( empty( $loop_background_video ) ) {
				$loop_background_video = 'false';
			} else {
				$loop_background_video = 'true';
			}

			$output .=
				'<div class="acf-block__background-video-wrapper' . $classes . '" data-video-url="' . esc_url( $background_video ) . '" data-video-loop="' . $loop_background_video . '" style="--videoRatio: ' . $background_video_ratio_width / $background_video_ratio_height . ';"></div>';
		}

		return $output;
	}

	/**
	 * Get bootstrap column classes from an array of breakpoints.
	 *
	 * @param array $sizes    Sizes to get classes for.
	 * @param array $defaults The default sizes.
	 *
	 * @return string column classes.
	 */
	public function get_column_classes( array $sizes, array $defaults = array() ): string {
		$classes = array_map(
			function ( $viewport, $size ) use ( $defaults ) {
				if ( empty( $size ) && isset( $defaults[ $viewport ] ) ) {
					$size = $defaults[ $viewport ];
				}

				if ( 'mobile' === $viewport ) {
					$viewport = 'sm';
				} elseif ( 'tablet' === $viewport ) {
					$viewport = 'md';
				} elseif ( 'desktop' === $viewport ) {
					$viewport = 'lg';
				}

				return "col-$viewport-$size";
			},
			array_keys( $sizes ),
			$sizes
		);

		return 'col-12 ' . implode( ' ', $classes );
	}

	/**
	 * Get bootstrap column classes from an array of breakpoints.
	 *
	 * @param string $field_name    The ACF field name.
	 * @param string $parent_block  The parent block name. Can contain acf/ prefix.
	 *
	 * @return string column classes.
	 */
	public function get_parent_field( $field_name, $parent_block ) {
		$parent_block = str_replace( 'acf/', '', $parent_block );
		$value        = '';

		if ( ! empty( $this->context ) && ! empty( $this->context[ $parent_block ] ) && ! empty( $this->context[ $parent_block ][ $field_name ] ) ) {
			$value = $this->context[ $parent_block ][ $field_name ];
		}

		return $value;
	}

	/**
	 * Function to allow easily adding search/replace strings to the render_block WordPress filter.
	 *
	 * @param string $search_string       Search for this string to replace content in the rendered block output. Can include regex.
	 * @param string $replace_string      Replace the search string in the rendered block output with this string.
	 * @param int    $limit               The maximum possible replacements for each pattern. Defaults to -1 (no limit).
	 */
	public function replace( $search_string, $replace_string, $limit = -1 ) {
		$this->search_string  = $search_string;
		$this->replace_string = $replace_string;
		$this->limit          = $limit;

		add_filter( 'render_block', array( $this, 'render_block' ), 10, 2 );
	}

	/**
	 * Filter the rendered block output.
	 *
	 * @param string $block_content The block content about to be appended.
	 * @param array  $block         The full block, including name and attributes.
	 */
	public function render_block( $block_content, $block ) {
		if ( ! is_admin() && ! empty( $block['blockName'] && ! empty( $this->name ) ) && $this->name === $block['blockName'] && ! empty( $this->search_string ) && ! empty( $this->replace_string ) ) {
			if ( 0 === strpos( $this->search_string, '/' ) || $this->limit > -1 ) {
				$block_content = preg_replace( $this->search_string, $this->replace_string, $block_content, $this->limit );
			} else {
				$block_content = str_replace( $this->search_string, $this->replace_string, $block_content );
			}
		}

		remove_filter( 'render_block', array( $this, 'render_block' ), 10, 2 );

		return $block_content;
	}
}
