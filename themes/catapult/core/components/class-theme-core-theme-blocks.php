<?php
/**
 * Functions and hooks for the theme blocks.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.7
 * @since   3.0.10
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.0
 */

defined( 'ABSPATH' ) || die();

/**
 * Class managing the theme blocks post type.
 */
class Theme_Core_Theme_Blocks extends Theme_Core_Component {
	/**
	 * An array of theme block locations with post type locations added.
	 *
	 * @var array
	 */
	public $theme_block_locations;

	/**
	 * Add support for thumbnails and register all images sizes
	 * added in the settings file.
	 */
	protected function init() {
		if ( isset( $this->settings->theme_block_locations ) ) {
			add_action( 'init', array( $this, 'register_theme_block_post_type' ) );
			add_filter( 'wpseo_sitemap_exclude_post_type', array( $this, 'exclude_from_sitemap' ), 10, 2 );
			add_action( 'acf/init', array( $this, 'add_theme_block_fieldgroup' ), 10, 2 );
			add_filter( 'manage_theme_block_posts_columns', array( $this, 'add_location_column' ) );
			add_action( 'manage_theme_block_posts_custom_column', array( $this, 'add_location_column_values' ), 10, 2 );
		}
	}

	/**
	 * Register Theme Blocks post type.
	 */
	public function register_theme_block_post_type() {
		$labels = array(
			'name'                  => __( 'Theme Blocks', 'catapult' ),
			'singular_name'         => __( 'Theme Block', 'catapult' ),
			'menu_name'             => __( 'Theme Blocks', 'catapult' ),
			'name_admin_bar'        => __( 'Theme Blocks', 'catapult' ),
			'add_new'               => __( 'Add New', 'catapult' ),
			'add_new_item'          => __( 'Add New Theme Block', 'catapult' ),
			'new_item'              => __( 'New Theme Block', 'catapult' ),
			'edit_item'             => __( 'Edit Theme Block', 'catapult' ),
			'view_item'             => __( 'View Theme Block', 'catapult' ),
			'all_items'             => __( 'All Theme Blocks', 'catapult' ),
			'search_items'          => __( 'Search Theme Blocks', 'catapult' ),
			'parent_item_colon'     => __( 'Parent Theme Blocks:', 'catapult' ),
			'not_found'             => __( 'No theme blocks found.', 'catapult' ),
			'not_found_in_trash'    => __( 'No theme blocks found in Trash.', 'catapult' ),
			'featured_image'        => __( 'Theme Block Cover Image', 'catapult' ),
			'archives'              => __( 'Theme Blocks archives', 'catapult' ),
			'insert_into_item'      => __( 'Insert into theme block', 'catapult' ),
			'uploaded_to_this_item' => __( 'Uploaded to this theme block', 'catapult' ),
			'filter_items_list'     => __( 'Filter theme blocks list', 'catapult' ),
			'items_list_navigation' => __( 'Theme Blocks list navigation', 'catapult' ),
			'items_list'            => __( 'Theme Blocks list', 'catapult' ),
		);

		register_post_type(
			'theme_block',
			array(
				'label'               => __( 'Theme Blocks', 'catapult' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'revisions', 'editor', 'author' ),
				'taxonomies'          => array(),
				'public'              => false,
				'show_ui'             => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'menu_icon'           => 'dashicons-admin-generic',
				'has_archive'         => false,
				'show_in_rest'        => true,
				'menu_position'       => 2,
			)
		);
	}

	/**
	 * Exclude theme blocks from sitemap.
	 *
	 * @param bool   $exclude   Default false.
	 * @param string $post_type Post type name.
	 */
	public function exclude_from_sitemap( $exclude, $post_type ) {
		if ( 'theme_block' === $post_type ) {
			return true;
		}

		return $exclude;
	}

