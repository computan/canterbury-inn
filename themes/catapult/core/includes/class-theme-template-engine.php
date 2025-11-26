<?php
/**
 * Theme Template Engine Class.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * The theme's template engine.
 */
class Theme_Template_Engine {

	/**
	 * Global instance of this class.
	 *
	 * @var $this
	 */
	private static $instances = array();

	/**
	 * Where template files are located.
	 *
	 * @var string
	 */
	private $location;

	/**
	 * A layout file.
	 *
	 * @var array
	 */
	private $layout;

	/**
	 * Parts of the layout.
	 *
	 * @var array
	 */
	private $layout_parts = array();

	/**
	 * Data to pass to the layout file.
	 *
	 * @var array
	 */
	private $layout_data = array();

	/**
	 * Theme_Template_Engine constructor.
	 *
	 * @param string $location Where template files are located.
	 */
	public function __construct( $location ) {
		$this->location = $location;
	}

	/**
	 * Retrieve the global instance of this class. Create it if one
	 * doesn't exist yet.
	 *
	 * @param string $location Key for this instance.
	 *
	 * @return $this
	 */
	public static function create( $location ) {
		if ( isset( self::$instances[ $location ] ) ) {
			return self::$instances[ $location ];
		}

		self::$instances[ $location ] = new self( $location );

		return self::$instances[ $location ];
	}

	/**
	 * Convert a template path to the full path to the .php file on the drive.
	 * The function will first look in the theme folder, but if the file is not found,
	 * it will check if the template is an absolute file path.
	 *
	 * @param string $template Template path to look for.
	 * @param string $folder   Overrides the default location.
	 *
	 * @return mixed|string Full path to the template file.
	 *
	 * @throws LogicException If the file doesn't exist.
	 */
	private function resolve_file( $template, $folder = '' ) {
		$location = $this->location;
		if ( ! empty( $folder ) ) {
			$location = $folder;
		}

		$file = preg_replace( '/\\|\//', DIRECTORY_SEPARATOR, $template );
		$part = $location . DIRECTORY_SEPARATOR . $file;

		$file = get_template_directory() . DIRECTORY_SEPARATOR . $part . '.php';
		if ( file_exists( $file ) ) {
			return $file;
		}

		$file = "$part.php";
		if ( file_exists( $file ) ) {
			return $file;
		}

		if ( is_admin() || in_array( wp_get_environment_type(), array( 'local', 'development' ), true ) ) {
			echo esc_html(
				sprintf(
					__( 'Template "%1$s" cannot be found in "%2$s".', 'catapult' ),
					$template,
					$location
				)
			);
		}

		return false;
	}

	/**
	 * Render the template.
	 * Template to render is relative to the templates location set when
	 * creating the instance of this class.
	 *
	 * @param string $template Template to render.
	 * @param array  $data     Data to pass to the template.
	 * @param string $folder   Overrides the default location.
	 */
	public function render( $template, $data = array(), $folder = '' ) {
		$file = $this->resolve_file( $template, $folder );

		if ( $file ) {
			// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			extract( $data ); // TODO: Remove instances of extract().
			include $file;
		}
	}

	/**
	 * Alias for "render".
	 *
	 * @param string $template Template to render.
	 * @param array  $data     Data to pass to the template.
	 */
	public function insert( $template, $data = array() ) {
		$this->render( $template, $data );
	}

	/**
	 * Begin generating the layout/
	 *
	 * @param string $layout Template name containing a layout.
	 * @param array  $data   Data to pass to the layout file.
	 */
	public function start_layout( $layout, $data = array() ) {
		$this->layout_data = $data;
		$file              = $this->resolve_file( $layout );
		$this->layout      = $file;
		ob_start();
	}

	/**
	 * Start outputting to the next part of the layout.
	 */
	public function next_layout_part() {
		$this->layout_parts[] = ob_get_clean();
		ob_start();
	}

	/**
	 * Finalize the layout. Print the generated HTML and mark
	 * that the we are not currently in the proccess of generating a layout.
	 */
	public function close_layout() {
		$this->layout_parts[] = ob_get_clean();

		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		extract( $this->layout_data ); // TODO: Remove instances of extract().
		$this->layout_data = array();
		include $this->layout;

		$this->layout       = null;
		$this->layout_parts = array();
		$this->layout_part  = 0;
	}

	/**
	 * This function should be used in the layout template.
	 * Mark the place where the theme part output should land.
	 */
	private function layout_part() {
		echo esc_html( array_shift( $this->layout_parts ) );
	}
}
