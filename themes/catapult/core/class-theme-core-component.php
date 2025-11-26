<?php
/**
 * Theme Core Component Class.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Theme Core Component.
 *
 * Theme Core is build with components, and this is a base class representing them.
 * In order to create new components, create a class in in 'components' directory
 * that extends this one, and implement the init() method.
 * Theme Core takes care if loading all components, but they need to be registered
 * in the config.php file.
 */
abstract class Theme_Core_Component {

	/**
	 * Theme settings.
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Theme_Core_Component constructor.
	 *
	 * @param mixed $settings Theme settings.
	 */
	public function __construct( $settings = array() ) {
		$this->settings = $settings;
		$this->init();
	}

	/**
	 * Initialize the component.
	 */
	abstract protected function init();
}
