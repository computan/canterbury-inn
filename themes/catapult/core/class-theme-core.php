<?php
/**
 * The main theme functionality.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * The main theme class.
 */
class Theme_Core {

	/**
	 * Theme Core singleton.
	 *
	 * @var Theme_Core
	 */
	protected static $instance = null;

	/**
	 * Theme Core configuration.
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Theme settings loaded from the .json file.
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Theme_Core constructor.
	 */
	protected function __construct() {
		$this->load_config();
		$this->load_settings();
		$this->load_components();
		$this->loader();
	}

	/**
	 * There can exist only one Theme Core, therefore it's a singleton.
	 * This function creates class instance if it doesn't exist yet.
	 */
	public static function setup() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
	}

	/**
	 * Load the Theme Core configuration.
	 * It should be stored in the 'config.php' file in the 'core' folder.
	 */
	protected function load_config() {
		$this->config = include_once get_template_directory() . '/core/config.php';
	}

	/**
	 * Get the variable from Theme Core configuration.
	 *
	 * @param string $name Name of the variable to retrieve.
	 *
	 * @return mixed The value of the config variable, null if doesn't exist.
	 */
	protected function get_config( $name ) {
		if ( ! isset( $this->config[ $name ] ) ) {
			return null;
		}

		return $this->config[ $name ];
	}

	/**
	 * Load theme settings from settings.json file.
	 * If loading and decoding of the settings file fails (e.g. it contains invalid json)
	 * the admin notice will be shown on the panel side.
	 */
	protected function load_settings() {
		/*
		 * This chaining of function calls is awful, but it seems to be much
		 * (up to 20-30%) faster when we don't create middleman variables.
		 */
		$this->settings          = json_decode( preg_replace( '/\/\*(?s).*?\*\//', '', file_get_contents( get_template_directory() . '/' . $this->get_config( 'settings_file' ) ) ) );
		$this->settings->version = filemtime( get_template_directory() . '/' . $this->get_config( 'settings_file' ) );

		if ( ! $this->settings ) {
			add_action(
				'admin_notices',
				function () {
					?>

				<div class="error">
					<p><?php esc_html_e( 'Settings.json is invalid.', 'catapult' ); ?></p>
				</div>

					<?php
				}
			);
		}
	}

	/**
	 * Initialize all components registered in the theme config file.
	 */
	protected function load_components() {
		$components = $this->get_config( 'components' );
		if ( ! $this->settings || ! $components ) {
			return;
		}

		foreach ( $components as $component ) {
			new $component( $this->settings );
		}
	}

	/**
	 * Load all additional PHP files used by the theme using the settings provided in the
	 * "includes" section of the config.
	 */
	protected function loader() {
		$loaders = array(
			'include' => 'simple_include',
			'PSR-4'   => 'psr4_autoloader',
			'WP'      => 'wp_autoloader',
		);

		$selected_loader = $this->config['includes']['loader'];
		if ( ! array_key_exists( $selected_loader, $loaders ) ) {
			return;
		}

		call_user_func(
			array( $this, $loaders[ $selected_loader ] ),
			get_template_directory() . DIRECTORY_SEPARATOR . $this->config['includes']['directory'],
			$this->config['includes']['namespace_root']
		);
	}

	/**
	 * Simply include (recursively) all files from the given directory.
	 *
	 * @param string $directory Path to the include.
	 */
	protected function simple_include( $directory ) {
		recursive_include( $directory );
	}

	/**
	 * User PSR-4 autoloader to autoload all classes from the given directory.
	 *
	 * @param string $directory Path to the files to autoload.
	 * @param string $root      Namespace root.
	 */
	protected function psr4_autoloader( $directory, $root ) {
		spl_autoload_register(
			function ( $this_class ) use ( $directory, $root ) {
				$len            = strlen( $root );
				$relative_class = substr( $this_class, $len );
				$file           = $directory . DIRECTORY_SEPARATOR . str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';
				if ( file_exists( $file ) ) {
					require_once $file;
				}
			}
		);
	}

	/**
	 * Use a class autoloader respecting WP coding standards to load all classes from
	 * given directory.
	 *
	 * @param string $directory Path to the files to autoload.
	 * @param string $root      Namespace root.
	 */
	protected function wp_autoloader( $directory, $root ) {
		spl_autoload_register(
			function ( $this_class ) use ( $directory, $root ) {
				$len            = strlen( $root );
				$relative_class = substr( $this_class, $len );

				$split             = explode( '\\', $relative_class );
				$class_i           = count( $split ) - 1;
				$split[ $class_i ] = 'class-' . strtolower( preg_replace( '/_/', '-', $split[ $class_i ] ) ) . '.php';

				$file = $directory . DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $split );
				if ( file_exists( $file ) ) {
					require_once $file;
				}
			}
		);
	}
}