	/**
	 * Add ACF fields to theme blocks to add location option.
	 */
	public function add_theme_block_fieldgroup() {
		if ( empty( $this->settings->theme_block_locations ) ) {
			return;
		}

		$this->theme_block_locations = (array) $this->settings->theme_block_locations;

		// Check for ACF registered post types and taxonomies.
		$acf_post_types = glob( get_template_directory() . '/acf-json/post_type_*.json' );
		$acf_taxonomies = glob( get_template_directory() . '/acf-json/taxonomy_*.json' );

		if ( ! empty( $acf_post_types ) ) {
			foreach ( $acf_post_types as $acf_post_type ) {
				$json_data = file_get_contents( $acf_post_type ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				if ( empty( $json_data ) ) {
					continue;
				}

				$data = json_decode( $json_data, true );
				if ( empty( $data ) ) {
					continue;
				}

				if ( isset( $data['post_type'] ) ) {
					$data_post_type            = $data['post_type'];
					$data_post_type_taxonomies = $data['taxonomies'];
					$display_location_name     = ucwords( str_replace( '-', ' ', $data_post_type ) );

					$this->theme_block_locations[ $data_post_type . '_label' ]   = '<span>' . $display_location_name . '</span>';
					$this->theme_block_locations[ $data_post_type . '_top' ]     = sprintf( __( '%s - Top', 'catapult' ), $display_location_name );
					$this->theme_block_locations[ $data_post_type . '_bottom' ]  = sprintf( __( '%s - Bottom', 'catapult' ), $display_location_name );
					$this->theme_block_locations[ $data_post_type . '_archive' ] = sprintf( __( '%s - Archive', 'catapult' ), $display_location_name );

					if ( ! empty( $data_post_type_taxonomies ) ) {
						foreach ( $data_post_type_taxonomies as $post_type_taxonomy ) {
							$this->theme_block_locations[ $post_type_taxonomy ] = ucwords( str_replace( '-', ' ', $post_type_taxonomy ) );
						}
					}
				}
			}
		}

		if ( ! empty( $this->settings->post_types ) ) {
			foreach ( $this->settings->post_types as $post_type_slug => $post_type_args ) {
				if ( ! empty( $post_type_args->singular ) ) {
					$theme_block_location_name = $post_type_args->singular;
				} elseif ( ! empty( $post_type_args->plural ) ) {
					$theme_block_location_name = $post_type_args->plural;
				} else {
					$theme_block_location_name = $post_type_slug;
				}

				if ( ! empty( $post_type_args->plural ) ) {
					$theme_block_location_label = $post_type_args->plural;
				} elseif ( ! empty( $post_type_args->singular ) ) {
					$theme_block_location_label = $post_type_args->singular;
				} else {
					$theme_block_location_label = $post_type_slug;
				}

				if ( empty( $post_type_args ) || empty( $post_type_args->args ) || ! isset( $post_type_args->args->public ) || true === $post_type_args->args->public ) {
					$has_public_posts = true;
				}

				if ( empty( $post_type_args ) || empty( $post_type_args->args ) || ! isset( $post_type_args->args->has_archive ) || true === $post_type_args->args->has_archive ) {
					$has_archive = true;
				}

				if ( ! empty( $has_public_posts ) || ! empty( $has_archive ) ) {
					$this->theme_block_locations[ $post_type_slug . '_label' ] = '<span>' . $theme_block_location_label . '</span>';
				}

				if ( ! empty( $has_public_posts ) ) {
					$this->theme_block_locations[ $post_type_slug . '_top' ]     = sprintf( __( '%s - Top', 'catapult' ), $theme_block_location_name );
					$this->theme_block_locations[ $post_type_slug . '_bottom' ]  = sprintf( __( '%s - Bottom', 'catapult' ), $theme_block_location_name );
					$this->theme_block_locations[ $post_type_slug . '_sidebar' ] = sprintf( __( '%s - Sidebar', 'catapult' ), $theme_block_location_name );
				}

				if ( ! empty( $has_archive ) ) {
					$this->theme_block_locations[ $post_type_slug . '_archive' ] = sprintf( __( '%s - Archive', 'catapult' ), $theme_block_location_name );
				}

				if ( ! empty( $post_type_args->taxonomies ) ) {
					foreach ( $post_type_args->taxonomies as $taxonomy_slug => $taxonomy_args ) {
						if ( ! empty( $taxonomy_args ) && ! empty( $taxonomy_args->args ) && isset( $taxonomy_args->args->public ) && false === $taxonomy_args->args->public ) {
							continue;
						}

						if ( ! empty( $taxonomy_args->singular ) ) {
							$theme_block_location_name = $taxonomy_args->singular;
						} elseif ( ! empty( $taxonomy_args->plural ) ) {
							$theme_block_location_name = $taxonomy_args->plural;
						} else {
							$theme_block_location_name = $taxonomy_slug;
						}

						$this->theme_block_locations[ $taxonomy_slug ] = $theme_block_location_name;
					}
				}

				if ( ! empty( $post_type_args->theme_block_locations ) ) {
					$this->theme_block_locations = array_merge( $this->theme_block_locations, (array) $post_type_args->theme_block_locations );
				}
			}
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'theme_block_options',
				'title'                 => 'Block Options',
				'fields'                => array(
					array(
						'key'               => 'theme_block_display_location',
						'label'             => 'Display Location',
						'name'              => 'display_location',
						'aria-label'        => '',
						'type'              => 'checkbox',
						'instructions'      => __( 'Select the area of the theme where this block should be displayed.', 'catapult' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'choices'           => $this->theme_block_locations,
						'default_value'     => array(),
						'return_format'     => 'value',
						'ui'                => 0,
						'ajax'              => 0,
						'placeholder'       => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'theme_block',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'side',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'show_in_rest'          => 0,
			)
		);
	}

	/**
	 * Add display location column to theme block dashboard page.
	 *
	 * @param string[] $post_columns An associative array of column headings.
	 */
	public function add_location_column( $post_columns ) {
		$post_columns['theme_block_display_location'] = __( 'Display Location(s)', 'catapult' );

		return $post_columns;
	}

	/**
	 * Add display location values to theme block dashboard posts.
	 *
	 * @param string $column_name The name of the column to display.
	 * @param int    $post_id    The current post ID.
	 */
	public function add_location_column_values( $column_name, $post_id ) {
		if ( 'theme_block_display_location' === $column_name ) {
			$theme_block_display_location = get_post_meta( $post_id, 'display_location', true );

			if ( ! empty( $theme_block_display_location ) && ! empty( $this->theme_block_locations ) ) {
				foreach ( $theme_block_display_location as $display_location_slug ) {
					if ( ! empty( $this->theme_block_locations[ $display_location_slug ] ) ) {
						echo wp_kses_post( '<div>' . $this->theme_block_locations[ $display_location_slug ] . '</div>' );
					}
				}
			}
		}
	}
}
