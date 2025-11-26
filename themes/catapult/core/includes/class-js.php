<?php
/**
 * JS Class.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Class Providing data to this themes JS.
 */
class JS {

	/**
	 * Data to be passed to JS.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Script name to localize the data to.
	 *
	 * @var string
	 */
	private $script_name = 'catapult-script';

	/**
	 * Singleton of this class.
	 *
	 * @var JS
	 */
	private static $instance;

	/**
	 * Retrieve singleton of this class, or create one if it doesn't exist yet.
	 *
	 * @return JS
	 */
	public static function get_instance() {
		if ( self::$instance ) {
			return self::$instance;
		}

		self::$instance = new self();

		return self::$instance;
	}

	/**
	 * Add more data to be passed to JS.
	 * The function accepts a key and value, or an associative array of values.
	 *
	 * @param string|array $key   Key of the new value, or array of data.
	 * @param string       $value New value.
	 */
	public static function add( $key, $value = null ) {
		$js = self::get_instance();

		if ( is_array( $key ) ) {
			$js->data = array_merge( $js->data, $key );
		} elseif ( null !== $value ) {
			$js->data[ $key ] = $value;
		}
	}

	/**
	 * Set the name of the script that should receive the data.
	 *
	 * @param string $name Script name.
	 */
	public static function set_script( $name ) {
		$js              = self::get_instance();
		$js->script_name = $name;
	}

	/**
	 * Pass all the data to the JS file.
	 *
	 * @param string $name Name of the object that the JS file should receive.
	 */
	public static function localize( $name ) {
		$js = self::get_instance();
		wp_localize_script( $js->script_name, $name, $js->data );
	}
}
