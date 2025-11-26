<?php
/**
 * A class to handle ACF block registration and asset enqueuing for all blocks.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   3.0.0
 * @since   3.0.3
 * @since   3.0.5
 * @since   3.0.7
 * @since   3.0.9
 * @since   3.0.10
 * @since   3.0.12
 * @since   3.0.14
 * @since   3.0.16
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.2
 * @since   3.1.6
 * @since   3.1.7
 */

defined( 'ABSPATH' ) || die();

/**
 * A class to handle ACF block registration and asset enqueuing for all blocks.
 */
class Theme_Core_Blocks extends Theme_Core_Component {
	/**
	 * The blocks directory.
	 *
	 * The class uses this directory to search for blocks to register on initialization.
	 *
	 * @access protected
	 * @var    string    $blocks_directory The path to the blocks directory.
	 */
	protected $blocks_directory;

	/**
	 * An array of block data from the header of each block.
	 *
	 * @access protected
	 * @var    array
	 */
	public $all_block_data;

	/**
	 * An array of background colors as defined in the css/__base-includes/_variables.scss file.
	 *
	 * @access public
	 * @var array
	 */
	public $background_colors = array();

	/**
	 * An array of all the unique categories specified in each block.php file.
	 *
	 * @access public
	 * @var array
	 */
	public $categories = array( 'Core', 'Navigation' );

	/**
	 * Init function.
	 *
	 * This function runs during init and can be used to set up other functions or the main functionality of the class.
	 */
	protected function init() {
		$this->get_background_colors();

		add_filter( 'block_categories_all', array( $this, 'add_block_category' ) );
		add_filter( 'admin_menu', array( $this, 'add_block_patterns_admin_menu' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_existing_block_assets' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_unused_optional_component_assets' ), 12 );
		add_filter( 'get_header', array( $this, 'load_global_blocks' ) );
		add_filter( 'acf/blocks/wrap_frontend_innerblocks', array( $this, 'wrap_frontend_innerblocks' ), 10, 2 );
		add_action( 'rest_api_init', array( $this, 'register_rest_route' ) );

		$this->blocks_directory = get_template_directory() . '/blocks/acf-blocks/';

		add_action( 'init', array( $this, 'register_acf_blocks' ) );
	}

