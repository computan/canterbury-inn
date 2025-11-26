<?php
/**
 * Register custom React blocks.
 *
 * @package Catapult
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || die();

/**
 * The class that sets up React blocks.
 *
 * ACF blocks should be built in the /blocks/react-blocks/ directory of the theme.
 */
class Theme_Core_React_Blocks extends Theme_Core_Component {
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
	 * Init function.
	 *
	 * This function runs during init and can be used to set up other functions or the main functionality of the class.
	 */
	protected function init() {
		$this->blocks_directory = get_template_directory() . '/blocks/react-blocks/';

		add_action( 'init', array( $this, 'register_react_blocks' ) );
		add_filter( 'block_type_metadata', array( $this, 'block_type_metadata' ) );

		add_action(
			'wp_enqueue_scripts',
			function () {
				$this->register_react_block_styles_and_scripts();
			}
		);

		add_action(
			'enqueue_block_editor_assets',
			function () {
				$this->register_react_block_styles_and_scripts( true );
			}
		);
	}

	/**
	 * Search the block directory and register all React Gutenberg blocks.
	 */
	public function register_react_blocks() {
		$block_paths = array_merge( glob( $this->blocks_directory . '*/block.json' ), glob( $this->blocks_directory . '*/**/block.json' ) );

		if ( ! empty( $block_paths ) ) {
			foreach ( $block_paths as $block_path ) {
				register_block_type( $block_path );
			}
		}
	}

	/**
	 * Filters the metadata provided for registering a block type.
	 *
	 * @param array $metadata Metadata for registering a block type.
	 *
	 * @return array
	 */
	public function block_type_metadata( $metadata ) {
		if ( empty( $metadata['name'] ) || empty( $metadata['file'] ) || false === strpos( $metadata['name'], 'catapult' ) ) {
			return $metadata;
		}

		foreach ( $metadata as $key => $meta ) {
			if ( ( false === stripos( $key, 'script' ) && false === stripos( $key, 'style' ) && false === stripos( $key, 'file:./' ) ) || ! is_string( $meta ) ) {
				continue;
			}

			$dist_directory = str_replace( 'block.json', '', $metadata['file'] );
			$dist_directory = str_replace( 'catapult/blocks/', 'catapult/dist/', $dist_directory );

			$metadata[ $key ] = str_replace( 'file:./', $dist_directory, $meta );
		}

		return $metadata;
	}

	/**
	 * Register and potentially enqueue react block styles and scripts.
	 *
	 * @param bool $is_editor   Whether currently in the editor or not.
	 */
	protected function register_react_block_styles_and_scripts( $is_editor = false ) {
		$block_registry        = WP_Block_Type_Registry::get_instance();
		$all_registered_blocks = $block_registry->get_all_registered();

		$css_dist_paths = array();
		$js_dist_paths  = array();

		foreach ( $all_registered_blocks as $registered_block ) {
			if ( empty( $registered_block->name ) || 0 !== strpos( $registered_block->name, 'catapult/' ) ) {
				continue;
			}

			if ( true === $is_editor ) {
				if ( ! empty( $registered_block->editor_style_handles ) ) {
					$css_dist_paths = array_merge( $css_dist_paths, $registered_block->editor_style_handles );
				}

				if ( ! empty( $registered_block->editor_script_handles ) ) {
					$js_dist_paths = array_merge( $js_dist_paths, $registered_block->editor_script_handles );
				}
			} else {
				if ( ! empty( $registered_block->view_style_handles ) ) {
					$css_dist_paths = array_merge( $css_dist_paths, $registered_block->view_style_handles );
				}

				if ( ! empty( $registered_block->view_script_handles ) ) {
					$js_dist_paths = array_merge( $js_dist_paths, $registered_block->view_script_handles );
				}
			}

			if ( ! empty( $registered_block->style_handles ) ) {
				if ( true === $is_editor ) {
					foreach ( $registered_block->style_handles as $style_handle ) {
						$css_dist_paths[] = str_replace( '.css', '-editor-styles.css', $style_handle );
					}
				} else {
					$css_dist_paths = array_merge( $css_dist_paths, $registered_block->style_handles );
				}
			}

			if ( ! empty( $registered_block->script_handles ) ) {
				$js_dist_paths = array_merge( $js_dist_paths, $registered_block->script_handles );
			}
		}

		if ( ! empty( $css_dist_paths ) ) {
			foreach ( $css_dist_paths as $css_dist_path ) {
				if ( ! file_exists( $css_dist_path ) ) {
					continue;
				}

				$css_source_file = str_replace( 'catapult/dist/', 'catapult/blocks/', $css_dist_path );
				$css_source_file = str_replace( '.css', '.scss', $css_source_file );
				$css_source_file = str_replace( '-editor-styles', '', $css_source_file );

				if ( file_exists( $css_source_file ) ) {
					preg_match( '/(?<=react-blocks\/).*?(?=.css)/', $css_dist_path, $matches );

					if ( ! empty( $matches[0] ) ) {
						$css_file_parts = explode( '/', $matches[0] );

						$style_handle = 'catapult-' . $matches[0];
						$style_handle = str_replace( '/style', '', $style_handle );
						$style_handle = str_replace( '/', '-', $style_handle );

						wp_register_style(
							$style_handle,
							get_template_directory_uri() . '/dist/react-blocks/' . $matches[0] . '.css',
							array(),
							filemtime( $css_dist_path )
						);

						if ( true === $is_editor ) {
							wp_enqueue_style( $style_handle );
						}
					}
				}
			}
		}

		if ( ! empty( $js_dist_paths ) ) {
			foreach ( $js_dist_paths as $js_dist_path ) {
				if ( ! file_exists( $js_dist_path ) ) {
					continue;
				}

				$js_source_file = str_replace( 'catapult/dist/', 'catapult/blocks/', $js_dist_path );

				if ( file_exists( $js_source_file ) ) {
					preg_match( '/(?<=react-blocks\/).*?(?=.js)/', $js_dist_path, $matches );

					if ( ! empty( $matches[0] ) ) {
						$js_file_parts = explode( '/', $matches[0] );

						$script_handle = 'catapult-' . $matches[0];
						$script_handle = str_replace( '/script', '', $script_handle );
						$script_handle = str_replace( '/', '-', $script_handle );

						wp_register_script(
							$script_handle,
							get_template_directory_uri() . '/dist/react-blocks/' . $matches[0] . '.js',
							catapult_get_script_dependences( $js_dist_path ),
							filemtime( $js_dist_path ),
							true
						);

						if ( true === $is_editor ) {
							wp_enqueue_script( $script_handle );
						}
					}
				}
			}
		}
	}
}
