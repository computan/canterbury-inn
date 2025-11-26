<?php
/**
 * Theme Scripts and Styles.
 *
 * This component enqueues all the scripts and styles used by the theme.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.1
 * @since   3.0.17
 * @since   3.1.0
 * @since   3.1.6
 */

defined( 'ABSPATH' ) || die();

/**
 * Class handing general enqueueing of scripts and styles.
 */
class Theme_Core_Scripts extends Theme_Core_Component {
	/**
	 * An array of font data.
	 *
	 * @access public
	 * @var array
	 */
	public $font_data;

	/**
	 * Enqueue all scripts and styles registered in settings.
	 */
	protected function init() {
		add_action(
			'init',
			function () {
				$this->load_theme_fonts();
				$this->localize_script();
			}
		);

		add_action(
			'wp_enqueue_scripts',
			function () {
				$this->register_theme_module_scripts();
				$this->enqueue_theme_scripts();
				$this->enqueue_theme_styles();
				$this->register_theme_component_and_template_styles();
				$this->register_theme_component_and_template_scripts();
				$this->register_core_block_styles();
				$this->register_core_block_scripts();
			}
		);

		add_action(
			'enqueue_block_editor_assets',
			function () {
				$this->register_theme_module_scripts( true );
				$this->register_theme_component_and_template_styles( true, true );
				$this->register_core_block_styles( true, true );
				$this->enqueue_admin_scripts();
				$this->enqueue_admin_styles();
				$this->enqueue_editor_styles_and_scripts();
			},
			1
		);

		add_action(
			'wp_head',
			function () {
				$this->enqueue_fonts();
			}
		);

		add_action(
			'admin_head',
			function () {
				$this->enqueue_admin_styles();
				$this->enqueue_fonts();
			}
		);

		add_theme_support( 'editor-font-sizes', array() );
		add_filter( 'should_load_separate_core_block_assets', '__return_true' );
	}

	/**
	 * Enqueue scripts defined by the developer in theme settings file.
	 */
	protected function enqueue_theme_scripts() {
		if ( ! $this->settings ) {
			return;
		}

		if ( false === $this->settings->enqueue_scripts && false === $this->settings->register_scripts ) {
			return;
		}

		if ( false !== $this->settings->enqueue_scripts ) {
			foreach ( $this->settings->enqueue_scripts as $handle => $script ) {
				$params = array(
					'src'          => '',
					'dependencies' => array(),
					'version'      => null,
					'in_footer'    => true,
				);

				if ( is_object( $script ) ) {
					$params = wp_parse_args( (array) $script, $params );
				} else {
					$params['src'] = $script;
				}

				if ( empty( $params['src'] ) ) {
					continue;
				}

				if ( 'filemtime' === $params['version'] ) {
					$path = $this->get_script_path( $params['src'] );

					if ( file_exists( $path ) ) {
						$params['version'] = filemtime( $path );
					}
				}

				wp_enqueue_script(
					$handle,
					$this->get_script_url( $params['src'] ),
					$params['dependencies'],
					$params['version'],
					$params['in_footer']
				);
			}
		}

		if ( false !== $this->settings->register_scripts ) {
			foreach ( $this->settings->register_scripts as $handle => $script ) {
				$params = array(
					'src'          => '',
					'dependencies' => array(),
					'version'      => null,
					'in_footer'    => true,
				);

				if ( is_object( $script ) ) {
					$params = wp_parse_args( (array) $script, $params );
				} else {
					$params['src'] = $script;
				}

				if ( empty( $params['src'] ) ) {
					continue;
				}

				if ( 'filemtime' === $params['version'] ) {
					$path = $this->get_script_path( $params['src'] );

					if ( file_exists( $path ) ) {
						$params['version'] = filemtime( $path );
					}
				}

				wp_register_script(
					$handle,
					$this->get_script_url( $params['src'] ),
					$params['dependencies'],
					$params['version'],
					$params['in_footer']
				);
			}
		}
	}