	/**
	 * Set $background_colors to the background colors defined in the css/__base-includes/_variables.scss file.
	 */
	private function get_background_colors() {
		$variables_path = get_stylesheet_directory() . '/css/__base-includes/_variables.scss';

		if ( file_exists( $variables_path ) ) {
			$variables_file_contents = file_get_contents( $variables_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

			if ( ! empty( $variables_file_contents ) ) {
				preg_match( '/(?<=\$background-colors: \()(.|\n)*?(?=\)\;)/m', $variables_file_contents, $background_color_string_match );

				if ( ! empty( $background_color_string_match ) ) {
					preg_match_all( '/(?<=").*?(?=")/m', $background_color_string_match[0], $background_color_name_matches );

					if ( ! empty( $background_color_name_matches ) ) {
						foreach ( $background_color_name_matches[0] as $background_color_name_match ) {
							$this->background_colors[ $background_color_name_match ] = ucwords( str_replace( '-', ' ', $background_color_name_match ) );
						}
					}
				}
			}
		}
	}

	/**
	 * Search the block directory for any ACF blocks that need to be registered.
	 */
	public function register_acf_blocks() {
		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		$block_paths = array_merge( glob( $this->blocks_directory . '*/block.php' ), glob( $this->blocks_directory . '*/**/block.php' ) );
		$categories  = array();

		require_once dirname( __DIR__, 1 ) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'icon786.php';

		if ( ! empty( $block_paths ) ) {
			foreach ( $block_paths as $block_path ) {
				preg_match( '/\/([A-Za-z0-9-_]+?)\/block\.php$/', $block_path, $matches );
				$block_name = ! empty( $matches ) && ! empty( $matches[1] ) ? $matches[1] : null;
				$block_data = get_file_data(
					$block_path,
					array(
						'title'                    => 'Title',
						'description'              => 'Description',
						'instructions'             => 'Instructions',
						'category'                 => 'Category',
						'icon'                     => 'Icon',
						'keywords'                 => 'Keywords',
						'post_types'               => 'Post Types',
						'multiple'                 => 'Multiple',
						'active'                   => 'Active',
						'inner_blocks'             => 'InnerBlocks',
						'wrap_inner_blocks'        => 'Wrap InnerBlocks',
						'mode'                     => 'Mode',
						'parent'                   => 'Parent',
						'ancestor'                 => 'Ancestor',
						'global_acf_fields'        => 'Global ACF Fields',
						'default_background_color' => 'Default BG Color',
						'background_colors'        => 'Background Colors',
						'styles'                   => 'Styles',
						'context'                  => 'Context',
						'cssdeps'                  => 'CSS Deps',
						'jsdeps'                   => 'JS Deps',
						'image_size'               => 'Image Size',
						'image_wrapper'            => 'Image Wrapper',
						'text_width_styles'        => 'Text Width Styles',
						'starts_with_text'         => 'Starts With Text',
						'button_styles'            => 'Button Styles',
						'css_custom_props'         => 'CSS Custom Props',
					)
				);

				$block_data['block_path'] = $block_path;

				$this->all_block_data[ $block_name ] = $block_data;

				if ( 'true' === $block_data['active'] || true === $block_data['active'] || ( in_array( wp_get_environment_type(), array( 'local', 'development' ), true ) && ( catapult_is_block_library() || ( ! empty( $_GET['post'] ) && 'acf-field-group' === get_post_type( sanitize_text_field( wp_unslash( $_GET['post'] ) ) ) ) ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( is_string( $block_data['icon'] ) ) {
						$block_data['icon'] = str_replace( 'dashicons-', '', $block_data['icon'] );

						if ( 0 === strpos( $block_data['icon'], 'icon786' ) ) {
							$block_data_icon = $block_data_icon786[ $block_data['icon'] ] ?? 'block-default';
						} else {
							$block_data_icon = $block_data['icon'];
						}
					} else {
						$block_data_icon = 'block-default';
					}

					$block_settings = array(
						'name'              => $block_name,
						'title'             => $block_data['title'],
						'description'       => $block_data['description'],
						'instructions'      => $block_data['instructions'],
						'active'            => $block_data['active'],
						'template_path'     => $block_path,
						'render_callback'   => array( $this, 'render_block_callback' ),
						'category'          => sanitize_title( $block_data['category'] ),
						'icon'              => $block_data_icon,
						'api_version'       => 2,
						'acf_block_version' => 2,
						'mode'              => 'auto',
						'keywords'          => array_map( 'trim', explode( ',', $block_data['keywords'] ) ),
						'supports'          => array(
							'align' => false,
							'jsx'   => true,
						),
						'example'           => array(
							'viewportWidth' => 1680,
							'attributes'    => array(
								'mode' => 'preview',
								'data' => array(),
							),
						),
						'enqueue_assets'    => function () use ( $block_name, $block_path, $block_data ) {
							$src_css_path      = str_replace( 'block.php', 'style.scss', $block_path );
							$compiled_css_uri  = get_template_directory_uri() . '/dist/acf-blocks/' . $block_name . '-editor-styles.css';

							$src_editor_css_path      = str_replace( 'block.php', 'editor.scss', $block_path );
							$compiled_editor_css_uri  = get_template_directory_uri() . '/dist/acf-blocks/' . $block_name . '-editor.css';

							if ( is_admin() ) {
								if ( file_exists( $src_css_path ) ) {
									wp_enqueue_style( 'acf-' . $block_name, $compiled_css_uri, array( 'wp-reset-editor-styles', 'theme-styles' ), filemtime( $src_css_path ) );
								}

								if ( file_exists( $src_editor_css_path ) ) {
									wp_enqueue_style( $block_name . '-editor-style', $compiled_editor_css_uri, array( 'wp-reset-editor-styles', 'theme-styles' ), filemtime( $src_editor_css_path ) );
								}
							}
						},
					);

					/**
					* Temporarily disabled. This code loads content from block library posts to display as block preview. But currently causes slow performance when editing pages. Need different solution here.
					*
					* phpcs:ignore Generic.Commenting.DocComment.LongNotCapital
					*
					if ( is_admin() ) {
						$block_library_post = get_page_by_title( $block_data['title'], OBJECT, 'library_block' );

						if ( ! empty( $block_library_post ) ) {
							$block_library_post_blocks = parse_blocks( $block_library_post->post_content );

							if ( ! empty( $block_library_post_blocks ) ) {
								foreach ( $block_library_post_blocks as $block_library_post_block ) {
									if ( ! empty( $block_library_post_block['blockName'] ) && 'acf/' . $block_name === $block_library_post_block['blockName'] ) {
										if ( ! empty( $block_library_post_block['attrs'] ) ) {
											$block_settings['example']['attributes'] = $block_library_post_block['attrs'];
										}

										if ( ! empty( $block_library_post_block['innerBlocks'] ) ) {
											$block_settings['example']['innerBlocks'] = $this->parse_example_inner_blocks( $block_library_post_block['innerBlocks'] );
										}
										break;
									}
								}
							}
						}
					}
					*/

					if ( ! empty( $block_data['post_types'] ) && 'all' !== $block_data['post_types'] ) {
						$block_settings['post_types'] = array_map( 'trim', explode( ',', $block_data['post_types'] ) );
					}

					if ( 'true' === $block_data['inner_blocks'] ) {
						$block_settings['supports']['jsx'] = true;
						$block_settings['mode']            = 'preview';
					}

					if ( ! empty( $block_data['mode'] ) ) {
						$block_settings['mode'] = $block_data['mode'];
					}

					if ( 'false' === $block_data['multiple'] ) {
						$block_settings['supports']['multiple'] = false;
					}

					if ( catapult_is_block_library() ) {
						$block_settings['supports']['multiple'] = true;
					}

					if ( ! empty( $block_data['image_size'] ) ) {
						$block_settings['image_size'] = $block_data['image_size'];
					}

					if ( ! empty( $block_data['image_wrapper'] ) && 'false' !== $block_data['image_wrapper'] ) {
						$block_settings['image_wrapper'] = true;
					}

					if ( ! empty( $block_data['starts_with_text'] ) && 'false' !== $block_data['starts_with_text'] ) {
						$block_settings['starts_with_text'] = true;
					}

					$block_settings['attributes']['data'] = array();
					$block_settings['provides_context']   = array( $block_name => 'data' );

					if ( ! empty( $block_data['text_width_styles'] ) && 'true' === $block_data['text_width_styles'] ) {
						$block_settings['provides_context']['text_width_styles'] = true;
					}

					if ( ! empty( $block_data['button_styles'] ) ) {
						$block_settings['button_styles'] = array_map( 'trim', explode( ',', $block_data['button_styles'] ) );
					}

					if ( ! empty( $block_data['parent'] ) ) {
						$parent_blocks = array_map( 'trim', explode( ',', $block_data['parent'] ) );

						$block_settings['parent'] = array_map(
							function ( $full_block_name ) {
								if ( false === strpos( $full_block_name, '/' ) ) {
									$full_block_name = 'acf/' . $full_block_name;
								}

								return $full_block_name;
							},
							$parent_blocks
						);

						$block_settings['uses_context'] = array_map(
							function ( $full_block_name ) {
								return str_replace( 'acf/', '', $full_block_name );
							},
							$parent_blocks
						);
					}

					if ( ! empty( $block_data['ancestor'] ) ) {
						$ancestor_blocks = array_map( 'trim', explode( ',', $block_data['ancestor'] ) );

						$block_settings['ancestor'] = array_map(
							function ( $full_block_name ) {
								if ( false === strpos( $full_block_name, '/' ) ) {
									$full_block_name = 'acf/' . $full_block_name;
								}

								return $full_block_name;
							},
							$ancestor_blocks
						);

						$block_settings['uses_context'] = array_map(
							function ( $full_block_name ) {
								return str_replace( 'acf/', '', $full_block_name );
							},
							$ancestor_blocks
						);
					}

					if ( ! empty( $block_data['context'] ) ) {
						$context = array_map( 'trim', explode( ',', str_replace( 'acf/', '', $block_data['context'] ) ) );

						if ( empty( $block_settings['uses_context'] ) ) {
							$block_settings['uses_context'] = array();
						}

						$block_settings['uses_context'] = array_merge( $block_settings['uses_context'], $context );
						$block_settings['uses_context'] = array_unique( $block_settings['uses_context'] );
					}

					if ( ! empty( $block_data['styles'] ) ) {
						$block_styles             = array_map( 'trim', explode( ',', $block_data['styles'] ) );
						$block_settings['styles'] = array();

						foreach ( $block_styles as $key => $block_style ) {
							$default = false;

							if ( 0 === $key ) {
								$default = true;
							}

							$block_settings['styles'][] = array(
								'name'      => sanitize_title( $block_style ),
								'label'     => $block_style,
								'isDefault' => $default,
							);
						}
					}

					if ( ! empty( $block_data['category'] ) ) {
						$categories[] = $block_data['category'];
					}

					$fields          = array();
					$sanitized_title = sanitize_title( $block_name );

					if ( ! empty( $block_data['global_acf_fields'] ) ) {
						$global_acf_fields = array_map( 'trim', explode( ',', $block_data['global_acf_fields'] ) );

						if ( in_array( 'image', $global_acf_fields, true ) ) {
							$block_settings['example']['attributes']['data']['image'] = 'placeholder';

							$fields[] = array(
								'key'               => 'field_acf_block_image',
								'label'             => 'Image',
								'name'              => 'image',
								'type'              => 'image',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'return_format'     => 'id',
								'preview_size'      => 'medium',
								'library'           => 'all',
								'min_width'         => '',
								'min_height'        => '',
								'min_size'          => '',
								'max_width'         => '',
								'max_height'        => '',
								'max_size'          => '',
								'mime_types'        => '',
							);
						}

						if ( in_array( 'video', $global_acf_fields, true ) ) {
							$block_settings['example']['attributes']['data']['video'] = 'placeholder';

							$fields[] = array(
								'key'               => 'field_acf_block_video',
								'label'             => 'Video',
								'name'              => 'video',
								'aria-label'        => '',
								'type'              => 'oembed',
								'instructions'      => 'Please ensure you also upload an image when using the video field.',
								'required'          => 0,
								'conditional_logic' => 0,
								'width'             => '',
								'height'            => '',
							);
						}

						if ( in_array( 'background_image', $global_acf_fields, true ) ) {
							$block_settings['example']['attributes']['data']['background_image'] = 'placeholder';

							$fields[] = array(
								'key'               => 'field_acf_block_background_image',
								'label'             => 'Background Image',
								'name'              => 'background_image',
								'type'              => 'image',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'return_format'     => 'id',
								'preview_size'      => 'medium',
								'library'           => 'all',
								'min_width'         => '',
								'min_height'        => '',
								'min_size'          => '',
								'max_width'         => '',
								'max_height'        => '',
								'max_size'          => '',
								'mime_types'        => '',
							);
						}

						if ( in_array( 'background_video', $global_acf_fields, true ) ) {
							$block_settings['example']['attributes']['data']['image'] = 'placeholder';

							$fields[] = array(
								'key'               => 'field_acf_block_background_video',
								'label'             => 'Background Video',
								'name'              => 'background_video',
								'aria-label'        => '',
								'type'              => 'oembed',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'width'             => '',
								'height'            => '',
							);

							$fields[] = array(
								'key'               => 'field_acf_block_background_video_ratio',
								'label'             => 'Background Video Ratio',
								'name'              => 'background_video_ratio',
								'aria-label'        => '',
								'type'              => 'group',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_acf_block_background_video',
											'operator' => '!=empty',
										),
									),
								),
								'layout'            => 'table',
								'sub_fields'        => array(
									array(
										'key'           => 'field_acf_block_background_video_ratio_width',
										'label'         => 'Width',
										'name'          => 'width',
										'aria-label'    => '',
										'type'          => 'number',
										'instructions'  => '',
										'required'      => 0,
										'conditional_logic' => 0,
										'default_value' => 16,
										'min'           => '',
										'max'           => '',
										'placeholder'   => '',
										'step'          => '',
										'prepend'       => '',
										'append'        => '',
									),
									array(
										'key'           => 'field_acf_block_background_video_ratio_height',
										'label'         => 'Height',
										'name'          => 'height',
										'aria-label'    => '',
										'type'          => 'number',
										'instructions'  => '',
										'required'      => 0,
										'conditional_logic' => 0,
										'default_value' => 9,
										'min'           => '',
										'max'           => '',
										'placeholder'   => '',
										'step'          => '',
										'prepend'       => '',
										'append'        => '',
									),
								),
							);

							$fields[] = array(
								'key'               => 'field_acf_block_loop_background_video',
								'label'             => 'Loop Background Video',
								'name'              => 'loop_background_video',
								'aria-label'        => '',
								'type'              => 'true_false',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_acf_block_background_video',
											'operator' => '!=empty',
										),
									),
								),
								'message'           => '',
								'default_value'     => 1,
								'ui_on_text'        => '',
								'ui_off_text'       => '',
								'ui'                => 1,
							);
						}

						if ( ( in_array( 'background_color', $global_acf_fields, true ) || ! empty( $block_data['background_colors'] ) ) && ! empty( $this->background_colors ) ) {
							$default_background_color = '';
							$background_colors        = $this->background_colors;

							if ( ! empty( $block_data['default_background_color'] ) ) {
								$default_background_color = $block_data['default_background_color'];
							}

							if ( ! empty( $block_data['background_colors'] ) ) {
								$background_color_data = array_map( 'trim', explode( ',', $block_data['background_colors'] ) );
								$background_colors     = array();

								foreach ( $background_color_data as $background_color ) {
									$background_colors[ $background_color ] = ucwords( str_replace( '-', ' ', $background_color ) );
								}
							}

							$fields[] = array(
								'key'           => 'field_acf_block_background_color_' . $sanitized_title,
								'label'         => 'Background Color',
								'name'          => 'background_color',
								'type'          => 'select',
								'choices'       => $background_colors,
								'default_value' => $default_background_color,
								'allow_null'    => true,
							);
						}

						if ( in_array( 'scroll_id', $global_acf_fields, true ) ) {
							$fields[] = array(
								'key'   => 'field_acf_block_scroll_id',
								'label' => 'Scroll ID',
								'name'  => 'scroll_id',
								'type'  => 'text',
							);
						}
					}

					if ( empty( $block_data['parent'] ) ) {
						$fields[] = array(
							'key'           => 'field_acf_block_gated_content',
							'label'         => 'Gated Content',
							'name'          => 'gated_content',
							'type'          => 'select',
							'instructions'  => __( 'A "Form (Catapult)" block must be included somewhere on the page in order to ungate content.', 'catapult' ),
							'choices'       => array(
								'visible' => __( 'Always Visible', 'catapult' ),
								'ungated' => __( 'Visible before ungating', 'catapult' ),
								'gated'   => __( 'Visible after ungating', 'catapult' ),
							),
							'default_value' => 'always',
							'allow_null'    => false,
						);
					}

					if ( ! empty( $fields ) ) {
						acf_add_local_field_group(
							array(
								'key'        => 'group_acf_block_global_options_' . $sanitized_title,
								'title'      => 'Options',
								'menu_order' => '999',
								'fields'     => $fields,
								'location'   => array(
									array(
										array(
											'param'    => 'block',
											'operator' => '==',
											'value'    => 'acf/' . $sanitized_title,
										),
									),
								),
							)
						);
					}

					if ( ! empty( $block_data['css_custom_props'] ) ) {
						$block_settings['css_custom_props'] = array_map( 'trim', explode( ',', $block_data['css_custom_props'] ) );

						foreach ( $block_settings['css_custom_props'] as $css_custom_prop_key => $css_custom_prop ) {
							preg_match( '/([A-Za-z0-9-_]*)[:= ]*([A-Za-z0-9-_]*)/m', $css_custom_prop, $css_custom_prop_matches );

							$block_settings['css_custom_props'][ $css_custom_prop_key ] = array(
								'name'  => $css_custom_prop,
								'field' => get_field_object( $css_custom_prop ),
							);

							if ( ! empty( $css_custom_prop_matches ) ) {
								$block_settings['css_custom_props'][ $css_custom_prop_key ]['name']    = $css_custom_prop_matches[1];
								$block_settings['css_custom_props'][ $css_custom_prop_key ]['default'] = $css_custom_prop_matches[2];
							}
						}
					}

					acf_register_block_type( $block_settings );
				}
			}
		}

		if ( ! empty( $categories ) ) {
			$this->categories = array_unique( array_merge( $this->categories, $categories ) );
		}
	}

	/**
	 * Renders the block.
	 *
	 * @param   array    $block The block attributes.
	 * @param   string   $content The block content.
	 * @param   bool     $is_preview Whether or not the block is being rendered for editing preview.
	 * @param   int      $post_id The current post being edited or viewed.
	 * @param   WP_Block $wp_block The block instance (since WP 5.5).
	 * @param   array    $context The block context array.
	 * @return  void|string
	 */
	public function render_block_callback( $block, $content, $is_preview, $post_id, $wp_block, $context ) {
		if ( empty( $is_preview ) ) {
			if ( defined( 'REST_REQUEST' ) && ! empty( REST_REQUEST ) && ( empty( $GLOBALS['wp']->query_vars['rest_route'] ) || false === strpos( $GLOBALS['wp']->query_vars['rest_route'], '/catapult/v1/post-content/' ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			if ( wp_doing_ajax() ) {
				return;
			}

			if ( is_admin() ) {
				return;
			}
		}

		if ( file_exists( $block['template_path'] ) && ! empty( $block['name'] ) ) {
			$block_name = str_replace( 'acf/', '', $block['name'] );

			if ( ! empty( $this->all_block_data[ $block_name ] ) && ! empty( $this->all_block_data[ $block_name ]['active'] ) && ( 'true' === $this->all_block_data[ $block_name ]['active'] || true === $this->all_block_data[ $block_name ]['active'] || ( in_array( wp_get_environment_type(), array( 'local', 'development' ), true ) && catapult_is_block_library() ) ) ) {
				include $block['template_path'];
			}
		}
	}

	/**
	 * Loads the block styles in the header.
	 */
	public function load_existing_block_assets() {
		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		if ( empty( $this->all_block_data ) ) {
			return;
		}

		global $blocks;
		global $innerblocks;

		if ( empty( $blocks ) ) {
			return;
		}

		$blocks_to_enqueue = array_map(
			function ( $block ) {
				if ( ! empty( $block['blockName'] ) ) {
					return $block['blockName'];
				}
			},
			$blocks
		);

		if ( ! empty( $blocks ) ) {
			$blocks_to_enqueue = array_column( $blocks, 'blockName' );
		}

		if ( ! empty( $innerblocks ) ) {
			$blocks_to_enqueue = array_merge( $blocks_to_enqueue, array_column( $innerblocks, 'blockName' ) );
		}

		$blocks_to_enqueue = array_unique( $blocks_to_enqueue );

		foreach ( $blocks_to_enqueue as $block_name_with_prefix ) {
			if ( empty( $block_name_with_prefix ) ) {
				continue;
			}

			if ( 0 === strpos( $block_name_with_prefix, 'core/' ) ) {
				$block_name = str_replace( 'core/', '', $block_name_with_prefix );

				wp_deregister_style( 'wp-block-' . $block_name );
				wp_enqueue_style( 'core-' . $block_name );
				wp_enqueue_script( 'core-' . $block_name );
			} elseif ( 0 === strpos( $block_name_with_prefix, 'catapult/' ) ) {
				$block_name = str_replace( 'catapult/', '', $block_name_with_prefix );
				wp_enqueue_style( 'catapult-' . $block_name );
				wp_enqueue_script( 'catapult-' . $block_name );
				wp_enqueue_style( 'catapult-' . $block_name . '-view' );
				wp_enqueue_script( 'catapult-' . $block_name . '-view' );
			} elseif ( false !== strpos( $block_name_with_prefix, 'contact-form-7' ) ) {
				wp_enqueue_style( 'catapult-component-cf7' );
				wp_enqueue_script( 'catapult-component-cf7' );
			}
		}

		foreach ( $this->all_block_data as $block_name => $block_data ) {
			if ( in_array( 'acf/' . $block_name, $blocks_to_enqueue, true ) ) {
				$this->enqueue_block_styles_and_scripts( $block_name, $block_data['block_path'], $block_data );
			}
		}
	}

	/**
	 * Checks for optional components and if they don't exist, dequeue their assets.
	 */
	public function dequeue_unused_optional_component_assets() {
		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		if ( empty( $this->all_block_data ) ) {
			return;
		}

		global $blocks;
		global $innerblocks;

		if ( empty( $blocks ) ) {
			return;
		}

		$has_video_component            = false;
		$has_background_video_component = false;

		foreach ( array_merge( $blocks, $innerblocks ) as $block ) {
			if ( $has_video_component && $has_background_video_component ) {
				break;
			}

			if ( empty( $block['blockName'] ) ) {
				continue;
			}

			$block_name = str_replace( 'acf/', '', $block['blockName'] );

			if ( empty( $this->all_block_data[ $block_name ] ) ) {
				continue;
			}

			if ( empty( $this->all_block_data[ $block_name ]['global_acf_fields'] ) ) {
				continue;
			}

			if ( empty( $block['attrs'] ) || empty( $block['attrs']['data'] ) ) {
				continue;
			}

			if ( empty( $block['attrs']['data']['video'] ) && empty( $block['attrs']['data']['background_video'] ) ) {
				continue;
			}

			$global_acf_fields = array_map( 'trim', explode( ',', $this->all_block_data[ $block_name ]['global_acf_fields'] ) );

			if ( empty( $has_video_component ) && in_array( 'video', $global_acf_fields, true ) && ! empty( $block['attrs']['data']['video'] ) ) {
				$has_video_component = true;
			}

			if ( empty( $has_background_video_component ) && in_array( 'background_video', $global_acf_fields, true ) && ! empty( $block['attrs']['data']['background_video'] ) ) {
				$has_background_video_component = true;
			}
		}

		if ( $has_video_component ) {
			wp_enqueue_style( 'catapult-component-video' );
			wp_enqueue_script( 'catapult-component-video' );
		}

		if ( $has_background_video_component ) {
			wp_enqueue_style( 'catapult-component-background-video' );
			wp_enqueue_script( 'catapult-component-background-video' );
		}
	}

	/**
	 * Enqueue a block's styles and scripts.
	 *
	 * @param  array  $block_name  The block name.
	 * @param  string $block_path The path to the block PHP file.
	 * @param  array  $block_data Array of block data.
	 */
	public function enqueue_block_styles_and_scripts( $block_name, $block_path = null, $block_data = array() ) {
		if ( empty( $block_path ) ) {
			if ( ! empty( $this->all_block_data[ $block_name ] ) && ! empty( $this->all_block_data[ $block_name ]['block_path'] ) ) {
				$block_path = $this->all_block_data[ $block_name ]['block_path'];
			} else {
				return;
			}
		}

		if ( ! file_exists( $block_path ) ) {
			return;
		}

		if ( empty( $block_data ) ) {
			$block_data = get_file_data(
				$block_path,
				array(
					'title'             => 'Title',
					'cssdeps'           => 'CSS Deps',
					'jsdeps'            => 'JS Deps',
					'active'            => 'Active',
					'global_acf_fields' => 'Global ACF Fields',
				)
			);
		}

		if ( ( empty( $block_data['active'] ) || ( 'true' !== $block_data['active'] && true !== $block_data['active'] ) ) && ! in_array( wp_get_environment_type(), array( 'local', 'development' ), true ) ) {
			return;
		}

		if ( ! empty( $block_data['jsdeps'] ) ) {
			$block_data['jsdeps'] = str_replace( 'acf/', 'acf-', $block_data['jsdeps'] );
			$block_data['jsdeps'] = str_replace( 'core/', 'core-', $block_data['jsdeps'] );
			$block_data['jsdeps'] = str_replace( 'catapult/', 'catapult-', $block_data['jsdeps'] );
			$deps                 = array_unique( array_map( 'trim', explode( ',', $block_data['jsdeps'] ) ) );

			foreach ( $deps as $dep ) {
				if ( ! wp_script_is( $dep, 'enqueued' ) && false !== strpos( $dep, 'acf-' ) ) {
					$this->enqueue_block_styles_and_scripts( str_replace( 'acf-', '', $dep ) );
				}
			}
		}

		if ( ! empty( $block_data['cssdeps'] ) ) {
			$block_data['cssdeps'] = str_replace( 'acf/', 'acf-', $block_data['cssdeps'] );
			$block_data['cssdeps'] = str_replace( 'core/', 'core-', $block_data['cssdeps'] );
			$block_data['cssdeps'] = str_replace( 'catapult/', 'catapult-', $block_data['cssdeps'] );
			$deps                  = array_unique( array_map( 'trim', explode( ',', $block_data['cssdeps'] ) ) );

			foreach ( $deps as $dep ) {
				if ( ! wp_style_is( $dep, 'enqueued' ) && false !== strpos( $dep, 'acf-' ) ) {
					$this->enqueue_block_styles_and_scripts( str_replace( 'acf-', '', $dep ) );
				}
			}
		}

		$src_css_path     = str_replace( 'block.php', 'style.scss', $block_path );
		$compiled_css_uri = get_template_directory_uri() . '/dist/acf-blocks/' . $block_name . '.css';
		$src_js_path      = str_replace( 'block.php', 'script.js', $block_path );
		$compiled_js_path = get_template_directory() . '/dist/acf-blocks/' . $block_name . '.js';
		$compiled_js_uri  = get_template_directory_uri() . '/dist/acf-blocks/' . $block_name . '.js';

		$css_deps = array_filter( array_merge( array( 'theme-styles' ), array_map( 'trim', explode( ',', $block_data['cssdeps'] ) ) ) );
		$js_deps  = array_filter( array_map( 'trim', explode( ',', $block_data['jsdeps'] ) ) );

		if ( ! empty( $css_deps ) ) {
			foreach ( $css_deps as &$dep ) {
				if ( ! wp_style_is( $dep, 'registered' ) && wp_style_is( 'catapult-component-' . $dep, 'registered' ) ) {
					$dep = 'catapult-component-' . $dep;
				}

				if ( ! wp_style_is( $dep, 'registered' ) && wp_style_is( $dep . '-view', 'registered' ) ) {
					$dep = $dep . '-view';
				}

				if ( ! wp_style_is( $dep, 'enqueued' ) ) {
					$dep_without_prefix = str_replace( 'catapult-component-', '', $dep );
					$css_dep_path       = get_template_directory() . '/blocks/components/' . $dep_without_prefix . '/style.scss';
					$css_dep_uri        = get_template_directory_uri() . '/dist/components/' . $dep_without_prefix . '.css';

					if ( file_exists( $css_dep_path ) && ! is_admin() ) {
						wp_enqueue_style( $dep, $css_dep_uri, array(), filemtime( $css_dep_path ) );
					} elseif ( ! is_admin() && wp_style_is( $dep, 'registered' ) ) {
						wp_enqueue_style( $dep );
					}
				}
			}
		}

		if ( file_exists( $src_css_path ) && ! is_admin() ) {
			wp_enqueue_style( 'acf-' . $block_name, $compiled_css_uri, $css_deps, filemtime( $src_css_path ) );
		}

		if ( ! empty( $js_deps ) ) {
			foreach ( $js_deps as &$dep ) {
				if ( ! wp_script_is( $dep, 'registered' ) && wp_script_is( 'catapult-component-' . $dep, 'registered' ) ) {
					$dep = 'catapult-component-' . $dep;
				}

				if ( ! wp_script_is( $dep, 'registered' ) && wp_script_is( $dep . '-view', 'registered' ) ) {
					$dep = $dep . '-view';
				}

				if ( ! wp_script_is( $dep, 'enqueued' ) ) {
					$dep_without_prefix = str_replace( 'catapult-component-', '', $dep );
					$js_dep_path        = get_template_directory() . '/blocks/components/' . $dep_without_prefix . '/script.js';
					$js_dep_uri         = get_template_directory_uri() . '/dist/components/' . $dep_without_prefix . '.js';

					if ( file_exists( $js_dep_path ) && ! is_admin() ) {
						wp_enqueue_script( $dep, $js_dep_uri, catapult_get_script_dependences( $js_dep_path ), filemtime( $js_dep_path ), true );
					} elseif ( ! is_admin() && wp_script_is( $dep, 'registered' ) ) {
						wp_enqueue_script( $dep );
					}
				}
			}
		}

		if ( file_exists( $src_js_path ) && ! is_admin() ) {
			wp_enqueue_script( 'acf-' . $block_name, $compiled_js_uri, catapult_get_script_dependences( $compiled_js_path, $js_deps ), filemtime( $src_js_path ), true );
		}
	}

	/**
	 * Add a block category for all computan ACF blocks.
	 *
	 * @param  array $categories The existing block categories.
	 * @return array The modified block categories array.
	 */
	public function add_block_category( $categories ) {
		if ( empty( $this->categories ) ) {
			return $categories;
		}

		foreach ( $this->categories as $category ) {
			$categories[] = array(
				'slug'  => sanitize_title( $category ),
				'title' => $category,
			);
		}

		return $categories;
	}

	/**
	 * This function parses all blocks on a page and saves them to the global $blocks variable. Any blocks in this variable will automatically have their CSS/JS assets loaded on the frontend. If a certain template uses the render_theme_block() function, then that theme location needs to have its blocks loaded here (see the 404 theme location for an example).
	 */
	public function load_global_blocks() {
		if ( ! class_exists( 'ACF' ) ) {
			return;
		}

		global $blocks;
		global $innerblocks;

		$blocks      = array();
		$innerblocks = array();

		if ( is_singular() ) {
			global $post;

			if ( ! empty( $post->post_content ) ) {
				$blocks = parse_blocks( $post->post_content );
			}

			$blocks = $this->load_theme_block_location_blocks( $blocks, $post->post_type . '_top', 'before' );
			$blocks = $this->load_theme_block_location_blocks( $blocks, $post->post_type . '_sidebar', 'before' );
			$blocks = $this->load_theme_block_location_blocks( $blocks, $post->post_type . '_bottom' );
		} elseif ( is_archive() && ! is_post_type_archive( 'library_block' ) ) {
			$queried_object = get_queried_object();

			if ( $queried_object instanceof WP_Term ) {
				$blocks = $this->load_theme_block_location_blocks( $blocks, $queried_object->taxonomy, 'before' );
			} elseif ( $queried_object instanceof WP_Post_Type ) {
				$blocks = $this->load_theme_block_location_blocks( $blocks, $queried_object->name . '_archive', 'before' );
			}
		} elseif ( is_404() ) {
			$blocks = $this->load_theme_block_location_blocks( $blocks, '404_page' );
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$queried_object = get_queried_object();

			if ( ! empty( $queried_object->taxonomy ) ) {
				$blocks = $this->load_theme_block_location_blocks( $blocks, $queried_object->taxonomy );
			}
		} elseif ( is_home() ) {
			$blog_page_id = get_option( 'page_for_posts' );

			if ( ! empty( $blog_page_id ) ) {
				$blog_blocks = parse_blocks( get_the_content( null, false, $blog_page_id ) );

				if ( ! empty( $blog_blocks ) ) {
					$blocks = array_merge( $blocks, $this->parse_block_patterns( $blog_blocks ) );
				}
			}
		} elseif ( is_post_type_archive( 'library_block' ) ) {
			$blocks = array( array( 'blockName' => 'acf/block-library' ) );

			$args = array(
				'post_type'      => 'library_block',
				'post_status'    => array( 'publish' ),
				'posts_per_page' => -1,
				'order'          => 'ASC',
				'orderby'        => 'title',
			);

			$library_block_posts = get_posts( $args );

			foreach ( $library_block_posts as $library_block_post ) {
				$library_block_post_blocks = parse_blocks( $library_block_post->post_content );

				if ( ! empty( $library_block_post_blocks ) ) {
					$blocks = array_merge( $blocks, $this->parse_block_patterns( $library_block_post_blocks ) );
				}
			}

			$blocks = $this->load_theme_block_location_blocks( $blocks, 'all' );
		}

		$blocks = $this->load_theme_block_location_blocks( $blocks, 'alert_top' );
		$blocks = $this->load_theme_block_location_blocks( $blocks, 'alert_bottom' );
		$blocks = $this->load_theme_block_location_blocks( $blocks, 'alert_popup' );
		$blocks = $this->load_theme_block_location_blocks( $blocks, 'primary_navigation', 'before' );
		$blocks = $this->load_theme_block_location_blocks( $blocks, 'footer' );

		if ( ! empty( $blocks ) ) {
			$innerblocks = $this->parse_inner_blocks( $blocks );
			$blocks      = $this->parse_block_patterns( $blocks );
		}
	}

	/**
	 * Generate array with all blocks including synced block patterns.
	 *
	 * @param array $blocks  Array of the parsed blocks.
	 */
	public function parse_block_patterns( $blocks ) {
		$sorted_blocks = array();

		if ( ! empty( $blocks ) ) {
			foreach ( $blocks as $key => $block ) {
				$block_patterns = array();

				if ( ! empty( $block['blockName'] ) ) {
					if ( 'core/block' === $block['blockName'] && ! empty( $block['attrs']['ref'] ) ) {
						$content = get_post_field( 'post_content', $block['attrs']['ref'] );

						if ( ! empty( $content ) ) {
							$block_patterns = parse_blocks( $content );

							if ( ! empty( $block_patterns ) ) {
								$block_patterns = $this->parse_block_patterns( $block_patterns );
								$sorted_blocks  = array_merge( $sorted_blocks, $block_patterns );
							}
						}
					} else {
						$sorted_blocks[] = $block;
					}
				}
			}
		}

		return $sorted_blocks;
	}

	/**
	 * Generate array with all blocks including nested innerBlocks.
	 *
	 * @param array $blocks  Array of the parsed blocks.
	 */
	public function parse_inner_blocks( $blocks ) {
		$sorted_blocks = array();

		$blocks = $this->parse_block_patterns( $blocks );

		if ( ! empty( $blocks ) ) {
			foreach ( $blocks as $key => $block ) {
				$sorted_blocks[] = $block;

				if ( ! empty( $block['innerBlocks'] ) ) {
					$innerblocks   = $this->parse_inner_blocks( $block['innerBlocks'] );
					$sorted_blocks = array_merge( $sorted_blocks, $this->parse_block_patterns( $innerblocks ) );
				}
			}
		}

		return $sorted_blocks;
	}

	/**
	 * Parse inner block data and return for use as block example.
	 *
	 * @param array $inner_blocks  An array of inner block data.
	 *
	 * @return array Inner block data restructured for example json data.
	 */
	public function parse_example_inner_blocks( $inner_blocks ) {
		$inner_block_data = array();

		if ( ! empty( $inner_blocks ) ) {
			foreach ( $inner_blocks as $inner_block ) {
				if ( ! empty( $inner_block['blockName'] ) ) {
					$parsed_inner_block = array(
						'name'       => $inner_block['blockName'],
						'attributes' => array(),
					);

					if ( ! empty( $inner_block['attrs'] ) ) {
						$parsed_inner_block['attributes'] = $inner_block['attrs'];
					}

					if ( ! empty( $inner_block['innerBlocks'] ) ) {
						$parsed_inner_block['innerBlocks'] = $this->parse_example_inner_blocks( $inner_block['innerBlocks'] );
					}

					if ( ! empty( $inner_block['innerHTML'] ) ) {
						$parsed_inner_block['attributes']['content'] = $inner_block['innerHTML'];
					}

					$inner_block_data[] = $parsed_inner_block;
				}
			}
		}

		return $inner_block_data;
	}

	/**
	 * Disables the InnerBlocks wrapper element on the frontend for blocks that have it disabled.
	 *
	 * @param string $blocks             Array of currently loaded blocks.
	 * @param string $display_location   Load blocks assigned to this location.
	 * @param string $position           Whether to load the blocks before or after the existing blocks.
	 */
	public function load_theme_block_location_blocks( $blocks, $display_location, $position = 'after' ) {
		$args = array(
			'post_type'      => 'theme_block',
			'post_status'    => array( 'publish' ),
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'display_location',
					'value'   => '"' . $display_location . '"',
					'compare' => 'LIKE',
				),
			),
		);

		if ( 'all' === $display_location ) {
			unset( $args['meta_query'] );
		}

		$theme_block_posts = get_posts( $args );

		foreach ( $theme_block_posts as $theme_block_post ) {
			$theme_block_post_blocks = parse_blocks( $theme_block_post->post_content );

			if ( ! empty( $theme_block_post_blocks ) ) {
				if ( 'before' === $position ) {
					$blocks = array_merge( $this->parse_block_patterns( $theme_block_post_blocks ), $blocks );
				} else {
					$blocks = array_merge( $blocks, $this->parse_block_patterns( $theme_block_post_blocks ) );
				}
			}
		}

		return $blocks;
	}

	/**
	 * Disables the InnerBlocks wrapper element on the frontend for blocks that have it disabled.
	 *
	 * @param bool   $wrap  Whether or not to wrap the InnerBlocks element.
	 * @param string $name  The name of the block with acf/ prefix.
	 */
	public function wrap_frontend_innerblocks( $wrap, $name ) {
		$block_name = str_replace( 'acf/', '', $name );

		if ( ! empty( $this->all_block_data ) && ! empty( $this->all_block_data[ $block_name ] ) && ! empty( $this->all_block_data[ $block_name ]['wrap_inner_blocks'] ) && 'false' === $this->all_block_data[ $block_name ]['wrap_inner_blocks'] ) {
			$wrap = false;
		}

		return $wrap;
	}

	/**
	 * Adds block patterns to admin menu.
	 */
	public function add_block_patterns_admin_menu() {
		add_menu_page( __( 'Block Patterns', 'catapult' ), __( 'Block Patterns', 'catapult' ), 'edit_posts', 'edit.php?post_type=wp_block', '', 'dashicons-editor-table', 22 );
	}

	/**
	 * Register rest routes.
	 */
	public function register_rest_route() {
		register_rest_route(
			'catapult/v1',
			'/block-lighthouse-urls/',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_block_lighthouse_urls_route' ),
				'permission_callback' => array( $this, 'get_block_lighthouse_urls_permission' ),
			)
		);
	}

	/**
	 * Load the block Lighthouse URLs for the Block URLs REST API endpoint.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public function get_block_lighthouse_urls_route( $request ) {
		$args = array(
			'post_type'      => 'library_block',
			'post_status'    => array( 'publish' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);

		$block_ids = get_posts( $args );

		$urls = array();

		$archive_url = get_post_type_archive_link( 'library_block' );

		if ( ! empty( $block_ids ) ) {
			foreach ( $block_ids as $block_id ) {
				$urls[] = $archive_url . 'qa/' . get_post_field( 'post_name', $block_id );
			}
		}

		return $urls;
	}

	/**
	 * Checks if a given request has access to get block Lighthouse URLs.
	 *
	 * @since 3.0.0
	 * @since 3.0.3
	 * @since 3.0.5
	 * @since 3.0.7
	 * @since 3.0.9
	 * @since 3.0.10
	 * @since 3.0.12
	 * @since 3.0.14
	 * @since 3.0.16
	 * @since 3.0.17
	 * @since 3.0.19
	 * @since 3.1.2
	 * @since 3.1.6
	 * @since 3.1.7
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_block_lighthouse_urls_permission( $request ) {
		return true;
	}
}
