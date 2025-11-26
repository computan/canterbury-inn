<?php
/**
 * The primary class for handling the theme icon system.
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.1.4
 * @since   3.1.5
 * @since   3.1.6
 */

defined( 'ABSPATH' ) || die();

/**
 * The primary class for handling the theme icon system.
 */
class Theme_Core_Icons extends Theme_Core_Component {
	/**
	 * An array of the icon data.
	 *
	 * @access public
	 * @var array
	 */
	private $icon_data = array();

	/**
	 * Kicks off this class' functionality.
	 */
	protected function init() {
		if ( ! class_exists( 'ACF' ) ) {
			return;
		}

		add_action( 'wp_head', array( $this, 'load_icon_styles' ), -999 );
		add_action( 'admin_head', array( $this, 'load_icon_styles' ), -999 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'localize_icon_data' ), 99 );
		add_action( 'init', array( $this, 'get_icon_data' ) );
		add_action( 'acf/load_field', array( $this, 'icon_field_choices' ) );
		add_action( 'acf/init', array( $this, 'add_custom_icons_fieldgroup' ), 10, 2 );
		add_action( 'acf/update_value/name=icon_name', array( $this, 'sanitize_icon_name' ), 10, 4 );
	}

	/**
	 * Gets the icon data.
	 */
	public function get_icon_data() {
		if ( empty( $this->icon_data ) ) {
			$icon_directory_path = get_template_directory() . '/icons/';
			$icon_paths          = glob( $icon_directory_path . '**/*.svg' );

			if ( empty( $icon_paths ) ) {
				return;
			}

			$icon_directory_url   = get_template_directory_uri() . '/icons/';
			$icon_multicolor_data = array();

			if ( file_exists( $icon_directory_path . '_icons.scss' ) ) {
				$icon_scss_data = file_get_contents( $icon_directory_path . '_icons.scss' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

				if ( ! empty( $icon_scss_data ) ) {
					preg_match_all( '/([a-zA-Z0-9]*): \(\s\t\tmulticolor: ([a-zA-Z0-9]*)/m', $icon_scss_data, $icon_multicolor_matches, PREG_SET_ORDER );

					if ( ! empty( $icon_multicolor_matches ) ) {
						foreach ( $icon_multicolor_matches as $icon_multicolor_match ) {
							$icon_multicolor_data[ $icon_multicolor_match[1] ] = $icon_multicolor_match[2];
						}
					}
				}
			}

			foreach ( $icon_paths as $icon_path ) {
				$icon_file_name = basename( $icon_path );
				$icon_name      = str_replace( '.svg', '', $icon_file_name );
				preg_match( '/(?<=catapult\/icons\/).*/m', $icon_path, $matches );
				$category_slug = basename( dirname( $icon_path ) );
				$category      = str_replace( '-', ' ', $category_slug );
				$category      = ucwords( $category );
				$icon_type     = 'default';

				if ( false !== strpos( $icon_name, 'multicolor' ) ) {
					$icon_type = 'multicolor';
					$icon_name = str_replace( '-multicolor', '', $icon_name );
				} elseif ( ! empty( $icon_multicolor_data[ $icon_name ] ) && 'true' === $icon_multicolor_data[ $icon_name ] ) {
					$icon_type = 'multicolor';
				}

				if ( 'Event Program Details' === $category ) {
					$category = 'Event/Program Details';
				} elseif ( 'E Commerce' === $category ) {
					$category = 'E-Commerce';
				}

				if ( empty( $matches ) ) {
					continue;
				}

				$this->icon_data[] = array(
					'name'          => $icon_name,
					'file'          => $icon_file_name,
					'url'           => $icon_directory_url . $matches[0],
					'category'      => $category,
					'category_slug' => $category_slug,
					'icon_type'     => $icon_type,
				);
			}
		}

		if ( ! is_admin() || empty( $_GET['page'] ) || 'acf-options-icons' !== sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$custom_icons = get_field( 'custom_icons', 'icons' );

			if ( ! empty( $custom_icons ) ) {
				foreach ( $custom_icons as $category_slug => $icons_field_data ) {
					if ( empty( $category_slug ) || empty( $icons_field_data ) || ! is_array( $icons_field_data ) ) {
						continue;
					}

					$category_name = str_replace( '-', ' ', $category_slug );
					$category_name = ucwords( $category_name );

					foreach ( $icons_field_data as $icon_field_data ) {
						if ( empty( $icon_field_data['icon_file'] ) || empty( $icon_field_data['icon_name'] ) ) {
							continue;
						}

						$icon_url       = wp_get_attachment_url( $icon_field_data['icon_file'] );
						$icon_path      = get_attached_file( $icon_field_data['icon_file'] );
						$icon_file_name = pathinfo( $icon_path, PATHINFO_BASENAME );

						$this->icon_data[] = array(
							'name'          => $icon_field_data['icon_name'],
							'file'          => $icon_file_name,
							'url'           => $icon_url,
							'category'      => $category_name,
							'category_slug' => $category_slug,
							'icon_type'     => $icon_field_data['multicolor'] ? 'multicolor' : 'default',
						);
					}
				}
			}
		}

		$this->icon_data = array_map( 'unserialize', array_unique( array_map( 'serialize', $this->icon_data ) ) );

		usort(
			$this->icon_data,
			function ( $a, $b ) {
				return strcmp( $a['category'], $b['category'] );
			}
		);

		return $this->icon_data;
	}

	/**
	 * Load icon styles as CSS custom properties in the header.
	 */
	public function load_icon_styles() {
		if ( empty( $this->icon_data ) ) {
			return;
		}

		$utility_classes = '';

		echo '<style>';

		echo ':root {';

		foreach ( $this->icon_data as $icon_data ) {
			if ( empty( $icon_data['name'] ) || empty( $icon_data['url'] ) ) {
				continue;
			}

			echo wp_kses_post( '--icon-' . $icon_data['name'] . ': url("' . $icon_data['url'] . '");' );

			if ( ! empty( $icon_data['icon_type'] ) && 'multicolor' === $icon_data['icon_type'] ) {
				$utility_classes .= '.icon-' . $icon_data['name'] . '{background-image:var(--icon-' . $icon_data['name'] . '); background-size: contain; background-color: transparent !important;}';
			} else {
				$utility_classes .= '.icon-' . $icon_data['name'] . '{-webkit-mask-image:var(--icon-' . $icon_data['name'] . '); mask-image:var(--icon-' . $icon_data['name'] . ');}';
			}
		}

		echo '}';

		echo wp_kses_post( $utility_classes );

		echo '</style>';
	}

	/**
	 * Add the icon options as a localized JS variable for use in button and other React Gutenberg blocks.
	 */
	public function localize_icon_data() {
		if ( empty( $this->icon_data ) ) {
			return;
		}

		$button_icon_categories = get_field( 'button_icon_categories', 'icons' );

		if ( empty( $button_icon_categories ) ) {
			$button_icon_categories = array( 'arrows' );
		}

		wp_localize_script(
			'catapult-editor-scripts',
			'catapultIcons',
			array(
				'icons'                => $this->icon_data,
				'buttonIconCategories' => $button_icon_categories,
			)
		);
	}

	/**
	 * Load icon field choices.
	 *
	 * @param array $field       ACF field array.
	 */
	public function icon_field_choices( $field ) {
		if ( empty( $field['name'] ) || 0 !== strpos( $field['name'], 'icon' ) || 'icon_block_categories' === $field['name'] ) {
			return $field;
		}

		$field['choices'] = array();

		if ( empty( $this->icon_data ) ) {
			return $field;
		}

		$icon_block_categories = get_field( 'icon_block_categories', 'icons' );

		if ( empty( $icon_block_categories ) ) {
			$icon_block_categories = array_values( array_unique( wp_list_pluck( $this->icon_data, 'category_slug' ) ) );
		}

		$icon_category = '';

		if ( false !== strpos( $field['name'], 'icon_' ) ) {
			$icon_category = strtolower( str_replace( 'icon_', '', $field['name'] ) );
		}

		foreach ( $this->icon_data as $icon_data ) {
			if ( empty( $icon_data['name'] ) || empty( $icon_data['url'] ) || empty( $icon_data['category'] ) || empty( $icon_data['category_slug'] ) || empty( $icon_data['icon_type'] ) ) {
				continue;
			}

			if ( ! empty( $icon_category ) && strtolower( $icon_data['category'] ) !== $icon_category ) {
				continue;
			}

			if ( empty( $icon_category ) && ! empty( $icon_block_categories ) && ! in_array( $icon_data['category_slug'], $icon_block_categories, true ) ) {
				continue;
			}

			if ( empty( $field['choices'][ $icon_data['category_slug'] ] ) ) {
				$field['choices'][ $icon_data['category'] . '_label' ] = '<span>' . $icon_data['category'] . '</span>';
			}

			$field['choices'][ 'icon-' . $icon_data['name'] ] = '<span class="icon icon-' . $icon_data['name'] . ' icon--' . $icon_data['icon_type'] . '" aria-label="' . $icon_data['name'] . '"></span>';
		}

		return $field;
	}

	/**
	 * Add ACF fields for custom icons to icons options page.
	 */
	public function add_custom_icons_fieldgroup() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$icon_category_paths = glob( get_template_directory() . '/icons/*', GLOB_ONLYDIR );

		if ( empty( $icon_category_paths ) ) {
			return;
		}

		$sub_fields      = array();
		$icon_categories = array();

		foreach ( $icon_category_paths as $icon_category_path ) {
			$category_slug                     = basename( $icon_category_path );
			$category_name                     = str_replace( '-', ' ', $category_slug );
			$category_name                     = ucwords( $category_name );
			$icon_categories[ $category_slug ] = $category_name;

			$sub_fields[] = array(
				'key'               => 'field_custom_icon_category_' . $category_slug,
				'label'             => $category_name,
				'name'              => '',
				'aria-label'        => '',
				'type'              => 'tab',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'placement'         => 'top',
				'endpoint'          => 0,
				'selected'          => 0,
			);

			if ( empty( $this->icon_data ) ) {
				$this->get_icon_data();
			}

			if ( ! empty( $this->icon_data ) ) {
				$existing_icon_markup = '';

				foreach ( $this->icon_data as $icon_data ) {
					if ( empty( $icon_data['name'] ) || empty( $icon_data['category_slug'] ) || $icon_data['category_slug'] !== $category_slug ) {
						continue;
					}

					$existing_icon_markup .= '<li><span class="icon icon-' . $icon_data['name'] . '"></span></li>';
				}

				if ( ! empty( $existing_icon_markup ) ) {
					$existing_icon_markup = '<div class="catapult-existing-icon-options"><strong>' . __( 'Theme Icons', 'catapult' ) . '</strong><ul>' . $existing_icon_markup . '</ul></div>';

					$sub_fields[] = array(
						'key'       => 'field_custom_icon_category_existing_icons_' . $category_slug,
						'label'     => '',
						'name'      => '',
						'type'      => 'message',
						'message'   => $existing_icon_markup,
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
						'wrapper'   => array(
							'width' => '70',
							'class' => '',
							'id'    => '',
						),
					);
				}
			}

			$sub_fields[] = array(
				'key'               => 'field_custom_icon_category_repeater_' . $category_slug,
				'label'             => __( 'Custom Icons', 'catapult' ),
				'name'              => $category_slug,
				'aria-label'        => '',
				'type'              => 'repeater',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'layout'            => 'block',
				'pagination'        => 0,
				'min'               => 0,
				'max'               => 0,
				'collapsed'         => '',
				'button_label'      => 'Add Icon',
				'rows_per_page'     => 20,
				'sub_fields'        => array(
					array(
						'key'               => 'field_custom_icon_file_' . $category_slug,
						'label'             => 'Icon File',
						'name'              => 'icon_file',
						'aria-label'        => '',
						'type'              => 'image',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '20',
							'class' => '',
							'id'    => '',
						),
						'return_format'     => 'id',
						'library'           => 'all',
						'min_width'         => '',
						'min_height'        => '',
						'min_size'          => '',
						'max_width'         => '',
						'max_height'        => '',
						'max_size'          => '',
						'mime_types'        => '',
						'allow_in_bindings' => 0,
						'preview_size'      => 'medium',
						'choices'           => array(),
						'parent_repeater'   => 'field_custom_icon_category_repeater_' . $category_slug,
					),
					array(
						'key'          => 'field_custom_icon_name_' . $category_slug,
						'label'        => 'Icon Name',
						'name'         => 'icon_name',
						'type'         => 'text',
						'instructions' => __( 'Names must be unique and not match any of the existing theme or custom icon names. Spaces or special characters will be automatically removed. <strong>Do not change this field once the icon has been used in site content.</strong>', 'catapult' ),
						'required'     => 1,
						'wrapper'      => array(
							'width' => '50',
							'class' => '',
							'id'    => '',
						),
					),
					array(
						'key'               => 'field_custom_icon_multicolor_' . $category_slug,
						'label'             => 'Multicolor',
						'name'              => 'multicolor',
						'aria-label'        => '',
						'type'              => 'true_false',
						'instructions'      => __( 'Multicolor icons will use whatever color is specified in the uploaded file. If `No`, the icon will inherit the color of the block where it is being used. This only works for icon blocks, button block icons are also a single color.', 'catapult' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '30',
							'class' => '',
							'id'    => '',
						),
						'message'           => '',
						'default_value'     => 0,
						'allow_in_bindings' => 0,
						'ui_on_text'        => '',
						'ui_off_text'       => '',
						'ui'                => 1,
						'parent_repeater'   => 'field_custom_icon_category_repeater_' . $category_slug,
					),
				),
			);
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'group_custom_icon_settings',
				'title'                 => __( 'Theme Options: Icons', 'catapult' ),
				'fields'                => array(
					array(
						'key'               => 'field_button_icon_categories',
						'label'             => 'Button Icon Categories',
						'name'              => 'button_icon_categories',
						'aria-label'        => '',
						'type'              => 'checkbox',
						'instructions'      => __( 'Select icon categories to make available in the button block. Button icons must be a single color.', 'catapult' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '50',
							'class' => '',
							'id'    => '',
						),
						'choices'           => $icon_categories,
						'default_value'     => array( 'arrows' ),
						'return_format'     => 'value',
						'ui'                => 0,
						'ajax'              => 0,
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_icon_block_categories',
						'label'             => 'Icon Block Categories',
						'name'              => 'icon_block_categories',
						'aria-label'        => '',
						'type'              => 'checkbox',
						'instructions'      => __( 'Select icon categories to make available in the icon block.', 'catapult' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '50',
							'class' => '',
							'id'    => '',
						),
						'choices'           => $icon_categories,
						'default_value'     => array_keys( $icon_categories ),
						'return_format'     => 'value',
						'ui'                => 0,
						'ajax'              => 0,
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_custom_icons',
						'label'             => 'Custom Icons',
						'name'              => 'custom_icons',
						'aria-label'        => '',
						'type'              => 'group',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'layout'            => 'block',
						'sub_fields'        => $sub_fields,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-icons',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
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
	 * Sanitize the icon name value.
	 *
	 * @param   mixed  $value The value to update.
	 * @param   string $post_id The post ID for this value.
	 * @param   array  $field The field array.
	 * @param   mixed  $original The original value before modification.
	 */
	public function sanitize_icon_name( $value, $post_id, $field, $original ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return sanitize_title( $value );
	}
}