	/**
	 * Enqueue styles defined by the user in theme settings file.
	 */
	protected function enqueue_theme_styles() {
		if ( ! $this->settings ) {
			return;
		}

		if ( false === $this->settings->enqueue_styles && false === $this->settings->register_styles ) {
			return;
		}

		if ( false !== $this->settings->enqueue_styles ) {
			foreach ( $this->settings->enqueue_styles as $handle => $style ) {
				$params = array(
					'src'          => '',
					'dependencies' => array(),
					'version'      => null,
					'media'        => 'all',
				);

				if ( is_object( $style ) ) {
					$params = wp_parse_args( (array) $style, $params );
				} else {
					$params['src'] = $style;
				}

				if ( empty( $params['src'] ) ) {
					continue;
				}

				if ( 'filemtime' === $params['version'] ) {
					$path = $this->get_script_path( $params['src'] );

					if ( file_exists( $path ) ) {
						$params['version'] = filemtime( $path );
					}
				}

				wp_enqueue_style(
					$handle,
					$this->get_script_url( $params['src'] ),
					$params['dependencies'],
					$params['version'],
					$params['media']
				);
			}
		}

		if ( false !== $this->settings->register_styles ) {
			foreach ( $this->settings->register_styles as $handle => $style ) {
				$params = array(
					'src'          => '',
					'dependencies' => array(),
					'version'      => null,
					'media'        => 'all',
				);

				if ( is_object( $style ) ) {
					$params = wp_parse_args( (array) $style, $params );
				} else {
					$params['src'] = $style;
				}

				if ( empty( $params['src'] ) ) {
					continue;
				}

				if ( 'filemtime' === $params['version'] ) {
					$path = $this->get_script_path( $params['src'] );

					if ( file_exists( $path ) ) {
						$params['version'] = filemtime( $path );
					}
				}

				wp_register_style(
					$handle,
					$this->get_script_url( $params['src'] ),
					$params['dependencies'],
					$params['version'],
					$params['media']
				);
			}
		}
	}

	/**
	 * Enqueue component and template styles. Template styles matching the current template name will be automatically enqueued.
	 *
	 * @param bool $enqueue_styles   Whether to also enqueue the styles.
	 * @param bool $editor_styles    Whether to use the editor styles.
	 */
	protected function register_theme_component_and_template_styles( $enqueue_styles = false, $editor_styles = false ) {
		global $template;

		$css_source_files = array_merge( glob( get_template_directory() . '/blocks/components/**/*.scss' ), glob( get_template_directory() . '/blocks/templates/**/*.scss' ) );

		if ( ! empty( $css_source_files ) ) {
			foreach ( $css_source_files as $css_source_file ) {
				$css_file = str_replace( '/style.scss', '.css', $css_source_file );
				$css_file = str_replace( '/blocks/', '/dist/', $css_file );

				if ( true === $editor_styles ) {
					$css_file = str_replace( '.css', '-editor-styles.css', $css_file );
				}

				if ( file_exists( $css_file ) ) {
					preg_match( '/(?<=components\/).*?(?=.css)/', $css_file, $matches );

					if ( ! empty( $matches[0] ) ) {
						$directory = 'components';
						if ( strpos( $matches[0], 'catapult-component-' ) === false ) {
							$prefix = 'catapult-component-';
						} else {
							$prefix = '';
						}
					} else {
						$directory = 'templates';
						$prefix    = 'catapult-template-';
						preg_match( '/(?<=templates\/).*?(?=.css)/', $css_file, $matches );
					}

					if ( ! empty( $matches[0] ) ) {
						$deps            = array( 'theme-styles' );
						$css_source_path = dirname( $css_source_file );

						if ( file_exists( $css_source_path . '/dependencies.json' ) ) {
							$dependency_settings = json_decode( file_get_contents( $css_source_path . '/dependencies.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
							if ( ! empty( $dependency_settings['css'] ) ) {
								foreach ( $dependency_settings['css'] as $dep ) {
									// Check if $dep contains 'catapult-component-'.
									if ( strpos( $dep, 'catapult-component-' ) === false ) {
										// If not, append 'catapult-component-' to $dep.
										$dep = 'catapult-component-' . $dep;
									}

									$deps[] = $dep;
								}
							}
						}

						wp_register_style(
							$prefix . $matches[0],
							get_template_directory_uri() . '/dist/' . $directory . '/' . $matches[0] . '.css',
							$deps,
							filemtime( $css_file )
						);

						if ( true === $enqueue_styles || ( 'templates' === $directory && ! empty( $template ) && basename( $template ) === $matches[0] . '.php' ) ) {
							wp_enqueue_style( $prefix . $matches[0] );
						}

						// Load card components used on post type archives and taxonomies in the header to improve CLS.
						if ( strpos( $matches[0], '-card' ) && ( is_tax() || is_category() || is_tag() || is_post_type_archive() ) ) {
							wp_enqueue_style( $prefix . $matches[0] );
						}
					}
				}
			}
		}
		if ( ! empty( $css_source_files ) ) {
			foreach ( $css_source_files as $css_source_file ) {

				if ( strpos( $css_source_file, 'contact-form-7' ) !== false ) {
					$css_file = str_replace( '/style.scss', '.css', $css_source_file );
					$css_file = str_replace( '/blocks/', '/dist/', $css_file );

					if ( file_exists( $css_file ) ) {
						wp_register_style(
							'catapult-component-cf7',
							get_template_directory_uri() . '/dist/components/contact-form-7.css',
							array( 'contact-form-7' ),
							filemtime( $css_file )
						);

						wp_enqueue_style( 'catapult-component-cf7' );
					}
					continue;
				}
			}
		}
		if ( post_password_required() ) {
			wp_enqueue_style( 'catapult-component-post-password-form' );
		}
	}

	/**
	 * Register component and template scripts. Template scripts matching the current template name will be automatically enqueued.
	 */
	protected function register_theme_component_and_template_scripts() {
		global $template;

		$js_files = array_merge( glob( get_template_directory() . '/blocks/components/**/script.js' ), glob( get_template_directory() . '/blocks/templates/**/script.js' ) );

		if ( ! empty( $js_files ) ) {
			foreach ( $js_files as $js_file ) {
				preg_match( '/(?<=components\/).*(?=\/)/U', $js_file, $matches );

				if ( ! empty( $matches[0] ) ) {
					$directory = 'components';
					if ( strpos( $matches[0], 'catapult-component-' ) === false ) {
						$prefix = 'catapult-component-';
					} else {
						$prefix = '';
					}
				} else {
					$directory = 'templates';
					$prefix    = 'catapult-template-';
					preg_match( '/(?<=templates\/).*(?=\/)/U', $js_file, $matches );
				}

				if ( ! empty( $matches[0] ) ) {
					$main_js_path = get_template_directory() . '/dist/' . $directory . '/' . $matches[0] . '.js';
					$main_js      = get_template_directory_uri() . '/dist/' . $directory . '/' . $matches[0] . '.js';

					$deps           = array();
					$js_source_path = dirname( $js_file );

					if ( file_exists( $js_source_path . '/dependencies.json' ) ) {
						$dependency_settings = json_decode( file_get_contents( $js_source_path . '/dependencies.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

						if ( ! empty( $dependency_settings['js'] ) ) {
							foreach ( $dependency_settings['js'] as $dep ) {
								// Check if $dep contains 'catapult-component-'.
								if ( strpos( $dep, 'catapult-component-' ) === false ) {
									// If not, append 'catapult-component-' to $dep.
									$dep = 'catapult-component-' . $dep;
								}

								$deps[] = $dep;
							}
						}
					}

					if ( file_exists( $main_js_path ) && ! is_admin() ) {
						wp_register_script( $prefix . $matches[0], $main_js, catapult_get_script_dependences( $main_js_path, $deps ), filemtime( $main_js_path ), true );
					}

					if ( 'templates' === $directory && ! empty( $template ) && basename( $template ) === $matches[0] . '.php' ) {
						wp_enqueue_script( $prefix . $matches[0] );
					}
				}
			}
		}
		if ( ! empty( $js_files ) ) {
			foreach ( $js_files as $js_file ) {
				// Handle CF7 component specifically.
				if ( strpos( $js_file, 'contact-form-7' ) !== false ) {
					$main_js_path = get_template_directory() . '/dist/components/contact-form-7.js';
					$main_js      = get_template_directory_uri() . '/dist/components/contact-form-7.js';

					if ( file_exists( $main_js_path ) ) {
						wp_register_script(
							'catapult-component-cf7',
							$main_js,
							array( 'jquery', 'contact-form-7' ), // CF7 scripts as dependency.
							filemtime( $main_js_path ),
							true
						);

						// Always enqueue CF7 scripts when form is present.
						wp_enqueue_script( 'catapult-component-cf7' );
					}
					continue;
				}
			}
		}
	}
	/**
	 * Register module scripts for use in script module asset files.
	 *
	 * @param bool $enqueue_scripts   Whether to also enqueue the scripts.
	 */
	protected function register_theme_module_scripts( $enqueue_scripts = false ) {
		global $template;

		$js_files = array_merge( glob( get_template_directory() . '/dist/modules/*.js' ) );

		if ( ! empty( $js_files ) ) {
			foreach ( $js_files as $main_js_path ) {
				preg_match( '/(?<=modules\/).*(?=\.js)/U', $main_js_path, $matches );

				if ( empty( $matches[0] ) ) {
					continue;
				}

				$main_js = get_template_directory_uri() . '/dist/modules/' . $matches[0] . '.js';
				wp_register_script( 'modules/' . $matches[0], $main_js, array(), filemtime( $main_js_path ), true );

				if ( true === $enqueue_scripts ) {
					wp_enqueue_script( 'modules/' . $matches[0] );
				}
			}
		}
	}

	/**
	 * Enqueue core block styles.
	 *
	 * @param bool $enqueue_styles   Whether to also enqueue the styles.
	 * @param bool $editor_styles    Whether to use the editor styles.
	 */
	protected function register_core_block_styles( $enqueue_styles = false, $editor_styles = false ) {
		$core_block_css_source_files = glob( get_template_directory() . '/blocks/core-blocks/**/*.scss' );

		if ( ! empty( $core_block_css_source_files ) ) {
			foreach ( $core_block_css_source_files as $css_source_file ) {

				$css_file = str_replace( '/style.scss', '.css', $css_source_file );
				$css_file = str_replace( '/editor.scss', '-editor.css', $css_file );
				$css_file = str_replace( '/blocks/', '/dist/', $css_file );

				if ( true === $editor_styles && false === strpos( $css_file, 'editor' ) ) {
					$css_file = str_replace( '.css', '-editor-styles.css', $css_file );
				}

				if ( file_exists( $css_file ) ) {
					preg_match( '/(?<=core-blocks\/).*?(?=.css)/', $css_file, $matches );

					if ( ! empty( $matches[0] ) ) {
						wp_register_style(
							'core-' . $matches[0],
							get_template_directory_uri() . '/dist/core-blocks/' . $matches[0] . '.css',
							array(),
							filemtime( $css_file )
						);

						if ( true === $enqueue_styles ) {
							wp_enqueue_style( 'core-' . $matches[0] );
						}
					}
				}
			}
		}
	}

	/**
	 * Enqueue core block scripts.
	 */
	protected function register_core_block_scripts() {
		$core_block_js_source_files = glob( get_template_directory() . '/blocks/core-blocks/**/script.js' );

		if ( ! empty( $core_block_js_source_files ) ) {
			foreach ( $core_block_js_source_files as $js_source_file ) {
				$js_file = str_replace( '/script.js', '.js', $js_source_file );
				$js_file = str_replace( '/blocks/', '/dist/', $js_file );

				if ( file_exists( $js_file ) ) {
					preg_match( '/(?<=core-blocks\/).*?(?=.js)/', $js_file, $matches );

					if ( ! empty( $matches[0] ) ) {
						wp_register_script(
							'core-' . $matches[0],
							get_template_directory_uri() . '/dist/core-blocks/' . $matches[0] . '.js',
							catapult_get_script_dependences( get_template_directory() . '/dist/core-blocks/' . $matches[0] . '.js' ),
							filemtime( $js_file ),
							true
						);
					}
				}
			}
		}
	}

	/**
	 * Enqueue admin scripts defined by the developer in theme settings file.
	 */
	protected function enqueue_admin_scripts() {
		if ( ! $this->settings ) {
			return;
		}

		if ( false === $this->settings->enqueue_admin_scripts ) {
			return;
		}

		foreach ( $this->settings->enqueue_admin_scripts as $handle => $script ) {
			$params = array(
				'src'          => '',
				'dependencies' => array(),
				'version'      => false,
				'in_footer'    => true,
			);

			if ( is_object( $script ) ) {
				$params = wp_parse_args( (array) $script, $params );
			} else {
				$params['src'] = $script;
			}

			if ( empty( $params['src'] ) ) {
				continue;
			}

			wp_enqueue_script(
				$handle,
				$this->get_script_url( $params['src'] ),
				$params['dependencies'],
				$params['version'],
				$params['in_footer']
			);
		}
	}

	/**
	 * Enqueue admin styles defined by the user in theme settings file.
	 */
	protected function enqueue_admin_styles() {
		if ( ! $this->settings ) {
			return;
		}

		if ( false === $this->settings->enqueue_admin_styles ) {
			return;
		}

		foreach ( $this->settings->enqueue_admin_styles as $handle => $style ) {
			$params = array(
				'src'          => '',
				'dependencies' => array(),
				'version'      => false,
				'media'        => 'all',
			);

			if ( is_object( $style ) ) {
				$params = wp_parse_args( (array) $style, $params );
			} else {
				$params['src'] = $style;
			}

			if ( empty( $params['src'] ) ) {
				continue;
			}

			wp_enqueue_style(
				$handle,
				$this->get_script_url( $params['src'] ),
				$params['dependencies'],
				$params['version'],
				$params['media']
			);
		}
	}

	/**
	 * Enqueue editor styles.
	 */
	protected function enqueue_editor_styles_and_scripts() {
		wp_deregister_style( 'wp-reset-editor-styles' );

		if ( file_exists( get_template_directory() . '/dist/styles-editor-styles.css' ) ) {
			wp_enqueue_style(
				'theme-styles',
				get_template_directory_uri() . '/dist/styles-editor-styles.css',
				array(),
				filemtime( get_template_directory() . '/dist/styles-editor-styles.css' )
			);
		}

		if ( file_exists( get_template_directory() . '/dist/editor.css' ) ) {
			wp_enqueue_style(
				'wp-reset-editor-styles',
				get_template_directory_uri() . '/dist/editor.css',
				array(),
				filemtime( get_template_directory() . '/dist/editor.css' )
			);
		}

		if ( file_exists( get_template_directory() . '/dist/editor.js' ) ) {
			wp_enqueue_script(
				'catapult-editor-scripts',
				get_template_directory_uri() . '/dist/editor.js',
				array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ),
				filemtime( get_template_directory() . '/dist/editor.js' ),
				true
			);
		}

		wp_localize_script(
			'catapult-editor-scripts',
			'catapult',
			array(
				'siteUrl'       => home_url(),
				'templateUrl'   => get_template_directory_uri(),
				'stylesheetUrl' => get_stylesheet_directory_uri(),
			)
		);

		$core_block_editor_source_files = glob( get_template_directory() . '/blocks/**/**/editor.js' );

		if ( ! empty( $core_block_editor_source_files ) ) {
			foreach ( $core_block_editor_source_files as $core_block_editor_source_file ) {
				$core_block_editor_file = str_replace( '/editor.js', '-editor.js', $core_block_editor_source_file );
				$core_block_editor_file = str_replace( '/blocks/', '/dist/', $core_block_editor_file );

				if ( file_exists( $core_block_editor_file ) ) {
					$file_info = pathinfo( $core_block_editor_file );
					preg_match( '/(?<=dist\/).*(?=' . $file_info['filename'] . ')/m', $core_block_editor_file, $matches );

					if ( ! empty( $matches[0] ) && file_exists( $core_block_editor_file ) ) {
						$main_js = get_template_directory_uri() . '/dist/' . $matches[0] . $file_info['basename'];

						wp_enqueue_script( $file_info['filename'], $main_js, array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ), filemtime( $core_block_editor_file ), true );
					}
				}
			}
		}
	}

	/**
	 * Pass basic information about the theme and WP installation to the JS.
	 */
	protected function localize_script() {
		JS::add(
			array(
				'siteUrl'       => home_url(),
				'templateUrl'   => get_template_directory_uri(),
				'stylesheetUrl' => get_stylesheet_directory_uri(),
				'ajaxUrl'       => site_url() . '/wp-admin/admin-ajax.php',
				'editPostsLink' => wp_json_encode(
					add_query_arg(
						array(
							'post'   => 'POSTID',
							'action' => 'edit',
						),
						get_admin_url( null, 'post.php' )
					)
				),
			)
		);

		add_action(
			'wp_enqueue_scripts',
			function () {
				JS::localize( 'catapult' );
			},
			99
		);
	}

	/*
	 * Helpers
	 * ---------------------------------------------------------------------------------------
	 */

	/**
	 * Convert the script source provided into full URL. If the source is already a full
	 * URL, it will not be modified. Otherwise the source will be added to the theme
	 * URL (script is relative to the theme root).
	 *
	 * @param string $src Script source to convert.
	 *
	 * @return string Full URL to the script.
	 */
	protected function get_script_url( $src ) {
		if ( preg_match( '@\/\/@', $src ) ) {
			return $src;
		}

		return get_template_directory_uri() . '/' . $src;
	}

	/**
	 * Get the path to the script if not an external file.
	 *
	 * @param string $src The relative path to the script.
	 */
	protected function get_script_path( $src ) {
		if ( preg_match( '@\/\/@', $src ) ) {
			return $src;
		}

		return get_template_directory() . '/' . $src;
	}

	/**
	 * Checks the fonts in the settings.json file and downloads any external fonts locally.
	 */
	protected function load_theme_fonts() {
		if ( ! $this->settings ) {
			return;
		}

		if ( false === $this->settings->fonts ) {
			return;
		}

		if ( empty( $this->settings->version ) ) {
			return;
		}

		if ( ! function_exists( 'wp_font_dir' ) ) {
			return;
		}

		$font_dir = wp_font_dir();

		if ( empty( $font_dir['baseurl'] ) || empty( $font_dir['basedir'] ) ) {
			return;
		}

		$fonts_directory_url  = $font_dir['baseurl'];
		$fonts_directory_path = $font_dir['basedir'];

		foreach ( $this->settings->fonts as $handle => $font_url ) {
			$this->font_data[ $handle ] = array(
				'font_url' => $font_url,
			);

			$font_option_key = 'catapult_' . sanitize_title( $handle ) . '_font-rules';
			$font_option     = get_option( $font_option_key );

			if ( false === strpos( $font_url, 'fonts.googleapis.com' ) ) {
				if ( ! empty( $font_option ) ) {
					delete_option( $font_option_key );
				}

				continue;
			}

			if ( ! empty( $font_option ) && ! empty( $font_option['version'] ) && $font_option['version'] >= $this->settings->version ) {
				$this->font_data[ $handle ] = array_merge( $this->font_data[ $handle ], $font_option );

				continue;
			}

			$response = wp_remote_get(
				$font_url,
				array(
					'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.3',
				)
			);

			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) || empty( $response['body'] ) ) {
				continue;
			}

			$font_face_css = $response['body'];

			$font_face_css = preg_replace( '/\/\*(?s).*?\*\//', '', $font_face_css );
			$font_face_css = preg_replace( '/\n */m', '', $font_face_css );
			$font_face_css = preg_replace( '/@font-face/m', "\n@font-face", $font_face_css );

			preg_match_all( '/src: *url\(["\']*([^)]*)["\']*\)/m', $font_face_css, $font_src_matches );

			if ( empty( $font_src_matches ) && empty( $font_src_matches[1] ) ) {
				continue;
			}

			$remote_font_file_urls = array_unique( $font_src_matches[1] );

			if ( ! function_exists( 'download_url' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			foreach ( $remote_font_file_urls as $remote_font_file_url ) {
				$font_file_exists = false;

				preg_match( '/([^\/]*)\.([^\/]*)$/', $remote_font_file_url, $font_file_name_matches );

				if ( empty( $font_file_name_matches ) ) {
					continue;
				}

				$font_file_name = sanitize_title( $font_file_name_matches[1] ) . '.' . $font_file_name_matches[2];
				$font_file_path = $fonts_directory_path . '/' . $font_file_name;
				$font_file_url  = 'file:./' . $font_file_name;

				if ( file_exists( $font_file_path ) ) {
					$font_file_exists = true;
				} else {
					$temporary_file   = download_url( $remote_font_file_url );
					$font_file_exists = copy( $temporary_file, $font_file_path );
					wp_delete_file( $temporary_file );
				}

				if ( ! empty( $font_file_exists ) ) {
					$font_face_css = str_replace( $remote_font_file_url, $font_file_url, $font_face_css );
				}
			}

			$font_option = array(
				'version'       => $this->settings->version,
				'font_face_css' => $font_face_css,
			);

			update_option( $font_option_key, $font_option, false );

			$this->font_data[ $handle ] = array_merge( $this->font_data[ $handle ], $font_option );
		}
	}

	/**
	 * Enqueue fonts defined by the user in theme settings file.
	 */
	protected function enqueue_fonts() {
		if ( empty( $this->font_data ) ) {
			return;
		}

		if ( ! function_exists( 'wp_font_dir' ) ) {
			return;
		}

		$font_dir = wp_font_dir();

		if ( empty( $font_dir['baseurl'] ) ) {
			return;
		}

		foreach ( $this->font_data as $key => $font_data ) {
			$handle = 'catapult-font-' . sanitize_title( $key );

			if ( is_ssl() ) {
				$font_dir['baseurl'] = str_replace( 'http:', 'https:', $font_dir['baseurl'] );
			}

			if ( ! empty( $font_data['font_face_css'] ) ) {
				$font_data['font_face_css'] = str_replace( 'file:./', $font_dir['baseurl'] . '/', $font_data['font_face_css'] );

				/*
				* The font-face CSS is contained within <style> tags and can only be interpreted
				* as CSS in the browser. Using wp_strip_all_tags() is sufficient escaping
				* to avoid malicious attempts to close </style> and open a <script>.
				*/
				echo '<style id="' . esc_attr( $handle ) . '">' . wp_strip_all_tags( $font_data['font_face_css'] ) . "\n</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} elseif ( ! empty( $font_data['font_url'] ) ) {
				echo '<link rel="stylesheet" href="' . esc_url( $font_data['font_url'] ) . '" media="screen" onload="this.media=\'all\'">'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
			}
		}
	}
}
